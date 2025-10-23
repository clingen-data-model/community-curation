<?php

namespace App\Actions;

use App\User;
use Mpdf\Mpdf;
use App\Upload;
use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use App\CurationActivity;
use Illuminate\Support\Str;
use Mpdf\Config\FontVariables;
use App\Actions\DocumentCreate;
use Mpdf\Config\ConfigVariables;
use App\Events\TrainingCompleted;
use Illuminate\Support\Facades\View;
use Lorisleiva\Actions\Concerns\AsJob;
use Lorisleiva\Actions\Concerns\AsListener;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class TrainingCertificateGenerate
{
    use AsJob, AsListener;

    public string $jobQueue = 'pdf';
    public int $tries = 3;
    public int $backoff = 10;
    public bool $afterCommit = true;

    public function __construct(private DocumentCreate $documentCreate)
    {        
    }

    public function handle(User $user, string $type, Carbon $date): Upload
    {        
        $mpdf = $this->setupMpdf();
        $activitiesByName = CurationActivity::all()->keyBy(fn ($ca) => strtolower(Str::kebab($ca->name)));

        if (!$activitiesByName->keys()->contains($type)) {
            throw new \InvalidArgumentException('valid types include '.$activitiesByName->keys()->join(', ', ', or '));
        }

        $data = [
            'name' => $user->name,
            'type' => $type,
            'curationActivity' => $activitiesByName->get($type)->legacy_name,
            'date' => $date
        ];
        $html = View::make('certificate', $data)->render();

        Storage::makeDirectory('training_certificates');
        Storage::makeDirectory('mpdf_temp');

        $relativePath = 'training_certificates/'.Uuid::uuid4()->toString().'.pdf';
        $storagePath = storage_path('app/'.$relativePath);

        try {
            $mpdf->WriteHTML($html);
            $mpdf->Output($storagePath, \Mpdf\Output\Destination::FILE);
        } catch (\Throwable $e) {
            Log::error('Training certificate PDF generation failed', [
                'user_id' => $user->id,
                'type' => $type,
                'date' => (string)$date,
                'message' => $e->getMessage(),
            ]);
            throw $e;
        }

        return $this->documentCreate
        ->handle(
            filepath: $relativePath,
            originalFileName: $type.'-training-certificate.pdf',
            user_id: $user->id,
            upload_category_id: config('project.upload-categories.training-certificate')
        );
    }

    public function asListener(TrainingCompleted $event): void
    {
        static::dispatch(
            user: $event->userAptitude->user,
            type: Str::kebab($event->userAptitude->aptitude->subject->name),
            date: $event->userAptitude->trained_at,
        )->onQueue($this->jobQueue);
    }

    private function setupMpdf(): Mpdf
    {
        $defaultConfig = (new ConfigVariables())->getDefaults();
        $fontDirs = array_merge($defaultConfig['fontDir'], [
            base_path('fonts/Lora'),
            base_path('fonts/Montserrat'),
            base_path('fonts/OpenSans'),
            base_path('fonts/Pacifico'),
        ]);

        $defaultFontConfig = (new FontVariables())->getDefaults();
        $fontdata = $defaultFontConfig['fontdata'] + [
            'lora' => ['R' => '/Lora-Regular.ttf', 'I' => '/Lora-Italic.ttf'],
            'montserrat' => ['R' => '/Montserrat-Regular.ttf', 'B' => '/Montserrat-ExtraBold.ttf'],
            'opensans' => ['R' => '/OpenSans-Medium.ttf', 'I' => '/OpenSans-MediumItalic.ttf', 'B' => '/OpenSans-Bold.ttf'],
            'pacifico' => ['R' => '/Pacifico-Regular.ttf']
        ];

        $mpdf = new Mpdf([
            'showImagesErrors' => true,
            'orientation' => 'L',
            'fontDir' => $fontDirs,
            'fontdata' => $fontdata,
            'tempDir' => storage_path('app/mpdf_temp'),
            'useKerning' => true,
        ]);
        $mpdf->showImageErrors = false;
        $mpdf->setBasePath(public_path());

        return $mpdf;
    }
}

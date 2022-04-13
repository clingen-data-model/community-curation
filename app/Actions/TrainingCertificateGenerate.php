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

class TrainingCertificateGenerate
{
    use AsJob;
    use AsListener;

    private Mpdf $converter;

    public function __construct(private DocumentCreate $documentCreate)
    {
        $this->converter = $this->setupMpdf();
    }

    public function handle(User $user, string $type, Carbon $date): Upload
    {
        $activitiesByName = CurationActivity::all()->keyBy(function ($ca) {
            return strtolower(Str::kebab($ca->name));
        });

        if (!$activitiesByName->keys()->contains($type)) {
            throw new \InvalidArgumentException('valid types include '.$activitiesByName->keys()->join(', ', ', or '));
        }

        $data = [
            'name' => $user->name, 
            'type' => $type, 
            'curationActivity' => $activitiesByName->get($type)->legacy_name,
            'date' => $date
        ];
        $view = View::make('certificate', $data);

        $relativePath = 'training_certificates/'.Uuid::uuid4()->toString().'.pdf';
        $storagePath = storage_path('app/'.$relativePath);
        $this->converter->WriteHTML($view->render());
        $this->converter->output($storagePath, \Mpdf\Output\Destination::FILE);


        
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
        );
    
    }
    
    
    private function setupMpdf(): Mpdf
    {
        $defaultConfig = (new ConfigVariables())->getDefaults();
        $fontDirs = array_merge(array_merge($defaultConfig['fontDir'], [
            base_path('fonts/Lora'),
            base_path('fonts/Montserrat'),
            base_path('fonts/OpenSans'),
            base_path('fonts/Pacifico'),
        ]));
        
        $defaultFontConfig = (new FontVariables())->getDefaults();
        $fontdata = $defaultFontConfig['fontdata'] + [
            'opensans' => [
                'R' => '/OpenSans-Medium.ttf',
                'I' => '/OpenSans-MediumItalic.ttf',
                'B' => '/OpenSans-Bold.ttf'
            ],
            'lora' => [
                'R' => '/Lora-Regular.ttf',
                'I' => '/Lora-MediumItalic.ttf'
            ],
            'montserrat' => [
                'R' => '/Montserrat-Regular.ttf',
                'B' => '/Montserrat-ExtraBold.ttf'
            ],
            'pacifico' => [
                'R' => '/Pacifico-Regular.ttf',
            ]
        ];
        
        $mpdf = new Mpdf([
            'showImagesErrors' => true,
            'orientation' => 'L',
            'fontDir' => $fontDirs,
            'fontdata' => $fontdata,
            'tempDir' => storage_path('/mpdf_temp'),
            'useKerning' => true
        ]);
        $mpdf->showImageErrors = false;

        if (!file_exists(storage_path('/mpdf_temp'))) {
            mkdir(storage_path('/mpdf_temp'));
        }

        return $mpdf;
    }
    
}

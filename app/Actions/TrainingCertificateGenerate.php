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
use Illuminate\Support\Facades\View;

class TrainingCertificateGenerate
{

    public function __construct(private Mpdf $converter, private DocumentCreate $documentCreate)
    {
        $defaultConfig = (new ConfigVariables())->getDefaults();
        $fontDirs = array_merge(array_merge($defaultConfig['fontDir'], [
            base_path('fonts/Lora'),
            base_path('fonts/Montserrat'),
            base_path('fonts/OpenSans'),
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
            ]
        ];
        
        $this->converter = new Mpdf([
            'showImagesErrors' => true,
            'orientation' => 'L',
            'fontDir' => $fontDirs,
            'fontdata' => $fontdata,
            'tempDir' => storage_path('/mpdf_temp'),
            'useKerning' => true
        ]);
        $this->converter->showImageErrors = true;

        if (!file_exists(storage_path('/mpdf_temp'))) {
            mkdir(storage_path('/mpdf_temp'));
        }

    }

    public function handle(User $user, string $type, Carbon $date): Upload
    {
        $activitiesByName = CurationActivity::all()->keyBy(function ($ca) {
            return strtolower(Str::kebab($ca->name));
        });

        if (!$activitiesByName->keys()->contains($type)) {
            throw new \Exception('valid types include '.$activitiesByName->keys()->join(', ', ', or '));
        }

        $data = [
            // 'name' => $user->name, 
            'name' => 'Jane Doe', 
            'type' => $type, 
            'curationActivity' => $activitiesByName->get($type)->legacy_name,
            'date' => $date
        ];
        $view = View::make('certificate', $data);

        $storagePath = storage_path('app/training_certificates').'/'.Uuid::uuid4()->toString().'.pdf';
        $this->converter->WriteHTML($view->render());
        file_put_contents($storagePath, $this->converter->output());
        
        return $this->documentCreate
            ->handle(
                filepath: $storagePath,
                originalFileName: $user->name.'-'.$type.'-training-certificate.pdf',
                user_id: $user->id
            );  
    }
    
}

<?php

namespace Tests\Feature\Integration\Training;

use App\Actions\TrainingCertificateGenerate;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CertificateGeneratonTest extends TestCase
{
    use DatabaseTransactions;

    public function setup():void
    {
        parent::setup();
        $this->volunteer = $this->createVolunteer();
    }
    
    /**
     * @test
     */
    public function action_generates_a_certificate_and_stores_as_upload()
    {
        $action = app()->make(TrainingCertificateGenerate::class);
        $upload = $action->handle(
            user: $this->volunteer,
            type: 'somatic-variant',
            date: Carbon::now()
        );

        $this->assertDatabaseHas('uploads', [
            'user_id' => $this->volunteer->id,
            'upload_category_id' => config('project.upload-categories.training-certificate'),
            'file_name' => 'somatic-variant-training-certificate.pdf',
        ]);

        $this->assertFileExists(storage_path('/app/'.$upload->file_path));
    }

    
    
}

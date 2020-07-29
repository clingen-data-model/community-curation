<?php

namespace Tests\Unit\Http\Api;

use App\User;
use App\Upload;
use Tests\TestCase;
use App\UploadCategory;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
 * @group uploads
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 * @SuppressWarnings(PHPMD.UnusedLocalVariable)
 */
class CuratorUploadControllerTest extends TestCase
{
    use DatabaseTransactions;

    const UPLOAD_PATH = 'public/curator_uploads/';
    const URL = '/api/curator-uploads';

    private $volunteer;
    private $admin;
    private $dummyFile;
    private $otherVolunteer;

    public function setUp():void
    {
        parent::setUp();
        $this->volunteer = factory(User::class)->states('volunteer', 'comprehensive')->create();
        $this->admin = factory(User::class)->states('admin')->create();
        $this->dummyFile = UploadedFile::fake()->create('document.pdf', 40);
        $this->otherVolunteer = factory(User::class)->states('volunteer', 'comprehensive')->create([]);
        $this->admin = factory(User::class)->states('admin')->create();
    }

    /**
     * @test
     */
    public function other_volunteer_cannot_upload_file_for_volunteer()
    {
        // $this->withoutExceptionHandling();

        $this->actingAs($this->otherVolunteer, 'api')
            ->call('POST', static::URL, [
                'file' => $this->dummyFile,
                'user_id' => $this->volunteer->id
            ])
            ->assertStatus(403);
    }

    /**
     * @test
     */
    public function admin_can_upload_file_for_volunteer()
    {
        $this->actingAs($this->admin, 'api')
            ->call('POST', static::URL, [
                'file' => $this->dummyFile,
                'user_id' => $this->volunteer->id
            ])
            ->assertStatus(201);
        $expectedPath = static::UPLOAD_PATH.$this->dummyFile->hashName();
        unlink(storage_path('app/'.$expectedPath));
    }

    /**
     * @test
     */
    public function volunteer_uploaded_file_stored_in_curator_uploads()
    {
        $this->withoutExceptionHandling();

        $this->actingAs($this->volunteer, 'api')
            ->call('POST', static::URL, [
                'file' => $this->dummyFile,
                'user_id' => $this->volunteer->id
            ])
            ->assertStatus(201);

        $expectedPath = static::UPLOAD_PATH.$this->dummyFile->hashName();
        $this->assertTrue(file_exists(storage_path('app/'.$expectedPath)));
        unlink(storage_path('app/'.$expectedPath));
    }

    /**
     * @test
     */
    public function upload_model_created_with_path_to_file()
    {
        $this->withoutExceptionHandling();
        $this->dummyFile = UploadedFile::fake()->create('document.pdf', 400);

        $this->actingAs($this->volunteer, 'api')
            ->call('POST', static::URL, [
                'file' => $this->dummyFile,
                'user_id' => $this->volunteer->id
            ]);

        $expectedPath = static::UPLOAD_PATH.$this->dummyFile->hashName();
        // dd($expectedPath);
        $this->assertDatabaseHas('uploads', [
            'user_id' => $this->volunteer->id,
            'file_name' => 'document.pdf',
            'file_path' => $expectedPath
        ]);
        unlink(storage_path('app/'.$expectedPath));
    }
    
    /**
     * @test
     */
    public function upload_can_be_associated_with_category()
    {
        $this->withoutExceptionHandling();

        $uploadCategory = factory(UploadCategory::class)->create();

        $this->dummyFile = UploadedFile::fake()->create('document.pdf', 400);

        $this->actingAs($this->volunteer, 'api')
            ->call('POST', static::URL, [
                'file' => $this->dummyFile,
                'user_id' => $this->volunteer->id,
                'upload_category_id' => $uploadCategory->id
            ]);

        $expectedPath = static::UPLOAD_PATH.$this->dummyFile->hashName();
        $this->assertDatabaseHas('uploads', [
            'user_id' => $this->volunteer->id,
            'file_name' => 'document.pdf',
            'file_path' => $expectedPath,
            'upload_category_id' => $uploadCategory->id
        ]);
        unlink(storage_path('app/'.$expectedPath));
    }

    /**
     * @test
     */
    public function other_volunteer_cant_see_volunteers_upload()
    {
        // $this->withoutExceptionHandling();
        $category = factory(UploadCategory::class)->create();
        $upload = factory(Upload::class)->create([
            'user_id' => $this->volunteer->id,
            'upload_category_id' => $category->id
        ]);

        $this->actingAs($this->otherVolunteer, 'api')
            ->call('GET', '/api/curator-uploads/'.$upload->id)
            ->assertStatus(404);
    }

    /**
     * @test
     */
    public function volunteer_can_see_their_own_uploads_with_file_url_and_category()
    {
        $category = factory(UploadCategory::class)->create();
        $upload = factory(Upload::class)->create([
            'user_id' => $this->volunteer->id,
            'upload_category_id' => $category->id
        ]);

        $response = $this->actingAs($this->volunteer, 'api')
            ->call('GET', '/api/curator-uploads/'.$upload->id)
            ->assertStatus(200)
            ->assertJsonFragment([
                'file_name' => $upload->file_name,
                'file_url' => url(Storage::url('curator_uploads/'.$upload->id.'/file/')),
                'category' => [
                    'id' => $category->id,
                    'name' => $category->name,
                ]
            ]);
    }
    
    /**
     * @test
     */
    public function other_volunteer_cannot_download_files_for_volunteer()
    {
        $category = factory(UploadCategory::class)->create();
        $upload = factory(Upload::class)->create([
            'user_id' => $this->volunteer->id,
            'upload_category_id' => $category->id
        ]);

        $this->actingAs($this->otherVolunteer, 'api')
            ->call('GET', 'api/curator-uploads/'.$upload->id.'/file')
            ->assertStatus(404);
    }
    
    /**
     * @test
     */
    public function volunteer_can_download_their_own_file()
    {
        $this->withoutExceptionHandling();
        $category = factory(UploadCategory::class)->create();
        
        $a = Storage::put('public/curator_uploads', $this->dummyFile);

        $upload = factory(Upload::class)->create([
            'user_id' => $this->volunteer->id,
            'upload_category_id' => $category->id,
            'file_path' => $a
        ]);
    
        $response = $this->actingAs($this->volunteer, 'api')
            ->call('GET', '/api/curator-uploads/'.$upload->id.'/file')
            ->assertStatus(200);
           
        $response->streamedContent();

        unlink(storage_path('app/'.$a));
    }
    
    /**
     * @test
     */
    public function admin_can_download_volunteer_file()
    {
        $category = factory(UploadCategory::class)->create();
        
        $a = Storage::put('public/curator_uploads', $this->dummyFile);

        $upload = factory(Upload::class)->create([
            'user_id' => $this->volunteer->id,
            'upload_category_id' => $category->id,
            'file_path' => $a
        ]);
    
        $response = $this->actingAs($this->admin, 'api')
            ->call('GET', '/api/curator-uploads/'.$upload->id.'/file')
            ->assertStatus(200);
           
        $response->streamedContent();

        unlink(storage_path('app/'.$a));
    }

    /**
     * @test
     */
    public function other_volunteer_cannot_update_volunteers_upload()
    {
        $upload = $this->makeUpload();

        $this->actingAs($this->otherVolunteer, 'api')
            ->call('PUT', '/api/curator-uploads/'.$upload->id, [
                'name' => 'test'
            ])
            ->assertStatus(403);
    }
    
    /**
     * @test
     */
    public function volunteer_can_update_own_upload()
    {
        $upload = $this->makeUpload();

        $this->actingAs($this->volunteer, 'api')
            ->call('PUT', '/api/curator-uploads/'.$upload->id, [
                'name' => 'test'
            ])
            ->assertStatus(200);

        $this->assertDatabaseHas('uploads', [
            'user_id' => $this->volunteer->id,
            'name' => 'test'
        ]);
    }

    /**
     * @test
     */
    public function user_id_cannot_be_updated()
    {
        $upload = $this->makeUpload();

        $this->actingAs($this->volunteer, 'api')
            ->call('PUT', '/api/curator-uploads/'.$upload->id, [
                'user_id' => $this->otherVolunteer->id,
                'name' => 'test'
            ])
            ->assertStatus(200);

        $this->assertDatabaseHas('uploads', [
            'user_id' => $this->volunteer->id,
            'name' => 'test'
        ]);
    }

    /**
     * @test
     */
    public function admin_can_update_volunteer_upload()
    {
        $upload = $this->makeUpload();

        $this->actingAs($this->admin, 'api')
            ->call('PUT', '/api/curator-uploads/'.$upload->id, [
                'name' => 'test'
            ])
            ->assertStatus(200);

        $this->assertDatabaseHas('uploads', [
            'user_id' => $this->volunteer->id,
            'name' => 'test'
        ]);
    }
    
    /**
     * @test
     */
    public function only_admin_can_delete_uploads()
    {
        $filepath = Storage::put('public/curator_uploads', $this->dummyFile);
        $upload = factory(Upload::class)->create([
            'user_id' => $this->volunteer->id,
            'file_path' => $filepath
        ]);

        $this->actingAs($this->volunteer, 'api')
            ->call('DELETE', '/api/curator-uploads/'.$upload->id)
            ->assertStatus(403);

        $this->actingAs($this->admin, 'api')
            ->call('DELETE', '/api/curator-uploads/'.$upload->id)
            ->assertStatus(204);

        $this->assertDatabaseHas('uploads', [
            'id' => $upload->id,
            'deleted_at' => Carbon::now()
        ]);

        unlink(storage_path('app/'.$filepath));
    }
    

    /**
     * @test
     */
    public function index_can_be_filtered_and_withed()
    {
        $category = factory(UploadCategory::class)->create([]);
        $this->withoutExceptionHandling();
        $this->makeUpload();
        $this->makeUpload([
                        'user_id' => $this->volunteer,
                        'upload_category_id' => $category->id
                    ]);
        $this->makeUpload(['user_id' => $this->otherVolunteer->id]);

        $this->actingAs($this->admin, 'api')
            ->call(
                'GET',
                '/api/curator-uploads?where[user_id]='.$this->volunteer->id
                    .'&where[upload_category_id]='
                    .$category->id.'&with[]=user&with[]=category'
            )
            ->assertStatus(200)
            ->assertJsonFragment([
                'user_id' => $this->volunteer->id,
                'upload_category_id' => $category->id,
            ])
            ->assertJsonFragment([
                'email' => $this->volunteer->email
            ])
            ->assertJsonFragment([
                'name' => $category->name
            ])
            ->assertJsonMissing([
                'upload_category_id' => null
            ])
            ->assertJsonMissing([
                'user_id' => $this->otherVolunteer->id
            ])
            ;
    }
    

    private function makeUpload($data = null)
    {
        $data = $data ?? ['user_id' => $this->volunteer->id];

        return factory(Upload::class)->create($data);
    }
}

<?php

namespace App\Actions;

use App\Upload;
use Lorisleiva\Actions\ActionRequest;

class DocumentCreate
{
    public function handle($filepath, $originalFileName, $user_id, $upload_category_id = null, $notes = null, $name = null)
    {
        $upload = Upload::create([
            'user_id' => $user_id,
            'name' => $name ?? $originalFileName,
            'file_name' => $originalFileName,
            'file_path' => $filepath,
            'upload_category_id' => $upload_category_id,
            'notes' => $notes,
        ]);

        return $upload;
    }
    
}

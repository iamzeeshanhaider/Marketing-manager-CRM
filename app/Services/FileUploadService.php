<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileUploadService
{


    public function uploadFile($file)
    {
        if ($file->isValid()) {
            $originalName = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $uniqueName = Str::uuid()->toString();
            $fileName = $uniqueName . '.' . $extension;
            $customDirectory = 'uploads/';
            if (!Storage::exists($customDirectory)) {
                Storage::makeDirectory($customDirectory);
            }
            // $filePath = $file->storeAs($customDirectory, $fileName);
            $fileUrl = Storage::disk('local')->url($customDirectory  . $fileName);
            // $url = Storage::url($filePath);
            return $fileUrl;
        }
        return false;
    }
}

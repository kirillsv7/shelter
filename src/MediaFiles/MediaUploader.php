<?php

namespace Source\MediaFiles;

use Illuminate\Http\UploadedFile;
use Source\Infrastructure\Laravel\Models\BaseModel;
use Source\MediaFiles\Models\MediaFile;
use Source\MediaFiles\Services\Storage;

final class MediaUploader
{
    public function __construct(
        private Storage $storage
    ) {
    }

    public function upload(
        UploadedFile $file,
        string $folderName,
        BaseModel $model
    ): MediaFile {
        $savedFile = $this->storage->saveFile(file: $file, folderName: $folderName);

        return MediaFile::query()->create([
            'disk' => $savedFile->disk,
            'path' => $savedFile->path,
            'mediable_type' => get_class($model),
            'mediable_id' => $model->id,
        ]);
    }
}

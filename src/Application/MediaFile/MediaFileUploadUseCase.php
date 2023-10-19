<?php

namespace Source\Application\MediaFile;

use Illuminate\Http\UploadedFile;
use Ramsey\Uuid\Uuid;
use Source\Domain\MediaFile\Aggregates\MediaFile;
use Source\Domain\MediaFile\Repositories\MediaFileRepository;
use Source\Domain\MediaFile\Services\Storage;
use Source\Infrastructure\Laravel\Models\BaseModel;

final class MediaFileUploadUseCase
{
    public function __construct(
        protected Storage $storage,
        protected MediaFileRepository $repository
    ) {
    }

    public function upload(
        UploadedFile $file,
        string $folderName,
        BaseModel $model,
        string $id
    ): MediaFile {
        $fileTypeFolder = $this->guessFolderFromMimeType($file);

        $savedFile = $this->storage->saveFile(
            file: $file,
            folderName: $fileTypeFolder . '/' . $folderName
        );

        $mediaFile = MediaFile::create(
            id: Uuid::uuid4(),
            disk: $savedFile->disk,
            path: $savedFile->path,
            mediableType: $model,
            mediableId: Uuid::fromString($id)
        );

        $this->repository->create($mediaFile);

        return $mediaFile;
    }

    private function guessFolderFromMimeType(UploadedFile $file): string
    {
        $mimeType = $file->getMimeType();

        $folder = 'other';

        if (str_contains($mimeType, 'image')) {
            $folder = 'images';
        } elseif (str_contains($mimeType, 'video')) {
            $folder = 'videos';
        }

        return $folder;
    }
}

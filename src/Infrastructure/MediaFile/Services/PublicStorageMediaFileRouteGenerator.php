<?php

namespace Source\Infrastructure\MediaFile\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Ramsey\Uuid\UuidInterface;
use Source\Domain\MediaFile\Contracts\MediaFileRouteGenerator;
use Source\Infrastructure\Laravel\Models\BaseModel;

final class PublicStorageMediaFileRouteGenerator implements MediaFileRouteGenerator
{
    public function __invoke(
        BaseModel $mediableModel,
        UuidInterface $mediableId,
        UploadedFile $uploadedFile
    ): string {
        $rootFolder = 'media_files';
        $mediableModelFolder = $mediableModel->getTable();
        $mediableIdFolder = $mediableId->toString();
        $mimeTypeFolder = $this->guessFolderNameFromMimeType($uploadedFile);
        $hashedFolderBasedOnFileName = Str::before($uploadedFile->hashName(), '.');

        return implode(DIRECTORY_SEPARATOR, [
            $rootFolder,
            $mediableModelFolder,
            $mediableIdFolder,
            $mimeTypeFolder,
            $hashedFolderBasedOnFileName,
        ]);
    }

    private function guessFolderNameFromMimeType(UploadedFile $file): string
    {
        $mimeType = $file->getMimeType();

        $folder = 'others';

        if (str_contains($mimeType, 'image')) {
            $folder = 'images';
        }
        if (str_contains($mimeType, 'video')) {
            $folder = 'videos';
        }
        if (str_contains($mimeType, 'pdf')) {
            $folder = 'documents';
        }

        return $folder;
    }
}

<?php

namespace Source\Infrastructure\MediaFile\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Ramsey\Uuid\UuidInterface;
use Source\Domain\MediaFile\Repositories\MediaFileRepository;

final readonly class ImageThumbsGenerator
{
    public function __construct(
        protected MediaFileRepository $repository,
        protected ImageManager $imageManager
    ) {
    }

    public function process(UuidInterface $id): void
    {
        $mediaFile = $this->repository->getById($id);

        $imageMediaFile = Storage::disk($mediaFile->storageInfo->disk->value())
            ->get($mediaFile->filePath());

        $imageMediaFileConvertedToImage = $this->imageManager->make($imageMediaFile);

        foreach (config('mediafiles.thumb_sizes') as $size) {
            if (
                $imageMediaFileConvertedToImage->width() <= $size &&
                $imageMediaFileConvertedToImage->height() <= $size
            ) {
                continue;
            }

            $newSizeFileName = Str::replace('original', $size, $mediaFile->storageInfo->fileName);

            $imageContent = $this->imageManager
                ->make($imageMediaFile)
                ->resize($size, $size, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })->stream();

            Storage::disk($mediaFile->storageInfo->disk->value())
                ->put(
                    $mediaFile->storageInfo->route . DIRECTORY_SEPARATOR . $newSizeFileName,
                    $imageContent
                );

            $mediaFile->addSize($newSizeFileName);
        }

        $this->repository->update($mediaFile->id, $mediaFile);
    }
}

<?php

namespace Source\Interface\MediaFile\Controllers;

use Illuminate\Http\JsonResponse;
use Ramsey\Uuid\Uuid;
use Source\Application\MediaFile\UseCases\MediaFileGetByIdUseCase;
use Source\Application\MediaFile\UseCases\MediaFileGetUrlUseCase;
use Source\Application\MediaFile\UseCases\MediaFileUploadUseCase;
use Source\Interface\MediaFile\Requests\MediaFileStoreRequest;

final class MediaFileController
{
    public function store(
        MediaFileStoreRequest $request,
        MediaFileUploadUseCase $mediaFileUploadUseCase,
    ): JsonResponse {
        $responseDTO = $mediaFileUploadUseCase->upload(
            $request->getDTO()
        );

        return response()->json(
            $responseDTO,
            JsonResponse::HTTP_CREATED,
        );
    }

    public function getById(
        string $id,
        MediaFileGetByIdUseCase $mediaFileGetByIdUseCase,
        MediaFileGetUrlUseCase $mediaFileGetUrlUseCase
    ): JsonResponse {
        $mediaFile = $mediaFileGetByIdUseCase->apply(
            Uuid::fromString($id),
        );

        $mediaFileArray = $mediaFile->toArray();

        $mediaFileArray['urls'][] = $mediaFileGetUrlUseCase(
            $mediaFile->storageInfo->route,
            $mediaFile->storageInfo->fileName,
        );

        foreach ($mediaFile->sizes() as $size) {
            $mediaFileArray['urls'][] = $mediaFileGetUrlUseCase(
                $mediaFile->storageInfo->route,
                $size,
            );
        }

        return response()->json(
            ['mediaFile' => $mediaFileArray],
            JsonResponse::HTTP_OK,
        );
    }
}

<?php

namespace Source\Interface\MediaFile\Controllers;

use Illuminate\Http\JsonResponse;
use Ramsey\Uuid\Uuid;
use Source\Application\MediaFile\UseCases\MediaFileGetByIdUseCase;
use Source\Application\MediaFile\UseCases\MediaFileUploadUseCase;
use Source\Interface\MediaFile\Requests\MediaFileStoreRequest;

final class MediaFileController
{
    public function getById(
        string $id,
        MediaFileGetByIdUseCase $mediaFileGetByIdUseCase,
    ): JsonResponse {
        $responseDTO = $mediaFileGetByIdUseCase->apply(
            Uuid::fromString($id),
        );

        return response()->json(
            $responseDTO,
            JsonResponse::HTTP_OK,
        );
    }

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
}

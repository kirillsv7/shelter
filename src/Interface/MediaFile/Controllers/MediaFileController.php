<?php

namespace Source\Interface\MediaFile\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\UploadedFile;
use Ramsey\Uuid\Uuid;
use Source\Application\MediaFile\UseCases\MediaFileGetByIdUseCase;
use Source\Application\MediaFile\UseCases\MediaFileGetUrlUseCase;
use Source\Application\MediaFile\UseCases\MediaFileUploadUseCase;
use Source\Domain\MediaFile\Enums\MediableModel;
use Source\Infrastructure\Laravel\Controllers\Controller;
use Source\Infrastructure\Laravel\Models\BaseModel;
use Source\Infrastructure\MediaFile\Services\MediaFileNameGenerator;
use Source\Infrastructure\MediaFile\Services\MediaFileRouteGenerator;
use Source\Interface\MediaFile\Requests\MediaFileStoreRequest;

final class MediaFileController extends Controller
{
    public function store(
        MediaFileStoreRequest $request,
        MediaFileUploadUseCase $mediaFileUploadUseCase,
        MediaFileRouteGenerator $mediaFileRouteGenerator,
        MediaFileNameGenerator $mediaFileNameGenerator,
        MediaFileGetUrlUseCase $mediaFileGetUrlUseCase
    ): JsonResponse {
        $mediableModel = MediableModel::fromName($request->validated('model'));

        /** @var BaseModel $model */
        $model = app($mediableModel->value);

        $mediableId = Uuid::fromString($request->validated('id'));

        $model->findOrFail($mediableId);

        /** @var UploadedFile $uploadedFile */
        $uploadedFile = $request->validated('file');

        $fileRoute = $mediaFileRouteGenerator(
            $model,
            $mediableId,
            $uploadedFile
        );

        $fileName = $mediaFileNameGenerator($uploadedFile);

        $mediaFile = $mediaFileUploadUseCase->upload(
            $uploadedFile,
            $fileRoute,
            $fileName,
            $model,
            $mediableId
        );

        return response()->json(
            ['mediafile' => $mediaFile->toArray()],
            JsonResponse::HTTP_CREATED
        );
    }

    public function getById(
        string $id,
        MediaFileGetByIdUseCase $mediaFileGetByIdUseCase,
        MediaFileGetUrlUseCase $mediaFileGetUrlUseCase
    ): JsonResponse {
        $mediaFile = $mediaFileGetByIdUseCase->apply(
            Uuid::fromString($id)
        );

        $mediaFileArray = $mediaFile->toArray();

        $mediaFileArray['urls'][] = $mediaFileGetUrlUseCase(
            $mediaFile->storageInfo->route,
            $mediaFile->storageInfo->fileName
        );

        foreach ($mediaFile->sizes() as $size) {
            $mediaFileArray['urls'][] = $mediaFileGetUrlUseCase(
                $mediaFile->storageInfo->route,
                $size
            );
        }

        return response()->json(
            ['mediaFile' => $mediaFileArray],
            JsonResponse::HTTP_OK
        );
    }
}

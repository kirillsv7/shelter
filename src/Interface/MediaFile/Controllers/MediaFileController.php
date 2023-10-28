<?php

namespace Source\Interface\MediaFile\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\UploadedFile;
use Ramsey\Uuid\Uuid;
use Source\Application\MediaFile\MediaFileGetUrlUseCase;
use Source\Application\MediaFile\MediaFileUploadUseCase;
use Source\Domain\MediaFile\Enums\MediableModel;
use Source\Infrastructure\Laravel\Controllers\Controller;
use Source\Infrastructure\Laravel\Models\BaseModel;
use Source\Infrastructure\MediaFile\Services\MediaFilePathGenerator;
use Source\Interface\MediaFile\Requests\MediaFileStoreRequest;

final class MediaFileController extends Controller
{
    public function store(
        MediaFileStoreRequest $request,
        MediaFileUploadUseCase $mediaFileUploadUseCase,
        MediaFileGetUrlUseCase $mediaFileGetUrlUseCase,
        MediaFilePathGenerator $mediaFilePathGenerator
    ): JsonResponse {
        $mediableModel = MediableModel::fromName($request->validated('model'));
        /** @var BaseModel $model */
        $model = app($mediableModel->value);
        $mediableId = Uuid::fromString($request->validated('id'));
        $model->findOrFail($mediableId);

        /** @var UploadedFile $uploadedFile */
        $uploadedFile = $request->validated('file');

        $filePath = $mediaFilePathGenerator(
            $model,
            $mediableId,
            $uploadedFile
        );

        $mediaFile = $mediaFileUploadUseCase->upload(
            $uploadedFile,
            $filePath,
            $model,
            $request->validated('id')
        );

        $mediaFileArray = $mediaFile->toArray();

        $mediaFileArray['url'] = $mediaFileGetUrlUseCase($mediaFile->path());

        return response()->json(
            ['mediafile' => $mediaFileArray],
            JsonResponse::HTTP_CREATED
        );
    }
}

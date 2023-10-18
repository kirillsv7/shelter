<?php

namespace Source\Interface\MediaFile\Controllers;

use Illuminate\Http\JsonResponse;
use Source\Application\MediaFile\MediaFileUploadUseCase;
use Source\Infrastructure\Laravel\Controllers\Controller;
use Source\Interface\MediaFile\Requests\MediaFileStoreRequest;

class MediaFileController extends Controller
{
    public function store(
        MediaFileStoreRequest $request,
        MediaFileUploadUseCase $mediaFileUploadUseCase
    ) {
        $model = app($request->validated('model'));

        $mediaFile = $mediaFileUploadUseCase->upload(
            $request->validated('file'),
            $model->getTable(),
            $model,
            $request->validated('id')
        );

        return response()->json(
            [
                'mediafile' => [
                    'disk' => $mediaFile->disk(),
                    'path' => $mediaFile->path(),
                    'fullPath' => $mediaFile->fullPath(),
                    'mediableType' => $mediaFile->mediableType(),
                    'mediableId' => $mediaFile->mediableId()
                ]
            ],
            JsonResponse::HTTP_CREATED
        );
    }
}

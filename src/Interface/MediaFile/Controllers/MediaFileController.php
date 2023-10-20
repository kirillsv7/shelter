<?php

namespace Source\Interface\MediaFile\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Source\Application\MediaFile\MediaFileUploadUseCase;
use Source\Infrastructure\Laravel\Controllers\Controller;
use Source\Infrastructure\Laravel\Models\BaseModel;
use Source\Interface\MediaFile\Requests\MediaFileStoreRequest;

final class MediaFileController extends Controller
{
    public function store(
        MediaFileStoreRequest $request,
        MediaFileUploadUseCase $mediaFileUploadUseCase
    ): JsonResponse {
        /** @var BaseModel $model */
        $model = app($request->validated('model'));
        $id = $request->validated('id');
        $model->findOrFail($id);

        /** @var UploadedFile $file */
        $file = $request->validated('file');

        $modelFolder = $model->getTable();
        $fileTypeFolder = $this->guessFolderFromMimeType($file);
        $filePath = implode('/', [
            $modelFolder,
            $id,
            $fileTypeFolder,
            Str::before($file->hashName(), '.'),
            'original.' . $file->extension()
        ]);

        $mediaFile = $mediaFileUploadUseCase->upload(
            $request->validated('file'),
            $filePath,
            $model,
            $request->validated('id')
        );

        return response()->json(
            ['mediafile' => $mediaFile->toArray()],
            JsonResponse::HTTP_CREATED
        );
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

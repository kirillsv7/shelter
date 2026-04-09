<?php

namespace Source\Interface\Slug\Controllers;

use Illuminate\Http\JsonResponse;
use Ramsey\Uuid\Uuid;
use Source\Application\Slug\UseCases\SlugUpdateUseCase;
use Source\Interface\Slug\Requests\SlugUpdateRequest;

final class SlugController
{
    public function update(
        SlugUpdateRequest $request,
        string $id,
        SlugUpdateUseCase $slugUpdateUseCase
    ): JsonResponse {
        $responseDTO = $slugUpdateUseCase->apply(
            id: Uuid::fromString($id),
            slugString: $request->validated('slug'),
        );

        return response()->json(
            $responseDTO,
            JsonResponse::HTTP_ACCEPTED,
        );
    }


}

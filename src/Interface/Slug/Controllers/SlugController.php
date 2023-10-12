<?php

namespace Source\Interface\Slug\Controllers;

use Illuminate\Http\JsonResponse;
use Ramsey\Uuid\Uuid;
use Source\Application\Slug\UseCases\SlugUpdateUseCase;
use Source\Infrastructure\Laravel\Controllers\Controller;
use Source\Interface\Slug\Requests\SlugUpdateRequest;

final class SlugController extends Controller
{
    public function update(
        SlugUpdateRequest $request,
        string $id,
        SlugUpdateUseCase $slugUpdateUseCase
    ): JsonResponse {
        $slug = $slugUpdateUseCase->apply(
            id: Uuid::fromString($id),
            slugString: $request->validated('slug'),
        );

        return response()->json(
            ['slug' => (string)$slug],
            JsonResponse::HTTP_ACCEPTED
        );
    }


}

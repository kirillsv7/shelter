<?php

namespace Source\Interface\Organization\Controllers;

use Illuminate\Http\JsonResponse;
use Ramsey\Uuid\Uuid;
use Source\Application\Organization\UseCases\OrganizationActivateUseCase;
use Source\Application\Organization\UseCases\OrganizationCreateUseCase;
use Source\Application\Organization\UseCases\OrganizationDeactivateUseCase;
use Source\Application\Organization\UseCases\OrganizationDestroyUseCase;
use Source\Application\Organization\UseCases\OrganizationGetByIdUseCase;
use Source\Application\Organization\UseCases\OrganizationGetBySlugUseCase;
use Source\Application\Organization\UseCases\OrganizationIndexUseCase;
use Source\Application\Organization\UseCases\OrganizationUnverifyUseCase;
use Source\Application\Organization\UseCases\OrganizationUpdateUseCase;
use Source\Application\Organization\UseCases\OrganizationVerifyUseCase;
use Source\Domain\Shared\ValueObjects\StringValueObject;
use Source\Interface\Organization\Requests\OrganizationIndexRequest;
use Source\Interface\Organization\Requests\OrganizationStoreRequest;
use Source\Interface\Organization\Requests\OrganizationUpdateRequest;

final class OrganizationController
{
    public function index(
        OrganizationIndexRequest $request,
        OrganizationIndexUseCase $organizationIndexUseCase,
    ): JsonResponse {
        $responseDTO = $organizationIndexUseCase->apply(
            dto: $request->getDTO(),
        );

        return response()->json($responseDTO);
    }

    public function getById(
        string $id,
        OrganizationGetByIdUseCase $organizationGetByIdUseCase,
    ): JsonResponse {
        $responseDTO = $organizationGetByIdUseCase->apply(
            id: Uuid::fromString($id),
        );

        return response()->json(
            $responseDTO,
            JsonResponse::HTTP_OK,
        );
    }

    public function getBySlug(
        string $type,
        string $slug,
        OrganizationGetBySlugUseCase $organizationGetBySlugUseCase
    ): JsonResponse {
        $responseDTO = $organizationGetBySlugUseCase->apply(
            slug: StringValueObject::fromString($slug),
        );

        return response()->json(
            $responseDTO,
            JsonResponse::HTTP_OK,
        );
    }

    public function store(
        OrganizationStoreRequest $request,
        OrganizationCreateUseCase $organizationCreateUseCase,
    ): JsonResponse {
        $responseDTO = $organizationCreateUseCase->apply(
            dto: $request->getDTO(),
        );

        return response()->json(
            $responseDTO,
            JsonResponse::HTTP_CREATED,
        );
    }

    public function update(
        OrganizationUpdateRequest $request,
        string $id,
        OrganizationUpdateUseCase $organizationUpdateUseCase,
    ): JsonResponse {
        $responseDTO = $organizationUpdateUseCase->apply(
            id: Uuid::fromString($id),
            dto: $request->getDTO(),
        );

        return response()->json(
            $responseDTO,
            JsonResponse::HTTP_ACCEPTED,
        );
    }

    public function destroy(
        string $id,
        OrganizationDestroyUseCase $organizationDestroyUseCase,
    ): JsonResponse {
        $organizationDestroyUseCase->apply(Uuid::fromString($id));

        return response()->json(
            [],
            JsonResponse::HTTP_NO_CONTENT,
        );
    }

    public function verify(
        string $id,
        OrganizationVerifyUseCase $organizationVerifyUseCase
    ): JsonResponse {
        $responseDTO = $organizationVerifyUseCase->apply(Uuid::fromString($id));

        return response()->json(
            $responseDTO,
            JsonResponse::HTTP_ACCEPTED,
        );
    }

    public function unverify(
        string $id,
        OrganizationUnverifyUseCase $organizationUnverifyUseCase
    ): JsonResponse {
        $responseDTO = $organizationUnverifyUseCase->apply(Uuid::fromString($id));

        return response()->json(
            $responseDTO,
            JsonResponse::HTTP_ACCEPTED,
        );
    }

    public function activate(
        string $id,
        OrganizationActivateUseCase $organizationActivateUseCase
    ): JsonResponse {
        $responseDTO = $organizationActivateUseCase->apply(Uuid::fromString($id));

        return response()->json(
            $responseDTO,
            JsonResponse::HTTP_ACCEPTED,
        );
    }

    public function deactivate(
        string $id,
        OrganizationDeactivateUseCase $organizationDeactivateUseCase
    ): JsonResponse {
        $responseDTO = $organizationDeactivateUseCase->apply(Uuid::fromString($id));

        return response()->json(
            $responseDTO,
            JsonResponse::HTTP_ACCEPTED,
        );
    }
}

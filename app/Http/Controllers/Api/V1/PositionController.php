<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePositionRequest;
use App\Http\Requests\UpdatePositionRequest;
use App\Http\Resources\PositionResource;
use App\Models\Position;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PositionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): AnonymousResourceCollection
    {
        $this->authorize('viewAny', Position::class);

        $positions = Position::query()
            ->with('company:id,name')
            ->latest('id')
            ->paginate(10);

        return PositionResource::collection($positions);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePositionRequest $request): JsonResponse
    {
        $position = Position::create($request->validated());

        return (new PositionResource($position->load('company:id,name')))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Position $position): PositionResource
    {
        $this->authorize('view', $position);

        return new PositionResource($position->load('company:id,name'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePositionRequest $request, Position $position): PositionResource
    {
        $position->update($request->validated());

        return new PositionResource($position->fresh()->load('company:id,name'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Position $position): JsonResponse
    {
        $this->authorize('delete', $position);
        $position->delete();

        return response()->json([
            'message' => 'Position deleted.',
        ]);
    }
}

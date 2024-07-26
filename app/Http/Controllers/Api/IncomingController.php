<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\IncomingItem;
use App\Http\Requests\StoreIncomingItemRequest;
use App\Http\Requests\UpdateIncomingItemRequest;
use App\Http\Resources\IncomingItemJsonResource;

class IncomingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $incomingItems = IncomingItem::orderBy('created_at', 'desc')
                            ->withTrashed()
                            ->get();

        return IncomingItemJsonResource::collection($incomingItems);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreIncomingItemRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(IncomingItem $incomingItem)
    {
        return new IncomingItemJsonResource($incomingItem);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(IncomingItem $incomingItem)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateIncomingItemRequest $request, IncomingItem $incomingItem)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(IncomingItem $incomingItem)
    {
        //
    }
}

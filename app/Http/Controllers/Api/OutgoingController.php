<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OutgoingItemJsonResource;
use App\Models\OutgoingItem;
use Illuminate\Http\Request;

class OutgoingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $outgoingItems = OutgoingItem::orderBy('created_at', 'desc')
                            ->withTrashed()
                            ->get();

        return OutgoingItemJsonResource::collection($outgoingItems);
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(OutgoingItem $outgoingItem)
    {
        return new OutgoingItemJsonResource($outgoingItem);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(OutgoingItem $outgoingItem)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, OutgoingItem $outgoingItem)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OutgoingItem $outgoingItem)
    {
        //
    }
}

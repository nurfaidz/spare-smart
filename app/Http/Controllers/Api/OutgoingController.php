<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\OutgoingItemRequest;
use App\Http\Resources\OutgoingItemJsonResource;
use App\Models\OutgoingItem;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

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
    public function store(OutgoingItemRequest $request)
    {
        try {
            // $outgoingItem = OutgoingItem::create($request->validated());

            $sparePart = \App\Models\SparePart::find($request->spare_part_id);
            $sparePart->stock -= $request->quantity;
            $sparePart->save();

            $outgoingItem = OutgoingItem::create([
                'spare_part_id' => $request->spare_part_id,
                'quantity' => $request->quantity,
                'outgoing_at' => $request->outgoing_at,
                'note' => $request->note,
                'total_price' => $request->quantity * $sparePart->current_price,
                'status' => \App\States\Status\Activated::class,
            ]);

            $outgoingItem->refresh();

            $report = \App\Models\Report::create([
                'reportable_id' => $outgoingItem->id,
                'reportable_type' => get_class($outgoingItem),
            ]);

            // activity()
            //     ->performedOn($report)
            //     ->causedBy(auth()->user())
            //     ->log('Membuat laporan barang keluar');

            // activity()
            //     ->performedOn($sparePart)
            //     ->causedBy(auth()->user())
            //     ->log('Mengupdate stok suku cadang dari barang keluar');

            //     activity()
            //     ->performedOn($outgoingItem)
            //     ->causedBy(auth()->user())
            //     ->log('Membuat data barang keluar');

            return response()->apiSuccess(new OutgoingItemJsonResource($outgoingItem));
        } catch (\Throwable $th) {
            return response()->apiError(422, 'Gagal membuat data barang keluar.', new UnprocessableEntityHttpException('Gagal membuat data barang keluar.', null, 422));
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($outgoingItem)
    {
        $outgoingItem = OutgoingItem::withTrashed()->find($outgoingItem);
        return new OutgoingItemJsonResource($outgoingItem);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($outgoingItem)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(OutgoingItemRequest $request, $outgoingItem)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($outgoingItem)
    {
        //
    }
}

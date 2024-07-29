<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\IncomingItem;
use App\Http\Requests\IncomingItemRequest;
use App\Http\Resources\IncomingItemJsonResource;
use App\Http\Resources\SparePartJsonResource;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

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
        $spareParts = \App\Models\SparePart::withTrashed()->get();

        return response()->apiSuccess(SparePartJsonResource::collection($spareParts));
    }

    /**
     * Store a newly created resource in storage.
     *
    * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(IncomingItemRequest $request)
    {
        try {
            // dd($request->all());
            $sparePart = \App\Models\SparePart::find($request->spare_part_id);
            $sparePart->stock += $request->quantity;
            $sparePart->save();

            $incomingItem = IncomingItem::create([
                'spare_part_id' => $request->spare_part_id,
                'quantity' => $request->quantity,
                'incoming_at' => $request->incoming_at,
                'note' => $request->note,
                'total_price' => $request->quantity * $sparePart->current_price,
                'status' => \App\States\Status\Activated::class,
            ]);

            $incomingItem->refresh();

            $report = \App\Models\Report::create([
                'reportable_id' => $incomingItem->id,
                'reportable_type' => get_class($incomingItem),
            ]);

            // activity()
            //     ->performedOn($report)
            //     ->causedBy(auth()->user())
            //     ->log('Membuat laporan barang masuk');

            // activity()
            //     ->performedOn($sparePart)
            //     ->causedBy(auth()->user())
            //     ->log('Mengupdate stok suku cadang dari barang masuk');


            // activity()
            //     ->performedOn($incomingItem)
            //     ->causedBy(auth()->user())
            //     ->log('Membuat data barang masuk');

            return response()->apiSuccess(new IncomingItemJsonResource($incomingItem));
        } catch (\Throwable $th) {
            return response()->apiError(422, 'Gagal membuat data barang masuk.', new UnprocessableEntityHttpException('Gagal membuat data barang masuk.', null, 422));
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($incomingItem)
    {
        $incomingItem = IncomingItem::withTrashed()->find($incomingItem);
        return new IncomingItemJsonResource($incomingItem);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($incomingItem)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(IncomingItemRequest $request, $incomingItem)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($incomingItem)
    {
        //
    }
}

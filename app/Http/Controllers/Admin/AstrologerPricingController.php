<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AstrologerPricing;
use Illuminate\Http\Request;
use App\Services\AstrologerService;

class AstrologerPricingController extends Controller
{
    protected $service;

    public function __construct(AstrologerService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $astrologerId)
    {
        $request->validate([
            'service_id' => 'required|exists:services,id',
            'price_per_minute' => 'required|numeric|min:0',
            'offer_price' => 'nullable|numeric|min:0',
        ]);

        // Check if pricing already exists for this service
        $existingPricing = AstrologerPricing::where('astrologer_id', $astrologerId)
            ->where('service_id', $request->service_id)
            ->first();

        if ($existingPricing) {
            return redirect()->route('admin.astrologers.show', [$astrologerId, 'tab' => 'pricing'])
                ->with('error', 'Pricing for this service already exists. Please edit the existing pricing instead.');
        }

        $this->service->addPricing($astrologerId, $request->service_id, $request->price_per_minute, $request->offer_price);
        return redirect()->route('admin.astrologers.show', [$astrologerId, 'tab' => 'pricing'])
            ->with('success', 'Pricing added successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $astrologerId
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($astrologerId, $id)
    {
        $pricing = AstrologerPricing::with(['astrologer', 'service'])->findOrFail($id);
        return view('admin.astrologers.pricing.edit', compact('pricing'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $astrologerId
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $astrologerId, $id)
    {
        $request->validate([
            'price_per_minute' => 'required|numeric|min:0',
            'offer_price' => 'nullable|numeric|min:0',
        ]);

        $pricing = AstrologerPricing::findOrFail($id);
        $this->service->updatePricing($id, $request->price_per_minute, $request->offer_price);

        return redirect()->route('admin.astrologers.show', [$astrologerId, 'tab' => 'pricing'])
            ->with('success', 'Pricing updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($astrologerId, $id)
    {
        $this->service->removePricing($id);
        return redirect()->route('admin.astrologers.show', [$astrologerId, 'tab' => 'pricing'])
            ->with('success', 'Pricing removed successfully.');
    }
}

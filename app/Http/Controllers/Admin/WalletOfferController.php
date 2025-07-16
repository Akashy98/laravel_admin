<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WalletOffer;
use Illuminate\Http\Request;
use App\Laratables\WalletOfferLaratables;
use Freshbitsweb\Laratables\Laratables;

class WalletOfferController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.wallet_offers.index');
    }

    public function list(Request $request)
    {
        return Laratables::recordsOf(\App\Models\WalletOffer::class, WalletOfferLaratables::class);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.wallet_offers.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'amount' => 'required|numeric|min:1',
            'extra_percent' => 'required|integer|min:0|max:100',
            'is_popular' => 'boolean',
            'label' => 'nullable|string|max:50',
            'status' => 'required|in:active,inactive',
            'sort_order' => 'nullable|integer|min:0',
        ]);
        $data['is_popular'] = $request->has('is_popular');
        WalletOffer::create($data);
        return redirect()->route('admin.wallet-offers.index')->with('success', 'Wallet offer created successfully.');
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(WalletOffer $wallet_offer)
    {
        return view('admin.wallet_offers.edit', compact('wallet_offer'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, WalletOffer $wallet_offer)
    {
        $data = $request->validate([
            'amount' => 'required|numeric|min:1',
            'extra_percent' => 'required|integer|min:0|max:100',
            'is_popular' => 'boolean',
            'label' => 'nullable|string|max:50',
            'status' => 'required|in:active,inactive',
            'sort_order' => 'nullable|integer|min:0',
        ]);
        $data['is_popular'] = $request->has('is_popular');
        $wallet_offer->update($data);
        return redirect()->route('admin.wallet-offers.index')->with('success', 'Wallet offer updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(WalletOffer $wallet_offer)
    {
        $wallet_offer->delete();
        return redirect()->route('admin.wallet-offers.index')->with('success', 'Wallet offer deleted successfully.');
    }

    public function toggleStatus(WalletOffer $wallet_offer)
    {
        $wallet_offer->status = $wallet_offer->status === 'active' ? 'inactive' : 'active';
        $wallet_offer->save();
        return redirect()->route('admin.wallet-offers.index')->with('success', 'Wallet offer status updated.');
    }
}

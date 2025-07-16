<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\AstrologerService;

class AstrologerSkillController extends Controller
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
            'category_id' => 'required|exists:astrologer_categories,id',
        ]);
        $this->service->addSkill($astrologerId, $request->category_id);
        return redirect()->route('admin.astrologers.show', [$astrologerId, 'tab' => 'skills'])
            ->with('success', 'Skill added successfully.');
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
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($astrologerId, $id)
    {
        $this->service->removeSkill($id);
        return redirect()->route('admin.astrologers.show', [$astrologerId, 'tab' => 'skills'])
            ->with('success', 'Skill removed successfully.');
    }
}

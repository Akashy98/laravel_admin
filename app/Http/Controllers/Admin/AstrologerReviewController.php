<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\AstrologerService;
use App\Models\Astrologer;
use App\Models\User;
use App\Models\AstrologerReview;
use App\Laratables\AstrologerReviewLaratables;
use Freshbitsweb\Laratables\Laratables;

class AstrologerReviewController extends Controller
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
    public function index(Request $request)
    {
        return view('admin.astrologer_reviews.index');
    }

    /**
     * Laratables AJAX listing for reviews
     */
    public function list(Request $request)
    {
        return Laratables::recordsOf(AstrologerReview::class, AstrologerReviewLaratables::class);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $astrologers = Astrologer::with('user')->get();
        $users = User::excludeAdmins()->get();
        return view('admin.astrologer_reviews.create', compact('astrologers', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'astrologer_id' => 'required|exists:astrologers,id',
            'user_id' => 'required|exists:users,id',
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string',
        ]);
        $review = $this->service->addOrUpdateReview(
            $validated['astrologer_id'],
            $validated['user_id'],
            $validated['rating'],
            $validated['review'] ?? null
        );
        return redirect()->route('admin.astrologer_reviews.index')->with('success', 'Review added successfully.');
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
        $review = AstrologerReview::findOrFail($id);
        $astrologers = Astrologer::with('user')->get();
        $users = User::excludeAdmins()->get();
        return view('admin.astrologer_reviews.edit', compact('review', 'astrologers', 'users'));
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
        $review = AstrologerReview::findOrFail($id);
        $validated = $request->validate([
            'astrologer_id' => 'required|exists:astrologers,id',
            'user_id' => 'required|exists:users,id',
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string',
        ]);
        $review->update($validated);
        return redirect()->route('admin.astrologer_reviews.index')->with('success', 'Review updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $review = AstrologerReview::findOrFail($id);
        $review->delete();
        return redirect()->route('admin.astrologer_reviews.index')->with('success', 'Review deleted successfully.');
    }
}

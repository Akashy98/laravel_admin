<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Str;
use App\Laratables\ProductLaratables;
use Freshbitsweb\Laratables\Laratables;
use App\Services\AzureStorageService;

class ProductController extends Controller
{
    protected $azureStorageService;

    public function __construct(AzureStorageService $azureStorageService)
    {
        $this->azureStorageService = $azureStorageService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.products.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.products.create');
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
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:products,slug',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'offer_percentage' => 'nullable|numeric|min:0|max:100',
            'rating' => 'nullable|numeric|min:0|max:5',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|max:2048',
            'is_active' => 'nullable|boolean',
        ]);

        $data = $validated;
        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            try {
                $result = $this->azureStorageService->uploadImage(
                    $request->file('image'),
                    'products',
                    'product_' . time()
                );
                if ($result['success']) {
                    $data['image'] = $result['file_url'];
                } else {
                    return redirect()->back()->withInput()->withErrors(['image' => 'Failed to upload product image']);
                }
            } catch (\Exception $e) {
                return redirect()->back()->withInput()->withErrors(['image' => 'Error uploading image: ' . $e->getMessage()]);
            }
        }

        Product::create($data);

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
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
        $product = Product::findOrFail($id);
        return view('admin.products.edit', compact('product'));
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
        $product = Product::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:products,slug,' . $product->id,
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'offer_percentage' => 'nullable|numeric|min:0|max:100',
            'rating' => 'nullable|numeric|min:0|max:5',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|max:2048',
            'is_active' => 'nullable|boolean',
        ]);

        $data = $validated;
        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            try {
                $result = $this->azureStorageService->uploadImage(
                    $request->file('image'),
                    'products',
                    'product_' . $product->id . '_' . time()
                );
                if ($result['success']) {
                    $data['image'] = $result['file_url'];
                } else {
                    return redirect()->back()->withInput()->withErrors(['image' => 'Failed to upload product image']);
                }
            } catch (\Exception $e) {
                return redirect()->back()->withInput()->withErrors(['image' => 'Error uploading image: ' . $e->getMessage()]);
            }
        }

        $product->update($data);

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
    }

    public function list(Request $request)
    {
        return Laratables::recordsOf(Product::class, ProductLaratables::class);
    }
}

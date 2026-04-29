<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Product;
use App\Models\Brand;
use App\Models\Category;
use App\Models\ProductGallery;
use App\Services\ImageService;

class ProductController extends Controller
{
    protected $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    public function index()
    {
        $products = Product::with(['category', 'brandRef', 'gallery', 'variants'])->latest()->paginate(10);
        $brands = Brand::all();
        $categories = Category::all();
        return view('admin.products.index', compact('products', 'brands', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'nullable|numeric',
            'discount_price' => 'nullable|numeric',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'gallery.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'stock' => 'nullable|integer',
            'sku' => 'nullable|unique:products,sku',
            'brand_id' => 'nullable|exists:brands,id',
            'short_description' => 'nullable|string',
            'description' => 'nullable|string',
            'specifications' => 'nullable|string',
            'features' => 'nullable|string',
            'meta_title' => 'nullable|string',
            'meta_description' => 'nullable|string',
            'tags' => 'nullable|string',
            'status' => 'nullable',
            'delivery_type' => 'required|in:free,paid',
            'delivery_charge' => 'nullable|numeric|min:0'
        ]);

        $data = $request->except(['image', 'gallery']);
        $data['price'] = $request->price ?? 0;
        $data['stock'] = $request->stock ?? 0;
        $data['status'] = $request->has('status') ? 1 : 0;
        $data['is_popular'] = $request->has('is_popular') ? 1 : 0;
        $data['delivery_charge'] = $request->delivery_type === 'free' ? 0 : ($request->delivery_charge ?? 0);
        $brand = $request->filled('brand_id') ? Brand::find($request->brand_id) : null;

        if ($request->filled('brand_id')) {
            $data['brand_id'] = $request->brand_id;
            $data['brand'] = $brand?->name;
        } else {
            $data['brand_id'] = null;
            $data['brand'] = null;
        }
        
        // Parse specifications and features from strings to arrays
        if ($request->filled('specifications')) {
            $data['specifications'] = array_filter(explode("\n", str_replace("\r", "", $request->specifications)));
        }
        if ($request->filled('features')) {
            $data['features'] = array_filter(explode("\n", str_replace("\r", "", $request->features)));
        }

        if ($request->hasFile('image')) {
            $data['image'] = $this->imageService->upload($request->file('image'), 'products');
        }

        $product = Product::create($data);

        // Handle Gallery
        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $image) {
                $path = $this->imageService->upload($image, 'products/gallery');
                $product->gallery()->create(['image_path' => $path]);
            }
        }

        // Handle Variants
        if ($request->has('variant_names')) {
            foreach ($request->variant_names as $key => $name) {
                if (!empty($name)) {
                    $product->variants()->create([
                        'name' => $name,
                        'price' => $request->variant_prices[$key] ?? 0,
                        'old_price' => $request->variant_old_prices[$key] ?? null,
                        'stock' => $request->variant_stocks[$key] ?? 0,
                    ]);
                }
            }
        }

        return redirect()->back()->with('success', 'Product created successfully!');
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'nullable|numeric',
            'discount_price' => 'nullable|numeric',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'gallery.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'stock' => 'nullable|integer',
            'sku' => 'nullable|unique:products,sku,' . $product->id,
            'brand_id' => 'nullable|exists:brands,id',
            'short_description' => 'nullable|string',
            'description' => 'nullable|string',
            'specifications' => 'nullable|string',
            'features' => 'nullable|string',
            'meta_title' => 'nullable|string',
            'meta_description' => 'nullable|string',
            'tags' => 'nullable|string',
            'status' => 'nullable',
            'delivery_type' => 'required|in:free,paid',
            'delivery_charge' => 'nullable|numeric|min:0'
        ]);

        $data = $request->except(['image', 'gallery']);
        $data['price'] = $request->price ?? 0;
        $data['stock'] = $request->stock ?? 0;
        $data['status'] = $request->has('status') ? 1 : 0;
        $data['is_popular'] = $request->has('is_popular') ? 1 : 0;
        $data['delivery_charge'] = $request->delivery_type === 'free' ? 0 : ($request->delivery_charge ?? 0);
        $brand = $request->filled('brand_id') ? Brand::find($request->brand_id) : null;

        if ($request->filled('brand_id')) {
            $data['brand_id'] = $request->brand_id;
            $data['brand'] = $brand?->name;
        } elseif ($product->brand_id) {
            $data['brand_id'] = null;
            $data['brand'] = null;
        } else {
            $data['brand_id'] = null;
            $data['brand'] = $product->brand;
        }

        // Parse specifications and features from strings to arrays
        if ($request->filled('specifications')) {
            $data['specifications'] = array_filter(explode("\n", str_replace("\r", "", $request->specifications)));
        } else {
            $data['specifications'] = [];
        }

        if ($request->filled('features')) {
            $data['features'] = array_filter(explode("\n", str_replace("\r", "", $request->features)));
        } else {
            $data['features'] = [];
        }

        if ($request->hasFile('image')) {
            if ($product->image) {
                $this->imageService->delete($product->image);
            }
            $data['image'] = $this->imageService->upload($request->file('image'), 'products');
        }

        $product->update($data);

        // Handle Gallery
        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $image) {
                $path = $this->imageService->upload($image, 'products/gallery');
                $product->gallery()->create(['image_path' => $path]);
            }
        }

        // Handle Variants
        $product->variants()->delete();
        if ($request->has('variant_names')) {
            foreach ($request->variant_names as $key => $name) {
                if (!empty($name)) {
                    $product->variants()->create([
                        'name' => $name,
                        'price' => $request->variant_prices[$key] ?? 0,
                        'old_price' => $request->variant_old_prices[$key] ?? null,
                        'stock' => $request->variant_stocks[$key] ?? 0,
                    ]);
                }
            }
        }

        return redirect()->back()->with('success', 'Product updated successfully!');
    }

    public function destroy(Product $product)
    {
        if ($product->image) {
            $this->imageService->delete($product->image);
        }

        // Delete gallery images
        foreach ($product->gallery as $item) {
            $this->imageService->delete($item->image_path);
            $item->delete();
        }

        $product->delete();
        return redirect()->back()->with('success', 'Product deleted successfully!');
    }

    public function destroyGallery(ProductGallery $gallery)
    {
        $this->imageService->delete($gallery->image_path);
        $gallery->delete();

        return response()->json(['success' => true]);
    }

    public function togglePopular(Request $request, Product $product)
    {
        try {
            $isPopular = $request->input('is_popular');
            $product->is_popular = $isPopular ? 1 : 0;
            $product->save();

            return response()->json([
                'success' => true, 
                'is_popular' => $product->is_popular,
                'message' => 'Product popular status updated successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating product: ' . $e->getMessage()
            ], 500);
        }
    }

    public function toggleStatus(Product $product)
    {
        $product->status = !$product->status;
        $product->save();

        return redirect()->route('admin.products.index')->with('success', 'Product status updated successfully.');
    }
}

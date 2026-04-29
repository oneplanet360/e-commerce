<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MarketingBanner;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MarketingBannerController extends Controller
{
    public function index()
    {
        $banners = MarketingBanner::orderBy('sort_order')->latest()->get()->groupBy('position');

        return view('admin.banners.index', compact('banners'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'background_color' => 'nullable|string|max:20',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            'link' => 'nullable|url|max:255',
            'position' => 'required|in:main_banner,middle_banner,bottom_banner',
            'sort_order' => [
                'required',
                'integer',
                'min:0',
                Rule::unique('marketing_banners', 'sort_order')
                    ->where(fn ($query) => $query->where('position', $request->input('position'))),
            ],
            'is_active' => 'nullable',
        ], [
            'sort_order.unique' => 'This sort order already exists in the selected section. Please choose a different number.',
        ]);

        $data = $request->except('image');
        $data['is_active'] = $request->has('is_active') ? 1 : 0;
        $data['sort_order'] = $request->input('sort_order', 0);
        $data['image'] = ImageService::upload($request->file('image'), 'banners');

        MarketingBanner::create($data);

        return redirect()->route('admin.banners.index')->with('success', 'Banner created successfully.');
    }

    public function update(Request $request, MarketingBanner $banner)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'background_color' => 'nullable|string|max:20',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            'link' => 'nullable|url|max:255',
            'position' => 'required|in:main_banner,middle_banner,bottom_banner',
            'sort_order' => [
                'required',
                'integer',
                'min:0',
                Rule::unique('marketing_banners', 'sort_order')
                    ->where(fn ($query) => $query->where('position', $request->input('position')))
                    ->ignore($banner->id),
            ],
            'is_active' => 'nullable',
        ], [
            'sort_order.unique' => 'This sort order already exists in the selected section. Please choose a different number.',
        ]);

        $data = $request->except('image');
        $data['is_active'] = $request->has('is_active') ? 1 : 0;
        $data['sort_order'] = $request->input('sort_order', 0);

        if ($request->hasFile('image')) {
            $data['image'] = ImageService::update($request->file('image'), 'banners', $banner->image);
        }

        $banner->update($data);

        return redirect()->route('admin.banners.index')->with('success', 'Banner updated successfully.');
    }

    public function destroy(MarketingBanner $banner)
    {
        ImageService::delete($banner->image);
        $banner->delete();

        return redirect()->route('admin.banners.index')->with('success', 'Banner deleted successfully.');
    }

    public function toggleStatus(MarketingBanner $banner)
    {
        $banner->is_active = !$banner->is_active;
        $banner->save();

        return redirect()->route('admin.banners.index')->with('success', 'Banner status updated.');
    }
}

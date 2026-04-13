<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\StoreBanner;
use Illuminate\Support\Facades\Storage;

class StoreBannerController extends Controller
{
    public function index()
    {
        $banners = StoreBanner::where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->get();

        return $banners->map(function ($banner) {
            return [
                'id' => $banner->id,
                'pre_title' => $banner->pre_title,
                'title' => $banner->title,
                'highlighted_text' => $banner->highlighted_text,
                'subtitle' => $banner->subtitle,
                'cta_text' => $banner->cta_text,
                'cta_link' => $banner->cta_link,
                'image' => $banner->image ? Storage::url($banner->image) : null,
                'mobile_image' => $banner->mobile_image ? Storage::url($banner->mobile_image) : null,
            ];
        });
    }
}

<?php

namespace App\Http\Controllers;

use App\Helpers\FrontendHelpers;
use App\Models\Accredited;
use App\Models\Blog;
use App\Models\BulkOrderBenefit;
use App\Models\BulkOrderCms;
use App\Models\Category;
use App\Models\Client;
use App\Models\GoogleReview;
use App\Models\HomeCms;
use App\Models\HomeFeature;
use App\Models\Innovator;
use App\Models\OfficeCms;
use App\Models\Partner;
use App\Models\Policy;
use App\Models\Slider;
use App\Models\Solution;
use App\Models\SpaceCategory;
use App\Models\SuccessStoryCategory;
use App\Models\TrustedBrand;
use App\Models\Usp;
use App\Models\WhyChooseUs;
use App\Traits\ProductTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class FrontendController extends Controller
{
    use ProductTrait;

    /**
     * Display the index page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $sliders = Cache::rememberForever(
            'sliders',
            fn () => Slider::with('media')->active()
                ->select([
                    'id',
                    'title',
                    'media_type',
                    'action_type',
                    'action_title',
                    'action_url',
                ])
                ->orderBy('sort_order')
                ->get()
        );

        $homeCms = Cache::rememberForever(
            'home.cms',
            fn () => HomeCms::with('media')->select([
                'id',
                'section_one_title',
                'section_one_image_alt_text',
                'section_one_title',
                'section_one_image_alt_text',
                'section_two_title',
                'section_three_title',
                'section_three_description',
                'section_four_title',
                'section_four_description',
                'section_five_title',
                'section_six_title',
                'section_six_description',
                'section_six_image_alt_text',
            ])->firstOrFail()
        );

        $homeFeatures = Cache::rememberForever(
            'home.features',
            fn () => HomeFeature::with('media')->active()
                ->select([
                    'id',
                    'title',
                    'subtitle',
                    'description',
                ])
                ->orderBy('sort_order')
                ->get()
        );

        $trustedBrands = Cache::rememberForever(
            'trusted.brands',
            fn () => TrustedBrand::with('media')->active()
                ->select('id')
                ->orderBy('sort_order')
                ->get()
        );

        $usps = Cache::rememberForever(
            'usps',
            fn () => Usp::with('media')->active()
                ->select([
                    'id',
                    'title',
                    'description',
                ])
                ->orderBy('sort_order')
                ->get()
        );

        $clients = Cache::rememberForever(
            'clients',
            fn () => Client::with('media')->active()
                ->select('id')
                ->orderBy('sort_order')
                ->get()
        );

        $accrediteds = Cache::rememberForever(
            'accrediteds',
            fn () => Accredited::active()
                ->with('media')
                ->select('id')
                ->orderBy('sort_order')
                ->get()
        );

        $categories = Cache::rememberForever(
            'homeCategories',
            fn () => $this->getHomeCategories()
        );

        $favouriteProducts = $this->getBaseQuery()->where('is_favourite', 1)->sort()->take(10)->get();

        $spaceCategories = Cache::rememberForever(
            'spaceCategories', fn () => SpaceCategory::with('state', 'city', 'media')->active()->sort()->get()
        );

        $googleReviews = Cache::rememberForever('googleReviews', fn () => GoogleReview::with('media')->active()->sort()->limit(15)->get());

        $meta = FrontendHelpers::getPageDetails('home');

        return view('index', compact('sliders', 'homeCms', 'homeFeatures', 'trustedBrands', 'usps', 'clients', 'accrediteds', 'categories', 'favouriteProducts', 'spaceCategories', 'meta', 'googleReviews'));
    }

    public function getHomeCategories()
    {
        return Category::with('media')->where('show_in_homepage', 1)->active()->sort()->limit(3)->get();
    }

    /**
     * Display the office-design page.
     *
     * @author [Your Name Here]
     *
     * @return \Illuminate\View\View
     */
    public function officeDesign()
    {
        $cms = OfficeCms::first();
        if (! $cms) {
            abort(404);
        }
        $whyChooseUses = WhyChooseUs::active()->sort()->get();
        $partners = Partner::active()->sort()->get();
        $solutions = Solution::active()->sort()->get();
        $spaceCategories = SpaceCategory::active()->sort()->get();

        $meta = FrontendHelpers::getPageDetails('office-design');

        $siteSettings = app('siteSettings');

        return view('office-design', compact('cms', 'whyChooseUses', 'partners', 'solutions', 'spaceCategories', 'meta', 'siteSettings'));
    }

    /**
     * Display the office-design page.
     *
     * @author [Your Name Here]
     *
     * @return \Illuminate\View\View
     */
    public function blogs(Request $request)
    {
        if ($request->ajax()) {
            $blogs = $this->getBlogs($request);
            $html = view('partials.blog-list', compact('blogs'))->render();

            return response()->json(['html' => $html, 'isLastPage' => $blogs->hasMorePages() ? false : true]);
        }
        $featuredArticle = Blog::query()->select('title', 'slug', 'id', 'short_content')->active()->where('featured', true)->first();
        $blogs = $this->getBlogs($request);
        $meta = FrontendHelpers::getPageDetails('blogs');

        return view('blog', compact('blogs', 'featuredArticle', 'meta'));
    }

    public function getBlogs($request)
    {
        return Blog::query()->select('title', 'slug', 'id', 'short_content')->active()->orderBy('published_on', 'desc')->paginate(6, ['*'], 'page', $request->page ?? 1);
    }

    public function blogArticle($slug)
    {
        $blog = Blog::query()->active()->where('slug', $slug)->firstOrFail();

        // get back and next blog
        // Get back and next blog with only required fields
        $backBlog = Blog::query()
            ->active()
            ->where(function ($query) use ($blog) {
                $query->where('published_on', '<', $blog->published_on)
                    ->orWhere(function ($q) use ($blog) {
                        $q->where('published_on', '=', $blog->published_on)
                            ->where('id', '<', $blog->id);
                    });
            })
            ->orderBy('published_on', 'desc')
            ->select('id', 'slug', 'title', 'published_on')
            ->first();

        $nextBlog = Blog::query()
            ->active()
            ->where(function ($query) use ($blog) {
                $query->where('published_on', '>', $blog->published_on)
                    ->orWhere(function ($q) use ($blog) {
                        $q->where('published_on', '=', $blog->published_on)
                            ->where('id', '>', $blog->id);
                    });
            })
            ->orderBy('published_on', 'asc')
            ->select('id', 'slug', 'title', 'published_on')
            ->first();

        $blog->backBlog = $backBlog;
        $blog->nextBlog = $nextBlog;

        $relatedBlogs = $blog->related_blogs !== null ? Blog::select('id', 'title', 'slug', 'short_content')->whereIn('id', $blog->related_blogs)->get() : collect();

        return view('blogArticle', compact('blog', 'relatedBlogs'));
    }

    /**
     * Display the office-design page.
     *
     * @author [Your Name Here]
     *
     * @return \Illuminate\View\View
     */
    public function refund()
    {
        $cms = Policy::where('slug', 'returns-refunds')->first();
        if (! $cms) {
            abort(404);
        }

        return view('refund', compact('cms'));
    }

    /**
     * Display the office-design page.
     *
     * @author [Your Name Here]
     *
     * @return \Illuminate\View\View
     */
    public function termsandcondition()
    {
        $cms = Policy::where('slug', 'terms-and-conditions')->first();
        if (! $cms) {
            abort(404);
        }

        return view('termsandcondition', compact('cms'));
    }

    /**
     * Display the office-design page.
     *
     * @author [Your Name Here]
     *
     * @return \Illuminate\View\View
     */
    public function privacy()
    {
        $cms = Policy::where('slug', 'privacy-policy')->first();
        if (! $cms) {
            abort(404);
        }

        return view('privacy', compact('cms'));
    }

    /**
     * Display the office-design page.
     *
     * @author [Your Name Here]
     *
     * @return \Illuminate\View\View
     */
    public function bulkOrder()
    {
        $cms = BulkOrderCms::first();
        if (! $cms) {
            abort(404);
        }
        $benefits = BulkOrderBenefit::active()->sort()->get();
        $successStoryCategories = SuccessStoryCategory::active()->sort()->get();
        $innovators = Innovator::active()->sort()->get();
        $bulkOrderProducts = $this->getBaseQuery()->whereHas('bulkOrders', function ($q) {
            $q->active();
        })->latest()->take(10)->get();
        $googleReviews = GoogleReview::active()->where('show_in_bulk_order', 1)->sort()->get();
        $meta = FrontendHelpers::getPageDetails('bulk-order');
        $siteSettings = app('siteSettings');

        return view('bulkOrder', compact('cms', 'benefits', 'successStoryCategories', 'innovators', 'bulkOrderProducts', 'googleReviews', 'meta', 'siteSettings'));
    }

    /**
     * Display the smartdesk-landing page.
     *
     * @author [Your Name Here]
     *
     * @return \Illuminate\View\View
     */
    public function smartdesklanding()
    {
        return view('smartdesk-landing');
    }
}

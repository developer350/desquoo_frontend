<?php

namespace App\Providers;

use App\Models\AdminSettings;
use App\Models\AppSettings;
use App\Models\Attribute;
use App\Models\BlogComment;
use App\Models\BulkOrderEnquiry;
use App\Models\Cart;
use App\Models\Category;
use App\Models\EnquiryLastRead;
use App\Models\GotAQuestion;
use App\Models\NewsletterSubscription;
use App\Models\OfficeEnquiry;
use App\Models\ProductCustomLanding;
use App\Models\SiteSettings;
use App\Models\SocialLink;
use App\Models\SupportSectionCms;
use App\Models\VisitEnquiry;
use App\Observers\MediaObserver;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton('backend.prefix', function () {
            if (Schema::hasTable('admin_settings')) {
                return optional(AdminSettings::where('key', 'backend-prefix')->first())->value ?? 'admin-portal';
            }

            return 'admin-portal';
        });

        $this->app->singleton('adminSettings', function () {
            return Cache::rememberForever('adminSettings', function () {
                return AdminSettings::with('media')->get()->keyBy('key');
            });
        });

        $this->app->singleton('socialLinks', function () {
            return Cache::rememberForever('socialLinks', function () {
                return SocialLink::with('media')->active()->sort()->get();
            });
        });

        $this->app->singleton('defaultAttribute', function () {
            return Cache::rememberForever('defaultAttribute', function () {
                return Attribute::where('default_listing_attribute', 1)->first();
            });
        });

        $this->app->singleton('supportSectionCms', function () {
            return Cache::rememberForever('supportSectionCms', function () {
                return SupportSectionCms::first();
            });
        });

        $this->app->singleton('siteSettings', function () {
            return Cache::rememberForever('siteSettings', function () {
                return SiteSettings::first();
            });
        });

        $this->app->singleton('appSettings', function () {
            return Cache::rememberForever('appSettings', function () {
                return AppSettings::get()->keyBy('key');
            });
        });

        $this->app->singleton('cart', function () {
            $userId = Auth::id();
            $sessionId = session()->getId();

            return Cart::when($userId != null, function ($q) use ($userId) {
                $q->where('user_id', $userId);
            })
                ->when($userId == null, function ($q) use ($sessionId) {
                    $q->where('session_id', $sessionId);
                })
                ->with([
                    'productVariant.attributeValues.media',
                    'productVariant.media',
                    'product.media',
                    'product' => function ($query) {
                        $query->select('id', 'name', 'slug', 'is_addon');
                    },
                ])
                ->whereHas('product', function ($query) {
                    $query->where('status', 1);
                })
                ->whereHas('productVariant', function ($query) {
                    $query->where('status', 1);
                })
                ->latest()
                ->get();
        });

        View::composer(['modals.cartModal', 'modals.partials.summary', 'checkout.partials.summary'], function ($view) {
            $carts = $this->app->make('cart');

            $regularCarts = $carts->filter(function ($cart) {
                return $cart->product && ! $cart->product->is_addon;
            });

            $addonCarts = $carts->filter(function ($cart) {
                return $cart->product && $cart->product->is_addon;
            });

            $subTotal = $carts->sum(function ($cart) {
                return $cart->productVariant->last_price * $cart->quantity;
            });

            $discountAmount = $this->getDiscountAmount($carts);

            // tax is already added in the product price
            $taxAmount = $this->getTaxAmount($carts, $subTotal, $discountAmount);

            $grandTotal = $subTotal - $discountAmount;

            $view->with([
                'carts' => $regularCarts,
                'addonCarts' => $addonCarts,
                'totalItems' => $carts->count(),
                'subTotal' => $subTotal,
                'discountAmount' => $discountAmount,
                'taxAmount' => $taxAmount,
                'grandTotal' => $grandTotal,
            ]);
        });

        $this->app->singleton('categories', function () {
            return Cache::rememberForever('categories', function () {
                return Category::with('media', 'children.media')->with('children')->whereNull('parent_id')->active()->sort()->get();
            });
        });

        $this->app->singleton('smartProducts', function () {
            return Cache::rememberForever('smartProducts', function () {
                return ProductCustomLanding::select('id', 'title', 'slug', 'status', 'product_id')->where('status', 1)->get();
            });
        });

        View::composer('layouts.header', function ($view) {
            $view->with(['headerCategories' => $this->app->make('categories')->where('show_in_menu', 1)->take(3), 'siteSettings' => $this->app->make('siteSettings'), 'cartCount' => $this->app->make('cart')->count()]);
        });

        View::composer('layouts.footer', function ($view) {
            $view->with(['siteSettings' => $this->app->make('siteSettings'), 'categories' => $this->app->make('categories'), 'smartProducts' => $this->app->make('smartProducts'), 'socialLinks' => $this->app->make('socialLinks')]);
        });

        View::composer('mails.*', function ($view) {
            $view->with('siteSettings', $this->app->make('siteSettings'));
        });

        View::composer('admin::layouts.left-sidebar', function ($view) {
            $userId = Auth::guard('admin')->id();
            $lastRead = EnquiryLastRead::firstOrCreate(['admin_id' => $userId]);

            $counts = [
                'got_a_question' => GotAQuestion::when($lastRead->got_a_question_at, fn ($q) => $q->where('created_at', '>', $lastRead->got_a_question_at))
                    ->count(),

                'visit' => VisitEnquiry::when($lastRead->visit_at, fn ($q) => $q->where('created_at', '>', $lastRead->visit_at))
                    ->count(),

                'office' => OfficeEnquiry::when($lastRead->office_at, fn ($q) => $q->where('created_at', '>', $lastRead->office_at))
                    ->count(),

                'bulk_order' => BulkOrderEnquiry::when($lastRead->bulk_order_at, fn ($q) => $q->where('created_at', '>', $lastRead->bulk_order_at))
                    ->count(),

                'newsletter' => NewsletterSubscription::when($lastRead->newsletter_at, fn ($q) => $q->where('created_at', '>', $lastRead->newsletter_at))
                    ->count(),

                'blog_comment' => BlogComment::when($lastRead->blog_comment_at, fn ($q) => $q->where('created_at', '>', $lastRead->blog_comment_at))
                    ->count(),
            ];

            $view->with('hasNewEnquiry', $counts['got_a_question'] > 0 || $counts['visit'] > 0 || $counts['office'] > 0 || $counts['bulk_order'] > 0 || $counts['newsletter'] > 0 || $counts['blog_comment'] > 0);
            $view->with('enquiryCounts', $counts);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // More efficient session and data management
        $this->initializeUserSessions();

        Schema::defaultStringLength(191);

        Media::observe(MediaObserver::class);

        Gate::before(function ($user, $ability) {
            return $user->hasRole('Super Admin') ? true : null;
        });

        Blade::directive('settings', function ($key) {
            return "<?php echo \App\Helpers\BackendHelpers::getValueByKey($key); ?>";
        });

        Blade::directive('appSettings', function ($key) {
            return "<?php echo \App\Models\AppSettings::getValue($key); ?>";
        });

        Blade::directive('linkTarget', function ($url) {
            return "<?php echo \\App\\Helpers\\FrontendHelpers::getTargetAttribute($url); ?>";
        });
    }

    protected function initializeUserSessions(): void
    {
        // Only initialize if not already set
        if (! session('cart') === null) {
            $this->loadUserData();
        }
    }

    protected function loadUserData(): void
    {
        $sessionId = session()->getId();
        $userId = Auth::id();

        // More efficient query approach
        $data = [
            'cart' => $this->fetchUserItems(Cart::class, $userId, $sessionId),
        ];

        // Bulk session put
        session()->put($data);
    }

    protected function fetchUserItems($model, $userId, $sessionId): array
    {
        return $userId
            ? $model::where('user_id', $userId)->pluck('product_variant_id')->toArray()
            : $model::where('session_id', $sessionId)->pluck('product_variant_id')->toArray();
    }

    protected function getDiscountAmount($carts)
    {
        $discountAmount = 0;
        foreach ($carts as $cartProduct) {
            if ($cartProduct->product->bulkOrders->count() > 0) {
                // check if the quantity is between in bulk order
                foreach ($cartProduct->product->bulkOrders as $bulkOrder) {
                    if ($cartProduct->quantity >= $bulkOrder->min_quantity && $cartProduct->quantity <= $bulkOrder->max_quantity) {
                        if ($bulkOrder->discount_percentage > 0) {
                            $discountAmount += $bulkOrder->discount_percentage / 100 * $cartProduct->productVariant->last_price * $cartProduct->quantity;
                        }
                        break;
                    }
                }
            }
        }

        return $discountAmount;
    }

    protected function getTaxAmount($carts, $subTotal, $discountAmount)
    {
        $taxPercentage = app('appSettings')->get('tax.percentage')->value ?? 0;
        if ($taxPercentage == 0) {
            return 0;
        }

        // tax calculation (reciprocal)

        $total = $subTotal - $discountAmount;

        $taxableAmount = ($total / (100 + $taxPercentage)) * 100;

        return round($taxableAmount * $taxPercentage / 100, 2);
    }
}

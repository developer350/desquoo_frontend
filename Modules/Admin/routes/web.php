<?php

use Illuminate\Support\Facades\Route;
use Modules\Admin\Http\Controllers\UspController;
use Modules\Admin\Http\Controllers\AuthController;
use Modules\Admin\Http\Controllers\BlogController;
use Modules\Admin\Http\Controllers\CityController;
use Modules\Admin\Http\Controllers\RoleController;
use Modules\Admin\Http\Controllers\UserController;
use Modules\Admin\Http\Controllers\AdminController;
use Modules\Admin\Http\Controllers\OrderController;
use Modules\Admin\Http\Controllers\SpaceController;
use Modules\Admin\Http\Controllers\StateController;
use Modules\Admin\Http\Controllers\ClientController;
use Modules\Admin\Http\Controllers\PCLFaqController;
use Modules\Admin\Http\Controllers\PolicyController;
use Modules\Admin\Http\Controllers\SearchController;
use Modules\Admin\Http\Controllers\SliderController;
use Modules\Admin\Http\Controllers\CountryController;
use Modules\Admin\Http\Controllers\FeatureController;
use Modules\Admin\Http\Controllers\HomeCmsController;
use Modules\Admin\Http\Controllers\PartnerController;
use Modules\Admin\Http\Controllers\PincodeController;
use Modules\Admin\Http\Controllers\ProductController;
use Modules\Admin\Http\Controllers\CategoryController;
use Modules\Admin\Http\Controllers\NotifyMeController;
use Modules\Admin\Http\Controllers\PCLModelController;
use Modules\Admin\Http\Controllers\SolutionController;
use Modules\Admin\Http\Controllers\AttributeController;
use Modules\Admin\Http\Controllers\DashboardController;
use Modules\Admin\Http\Controllers\InnovatorController;
use Modules\Admin\Http\Controllers\OfficeCmsController;
use Modules\Admin\Http\Controllers\AccreditedController;
use Modules\Admin\Http\Controllers\SocialLinkController;
use Modules\Admin\Http\Controllers\AppSettingsController;
use Modules\Admin\Http\Controllers\BlogCommentController;
use Modules\Admin\Http\Controllers\HomeFeatureController;
use Modules\Admin\Http\Controllers\WhyChooseUsController;
use Modules\Admin\Http\Controllers\BulkOrderCmsController;
use Modules\Admin\Http\Controllers\SiteSettingsController;
use Modules\Admin\Http\Controllers\SuccessStoryController;
use Modules\Admin\Http\Controllers\TrustedBrandController;
use Modules\Admin\Http\Controllers\VisitEnquiryController;
use Modules\Admin\Http\Controllers\AdminSettingsController;
use Modules\Admin\Http\Controllers\OfficeEnquiryController;
use Modules\Admin\Http\Controllers\ProductReviewController;
use Modules\Admin\Http\Controllers\SpaceCategoryController;
use Modules\Admin\Http\Controllers\AttributeValueController;
use Modules\Admin\Http\Controllers\ProductGalleryController;
use Modules\Admin\Http\Controllers\ProductVariantController;
use Modules\Admin\Http\Controllers\PCLProductivityController;
use Modules\Admin\Http\Controllers\BannerAndMetaTagController;
use Modules\Admin\Http\Controllers\BulkOrderBenefitController;
use Modules\Admin\Http\Controllers\BulkOrderEnquiryController;
use Modules\Admin\Http\Controllers\GoogleReviewController;
use Modules\Admin\Http\Controllers\GotAQuestionController;
use Modules\Admin\Http\Controllers\ProductBulkOrderController;
use Modules\Admin\Http\Controllers\SupportSectionCmsController;
use Modules\Admin\Http\Controllers\ProductCustomLandingController;
use Modules\Admin\Http\Controllers\SuccessStoryCategoryController;
use Modules\Admin\Http\Controllers\PCLMindfulEngineeringController;
use Modules\Admin\Http\Controllers\ProductVariantGalleryController;
use Modules\Admin\Http\Controllers\NewsletterSubscriptionController;
use Modules\Admin\Http\Controllers\ProductAttributeValueMediaController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// define routes under the admin panel prefix
Route::prefix(app('backend.prefix'))->group(function () {
    // admin login routes
    Route::get('login', [AuthController::class, 'show'])->name('admin.show');
    Route::post('login', [AuthController::class, 'login'])->name('admin.login');

    // group routes that require the admin to be authenticated
    Route::middleware('admin.auth')->group(function () {
        // admin logout route
        Route::post('logout', [AdminController::class, 'logout'])->name('admin.logout');
        // redirect root to dashboard
        Route::get('/', function () {
            return redirect()->route('dashboard.index');
        });
        // dashboard
        Route::resource('dashboard', DashboardController::class)->only('index')->names('dashboard');
        // admins
        Route::middleware('permission:admins,admin')->group(function () {
            Route::get('admins/{admin}/change-password', [AdminController::class, 'editPassword'])->name('admin.change-password.edit');
            Route::put('admins/{admin}/change-password', [AdminController::class, 'updatePassword'])->name('admin.change-password.update');
            Route::resource('admins', AdminController::class)->names('admin')->except('show');
        });
        // roles
        Route::resource('roles', RoleController::class)->names('admin.roles')->except('show')->middleware('permission:roles,admin');
        // updating the sort order
        Route::post('admin-settings/update-sort-order', [AdminSettingsController::class, 'updateSortOrder'])->name('admin-settings.updateSortOrder');
        // updating the toggle status
        Route::post('admin-settings/update-toggle-status', [AdminSettingsController::class, 'updateToggleStatus'])->name('admin-settings.updateToggleStatus');
        // check slug
        Route::get('admin-settings/check-slug', [AdminSettingsController::class, 'checkSlug'])->name('admin-settings.checkSlug');
        // admin settings
        Route::resource('admin-settings', AdminSettingsController::class)->only(['index', 'edit', 'update'])->names('admin-settings')->middleware('permission:admin-settings,admin');
        // app settings
        Route::middleware('permission:app-settings,admin')->group(function () {
            Route::get('app-settings/edit', [AppSettingsController::class, 'edit'])->name('app-settings.edit');
            Route::put('app-settings/update', [AppSettingsController::class, 'update'])->name('app-settings.update');
        });
        // site settings
        Route::middleware('permission:site-settings,admin')->group(function () {
            Route::get('site-settings/edit', [SiteSettingsController::class, 'edit'])->name('site-settings.edit');
            Route::put('site-settings/{siteSettings}/update', [SiteSettingsController::class, 'update'])->name('site-settings.update');
        });
        // sliders
        Route::resource('sliders', SliderController::class)->except(['show'])->names('sliders')->middleware('permission:sliders,admin');
        // home cms
        Route::middleware('permission:home-cms,admin')->group(function () {
            Route::get('home-cms/edit', [HomeCmsController::class, 'edit'])->name('home-cms.edit');
            Route::put('home-cms/{homeCms}/update', [HomeCmsController::class, 'update'])->name('home-cms.update');
        });
        // home features
        Route::resource('home-features', HomeFeatureController::class)->except(['show'])->names('home-features')->middleware('permission:home-features,admin');
        // trusted brands
        Route::resource('trusted-brands', TrustedBrandController::class)->except(['show'])->names('trusted-brands')->middleware('permission:trusted-brands,admin');
        // accredited
        Route::resource('accredited', AccreditedController::class)->except(['show'])->names('accredited')->middleware('permission:accredited,admin');
        // usps
        Route::resource('usps', UspController::class)->except(['show'])->names('usps')->middleware('permission:usps,admin');
        // clients
        Route::resource('clients', ClientController::class)->except(['show'])->names('clients')->middleware('permission:clients,admin');
        // office cms
        Route::middleware('permission:office-cms,admin')->group(function () {
            Route::get('office-cms/edit', [OfficeCmsController::class, 'edit'])->name('office-cms.edit');
            Route::put('office-cms/{officeCms}/update', [OfficeCmsController::class, 'update'])->name('office-cms.update');
        });
        // why choose us
        Route::resource('why-choose-us', WhyChooseUsController::class)->except(['show'])->names('why-choose-us')->middleware('permission:why-choose-us,admin');
        // partners
        Route::resource('partners', PartnerController::class)->except(['show'])->names('partners')->middleware('permission:partners,admin');
        // solutions
        Route::resource('solutions', SolutionController::class)->except(['show'])->names('solutions')->middleware('permission:solutions,admin');
        // bulk order cms
        Route::middleware('permission:bulk-order-cms,admin')->group(function () {
            Route::get('bulk-order-cms/edit', [BulkOrderCmsController::class, 'edit'])->name('bulk-order-cms.edit');
            Route::put('bulk-order-cms/{bulkOrderCms}/update', [BulkOrderCmsController::class, 'update'])->name('bulk-order-cms.update');
        });
        // bulk order benefits
        Route::resource('bulk-order-benefits', BulkOrderBenefitController::class)->except(['show'])->names('bulk-order-benefits')->middleware('permission:bulk-order-benefits,admin');

        // blogs
        Route::middleware('permission:blogs,admin')->group(function () {
            Route::resource('blogs', BlogController::class)->names('blogs');
            Route::get('get-blogs', [BlogController::class, 'getBlogs'])->name('get-blogs');
        });
        // banner and meta tags
        Route::resource('banner-and-meta-tags', BannerAndMetaTagController::class)->only(['index', 'edit', 'update'])->names('banner-and-meta-tags')->middleware('permission:banner-and-meta-tags,admin');
        // policies
        Route::resource('policies', PolicyController::class)->except(['show'])->names('policies')->middleware('permission:policies,admin');
        // locations
        Route::middleware('permission:locations,admin')->group(function () {
            Route::resource('countries', CountryController::class)->only(['index'])->names('countries');
            Route::resource('states', StateController::class)->only(['index'])->names('states');
            Route::resource('cities', CityController::class)->only(['index'])->names('cities');
            Route::get('get-cities', [CityController::class, 'getCities'])->name('get-cities');
        });
        // space categories and spaces
        Route::middleware('permission:spaces,admin')->group(function () {
            Route::resource('space-categories', SpaceCategoryController::class)->names('space-categories')->except('show');
            Route::resource('space-categories.spaces', SpaceController::class)->names('space-categories.spaces')->except('show');
        });
        // success story categories and success stories
        Route::middleware('permission:success-stories,admin')->group(function () {
            Route::resource('success-story-categories', SuccessStoryCategoryController::class)->names('success-story-categories')->except('show');
            Route::resource('success-story-categories.success-stories', SuccessStoryController::class)->names('success-story-categories.success-stories')->except('show');
        });
        // innovators
        Route::resource('innovators', InnovatorController::class)->except(['show'])->names('innovators')->middleware('permission:innovators,admin');
        // social links
        Route::resource('social-links', SocialLinkController::class)->except(['show'])->names('social-links')->middleware('permission:social-links,admin');
        // enquiries
        Route::middleware('permission:enquiries,admin')->group(function () {
            Route::get('got-a-question-enquiries/export', [GotAQuestionController::class, 'export'])->name('got-a-question-enquiries.export');
            Route::resource('got-a-question-enquiries', GotAQuestionController::class)->only(['index', 'destroy'])->names('got-a-question-enquiries');
            Route::get('visit-enquiries/export', [VisitEnquiryController::class, 'export'])->name('visit-enquiries.export');
            Route::resource('visit-enquiries', VisitEnquiryController::class)->only(['index', 'destroy'])->names('visit-enquiries');
            Route::get('office-enquiries/export', [OfficeEnquiryController::class, 'export'])->name('office-enquiries.export');
            Route::resource('office-enquiries', OfficeEnquiryController::class)->only(['index', 'destroy'])->names('office-enquiries');
            Route::get('bulk-order-enquiries/export', [BulkOrderEnquiryController::class, 'export'])->name('bulk-order-enquiries.export');
            Route::resource('bulk-order-enquiries', BulkOrderEnquiryController::class)->only(['index', 'destroy'])->names('bulk-order-enquiries');
            Route::get('newsletter-subscriptions/export', [NewsletterSubscriptionController::class, 'export'])->name('newsletter-subscriptions.export');
            Route::resource('newsletter-subscriptions', NewsletterSubscriptionController::class)->only(['index', 'destroy'])->names('newsletter-subscriptions');
            Route::get('blog-comments/export', [BlogCommentController::class, 'export'])->name('blog-comments.export');
            Route::resource('blog-comments', BlogCommentController::class)->only(['index', 'show', 'destroy'])->names('blog-comments');
        });
        // masters
        Route::middleware('permission:masters,admin')->group(function () {
            Route::resource('features', FeatureController::class)->names('features')->except('show');
        });

        // categories
        Route::middleware('permission:categories,admin')->group(function () {
            Route::resource('categories', CategoryController::class)->names('categories')->except('show');
            Route::get('get-categories', [CategoryController::class, 'getCategories'])->name('get-categories');
            Route::get('get-product-categories', [CategoryController::class, 'getProductCategories'])->name('get-product-categories');
        });
        // attributes and values
        Route::middleware('permission:attributes,admin')->group(function () {
            Route::resource('attributes', AttributeController::class)->names('attributes')->except('show');
            Route::get('get-attributes', [AttributeController::class, 'getAttributes'])->name('get-attributes');
            Route::resource('attributes.values', AttributeValueController::class)->names('attributes.values')->except('show');
            Route::get('get-attribute-values', [AttributeValueController::class, 'getAttributeValues'])->name('get-attribute-values');
        });
        // products
        Route::middleware('permission:products,admin')->group(function () {
            Route::resource('products', ProductController::class)->names('products');
            Route::get('get-products', [ProductController::class, 'getProducts'])->name('get-products');
            Route::get('get-addons', [ProductController::class, 'getAddons'])->name('get-addons');
            Route::get('get-features', [ProductController::class, 'getFeatures'])->name('get-features');
            Route::get('get-variant-template', [ProductController::class, 'getVariantTemplate'])->name('get-variant-template');
            Route::get('get-variant-attribute-row', [ProductController::class, 'getAttributeRow'])->name('get-attribute-row');
            Route::get('generate-variations', [ProductController::class, 'generateVariations'])->name('generate-variations');
            Route::resource('products.galleries', ProductGalleryController::class)->names('products.galleries')->except('show');
            Route::resource('products.variants', ProductVariantController::class)->names('products.variants')->except('show');
            Route::resource('product-variants.galleries', ProductVariantGalleryController::class)->names('product-variants.galleries')->except('show');
            Route::resource('products.bulk-orders', ProductBulkOrderController::class)->names('products.bulk-orders')->except('show');
            Route::resource('products.attribute-value-medias', ProductAttributeValueMediaController::class)->names('products.attribute-value-medias')->only(['index', 'store']);
        });

        // custom product landing
        Route::middleware('permission:product-custom-landing,admin')->group(function () {
            Route::resource('product-custom-landings', ProductCustomLandingController::class)->names('product-custom-landings');
            Route::resource('product-custom-landings.productivity', PCLProductivityController::class)->names('product-custom-landings.productivity')->except('show');
            Route::resource('product-custom-landings.mindful-engineering', PCLMindfulEngineeringController::class)->names('product-custom-landings.mindful-engineering')->except('show');
            Route::resource('product-custom-landings.model', PCLModelController::class)->names('product-custom-landings.model')->except('show');
            Route::resource('product-custom-landings.faqs', PCLFaqController::class)->names('product-custom-landings.faqs')->except('show');
        });

        // product reviewws
        Route::resource('product-reviews', ProductReviewController::class)->names('product-reviews')->only(['index', 'destroy'])->middleware('permission:product-reviews,admin');
        Route::resource('notify-mes', NotifyMeController::class)->names('notify-mes')->only(['index', 'destroy'])->middleware('permission:notify-mes,admin');

        // support section smc
        Route::resource('support-section-cms', SupportSectionCmsController::class)->names('support-section-cms')->only(['index', 'store'])->middleware('permission:support-section-cms,admin');

        // orders
        Route::resource('orders', OrderController::class)->names('orders')->only(['index', 'show'])->middleware('permission:orders,admin');
        Route::post('orders/update-status', [OrderController::class, 'changeStatus'])->name('orders.change-status')->middleware('permission:orders,admin');

        // users
        Route::resource('users', UserController::class)->names('users')->only(['index', 'show'])->middleware('permission:users,admin');

        // Pincodes
        Route::resource('pincodes', PincodeController::class)->names('pincodes')->except(['show'])->middleware('permission:pincodes,admin');

        // search
        Route::group(['prefix' => 'search'], function () {
            Route::get('users', [SearchController::class, 'users'])->name('search.users');
        });

        //google reviews
        Route::resource('google-reviews', GoogleReviewController::class)->names('google-reviews')->except(['show'])->middleware('permission:google-reviews,admin');
    });
});

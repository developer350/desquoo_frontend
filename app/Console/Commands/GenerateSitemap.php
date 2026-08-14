<?php

namespace App\Console\Commands;

use App\Models\Blog;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductCustomLanding;
use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class GenerateSitemap extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate the sitemap.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $sitemap = Sitemap::create();

        $this->addStaticRoutes($sitemap);

        ProductCustomLanding::query()->select('id', 'slug')->active()->get()->each(function (ProductCustomLanding $product) use ($sitemap) {
            $sitemap->add(
                Url::create(route('custom-page', ['slug' => $product->slug]))
                    ->setPriority(0.8)
                    ->setLastModificationDate(now())
            );
        });

        Blog::query()->select('id', 'slug')->active()->get()->each(function (Blog $blog) use ($sitemap) {
            $sitemap->add(
                Url::create(route('blogArticle', ['slug' => $blog->slug]))
                    ->setPriority(0.8)
                    ->setLastModificationDate(now())
            );
        });

        Category::query()->select('id', 'slug')->where('parent_id', null)->active()->get()->each(function (Category $category) use ($sitemap) {
            $sitemap->add(
                Url::create(route('category-detail', ['slug' => $category->slug]))
                    ->setPriority(0.8)
                    ->setLastModificationDate(now())
            );
        });

        Category::query()->select('id', 'slug', 'parent_id')->with('parent:id,slug')->whereHas('parent', function ($q) {
            $q->active();
        })->active()->where('parent_id', '!=', null)->get()->each(function (Category $subCategory) use ($sitemap) {
            $sitemap->add(
                Url::create(route('sub-category-detail', ['slug' => $subCategory->parent->slug, 'subcategory' => $subCategory->slug]))
                    ->setPriority(0.8)
                    ->setLastModificationDate(now())
            );
        });

        Product::query()->select('id', 'slug')->active()->get()->each(function (Product $product) use ($sitemap) {
            $sitemap->add(
                Url::create(route('product-detail', ['slug' => $product->slug]))
                    ->setPriority(0.8)
                    ->setLastModificationDate(now())
            );
        });

        $sitemap->writeToFile(public_path('sitemap.xml'));

        $this->info('Sitemap generated successfully.');
    }

    /**
     * Add static routes to the sitemap.
     *
     * @return void
     */
    protected function addStaticRoutes(Sitemap $sitemap)
    {
        collect([
            'home',
            'office-design',
            'bulkOrder',
            'blog',
            'product-listing',
            'login',
            'signup',
            'refund',
            'termsandcondition',
            'privacy',
        ])->each(function ($route) use ($sitemap) {
            $sitemap->add(
                Url::create(route($route))
                    ->setPriority(1)
                    ->setLastModificationDate(now())
            );
        });
    }
}

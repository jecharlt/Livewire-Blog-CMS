<?php

namespace Jecharlt\LivewireBlogCMS;

use Jecharlt\LivewireBlogCMS\Http\Middleware\BlogAuthenticate;
use Jecharlt\LivewireBlogCMS\Http\Middleware\BlogGuest;
use Livewire\Livewire;
use Jecharlt\LivewireBlogCMS\Livewire\Components\{
  AdminNavbar, AllArticles, ArticleTypes, BlogDetails, BlogUsers,
  Categories, ComingSoon, Header, MobileWarning, NewArticle
};
use Jecharlt\LivewireBlogCMS\Livewire\Pages\{
    AdminDashboard, AdminLogin
};

use Spatie\LaravelPackageTools\Package;
use Illuminate\Support\Facades\Config;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Spatie\LaravelPackageTools\Commands\InstallCommand;

class LivewireBlogCMSServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('livewire-blog-cms')
            ->hasViews()
            ->hasConfigFile([
                'livewire-blog-cms'
            ])
            ->hasAssets()
            ->hasMigrations([
                'create_blog_users_table',
                'create_blog_categories_table',
                'create_blog_article_types_table',
                'create_blog_articles_table',
                'create_blog_details_table'
            ])
            ->hasRoutes('web');
    }

    public function boot(): void {
        Livewire::component('blog-admin.components.admin-navbar', AdminNavbar::class);
        Livewire::component('blog-admin.components.all-articles', AllArticles::class);
        Livewire::component('blog-admin.components.article-types', ArticleTypes::class);
        Livewire::component('blog-admin.components.blog-details', BlogDetails::class);
        Livewire::component('blog-admin.components.blog-users', BlogUsers::class);
        Livewire::component('blog-admin.components.categories', Categories::class);
        Livewire::component('blog-admin.components.coming-soon', ComingSoon::class);
        Livewire::component('blog-admin.components.header', Header::class);
        Livewire::component('blog-admin.components.mobile-warning', MobileWarning::class);
        Livewire::component('blog-admin.components.new-article', NewArticle::class);
        Livewire::component('blog-admin.pages.admin-dashboard', AdminDashboard::class);
        Livewire::component('blog-admin.pages.admin-login', AdminLogin::class);

        $this->loadRoutesFrom(__DIR__ . '/../src/web.php');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'livewire-blog-cms');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->publishes([
            __DIR__ . '/../public/ckeditor5' => public_path('vendor/livewire-blog-cms/ckeditor5'),
            __DIR__ . '/../resources/css' => public_path('vendor/livewire-blog-cms/css'),
        ], 'livewire-blog-cms-assets');
        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/livewire-blog-cms')
        ], 'livewire-blog-cms-views');
        $this->publishes([
            __DIR__ . '/../config' => config_path('livewire-blog-cms')
        ], 'livewire-blog-cms-config');
        $this->publishes([
            __DIR__ . '/../database/migrations' => database_path('migrations/vendor/livewire-blog-cms')
        ], 'livewire-blog-cms-migrations');
        $this->publishes([
            __DIR__ . '/../src/Commands' => app_path('Console/Commands/LivewireBlogCMS')
        ], 'livewire-blog-cms-commands');
        $this->publishes([
            __DIR__ . '/../src/Http/Controllers' => app_path('Http/Controllers/LivewireBlogCMS')
        ], 'livewire-blog-cms-controllers');
        $this->publishes([
            __DIR__ . '/../src/Livewire' => app_path('Livewire/LivewireBlogCMS')
        ], 'livewire-blog-cms-livewire-component-classes');
        $this->publishes([
            __DIR__ . '/../src/Models' => app_path('Models/LivewireBlogCMS')
        ], 'livewire-blog-cms-models');
        $this->publishes([
            __DIR__ . '/../src/web.php' => base_path('routes/livewire-blog-cms.php')
        ], 'livewire-blog-cms-routes');

        Config::set('auth.guards.blog', [
            'driver' => 'session',
            'provider' => 'blog_users'
        ]);

        Config::set('auth.providers.blog_users', [
            'driver' => 'eloquent',
            'model' => \Jecharlt\LivewireBlogCMS\Models\BlogUser::class
        ]);

        if ($this->app->runningInConsole()) {
            $this->commands([
                \Jecharlt\LivewireBlogCMS\Commands\LivewireBlogCMSInstall::class
            ]);
        };
    }
}

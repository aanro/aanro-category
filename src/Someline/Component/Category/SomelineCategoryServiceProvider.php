<?php

namespace Someline\Component\Category;


use Dingo\Api\Routing\Router as ApiRouter;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;
use Someline\Models\Category\SomelineCategory;
use Someline\Repositories\Eloquent\SomelineCategoryRepositoryEloquent;
use Someline\Repositories\Interfaces\SomelineCategoryRepository;

class SomelineCategoryServiceProvider extends ServiceProvider
{

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        if (class_exists(SomelineCategory::class)) {
            Relation::morphMap([
                SomelineCategory::MORPH_NAME => SomelineCategory::class,
            ]);
        }
        $this->loadMigrationsFrom(__DIR__ . '/../../../database/migrations');
        $this->publishes([
            __DIR__ . '/../../../config/config.php' => config_path('someline-category.php'),

            // master files
            __DIR__ . '/../../../master/Api/SomelineCategory.php.dist' => app_path('Models/Category/SomelineCategory.php'),
            __DIR__ . '/../../../master/Api/SomelineCategoryRepository.php.dist' => app_path('Repositories/Interfaces/SomelineCategoryRepository.php'),
            __DIR__ . '/../../../master/Api/SomelineCategoryRepositoryEloquent.php.dist' => app_path('Repositories/Eloquent/SomelineCategoryRepositoryEloquent.php'),
            __DIR__ . '/../../../master/Api/SomelineCategoriesController.php.dist' => app_path('Api/Controllers/SomelineCategoriesController.php'),
            __DIR__ . '/../../../master/Api/SomelineCategoryTransformer.php.dist' => app_path('Transformers/SomelineCategoryTransformer.php'),
            __DIR__ . '/../../../master/Api/SomelineCategoryValidator.php.dist' => app_path('Validators/SomelineCategoryValidator.php'),
            __DIR__ . '/../../../master/Http/Console/SomelineCategoryController.php.dist' => app_path('Http/Controllers/Console/SomelineCategoryController.php'),

            // database
            __DIR__ . '/../../../database/seeds/SomelineCategoriesTableSeeder.php.dist' => base_path('database/seeds/SomelineCategoriesTableSeeder.php'),

            // resources folders
            __DIR__ . '/../../../resources/assets/js/console' => resource_path('assets/js/components/console/categories'),
            __DIR__ . '/../../../resources/views/console' => resource_path('views/console/categories'),
        ]);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../../../config/config.php',
            'someline-category'
        );

        // repository
        if (interface_exists(SomelineCategoryRepository::class)) {
            $this->app->bind(SomelineCategoryRepository::class, SomelineCategoryRepositoryEloquent::class);
        }
    }

    public static function api_routes(ApiRouter $api, callable $callback = null)
    {

        $api->group(['prefix' => 'categories'], function (ApiRouter $api) use ($callback) {
            $callback && call_user_func($callback, $api);

            $api->get('/', 'SomelineCategoriesController@index');
            $api->post('/', 'SomelineCategoriesController@store');
            $api->get('/{id}', 'SomelineCategoriesController@show');
            $api->put('/', 'SomelineCategoriesController@updateCategories');
            $api->put('/{id}', 'SomelineCategoriesController@update');
            $api->delete('/{id}', 'SomelineCategoriesController@destroy');

        });

    }

    public static function console_routes(callable $callback = null)
    {
        \Route::group(['prefix' => 'categories'], function () use ($callback) {
            $callback && call_user_func($callback);

            \Route::get('/', 'SomelineCategoryController@getCategoryList')->name('console.category.list');
            \Route::get('/new', 'SomelineCategoryController@getCategoryNew')->name('console.category.new');
            \Route::get('/{shop_id}/edit', 'SomelineCategoryController@getCategoryEdit')->name('console.category.edit');

        });
    }

    public static function getConfig($name)
    {
        return config('someline-category.' . $name);
    }

    public static function isWithPackage($package_name)
    {
        $packages_config = self::getConfig('packages');
        return (isset($packages_config[$package_name]) && $packages_config[$package_name]);
    }

}
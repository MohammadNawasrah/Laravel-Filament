<?php

namespace App\Providers;

use App\Models\Action;
use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use App\Models\UserPermission;
use App\Policies\ActionPolicy;
use App\Policies\GenericPolicy;
use App\Policies\UserPermissionPolicy;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use ReflectionClass;

class AppServiceProvider extends ServiceProvider
{
    // protected $policies = [
    //     Action::class => GenericPolicy::class,
    // ];
    /**
     * Register any application services.
     */
    public function register(): void
    {
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        foreach ($this->getAllModels() as $model) {
            Gate::policy($model, GenericPolicy::class);
        }

    }
    protected function getAllModels()
    {
        $modelsPath = app_path('Models');
        $modelsNamespace = 'App\Models\\';
        $allFiles = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($modelsPath));
        $models = [];

        foreach ($allFiles as $file) {
            if ($file->isFile() && $file->getExtension() === 'php') {
                $relativePath = str_replace($modelsPath . DIRECTORY_SEPARATOR, '', $file->getPathname());
                $className = $modelsNamespace . str_replace([DIRECTORY_SEPARATOR, '.php'], ['\\', ''], $relativePath);

                if (class_exists($className)) {
                    $reflection = new ReflectionClass($className);
                    if ($reflection->isSubclassOf('Illuminate\Database\Eloquent\Model') && !$reflection->isAbstract()) {
                        $models[] = $className;
                    }
                }
            }
        }

        return $models;
    }
}

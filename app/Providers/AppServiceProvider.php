<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Post;
use app\Models\User;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define('update-post', function (User $user , Post $post){
            return $user->id === $post->user_id;
        });
        Gate::define('delete-post', function (User $user , Post $post){
            return $user->id === $post->user_id;
        });
        Gate::define('hdelete-post', function (User $user , Post $post){
            return $user->id === $post->user_id;
        });
        Gate::define('restore-post', function (User $user , Post $post){
            return $user->id === $post->user_id;
        });
    }
}

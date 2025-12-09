<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password; // Import this

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
        // Define Global Password Rules
        Password::defaults(function () {
            $rule = Password::min(8)
                        ->letters()
                        ->mixedCase()
                        ->numbers()
                        ->symbols();

            // Enforce "Uncompromised" check in Production only
            // This checks the password against a database of known data leaks.
            if ($this->app->isProduction()) {
                $rule->uncompromised(); 
            }

            return $rule;
        });
    }
}
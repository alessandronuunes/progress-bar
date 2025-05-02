<?php

namespace App\Providers;

use Filament\View\PanelsRenderHook;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Filament\Support\Facades\FilamentView;

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
        //
        FilamentView::registerRenderHook(
            PanelsRenderHook::CONTENT_START,
            fn (): View => view('progress-bar'),
        );
        // FilamentView::registerRenderHook(
        //     PanelsRenderHook::CONTENT_START,
        //     fn (): string => Blade::render('@livewire(\'progress-bar-component\')'),
        // );
    }
}

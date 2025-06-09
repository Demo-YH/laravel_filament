<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Model;
use Filament\Support\Facades\FilamentView;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //ファイルを更新すると自動でページのリフレッシュ
        FilamentView::registerRenderHook('panels::body.end',fn():string => Blade::render("@vite('resources/js/app.js')"));
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //Laravel の一括割り当て保護を無効、フィラメントは有効なデータのみをモデルに
        //保存するため、モデルを安全に保護解除できる。
        Model::unguard();
    }
}

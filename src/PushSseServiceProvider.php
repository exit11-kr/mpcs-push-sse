<?php

namespace Mpcs\PushSse;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory as EloquentFactory;
use Mpcs\Core\Facades\Core;

class PushSseServiceProvider extends ServiceProvider
{

    /**
     * @var array
     */
    protected $commands = [
        Commands\InstallCommand::class,
    ];


    public function boot()
    {

        // 뷰템플릿 로드
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'mpcs-push-sse');

        /* 콘솔에서 vendor:publish 가동시 설치 파일 */
        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
            $this->loadMigrationsFrom(__DIR__ . '/../database/migrations/universal');
            $this->publishes([__DIR__ . '/../dist/' => public_path() . '/vendor/mpcs/push-sse/'], 'mpcs-push-sse-assets');
        }

        /* 라우터, 다국어 */
        $this->app->booted(function () {

            // 다국어 알리어스를 mpcs로 네이밍 규칙을 통일하여 사용하기로 함
            $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'mpcs-push-sse');
            $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        });

        Core::setPackageModelVendors(__NAMESPACE__, __DIR__);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        // config
        $this->mergeConfigFrom(__DIR__ . '/../config/mpcspushsse.php', 'mpcspushsse');

        $this->commands($this->commands);
    }
}

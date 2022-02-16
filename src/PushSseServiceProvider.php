<?php

namespace Exit11\PushSse;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory as EloquentFactory;

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
            $this->publishes([__DIR__ . '/../config' => config_path()], 'mpcs-push-sse-config');
            $this->publishes([__DIR__ . '/../dist/' => public_path() . '/vendor/exit11/push-sse/'], 'mpcs-push-sse-assets');
        }

        /* 라우터, 다국어 */
        $this->app->booted(function () {

            // 다국어 알리어스를 mpcs로 네이밍 규칙을 통일하여 사용하기로 함
            //$this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'mpcs-push-sse');
            $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        });
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->commands($this->commands);
    }
}

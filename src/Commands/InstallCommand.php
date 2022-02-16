<?php

namespace Exit11\PushSse\Commands;

use Illuminate\Console\Command;

class InstallCommand extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mpcs-push-sse:install';

    /**
     * 커맨드 설명
     *
     * @var string
     */
    protected $description = 'Install the mpcs-push-sse : vendor publish, migrate, seed';

    protected $isForce = false;
    protected $isReset = false;

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->isForce = true;
        $this->isReset = true;

        $confirmMessage = 'PushSse Package를 설치하시겠습니까?';
        $confirmMessage .= PHP_EOL . '(DB Table 생성, Permission 구성 및 초기 데이터 생성)';

        if (!app()->environment(['production'])) {
            if ($this->confirm($confirmMessage)) {

                // publish
                $this->call('vendor:publish', [
                    '--provider' => 'Exit11\PushSse\PushSseServiceProvider',
                    '--force' => $this->isForce
                ]);

                $this->call('cache:clear');
                $this->call('config:cache');
            }
        } else {
            $this->error('운영환경에서는 실행할 수 없습니다.');
            exit();
        }
    }
}

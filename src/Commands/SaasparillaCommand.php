<?php

namespace R4nkt\Saasparilla\Commands;

use Illuminate\Console\Command;

class SaasparillaCommand extends Command
{
    public $signature = 'laravel-saasparilla';

    public $description = 'My command';

    public function handle()
    {
        $this->comment('All done');
    }
}

<?php

namespace R4nkt\Saasparilla\Commands;

use Illuminate\Console\Command;
use R4nkt\Saasparilla\Support\Facades\Saasparilla;

class CullUnverifiedUsers extends Command
{
    protected $signature = 'saasparilla:cull-unverified-users';

    public $description = 'Finds unverified users, marks them for deletion, and notifies them via mail.';

    public function handle()
    {
        $this->info('Culling unverified users...');

        $count = Saasparilla::cullUnverifiedUsers();

        $this->comment("Culled {$count} unverified users.");

        $this->info('All done!');
    }
}

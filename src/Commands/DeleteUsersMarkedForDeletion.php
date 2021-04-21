<?php

namespace R4nkt\Saasparilla\Commands;

use Illuminate\Console\Command;
use R4nkt\Saasparilla\Support\Facades\Saasparilla;

class DeleteUsersMarkedForDeletion extends Command
{
    protected $signature = 'saasparilla:delete-users-marked-for-deletion';

    public function handle()
    {
        $this->info('Deleting users marked for deletion...');

        $count = Saasparilla::deleteUsersMarkedForDeletion();

        $this->comment("Deleted {$count} users marked for deletion.");

        $this->info('All done!');
    }
}

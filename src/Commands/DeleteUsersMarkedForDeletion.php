<?php

namespace R4nkt\Saasparilla\Commands;

use Illuminate\Console\Command;
use R4nkt\Saasparilla\Support\Facades\Saasparilla;

class DeleteUsersMarkedForDeletion extends Command
{
    protected $signature = 'saasparilla:delete-users-ready-for-deletion';

    public $description = 'Deletes all users ready for deletion.';

    public function handle()
    {
        $this->info('Deleting users ready for deletion...');

        $count = Saasparilla::purgeCulledUsers();

        $this->comment("Deleted {$count} users ready for deletion.");

        $this->info('All done!');
    }
}

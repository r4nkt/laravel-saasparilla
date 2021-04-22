<?php

namespace R4nkt\Saasparilla\Commands;

use Illuminate\Console\Command;
use R4nkt\Saasparilla\Support\Facades\Saasparilla;

class MarkUnverifiedUsersForDeletion extends Command
{
    protected $signature = 'saasparilla:mark-unverified-users-for-deletion';

    public $description = 'Finds unverified users, marks them for deletion, and notifies them via mail.';

    public function handle()
    {
        $this->info('Finding unverified users and marking them for deletion...');

        $count = Saasparilla::marksUnverifiedUsersForDeletion();

        $this->comment("Found {$count} unverified users and marked them for deletion.");

        $this->info('All done!');
    }
}

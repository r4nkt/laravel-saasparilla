<?php

namespace R4nkt\Saasparilla\Actions;

use R4nkt\Saasparilla\Actions\Contracts\DeletesDeletableResources;

class DeleteDeletableUsers implements DeletesDeletableResources
{
    use Concerns\HasDeleter;
    use Concerns\HasGetter;
    use Concerns\HasParams;

    public function execute(): int
    {
        $deletedCount = 0;

        $deleter = $this->deleter();

        $this->getter()
            ->get()
            ->each(function ($user) use (&$deletedCount, $deleter) {
                if (! $deleter->delete($user)) {
                    return;
                }

                $deletedCount++;
            });

        return $deletedCount;
    }
}

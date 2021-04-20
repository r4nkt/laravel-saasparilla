<?php

namespace R4nkt\Saasparilla\Actions;

use R4nkt\Saasparilla\Actions\Contracts\MarksResourcesForDeletion;

class MarkUsersForDeletion implements MarksResourcesForDeletion
{
    use Concerns\HasGetter;
    use Concerns\HasMarker;
    use Concerns\HasNotifier;
    use Concerns\HasParams;

    public function execute(): int
    {
        $markedForDeletionCount = 0;

        $marker = $this->marker();
        $notifier = $this->notifier();

        $this->getter()
            ->get()
            ->each(function ($user) use (&$markedForDeletionCount, $marker, $notifier) {
                $marker->mark($user);

                optional($notifier)->notify($user);

                $markedForDeletionCount++;
            });

        return $markedForDeletionCount;
    }
}

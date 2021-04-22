<?php

namespace R4nkt\Saasparilla\Models\Concerns;

use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Event;
use R4nkt\Saasparilla\Features;
use R4nkt\Saasparilla\UnmarkerFactory;

trait CanBeMarkedForDeletion
{
    protected static function bootCanBeMarkedForDeletion()
    {
        if (Features::hasMarksUnverifiedUsersForDeletionFeature()) {
            Event::listen(function (Verified $event) {
                $options = Features::options(Features::marksUnverifiedUsersForDeletion());

                $unmarker = UnmarkerFactory::make($options['params']['unmarker']);

                $unmarker->unmark($event->user);
            });
        }
    }

    /**
     * Initialize the trait
     *
     * @return void
     */
    protected function initializeCanBeMarkedForDeletion(): void
    {
        $this->mergeCasts(['automatically_delete_at' => 'datetime']);
    }

    public function getMarkedForDeletionAttribute(): bool
    {
        return (bool) $this->automatically_delete_at;
    }
}

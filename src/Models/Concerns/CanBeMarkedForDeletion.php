<?php

namespace R4nkt\Saasparilla\Models\Concerns;

use Carbon\Carbon;
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

    public function getAutomaticallyDeleteAtAttribute(?string $value): ?Carbon
    {
        return $value ? $this->asDateTime($value) : $value;
    }

    public function setAutomaticallyDeleteAtAttribute(?Carbon $value): void
    {
        $this->attributes['automatically_delete_at'] = $this->fromDateTime($value);
    }

    public function getMarkedForDeletionAttribute(): bool
    {
        return (bool) $this->automatically_delete_at;
    }
}

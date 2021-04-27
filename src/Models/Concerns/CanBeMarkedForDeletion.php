<?php

namespace R4nkt\Saasparilla\Models\Concerns;

use Carbon\Carbon;

trait CanBeMarkedForDeletion
{
    public function getDeletingSoonMailSentAtAttribute(?string $value): ?Carbon
    {
        return $value ? $this->asDateTime($value) : $value;
    }

    public function setDeletingSoonMailSentAtAttribute(?Carbon $value): void
    {
        $this->attributes['deleting_soon_mail_sent_at'] = $this->fromDateTime($value);
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

<?php

namespace R4nkt\Saasparilla\Models\Concerns;

trait CanBeMarkedForDeletion
{
    public function getMarkedForDeletionAttribute(): bool
    {
        return (bool) $this->automatically_delete_at;
    }
}

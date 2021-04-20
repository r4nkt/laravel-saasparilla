<?php

namespace R4nkt\Saasparilla\Models\Concerns;

trait CanBeMarkedForDeletion
{
    public function getMarkedForDeletionAttribute()
    {
        return (bool) $this->automatically_delete_at;
    }
}

<?php

namespace R4nkt\Saasparilla\Actions\Default;

use R4nkt\ResourceTidier\Actions\Contracts\UnmarksResource;
use R4nkt\ResourceTidier\Concerns\HasParams;

class UnmarkUserMarkedForDeletion implements UnmarksResource
{
    use HasParams;

    public function unmark(mixed $resource): void
    {
        $resource->deleting_soon_mail_sent_at = null;
        $resource->automatically_delete_at = null;
        $resource->save();
    }
}

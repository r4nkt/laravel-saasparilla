<?php

namespace R4nkt\Saasparilla\Actions\Default;

use R4nkt\Saasparilla\Actions\Concerns\HasParams;
use R4nkt\Saasparilla\Actions\Contracts\UnmarksResourceMarkedForDeletion;

class UnmarkUserMarkedForDeletion implements UnmarksResourceMarkedForDeletion
{
    use HasParams;

    public function unmark($resource): void
    {
        $resource->deleting_soon_mail_sent_at = null;
        $resource->automatically_delete_at = null;
        $resource->save();
    }
}

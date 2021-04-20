<?php

namespace R4nkt\Saasparilla\Actions\Default;

use R4nkt\Saasparilla\Actions\Concerns\HasParams;
use R4nkt\Saasparilla\Actions\Contracts\SavesDeletableResource;

class SaveDeletableUser implements SavesDeletableResource
{
    use HasParams;

    public function save($resource): void
    {
        $resource->deleting_soon_mail_sent_at = null;
        $resource->automatically_delete_at = null;
        $resource->save();
    }
}

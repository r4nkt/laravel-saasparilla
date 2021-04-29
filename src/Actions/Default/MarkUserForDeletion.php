<?php

namespace R4nkt\Saasparilla\Actions\Default;

use R4nkt\ResourceTidier\Actions\Contracts\MarksResource;
use R4nkt\ResourceTidier\Concerns\HasParams;

class MarkUserForDeletion implements MarksResource
{
    use HasParams;

    public function mark(mixed $resource): void
    {
        $resource->deleting_soon_mail_sent_at = now();
        $resource->automatically_delete_at = now()->addDays($this->requiredParam('grace'));
        $resource->save();
    }
}

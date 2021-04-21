<?php

namespace R4nkt\Saasparilla\Actions\Default;

use R4nkt\Saasparilla\Actions\Concerns\HasParams;
use R4nkt\Saasparilla\Actions\Contracts\MarksResource;

class MarkUserForDeletion implements MarksResource
{
    use HasParams;

    public function mark(mixed $resource): void
    {
        $resource->deleting_soon_mail_sent_at = now();
        $resource->automatically_delete_at = now()->addDays($this->param('grace', 30));
        $resource->save();
    }
}

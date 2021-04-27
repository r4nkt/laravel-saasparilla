<?php

namespace R4nkt\Saasparilla\Actions\Default;

use R4nkt\ResourceTidier\Actions\Contracts\ExecutesResourceTask;
use R4nkt\ResourceTidier\Concerns\HasParams;

class DeleteUser implements ExecutesResourceTask
{
    use HasParams;

    public function execute(mixed $resource): bool
    {
        return $resource->delete();
    }
}

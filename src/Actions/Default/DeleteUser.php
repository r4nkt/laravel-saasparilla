<?php

namespace R4nkt\Saasparilla\Actions\Default;

use R4nkt\Saasparilla\Actions\Concerns\HasParams;
use R4nkt\Saasparilla\Actions\Contracts\DeletesResource;

class DeleteUser implements DeletesResource
{
    use HasParams;

    public function delete(mixed $resource): bool
    {
        return $resource->delete();
    }
}

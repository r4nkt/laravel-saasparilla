<?php

namespace R4nkt\Saasparilla\Actions\Jetstream;

use Laravel\Jetstream\Contracts\DeletesUsers;
use R4nkt\Saasparilla\Actions\Concerns\HasParams;
use R4nkt\Saasparilla\Actions\Contracts\DeletesResource;

class DeleteUser implements DeletesResource
{
    use HasParams;

    public function delete(mixed $resource): bool
    {
        app(DeletesUsers::class)->delete($resource);

        return ! $resource->exists;
    }
}

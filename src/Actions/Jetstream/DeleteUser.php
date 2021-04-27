<?php

namespace R4nkt\Saasparilla\Actions\Jetstream;

use Laravel\Jetstream\Contracts\DeletesUsers;
use R4nkt\ResourceTidier\Actions\Contracts\ExecutesResourceTask;
use R4nkt\ResourceTidier\Concerns\HasParams;

class DeleteUser implements ExecutesResourceTask
{
    use HasParams;

    public function delete(mixed $resource): bool
    {
        app(DeletesUsers::class)->delete($resource);

        return ! $resource->exists;
    }
}

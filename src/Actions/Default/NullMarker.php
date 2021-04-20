<?php

namespace R4nkt\Saasparilla\Actions\Default;

use R4nkt\Saasparilla\Actions\Concerns\HasParams;
use R4nkt\Saasparilla\Actions\Contracts\MarksResource;

class NullMarker implements MarksResource
{
    use HasParams;

    public function mark($resource): void
    {
        return;
    }
}

<?php

namespace R4nkt\Saasparilla\Actions\Contracts;

interface NotifiesUser
{
    public function setParams(array $params = []): mixed;

    public function notify(mixed $user): void;
}

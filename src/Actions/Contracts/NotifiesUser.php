<?php

namespace R4nkt\Saasparilla\Actions\Contracts;

interface NotifiesUser
{
    public function setParams(array $params = []);

    public function notify($user): void;
}

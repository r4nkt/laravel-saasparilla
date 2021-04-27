<?php

namespace R4nkt\Saasparilla;

use Illuminate\Support\Arr;
use R4nkt\ResourceTidier\Support\Facades\ResourceTidier;
use R4nkt\Saasparilla\Exceptions\FeatureNotEnabled;

class Saasparilla
{
    public function unverifiedUserTidier()
    {
        $options = Features::options(Features::cleansUpUnverifiedUsers());

        return ResourceTidier::tidier(Arr::get($options, 'tidier'));
    }

    public function cullUnverifiedUsers(): ?int
    {
        if (! Features::hasCleansUpUnverifiedUsersFeature()) {
            FeatureNotEnabled::cleansUpUnverifiedUsers();
        }

        return $this->unverifiedUserTidier()->cull();
    }

    public function purgeCulledUsers(): ?int
    {
        if (! Features::hasCleansUpUnverifiedUsersFeature()) {
            FeatureNotEnabled::cleansUpUnverifiedUsers();
        }

        return $this->unverifiedUserTidier()->handle();
    }
}

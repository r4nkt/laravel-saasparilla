<?php

namespace R4nkt\Saasparilla;

use Illuminate\Support\Arr;
use R4nkt\Saasparilla\Exceptions\FeatureNotEnabled;

class Saasparilla
{
    public function markUnverifiedUsersForDeletion(): ?int
    {
        if (! Features::hasMarksUnverifiedUsersForDeletionFeature()) {
            FeatureNotEnabled::marksUnverifiedUsersForDeletion();
        }

        return $this->buildAction(Features::marksUnverifiedUsersForDeletion());
    }

    public function deleteUsersMarkedForDeletion(): ?int
    {
        if (! Features::hasDeletesUsersMarkedForDeletionFeature()) {
            FeatureNotEnabled::deletesUsersMarkedForDeletionFeature();
        }

        return $this->buildAction(Features::deletesUsersMarkedForDeletion());
    }

    protected function buildAction(string $feature)
    {
        $options = Features::options($feature);

        $class = Arr::get($options, 'class');
        $params = Arr::get($options, 'params', []);

        return (new $class())
            ->setParams($params)
            ->execute();
    }
}

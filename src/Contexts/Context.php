<?php

namespace R4nkt\Saasparilla\Contexts;

use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Collection;

class Context
{
    protected string $model;
    protected array $find;
    protected ?array $mark;
    protected ?array $warn;
    protected ?array $delete;

    public function __construct(array $config)
    {
        $this->model = $config['model'] ?? User::class;
        $this->find = $config['actions']['find'];
        $this->mark = $config['actions']['mark'];
        $this->warn = $config['actions']['warn'];
        $this->delete = $config['actions']['delete'];
    }

    public function find(): Collection
    {
        $class = $this->find['class'];
        $params = $this->find['params'];

        return (new $class($this->model, $params))->find();
    }

    public function warn(Collection $resources): void
    {
        $class = $this->warn['class'];
        $params = $this->find['params'];

        (new $class($params))->warn($resources);
    }

    public function mark(): void
    {
        // $class = $this->find['class'];
        // $params = $this->find['params'];

        (new $this->mark($this->model))->mark();
    }

    public function delete(): void
    {
        (new $this->delete($this->model))->delete();
    }
}

<?php

namespace App\Repository\Eloquent;

use App\Repository\EloquentRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class BaseRepository implements EloquentRepositoryInterface
{
    protected $model;

    protected $find = null;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        return $this->model->all();
    }

    public function create(array $attributes)
    {
        return $this->model->create($attributes);
    }

    public function find($id)
    {
        if ($this->find) {
            return $this->find;
        }
        return $this->find = $this->model->find($id);
    }

    public function update($id,$attributes)
    {
        return $this->model->find($id)->update($attributes);
    }
}

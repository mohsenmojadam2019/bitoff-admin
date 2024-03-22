<?php

namespace App\Repository;

interface EloquentRepositoryInterface
{
    public function all();
    
    public function create(array $attributes);

    public function find($id);

    public function update($id,$attributes);

}
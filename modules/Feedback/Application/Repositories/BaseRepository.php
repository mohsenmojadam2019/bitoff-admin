<?php

namespace Bitoff\Feedback\Application\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Http\Request;

class BaseRepository
{
    protected Builder|QueryBuilder|Relation $query;
    protected Request $request;

    /**
     * Set base query
     *
     * @param $query
     * @return $this
     */
    public function setBaseQuery($query): self
    {
        $this->query = $query;
        return $this;
    }


    /**
     * Set Request
     *
     * @param $request
     * @return $this
     */
    public function setRequest($request): self
    {
        $this->request = $request;
        return $this;
    }

    /**
     * Set pagination.
     *
     * @return mixed
     */
    public function paginate(int $perPage = 15)
    {
        return $this->query->paginate($perPage);
    }

    /**
     * Get
     *
     * @param array $columns
     * @return mixed
     */
    public function get(array $columns = ['*'])
    {
        return $this->query->get($columns);
    }

    /**
     * Call paginate or get depending on request (with_pagination)
     *
     * @param string $default // get or paginate
     * @return mixed
     */
    public function getOrPaginateDetectByRequest(string $default = 'get')
    {
        $method = $default === 'paginate' ? 'paginate' : 'get';

        if($this->request->has('page')) {
            $method = 'paginate';
        }

        return $this->$method(
            $method === 'paginate' ? $this->request->get('per_page', 20) : ['*']
        );
    }
}

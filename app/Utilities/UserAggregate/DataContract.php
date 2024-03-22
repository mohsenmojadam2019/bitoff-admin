<?php

namespace App\Utilities\UserAggregate;

use App\Models\UserAggregate;

abstract class DataContract
{
    protected UserAggregate $aggregate;

    /** Column name must fill by each aggregate class. */
    protected string $field;

    public function __construct(UserAggregate $aggregate)
    {
        $this->aggregate = $aggregate;
    }

    public function getField(): string
    {
        return $this->field;
    }

    /**
     * Refresh.
     *
     * this is data fill behavior when column (field) is null
     */
    abstract public function refresh(): DataContractInterface;

    /**
     * Set new data.
     *
     * call this for update column (field)
     *
     * @param mixed $data
     */
    public function set($data): DataContractInterface
    {
        $this->aggregate->{$this->field} = $data;
        $this->aggregate->save();

        $childClassName = get_called_class();

        return new $childClassName($this->aggregate);
    }

    /**
     * Get data.
     *
     * first check , if is not filled first call refresh then call get again
     * if is existed , return column (field)
     *
     * @return mixed
     */
    public function get()
    {
        if (! $this->aggregate->exists || ! isset($this->aggregate->{$this->field})) {
            return $this->refresh()->get();
        }

        return $this->aggregate->refresh()
            ->{$this->field};
    }

    /**
     * Get data with database lock.
     */
    public function getForUpdate(): mixed
    {
        if ($this->isDataEmpty()) {
            return $this->refresh()->getForUpdate();
        }

        return UserAggregate::lockForUpdate()
            ->where('id', $this->aggregate->id)
            ->first()->{$this->field};
    }

    private function isDataEmpty(): bool
    {
        return ! $this->aggregate->exists || ! isset($this->aggregate->{$this->field});
    }
}

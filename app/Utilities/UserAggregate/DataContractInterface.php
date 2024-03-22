<?php

namespace App\Utilities\UserAggregate;

use App\Models\UserAggregate as UserAggregateAlias;

/**
 * DataContractInterface Doc.
 *
 * all fields of user aggregate table must have a class that implements this interface
 * each aggregate class must implements refresh method , this is data fill behavior when column is null
 */
interface DataContractInterface
{
    public function __construct(UserAggregateAlias $aggregate);

    public function get();

    public function set($data): DataContractInterface;

    public function refresh();

    public function getField(): string;

    public function getForUpdate();
}

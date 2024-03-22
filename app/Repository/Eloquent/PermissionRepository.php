<?php 

namespace App\Repository\Eloquent;

use App\Repository\PermissionRepositoryInterface;
use Spatie\Permission\Models\Permission;

class PermissionRepository extends BaseRepository implements PermissionRepositoryInterface
{
    public function __construct(Permission $permission)
    {
        parent::__construct($permission);
    }
}
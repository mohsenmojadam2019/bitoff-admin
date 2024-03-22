<?php

namespace App\Http\Controllers;


use App\Http\Requests\RoleRequest;
use App\Support\ACL;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AclController extends Controller
{

    private $acl;

    public function __construct(ACL $acl)
    {
        $this->acl = $acl;
    }

    public function showAllPermissions()
    {
        return view('acl.permissions')->with([
            'permissions' => $this->acl->allPermissions()
        ]);
    }

    public function showAllRoles()
    {
        $roles = Role::with('permissions', 'users')->get();
        $permissions = $this->acl->allPermissions();
        return view('acl.roles')->with([
            'roles' => $roles,
            'permissions' => $permissions
        ]);
    }

    public function editRole(Role $role)
    {
        return view('acl.edit_role')->with([
            'role' => $role,
            'permissions' => Permission::all()
        ]);
    }

    public function createRole()
    {
        $permissions = Permission::orderBy('name')->get()->groupBy(function ($permission) {
            return explode('.', $permission->name)[0];
        });

        return view('acl.create_role')->with([
            'permissions' => $permissions
        ]);
    }

    public function storeRole(RoleRequest $request)
    {
        Role::create($request->only('name'))
            ->syncPermissions($request->permissions);

        $this->info("Role created");

        return redirect()->route('acl.roles');
    }

    public function updateRole(Role $role, RoleRequest $request)
    {
        $role->fill(['name' => $request->name])
            ->syncPermissions($request->permissions)
            ->save();

        $this->info("Role updated");

        return redirect()->route('acl.roles');

    }

}

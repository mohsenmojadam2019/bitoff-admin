<?php

namespace App\Console\Commands;

use App\Support\ACL;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Spatie\Permission\Models\Permission;

class FillPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permission:db';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Insert to permissions table based on application routes';

    protected $acl;

    public function __construct(ACL $acl)
    {
        parent::__construct();
        $this->acl = $acl;
    }

    /**
     * Execute the console command.
     *
     * @return string
     */
    public function handle()
    {
        $this->clearCache();

        $before = Permission::count();
        foreach ($this->acl->allPermissions() as $permission) {
            Permission::findOrCreate($permission->getName(), ACL::GUARD);
        }
        if ($count = Permission::count() - $before) {
            $this->info(sprintf("%s permission inserted", Permission::count() - $before));
        } else {
            $this->warn(sprintf("No new permission detected"));
        }
    }

    private function clearCache()
    {
        Artisan::call('permission:cache-reset');
    }
}

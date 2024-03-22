<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('permissions')->delete();

        DB::table('permissions')->insertOrIgnore([
            [
                'id' => 1,
                'name' => 'orders',
                'guard_name' => 'web',
                'created_at' => now(),
            ],
            [
                'id' => 2,
                'name' => 'orders.show',
                'guard_name' => 'web',
                'created_at' => now(),
            ],
            [
                'id' => 3,
                'name' => 'orders.resolve',
                'guard_name' => 'web',
                'created_at' => now(),
            ],
            [
                'id' => 4,
                'name' => 'orders.cancel.earner',
                'guard_name' => 'web',
                'created_at' => now(),
            ],
            [
                'id' => 5,
                'name' => 'orders.cancel.shopper',
                'guard_name' => 'web',
                'created_at' => now(),
            ],
            [
                'id' => 6,
                'name' => 'orders.items.cancel',
                'guard_name' => 'web',
                'created_at' => now(),
            ],
            [
                'id' => 7,
                'name' => 'orders.items.deliver',
                'guard_name' => 'web',
                'created_at' => now(),
            ],
            [
                'id' => 8,
                'name' => 'orders.items.tracking',
                'guard_name' => 'web',
                'created_at' => now(),
            ],
            [
                'id' => 9,
                'name' => 'orders.update.tracking',
                'guard_name' => 'web',
                'created_at' => now(),
            ],
            [
                'id' => 10,
                'name' => 'orders.chat.store',
                'guard_name' => 'web',
                'created_at' => now(),
            ],
            [
                'id' => 11,
                'name' => 'users',
                'guard_name' => 'web',
                'created_at' => now(),
            ],
            [
                'id' => 12,
                'name' => 'users.store',
                'guard_name' => 'web',
                'created_at' => now(),
            ],
            [
                'id' => 13,
                'name' => 'users.show',
                'guard_name' => 'web',
                'created_at' => now(),
            ],
            [
                'id' => 14,
                'name' => 'users.update',
                'guard_name' => 'web',
                'created_at' => now(),
            ],
            [
                'id' => 15,
                'name' => 'users.scores',
                'guard_name' => 'web',
                'created_at' => now(),
            ],
            [
                'id' => 16,
                'name' => 'tickets',
                'guard_name' => 'web',
                'created_at' => now(),
            ],
            [
                'id' => 17,
                'name' => 'tickets.get-replies',
                'guard_name' => 'web',
                'created_at' => now(),
            ],
            [
                'id' => 18,
                'name' => 'tickets.store-reply',
                'guard_name' => 'web',
                'created_at' => now(),
            ],
            [
                'id' => 19,
                'name' => 'transactions',
                'guard_name' => 'web',
                'created_at' => now(),
            ],
            [
                'id' => 20,
                'name' => 'transactions.confirm',
                'guard_name' => 'web',
                'created_at' => now(),
            ],
            [
                'id' => 21,
                'name' => 'transactions.manual',
                'guard_name' => 'web',
                'created_at' => now(),
            ],
            [
                'id' => 22,
                'name' => 'settings',
                'guard_name' => 'web',
                'created_at' => now(),
            ],
            [
                'id' => 23,
                'name' => 'settings.store',
                'guard_name' => 'web',
                'created_at' => now(),
            ],
            [
                'id' => 24,
                'name' => 'settings.update',
                'guard_name' => 'web',
                'created_at' => now(),
            ],
            [
                'id' => 25,
                'name' => 'products',
                'guard_name' => 'web',
                'created_at' => now(),
            ],
            [
                'id' => 26,
                'name' => 'areas.index',
                'guard_name' => 'web',
                'created_at' => now(),
            ],
            [
                'id' => 27,
                'name' => 'areas.cities',
                'guard_name' => 'web',
                'created_at' => now(),
            ],
            [
                'id' => 28,
                'name' => 'acl.roles',
                'guard_name' => 'web',
                'created_at' => now(),
            ],
            [
                'id' => 29,
                'name' => 'acl.roles.create',
                'guard_name' => 'web',
                'created_at' => now(),
            ],
            [
                'id' => 30,
                'name' => 'acl.roles.store',
                'guard_name' => 'web',
                'created_at' => now(),
            ],
            [
                'id' => 31,
                'name' => 'acl.roles.edit',
                'guard_name' => 'web',
                'created_at' => now(),
            ],
            [
                'id' => 32,
                'name' => 'acl.roles.update',
                'guard_name' => 'web',
                'created_at' => now(),
            ],
            [
                'id' => 33,
                'name' => 'report.show',
                'guard_name' => 'web',
                'created_at' => now(),
            ],
            [
                'id' => 34,
                'name' => 'report.filter',
                'guard_name' => 'web',
                'created_at' => now(),
            ],
            [
                'id' => 35,
                'name' => 'contacts',
                'guard_name' => 'web',
                'created_at' => now(),
            ],
            [
                'id' => 36,
                'name' => 'activities',
                'guard_name' => 'web',
                'created_at' => now(),
            ],
            [
                'id' => 37,
                'name' => 'pay.refresh',
                'guard_name' => 'web',
                'created_at' => now(),
            ],
            [
                'id' => 38,
                'name' => 'pay.show',
                'guard_name' => 'web',
                'created_at' => now(),
            ],
            [
                'id' => 39,
                'name' => 'products.show',
                'guard_name' => 'web',
                'created_at' => now(),
            ],
            [
                'id' => 40,
                'name' => 'send.wallet.notif',
                'guard_name' => 'web',
                'created_at' => now(),
            ],
            [
                'id' => 41,
                'name' => 'reports.show',
                'guard_name' => 'web',
                'created_at' => now(),
            ],
            [
                'id' => 42,
                'name' => 'usernames.index',
                'guard_name' => 'web',
                'created_at' => now(),
            ],
            [
                'id' => 43,
                'name' => 'username.store',
                'guard_name' => 'web',
                'created_at' => now(),
            ],
            [
                'id' => 44,
                'name' => 'orders.reorder',
                'guard_name' => 'web',
                'created_at' => now(),
            ],
            [
                'id' => 45,
                'name' => 'sync.vip.wallet',
                'guard_name' => 'web',
                'created_at' => now(),
            ],
        ]);
    }
}

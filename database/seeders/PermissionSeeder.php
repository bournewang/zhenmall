<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Permission::whereNotNull('id')->forceDelete();
        $actions = ['View', 'Create', 'Update', 'Delete', 'Restore', 'ForceDelete', 'Action'];
        $resources = config('seed.resources');
        foreach ($resources as $res) {
            foreach ($actions as $action) {
                $label = __($action) . __($res);
                $perm = Permission::create(['name' => $label, 'guard_name' => 'web']);
            }
        }

        foreach (config('seed.permissions') as $name) {
            $perm = Permission::create(['name' => __($name), 'guard_name' => 'web']);
        }
    }
}

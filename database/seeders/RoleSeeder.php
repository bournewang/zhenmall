<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (!$role = Role::where('name', '-')->first()) {
            Role::create(['name' => '-'])->permissions()->sync(Permission::all());
        }

        $role_name = __('System Admin');
        if (!$role = Role::where('name', $role_name)->first()){
            echo "create role $role_name\n";
            $role = Role::create(['name' => $role_name]);
        }
        $role->permissions()->sync(Permission::whereNotIn('name', [
            __('Create').__('PurchaseOrder'),
            __('Update').__('PurchaseOrder'),
            __('Create').__('SalesOrder'),
            __('Update').__('SalesOrder'),
        ])->get());
        $i=1;
        User::find($i++)->assignRole($role);
        foreach (config('seed.roles') as $role_val => $array) {
            $role_name = __(ucwords(str_replace('_', ' ', $role_val)));
            if (!$role = Role::where('name', $role_name)->first()){
                echo "create role $role_name\n";
                $role = Role::create(['name' => $role_name]);
            }
            $perms = [];
            foreach ($array as $item) {
                $b = explode(' ', $item);
                $perms[] = __($b[0]) . (($b[1]??null) ? __($b[1]) : '');
            }
            echo "-----------------------------------------\n";
            echo "set perms to role $role_name \n";
            echo implode(',',$perms) . "\n";
            $permissions = Permission::whereIn('name', $perms)->get();
            $role->permissions()->sync($permissions);

            if ($user = User::where('type', $role_val)->first()) {
                $user->assignRole($role);
                echo "assign $user->name to $role->name\n";
            }
        }
    }
}

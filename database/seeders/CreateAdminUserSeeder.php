<?php



namespace Database\Seeders;



use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class CreateAdminUserSeeder extends Seeder
{

    /**

     * Run the database seeds.

     */

    public function run(): void

    {

        $user = User::create([
            'name' => 'Admin',
            'last_name' => 'Admin',
            'email' => 'admin@1.com',
            'password' => bcrypt('123'),
            'isUser' => 1
        ]);

        $user2 = User::create([
            'name' => 'cliente',
            'last_name' => 'cliente',
            'email' => 'client@1.com',
            'password' => bcrypt('123'),
            'isUser' => 0
        ]);


        $role = Role::create(['name' => 'Admin']);
        $permissions = Permission::where('name', 'not like', '%client%')->pluck('id', 'id');
        $role->syncPermissions($permissions);
        $user->assignRole([$role->id]);


        $role2 = Role::create(['name' => 'Client']);
        $permissions2 = Permission::where('name', 'not like', '%admin%')->pluck('id', 'id');
        $role2->syncPermissions($permissions2);
        $user2->assignRole([$role2->id]);
    }
}

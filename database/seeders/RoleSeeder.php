<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Department;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->seedDefaultPermissions();

        $roles = $this->determineRoles();

        $this->createRoles($roles);
    }

    private function seedDefaultPermissions()
    {

        $this->command->info('Adding default permissions...');

        $defaultPermissions = collect([
            Permission::defaultAdminPermissions(),
            Permission::defaultAgentPermissions(),
            Permission::defaultMarketerPermissions(),
            Permission::defaultManagerPermissions(),
            Permission::defaultDataCollectorPermissions()
        ])->flatten()->unique()->map(function ($permission) {
            return ['name' => $permission, 'guard_name' => 'web'];
        });

        Permission::insert($defaultPermissions->toArray());

        $this->command->info('Default permissions added.');
    }

    private function determineRoles()
    {

        // Confirm roles needed
        if ($this->command->confirm('Would you like to specify your own user Roles? If not, "Super Admin", "Admin" and "User" will automatically be created. ', false)) {

            // Ask for roles from input
            $input_roles = $this->command->ask('Enter roles in comma separate format.', 'Super Admin, Admin, User');

            // Explode roles
            $roles_array = explode(',', $input_roles);

            return $roles_array;
        }

        return Role::defaultRoles();
    }


    private function createRoles($roles)
    {
        // add roles
        foreach ($roles as $role_name) {
            $role_name = ucfirst(str_replace('_', ' ', trim($role_name)));
            $role = $this->createRole($role_name);

            if ($role) {
                $this->createUser($role);
            }
        }
    }

    private function createRole($role_name)
    {
        $this->command->info(sprintf("Creating '%s' Role...", $role_name));

        $role = Role::create(['name' => $role_name, 'guard_name' => 'web']);

        if ($role) {
            $this->command->info(sprintf("'%s' Role created.", $role->name));
            $role->syncPermissions($this->assignRolePermissions($role));
        } else {
            $this->command->error(sprintf("Could not create the '%s' Role!", $role_name));
        }

        return $role;
    }


    private function assignRolePermissions($role)
    {
        switch ($role->name) {
            case 'Admin':
                return Permission::all()->pluck('id');

            case 'Manager':
                return Permission::whereIn('name', Permission::defaultManagerPermissions())->get()->pluck('id');

            case 'Marketer':
                return Permission::whereIn('name', Permission::defaultMarketerPermissions())->get()->pluck('id');

            case 'Agent':
                return Permission::whereIn('name', Permission::defaultAgentPermissions())->get()->pluck('id');

            case 'Data Collector':
                return Permission::whereIn('name', Permission::defaultDataCollectorPermissions())->get()->pluck('id');

            default:
                return [];
        }
    }

    /**
     * Create a user with given role
     *
     * @param $role
     */
    private function createUser($role)
    {

        $this->command->info(sprintf("Creating user for '%s' role...", $role->name));

        $user = $this->createUserWithRole($role);

        if ($user) {
            $this->showUserCredentials($user, $role);
        } else {

            $this->command->error(sprintf("Could not create user for '%s' role!", $role->name));
        }
    }

    private function createUserWithRole($role)
    {
        $userEmails = [
            'Admin' => 'admin@guardians.uk',
            'Agent' => 'agent@guardians.uk',
            'Manager' => 'manager@guardians.uk',
            'Marketer' => 'marketer@guardians.uk',
            'Data Collector' => 'data_collection@guardians.uk',
        ];

        $user = User::factory()->create([
            'email' => $userEmails[$role->name] ?? 'default@guardians.uk',
        ]);

        $user->assignRole($role);
        $user->syncPermissions($this->assignRolePermissions($role));

        $company = Company::inRandomOrder()->first();
        $department = Department::inRandomOrder()->first();
        $user->companies()->attach($company->id, ['department_id' => $department->id]);

        return $user;
    }

    private function showUserCredentials($user, $role)
    {
        $this->command->info($role->name . " Credentials:");

        $this->command->warn(sprintf("Name: %s", $user->name));

        $this->command->warn(sprintf("Email: %s", $user->email));

        $this->command->warn(sprintf("Password: %s", 'password'));
    }
}

<?php

namespace Forone\Admin\Console;

use Artesaos\Defender\Permission;
use Artesaos\Defender\Role;
use Forone\Admin\Admin;
use Illuminate\Console\Command;

class InitCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:init';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Init Database.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->call('db:clear');
        $this->call('migrate');
        $roles = $this->initRoles();
        $this->initPerms();
        $admin = $this->initAdmins();
        $this->initRoleUsers($admin, $roles);
    }

    /**
     *
     */
    private function initAdmins()
    {
        return Admin::create(['name' => '超级管理员', 'email' => env('ADMIN_EMAIL','admin@admin.com'), 'password' => bcrypt(env('ADMIN_PASSWORD','admin')),]);
    }

    /**
     *
     */
    private function initPerms()
    {
        Permission::create(['name' => 'permissions#', 'readable_name' => '权限']);
        Permission::create(['name' => 'admin.roles.index', 'readable_name' => '角色管理']);
        Permission::create(['name' => 'admin.permissions.index', 'readable_name' => '权限管理']);
        Permission::create(['name' => 'admin.admins.index', 'readable_name' => '管理员管理']);
    }

    /**
     *
     */
    private function initRoles()
    {
        return Role::create(['name' => config('defender.superuser_role')]);
    }

    /**
     * @param Admin $admin
     * @param Role $role
     */
    private function initRoleUsers(Admin $admin, Role $role)
    {
        $admin->attachRole($role);
    }
}

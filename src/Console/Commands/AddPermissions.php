<?php

namespace App\Console\Commands;

use App\Permission;
use App\Role;
use Illuminate\Console\Command;
use DB;

class AddPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'add:permissions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $role = Role::where('name','super')->first();

        $permission = Permission::firstOrCreate(['name' => 'frontend_collections',
            'display_name' => 'frontend_collections', 'description' => 'frontend_collections']);
        DB::table('permission_role')->insert(['role_id' => $role->id , 'permission_id'=> $permission->id]);
        $permission = Permission::firstOrCreate(['name' => 'slider', 'display_name' => 'slider', 'description' => 'slider']);
        DB::table('permission_role')->insert(['role_id' => $role->id , 'permission_id'=> $permission->id]);
        $permission = Permission::firstOrCreate(['name' => 'uom', 'display_name' => 'uom', 'description' => 'uom']);
        DB::table('permission_role')->insert(['role_id' => $role->id , 'permission_id'=> $permission->id]);

        echo "updated \n";
    }
}

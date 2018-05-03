<?php

use Illuminate\Database\Seeder;
use App\Models\AuthNode;

class nodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = $this->getInsertData();
        AuthNode::insert($data);

    }

    public function getInsertData()
    {
        $base_url = 'passport.local.app';
        $time = time();
        //
        $insert = [
            // app
            [
                'name' => '应用创建',
                'desc' => '应用创建',
                'url'  => $base_url.'/api/app_create',
                'key'  => 'passport/app_create',
                'create_time' => $time
            ],
            [
                'name' => '应用移除',
                'desc' => '应用移除',
                'url'  => $base_url.'/api/app_del',
                'key'  => 'passport/app_del',
                'create_time' => $time
            ],
            [
                'name' => '应用编辑',
                'desc' => '应用编辑',
                'url'  => $base_url.'/api/app_update',
                'key'  => 'passport/app_update',
                'create_time' => $time
            ],
            [
                'name' => '应用列表',
                'desc' => '应用列表',
                'url'  => $base_url.'/api/app_list',
                'key'  => 'passport/app_list',
                'create_time' => $time
            ],
            [
                'name' => '应用登录key列表',
                'desc' => '应用登录key列表',
                'url'  => $base_url.'/api/key_list',
                'key'  => 'passport/key_list',
                'create_time' => $time
            ],

            // node
            [
                'name' => '节点明细',
                'desc' => '节点明细',
                'url'  => $base_url.'/api/node_info',
                'key'  => 'passport/node_info',
                'create_time' => $time
            ],
            [
                'name' => '节点创建',
                'desc' => '节点创建',
                'url'  => $base_url.'/api/node_create',
                'key'  => 'passport/node_create',
                'create_time' => $time
            ],
            [
                'name' => '节点删除',
                'desc' => '节点删除',
                'url'  => $base_url.'/api/node_delete',
                'key'  => 'passport/node_delete',
                'create_time' => $time
            ],
            [
                'name' => '节点编辑',
                'desc' => '节点编辑',
                'url'  => $base_url.'/api/node_update',
                'key'  => 'passport/node_update',
                'create_time' => $time
            ],
            [
                'name' => '节点列表',
                'desc' => '节点列表',
                'url'  => $base_url.'/api/node_delete',
                'key'  => 'passport/node_list',
                'create_time' => $time
            ],

            // user_node
            [
                'name' => '用户节点关联',
                'desc' => '用户节点关联',
                'url'  => $base_url.'/api/user_node_create',
                'key'  => 'passport/user_node_create',
                'create_time' => $time
            ],
            [
                'name' => '用户节点删除',
                'desc' => '用户节点删除',
                'url'  => $base_url.'/api/user_node_delete',
                'key'  => 'passport/user_node_delete',
                'create_time' => $time
            ],
            [
                'name' => '用户节点列表',
                'desc' => '用户节点列表',
                'url'  => $base_url.'/api/user_node_list',
                'key'  => 'passport/user_node_list',
                'create_time' => $time
            ],

            // user_menu
            [
                'name' => '菜单节点关联',
                'desc' => '菜单节点关联',
                'url'  => $base_url.'/api/menu_node_create',
                'key'  => 'passport/menu_node_create',
                'create_time' => $time
            ],
            [
                'name' => '菜单节点移除',
                'desc' => '菜单节点移除',
                'url'  => $base_url.'/api/menu_node_delete',
                'key'  => 'passport/menu_node_delete',
                'create_time' => $time
            ],
            [
                'name' => '菜单下所有节点',
                'desc' => '菜单下所有节点',
                'url'  => $base_url.'/api/menu_node_check_all',
                'key'  => 'passport/menu_node_check_all',
                'create_time' => $time
            ],
            [
                'name' => '菜单下节点',
                'desc' => '菜单下节点',
                'url'  => $base_url.'/api/menu_node_check_one',
                'key'  => 'passport/menu_node_check_one',
                'create_time' => $time
            ],

            // role_node
            [
                'name' => '角色节点列表',
                'desc' => '角色节点列表',
                'url'  => $base_url.'/api/role_node_list',
                'key'  => 'passport/role_node_list',
                'create_time' => $time
            ],
            [
                'name' => '角色节点添加',
                'desc' => '角色节点添加',
                'url'  => $base_url.'/api/role_node_add',
                'key'  => 'passport/role_node_add',
                'create_time' => $time
            ],
            [
                'name' => '角色节点移除',
                'desc' => '角色节点移除',
                'url'  => $base_url.'/api/role_node_del',
                'key'  => 'passport/role_node_del',
                'create_time' => $time
            ],

            // role
            [
                'name' => '角色列表',
                'desc' => '角色列表',
                'url'  => $base_url.'/api/role_list',
                'key'  => 'passport/role_list',
                'create_time' => $time
            ],
            [
                'name' => '角色明细',
                'desc' => '角色明细',
                'url'  => $base_url.'/api/role_info',
                'key'  => 'passport/role_info',
                'create_time' => $time
            ],
            [
                'name' => '角色创建',
                'desc' => '角色创建',
                'url'  => $base_url.'/api/role_create',
                'key'  => 'passport/role_create',
                'create_time' => $time
            ],
            [
                'name' => '角色编辑',
                'desc' => '角色编辑',
                'url'  => $base_url.'/api/role_update',
                'key'  => 'passport/role_update',
                'create_time' => $time
            ],
            [
                'name' => '角色删除',
                'desc' => '角色删除',
                'url'  => $base_url.'/api/role_delete',
                'key'  => 'passport/role_delete',
                'create_time' => $time
            ],

            //菜单
            [
                'name' => '菜单添加',
                'desc' => '菜单添加',
                'url'  => $base_url.'/api/menu_create',
                'key'  => 'passport/menu_create',
                'create_time' => $time
            ],
            [
                'name' => '菜单删除',
                'desc' => '菜单删除',
                'url'  => $base_url.'/api/menu_del',
                'key'  => 'passport/menu_del',
                'create_time' => $time
            ],
            [
                'name' => '菜单列表',
                'desc' => '菜单列表',
                'url'  => $base_url.'/api/menu_list',
                'key'  => 'passport/menu_list',
                'create_time' => $time
            ],
            [
                'name' => '菜单编辑',
                'desc' => '菜单编辑',
                'url'  => $base_url.'/api/menu_update',
                'key'  => 'passport/menu_update',
                'create_time' => $time
            ],
            [
                'name' => '用户菜单关联',
                'desc' => '用户菜单关联',
                'url'  => $base_url.'/api/user_menu_bind',
                'key'  => 'passport/user_menu_bind',
                'create_time' => $time
            ],
            [
                'name' => '用户角色编辑',
                'desc' => '用户角色编辑',
                'url'  => $base_url.'/api/user_role_update',
                'key'  => 'passport/user_role_update',
                'create_time' => $time
            ],
        ];
        return $insert;
    }
}

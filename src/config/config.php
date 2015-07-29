<?php
/**
 * User : YuGang Yang
 * Date : 7/27/15
 * Time : 15:26
 * Email: smartydroid@gmail.com
 */

return [
    'site_config'  => [
        'site_name'   => 'your site name',
        'title'       => 'your site title',
        'description' => 'you site description'
    ],
    'RedirectAfterLoginPath' => 'admin/roles', // 登录后跳转页面
    'RedirectIfAuthenticatedPath' => 'admin/roles', // 如果授权后直接跳转到指定页面

    'menus'        => [
        '权限' => [
            'icon'            => 'mdi-toggle-radio-button-on',
            'active_uri'      => 'roles|permissions|admins|navs',
            'permission_name' => 'permissions#',
            'route_name'      => 'permissions#',
            'is_redirect'     => false,
            'children'        => [
                '角色管理'  => [
                    'active_uri'      => 'roles',
                    'icon'            => null,
                    'permission_name' => 'admin.roles.index',
                    'route_name'      => 'admin.roles.index',
                    'is_redirect'     => true,
                ],
                '权限管理'  => [
                    'active_uri'      => 'permissions',
                    'icon'            => null,
                    'permission_name' => 'admin.permissions.index',
                    'route_name'      => 'admin.permissions.index',
                    'is_redirect'     => true,
                ],
                '管理员管理' => [
                    'active_uri'      => 'admins',
                    'icon'            => null,
                    'permission_name' => 'admin.admins.index',
                    'route_name'      => 'admin.admins.index',
                    'is_redirect'     => true,
                ]
            ],
        ],
    ],

    'nav_titles'   => [
        'admin.roles.index'        => '角色管理',
        'admin.roles.create'       => '创建角色',
        'admin.permissions.index'  => '权限管理',
        'admin.permissions.create' => '创建权限',
        'admin.admins.index'       => '管理员管理',
        'admin.admins.create'      => '创建管理员管理',
    ],
];
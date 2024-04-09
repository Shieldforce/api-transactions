<?php

return [
    'user'       => App\Models\User::class,
    'role'       => Yajra\Acl\Models\Role::class,
    'permission' => Yajra\Acl\Models\Permission::class,
    'cache'      => [
        'enabled' => true,
        'key'     => 'permissions.policies',
    ],
];

<?php

namespace App\Models;

class Permission extends \Spatie\Permission\Models\Permission
{

    public static function defaultPermissions()
    {
        return [
            'view users',
            'add users',
            'edit users',
            'delete users',

            'view roles',
            'add roles',
            'edit roles',
            'delete roles',
        ];
    }
}

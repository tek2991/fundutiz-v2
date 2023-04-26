<?php

namespace App\Models;

class Permission extends \Spatie\Permission\Models\Permission
{

    public static function defaultPermissions()
    {
        return [
            'view user',
            'add user',
            'edit user',
            'delete user',

            'view role',
            'add role',
            'edit role',
            'delete role',

            'view financial year',
            'add financial year',
            'edit financial year',
            'delete financial year',

            'view fund',
            'add fund',
            'edit fund',
            'delete fund',

            'view office',
            'add office',
            'edit office',
            'delete office',

            'view approver',
            'add approver',
            'edit approver',
            'delete approver',

            'view transaction',
            'add transaction',
            'edit transaction',
            'delete transaction',
        ];
    }
}

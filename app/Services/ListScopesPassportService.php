<?php

namespace App\Services;

class ListScopesPassportService
{
    public static function getList()
    {
        return [
            'panel.transaction.index',
            'api.transaction.index',
            'api.transaction.store',
            'api.transaction.show',
            'api.transaction.update',
            'api.transaction.destroy',
        ];
    }
}

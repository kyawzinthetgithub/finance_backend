<?php

namespace App\Repositories;

use App\Models\WalletType;

class WalletTypeRepository
{
    public function getAll()
    {
        $type = WalletType::all();
        return $type;
    }
}

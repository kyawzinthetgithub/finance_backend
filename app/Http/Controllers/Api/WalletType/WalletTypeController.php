<?php

namespace App\Http\Controllers\Api\WalletType;

use App\Http\Controllers\Controller;
use App\Repositories\WalletTypeRepository;
use Illuminate\Http\Request;

class WalletTypeController extends Controller
{
    protected $repo;
    public function __construct(WalletTypeRepository $repo)
    {
        $this->repo = $repo;
    }

    public function getAll()
    {
        $response = $this->repo->getAll();
        return $response;
    }
}

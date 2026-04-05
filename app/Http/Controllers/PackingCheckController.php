<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PackingCheckService;

class PackingCheckController extends Controller
{
    protected $service;

    public function __construct(PackingCheckService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return $this->service->index();
    }

    public function loadOrder(Request $request)
    {
        return $this->service->loadOrder($request);
    }

    public function scanSku(Request $request)
    {
        return $this->service->scanSku($request);
    }

    public function confirmPack(Request $request)
    {
        return $this->service->confirmPack($request);
    }
}
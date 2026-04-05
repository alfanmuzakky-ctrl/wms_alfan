<?php

namespace App\Http\Controllers;

use App\Services\InventoryService;

class InventoryController extends Controller
{
    protected $service;

    public function __construct(InventoryService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return $this->service->index();
    }
}
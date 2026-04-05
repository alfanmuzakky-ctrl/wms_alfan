<?php

namespace App\Http\Controllers;

use App\Services\PrintService;

class PrintController extends Controller
{
    protected $service;

    public function __construct(PrintService $service)
    {
        $this->service = $service;
    }

    public function printInbound($id)
    {
        return $this->service->printInbound($id);
    }

    public function printSku($detail_id)
    {
        return $this->service->printSku($detail_id);
    }

    public function printPicking($id)
    {
        return $this->service->printPicking($id);
    }
}
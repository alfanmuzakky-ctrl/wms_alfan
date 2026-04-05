<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\OutboundService;
use App\Services\AllocationService;
use App\Services\PickingService;
use App\Services\ShippingService;

class OutboundController extends Controller
{
    protected $service;
    protected $allocationService;
    protected $pickingService;
    protected $shippingService;

    public function __construct(
        OutboundService $service,
        AllocationService $allocationService,
        PickingService $pickingService,
        ShippingService $shippingService
    ) {
        $this->service = $service;
        $this->allocationService = $allocationService;
        $this->pickingService = $pickingService;
        $this->shippingService = $shippingService;
    }

    public function index()
    {
        return $this->service->index();
    }

    public function create()
    {
        return $this->service->create();
    }

    public function store(Request $request)
    {
        return $this->service->store($request);
    }

    public function show($id)
    {
        return $this->service->show($id);
    }

    public function addSku(Request $request,$id)
    {
        return $this->service->addSku($request,$id);
    }

    public function allocate($id)
    {
        return $this->allocationService->allocate($id);
    }

    public function picking($id)
    {
        return $this->pickingService->picking($id);
    }

    public function packing($id)
    {
        return $this->pickingService->packing($id);
    }

    public function ship($id)
    {
        return $this->shippingService->ship($id);
    }

    public function reallocate(Request $request)
    {
        return $this->allocationService->reallocate($request);
    }
}
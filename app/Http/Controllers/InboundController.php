<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\InboundService;
use App\Services\InboundProcessService;
use App\Services\PutawayService;

class InboundController extends Controller
{
    protected $service;
    protected $processService;
    protected $putawayService;

    public function __construct(
        InboundService $service,
        InboundProcessService $processService,
        PutawayService $putawayService
    ) {
        $this->service = $service;
        $this->processService = $processService;
        $this->putawayService = $putawayService;
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

    public function addSku(Request $request, $id)
    {
        return $this->service->addSku($request, $id);
    }

    public function received(Request $request)
    {
        return $this->processService->received($request);
    }

    public function putawayIndex()
    {
        return $this->putawayService->putawayIndex();
    }

    public function putawayProcess(Request $request)
    {
        return $this->putawayService->putawayProcess($request);
    }

    public function close($id)
    {
        return $this->service->close($id);
    }
}
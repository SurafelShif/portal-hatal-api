<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRahtalRequest;
use App\Services\CommonService;
use App\Services\RahtalService;
use Illuminate\Http\Request;

class RahtalController extends Controller
{
    protected $RahtalService;
    protected $CommonService;

    public function __construct(RahtalService $RahtalService, CommonService $CommonService)
    {
        $this->RahtalService = $RahtalService;
        $this->CommonService = $CommonService;
    }
    //
    public function index()
    {
        $rahtal = $this->RahtalService->getCurrentRahtal();
        return $rahtal;
    }
    public function update(StoreRahtalRequest $request, $uuid)
    {

        $rahtal = $this->RahtalService->updateRahtal($request, $uuid);
        return $rahtal;
    }
}

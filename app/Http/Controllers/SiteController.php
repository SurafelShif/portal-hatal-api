<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSiteRequest;
use App\Services\SiteService;

class SiteController extends Controller
{
    protected $siteService;

    // Dependency Injection via Constructor
    public function __construct(SiteService $siteService)
    {
        $this->siteService = $siteService;
    }
    //
    public function store(StoreSiteRequest $request)
    {
        dd('dfd');

        $this->siteService->createSite($request);
    }
}

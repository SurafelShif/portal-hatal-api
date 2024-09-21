<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreWebsiteRequest;
use App\Http\Requests\UpdateWebsiteRequest;
use App\Services\WebsiteService;

class WebsiteController extends Controller
{
    protected $WebsiteService;

    // Dependency Injection via Constructor
    public function __construct(WebsiteService $WebsiteService)
    {
        $this->WebsiteService = $WebsiteService;
    }
    //
    public function index()
    {
        $websites = $this->WebsiteService->getWebsites();
        return $websites;
    }
    public function store(StoreWebsiteRequest $request)
    {
        $image = $this->WebsiteService->uploadimage($request);
        $website = $this->WebsiteService->createWebsite($request, $image->id);
        return $website;
    }
    public function delete($uuid)
    {
        $deleted = $this->WebsiteService->deleteWebsite($uuid);
        return $deleted;
    }
    public function update(UpdateWebsiteRequest $request, $uuid)
    {
        $updatedWebsite = $this->WebsiteService->updateWebsite($request, $uuid);
        return $updatedWebsite;
    }
}

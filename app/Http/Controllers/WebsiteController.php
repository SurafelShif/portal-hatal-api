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
    public function store(StoreWebsiteRequest $request)
    {
        try {
            $image = $this->WebsiteService->uploadimage($request);
            $Website = $this->WebsiteService->createWebsite($request, $image->id);

            return response()->json([
                'message' => 'Website created successfully!',
                'Website' => $Website,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 400);
        }
    }
    public function delete($id)
    {
        try {
            $deleted = $this->WebsiteService->deleteWebsite($id);
            if (!$deleted) {
                return response()->json([
                    'message' => 'Website not found or already deleted.',
                ], 404);
            }
            return response()->json([
                'message' => 'Website deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 400);
        }
    }
    public function update(UpdateWebsiteRequest $request, $id)
    {
        $updatedWebsite = $this->WebsiteService->updateWebsite($request, $id);
        return $updatedWebsite;
    }
}

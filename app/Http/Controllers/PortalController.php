<?php

namespace App\Http\Controllers;

use App\Enums\HttpStatusEnum;
use App\Enums\ResponseMessages;
use App\Services\PortalService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PortalController extends Controller
{
    public function __construct(private PortalService $portalService) {}
    public function index()
    {
        $result = $this->portalService->getPortals();
        if ($result instanceof HttpStatusEnum) {
            return match ($result) {
                HttpStatusEnum::ERROR => response()->json(ResponseMessages::ERROR_OCCURRED, Response::HTTP_INTERNAL_SERVER_ERROR),
            };
        }
        return response()->json([
            'message' => ResponseMessages::SUCCESS_ACTION,
            'data' => $result,
        ], Response::HTTP_OK);
    }
}

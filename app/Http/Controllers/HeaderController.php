<?php

namespace App\Http\Controllers;

use App\Enums\HttpStatusEnum;
use App\Enums\ResponseMessages;
use App\Http\Requests\UpdateHeaderRequest;
use App\Services\HeaderService;
use Illuminate\Http\Response;

class HeaderController extends Controller
{
    //
    public function __construct(private HeaderService $headerService) {}
    public function index()
    {
        $result = $this->headerService->getSettings();
        if ($result instanceof HttpStatusEnum) {
            return response()->json(ResponseMessages::ERROR_OCCURRED, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return $result;
    }
    public function update(UpdateHeaderRequest $request)
    {
        $result = $this->headerService->update($request->icons ?? null, $request->description ?? null);
        if ($result instanceof HttpStatusEnum) {
            return match ($result) {
                HttpStatusEnum::ERROR => response()->json(["message" => ResponseMessages::ERROR_OCCURRED], Response::HTTP_INTERNAL_SERVER_ERROR),
            };
        }
        return response()->json(["message" => ResponseMessages::SUCCESS_ACTION], Response::HTTP_OK);
    }
}

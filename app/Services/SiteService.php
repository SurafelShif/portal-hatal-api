<?php

namespace App\Services;

use App\Http\Requests\StoreSiteRequest;


class SiteService
{
    public function createSite(StoreSiteRequest $request)
    {
        return response()->json("idk");
    }
}

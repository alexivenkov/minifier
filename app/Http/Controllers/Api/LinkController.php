<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\GenerateLinkRequest;
use App\Http\Resources\LinkResource;
use App\Models\Link;
use App\Services\LinkService;

class LinkController extends Controller
{
    /**
     * @param GenerateLinkRequest $request
     * @param LinkService         $service
     *
     * @return LinkResource
     * @throws \Exception
     */
    public function generate(GenerateLinkRequest $request, LinkService $service)
    {
        $link = $service->generateLink($request->input('link'));

        return LinkResource::make($link);
    }

    /**
     * @param LinkService $service
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function limits(LinkService $service)
    {
        return response()->json([
            'data' => $service->getAllAvailableLimits()
        ]);
    }

    /**
     * @param Link $link
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function transitions(Link $link)
    {
        return response()->json([
            'data' => [
                'transitions' => $link->transitions_count
            ]
        ]);
    }
}

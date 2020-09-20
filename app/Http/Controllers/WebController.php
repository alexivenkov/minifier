<?php

namespace App\Http\Controllers;

use App\Models\Link;
use App\Services\LinkService;

class WebController extends Controller
{
    /**
     * @param Link        $link
     * @param LinkService $service
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function index(Link $link, LinkService $service)
    {
        $service->incrementTransitions($link);

        return redirect($link->original, 301);
    }
}

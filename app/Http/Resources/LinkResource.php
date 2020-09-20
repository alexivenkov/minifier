<?php

namespace App\Http\Resources;

use App\Models\Link;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Link */
class LinkResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'original_link' => $this->original,
            'minified_link' => $this->minified
        ];
    }
}

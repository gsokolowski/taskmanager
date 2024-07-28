<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        //When building an API, you may need a transformation layer that sits between your
        //Eloquent models and the JSON responses that are actually returned to your
        // application's users

        $data =  parent::toArray($request);
        // you can add remove modify data here
        $data['status'] = $this->is_done ? 'finished' : 'open';
        return $data;
    }
}

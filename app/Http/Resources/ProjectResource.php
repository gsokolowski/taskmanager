<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        //return parent::toArray($request);
        return [
            'id' => $this->id,
            'title' => $this->title,
            //'tasks' => TaskResource::collection($this->tasks),
            'tasks' => TaskResource::collection($this->whenLoaded('tasks')), // conditionally when tasks are loaded in ProjecController
            'members' => UserResource::collection($this->whenLoaded('members')), /// conditionally when members are loaded in ProjecController
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}

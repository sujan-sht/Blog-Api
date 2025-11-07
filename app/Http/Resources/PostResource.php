<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'user'        => $this->user->only(['id','name','email']),
            'category'    => $this->category->only(['id','name','slug']),
            'tags'        => TagResource::collection($this->whenLoaded('tags')),
            'comments'    => CommentResource::collection($this->whenLoaded('comments')),
            'title'       => $this->title,
            'slug'        => $this->slug,
            'excerpt'     => $this->excerpt,
            'body'        => $this->body,
            'status'      => $this->status,
            'image'       => $this->image ? asset('storage/'.$this->image) : null,
            'created_at'  => $this->created_at->toDateTimeString(),
            'updated_at'  => $this->updated_at->toDateTimeString(),
        ];
    }
}

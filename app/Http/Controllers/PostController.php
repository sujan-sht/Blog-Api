<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\Post;
use App\Http\Requests\PostRequest;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\PostResource;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


class PostController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = QueryBuilder::for(Post::class)
            ->with(['user', 'category', 'tags', 'comments'])
            ->allowedFilters([
                AllowedFilter::partial('title'),

                AllowedFilter::callback('author', function ($query, $value) {
                    $query->whereHas('user', function ($q) use ($value) {
                        $q->where('name', 'like', "%{$value}%");
                    });
                }),

                AllowedFilter::callback('category', function ($query, $value) {
                    $query->whereHas('category', function ($q) use ($value) {
                        $q->where('name', 'like', "%{$value}%");
                    });
                }),

                AllowedFilter::callback('tags', function ($query, $value) {
                    $query->whereHas('tags', function ($q) use ($value) {
                        $q->where('name', 'like', "%{$value}%");
                    });
                }),
            ])
            ->latest()
            ->paginate(10);

        return PostResource::collection($posts);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PostRequest $request)
    {
        $data = $request->validated();
        try{
            DB::beginTransaction();
            $data['user_id'] = auth()->user()->id;

            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image')->store('posts', 'public');
            }


            $post = Post::create($data);

            if ($request->filled('tags')) {
                $post->tags()->sync($request->tags);
            }
            DB::commit();
            return new PostResource($post->load(['user','category','tags']));
        }catch(\Exception $e){
            DB::rollBack();
            throw $e;
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PostRequest $request, Post $post)
    {
        $data = $request->validated();
        try{
            DB::beginTransaction();
            if ($request->hasFile('image')) {
                if ($post->image) {
                    Storage::disk('public')->delete($post->image);
                }
                $data['image'] = $request->file('image')->store('posts', 'public');
            }

            $post->update($data);
            if ($request->filled('tags')) {
                $post->tags()->sync($request->tags);
            } else {
                $post->tags()->detach();
            }
            DB::commit();
            return new PostResource($post->load(['user','category','tags']));
        }catch(\Exception $e){
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);

        $post->tags()->detach();
        if ($post->image) {
            Storage::disk('public')->delete($post->image);
        }

        $post->delete();

        return response()->json(['message' => 'Post deleted successfully.']);
    }
}

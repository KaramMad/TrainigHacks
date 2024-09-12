<?php

namespace App\Http\Controllers\api\V1;
use App\Models\Like;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;


class likeController extends Controller
{

    //public function likePost($id)
    //{
        // $post = Post::findOrFail($id);

        // $like = new Like();
        // $like->user_id = Auth::id();
        // $like->likeable_id = $post->id;
        // $like->likeable_type = Post::class;

        // $like->save();

        // return response()->json($like, 201);
    //}
    public function likePost($id)
    {
        $post = Post::findOrFail($id);
        $userId = Auth::id();

        $existingLike = Like::where('user_id', $userId)
                            ->where('likeable_id', $post->id)
                            ->where('likeable_type', Post::class)
                            ->first();

        if ($existingLike) {
            return response()->json(['message' => 'Already liked'], 200);
        }

        $like = null;
        DB::transaction(function () use ($userId, $post, &$like) {
            $like = new Like();
            $like->user_id = $userId;
            $like->likeable_id = $post->id;
            $like->likeable_type = Post::class;

            $like->save();
            Cache::increment("post_{$post->id}_likes_count");
        });

        return response()->json($like, 201);
    }

    // public function likeComment($id)
    // {
    //     $comment = Comment::findOrFail($id);

    //     $like = new Like();
    //     $like->user_id = Auth::id();
    //     $like->likeable_id = $comment->id;
    //     $like->likeable_type = Comment::class;

    //     $like->save();

    //     return response()->json($like, 201);
    // }
    public function likeComment($id)
    {
        $comment = Comment::findOrFail($id);
        $userId = Auth::id();

        $existingLike = Like::where('user_id', $userId)
                            ->where('likeable_id', $comment->id)
                            ->where('likeable_type', Comment::class)
                            ->first();

        if ($existingLike) {
            return response()->json(['message' => 'Already liked'], 200);
        }

        $like = null;
        DB::transaction(function () use ($userId, $comment, &$like) {
            $like = new Like();
            $like->user_id = $userId;
            $like->likeable_id = $comment->id;
            $like->likeable_type = Comment::class;

            $like->save();

            Cache::increment("comment_{$comment->id}_likes_count");
        });

        return response()->json($like, 201);
    }

    public function likePostsCount($id)
    {
        $post = Post::findOrFail($id);
        return response()->json(['likes_count' => $post->likesCount()], 200);
    }


    public function likesCommentCount($id)
    {
        $comment = Comment::findOrFail($id);
        return response()->json(['likes_count' => $comment->likesCount()], 200);
    }

    public function unlikePost($id)
    {
        $post = Post::findOrFail($id);
        $like = Like::where('user_id', Auth::id())
            ->where('likeable_id', $post->id)
            ->where('likeable_type', Post::class)
            ->first();

        if ($like) {
            $like->delete();
            return response()->json(['message' => 'Like removed successfully'], 200);
        }

        return response()->json(['message' => 'Like not found'], 404);
    }

    public function unlikeComment($id)
    {
        $comment = Comment::findOrFail($id);
        $like = Like::where('user_id', Auth::id())
            ->where('likeable_id', $comment->id)
            ->where('likeable_type', Comment::class)
            ->first();

        if ($like) {
            $like->delete();
            return response()->json(['message' => 'Like removed successfully'], 200);
        }

        return response()->json(['message' => 'Like not found'], 404);
    }
}

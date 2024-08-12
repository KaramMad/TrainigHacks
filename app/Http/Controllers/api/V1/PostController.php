<?php

namespace App\Http\Controllers\api\V1;

use App\Models\Post;
use App\Models\Meal;
use App\Models\User;
use App\Http\Requests\PostRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Helper\OffensiveWordChecker;
use App\Traits\ImageTrait;

class PostController extends Controller
{
    use ImageTrait;
    public function index()
    {
        $posts = Post::with('images', 'user', 'comments')->get();

        $posts = $posts->map(function ($dat) {
            $dat['islike'] = $dat->islike();
            return $dat;
        });

        $postsData = $posts->map(function ($post) {
            return [
                'id' => $post->id,
                'body' => $post->body,
                'postable_id' => $post->postable_id,
                'postable_type' => $post->postable_type,
                'created_at' => $post->created_at,
                'updated_at' => $post->updated_at,
                'images' => $post->images,
                'user_name' => $post->user->name,
                'user_avatar' => $post->user->image,
                'likes_count' => $post->likesCount(),
                'comments_count' => $post->commentsCount(),
                'islike' => $post['islike'],
                'comments' => $post->comments->map(function ($comment) {
                    return [
                        'id' => $comment->id,
                        'body' => $comment->body,
                        'comment_image' => $comment->image,
                        'user_name' => $comment->user->name,
                        'user_avatar' => $comment->user->image,
                        'created_at' => $comment->created_at,
                        'updated_at' => $comment->updated_at,
                        'likes_comments_count' => $comment->likesCount(),
                        'islikeComment' => $comment->islike(),
                    ];
                })
            ];
        })->filter()->values();

        return response()->json([
            'posts' => $postsData
        ]);
    }

    public function create()
    {
        //
    }

    public function store(PostRequest $request)
    {
        $data = $request->validated();
        if (isset($data['body']) && OffensiveWordChecker::containsOffensiveWords($data['body'])) {
            return response()->json([
                'message' => 'Your comment contains offensive words and cannot be submitted.'
            ], 400);
        }

        $user = Auth::user();
        $post = new Post($data);
        $post->user_id = $user->id;
        $post->postable_type = 'App\Models\User';
        $post->postable_id = $user->id;
        $post->save();

        $images = $request->file('images');
        if (!empty($images)) {
            if (!is_array($images)) {
                $images = [$images];
            }

            foreach ($images as $image) {
                if ($image instanceof \Illuminate\Http\UploadedFile) {
                    $imagePath = ImageTrait::store($image, 'Posts');
                    $post->images()->create(['path' => $imagePath]);
                }
            }

        if ($request->hasFile('video')) {
            $videoPath = ImageTrait::store($request->file('video'), 'Posts');
            $post->video = $videoPath;
            $post->save();
        }

        $post->load('images');

        $post->islike = $post->islike();
        return response()->json([
            'message' => 'Post added successfully',
            'post' => [
                'id' => $post->id,
                'body' => $post->body,
                'postable_id' => $post->postable_id,
                'postable_type' => $post->postable_type,
                'created_at' => $post->created_at,
                'updated_at' => $post->updated_at,
                'images' => $post->images,
                'user_name' => $user->name,
                'user_avatar' => $user->avatar,
                'likes_count' => $post->likesCount(),
                'comments_count' => $post->commentsCount(),
                'islike' => $post->islike,
            ]
        ]);
        }
    }



    public function getUserPostsAndBio($userId)
    {
        $user = User::findOrFail($userId);
        $postsWithBio = $user->postsWithBio()->get();

        $postsWithBio = $postsWithBio->map(function ($post) {
            return [
                'id' => $post->id,
                'body' => $post->body,
                'postable_id' => $post->postable_id,
                'postable_type' => $post->postable_type,
                'created_at' => $post->created_at,
                'updated_at' => $post->updated_at,
                'islike' => $post->islike(),
                'likes_count' => $post->likes()->count(),
                'comments_count' => $post->comments()->count(),
                'images' => $post->images->map(function ($image) {
                    return [
                        'id' => $image->id,
                        'path' => $image->path,
                        'url' => url($image->path)
                    ];
                }),
                'user_name' => $post->user->name,
                'user_avatar' => $post->user->image,
                'comments' => $post->comments->map(function ($comment) {
                    return [
                        'id' => $comment->id,
                        'body' => $comment->body,
                        'comment_image' => $comment->image,
                        'user_name' => $comment->user->name,
                        'user_avatar' => $comment->user->image,
                        'created_at' => $comment->created_at,
                        'updated_at' => $comment->updated_at,
                        'likes_comments_count' => $comment->likesCount(),
                        'islikeComment' => $comment->islike(),
                    ];
                }),
            ];
        });

        return response()->json([
            'user_bio' => $user->bio,
            'posts_with_bio' => $postsWithBio,
        ]);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy($id)
    {
        $post = Post::find($id);
        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }
        $user = auth()->user();
        if ($post->postable_type === get_class($user) && $post->postable_id == $user->id) {
            $post->delete();
            return response()->json(['message' => 'Post deleted successfully']);
        } else {
            return response()->json(['message' => 'You do not have permission to delete this post'], 403);
        }
    }
}

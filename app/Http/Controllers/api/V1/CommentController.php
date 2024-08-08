<?php

namespace App\Http\Controllers\api\V1;

use Illuminate\Http\Request;
use App\Http\Requests\CommentRequest;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Helper\OffensiveWordChecker;
use App\Traits\ImageTrait;

class CommentController extends Controller
{
    use ImageTrait;

    public function store(CommentRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id();


        if (isset($data['body']) && OffensiveWordChecker::containsOffensiveWords($data['body'])) {
            return response()->json([
                'message' => 'Your comment contains offensive words and cannot be submitted.'
            ], 400);
        }


        if ($request->hasFile('image')) {
            $data['image'] = ImageTrait::store($request->file('image'), 'Comments');
        }

        $user = Auth::user();
        $comment = new Comment($data);
        $user->comments()->save($comment);


        $post = $comment->post;

        $postLikesCount = $post ? $post->likes()->count() : 0;
        $postIsLike = $post ? $post->islike() : false;


        $commentImagePath = $comment->image
            ? $comment->image
            : null;

        return response()->json([
            'message' => 'Comment added successfully',
            'comment' => [
                'id' => $comment->id,
                'body' => $comment->body,
                'user_name' => $user->name,
                'user_avatar' => $user->image,
                'created_at' => $comment->created_at,
                'updated_at' => $comment->updated_at,
                'likes_count' => $comment->likes()->count(),
                'islike' => $comment->islike(),
                'image' => $commentImagePath,
            ],
            'post' => $post ? [
                'id' => $post->id,
                'body' => $post->body,
                'postable_id' => $post->postable_id,
                'postable_type' => $post->postable_type,
                'created_at' => $post->created_at,
                'updated_at' => $post->updated_at,
                'images' => $post->images->map(function ($image) {
                    return [
                        'id' => $image->id,
                        'path' => $image->path,
                        'url' => $image->path
                    ];
                }),
                'likes_count' => $postLikesCount,
                'islike' => $postIsLike,
            ] : null
        ]);
    }

    public function showComments($postId)
    {
        $post = Post::find($postId);

        if (!$post) {
            return response()->json([
                'message' => 'Post not found.'
            ], 404);
        }

        $comments = Comment::where('post_id', $postId)
            ->with('user', 'images', 'likes')
            ->get()
            ->map(function ($comment) {
                return [
                    'id' => $comment->id,
                    'body' => $comment->body,
                    'user_name' => $comment->user ? $comment->user->name : null,
                    'user_avatar' => $comment->user ? $comment->user->image : null,
                    'likes_count' => $comment->likes()->count(),
                    'islike' => $comment->islike(),
                    'images' => $comment->images ? $comment->images->map(function ($image) {
                        return [
                            'id' => $image->id,
                            'path' => $image->path,
                            'url' => url($image->path)
                        ];
                    })->toArray() : []
                ];
            });

        return response()->json([
            'post_id' => $postId,
            'comments' => $comments
        ]);
    }

    //جربيه يا لنوشة على شانك هلا نعستي
    public function getRepliesByComment($commentId)
    {
        $comment = Comment::find($commentId);
        if (!$comment) {
            return response()->json([
                'message' => 'Comment not found.'
            ], 404);
        }

        $replies = Comment::where('parent_id', $commentId)->get();

        return response()->json([
            'comment_id' => $commentId,
            'replies' => $replies
        ]);
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);
        if (Auth::id() !== $comment->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        $comment->delete();

        return response()->json(['message' => 'Comment deleted successfully'], 200);
    }
}

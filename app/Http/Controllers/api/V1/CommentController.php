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

        if (OffensiveWordChecker::containsOffensiveWords($data['body'])) {
            return response()->json([
                'message' => 'Your comment contains offensive words and cannot be submitted.'
            ], 400);
        }
        $imagePath = null;
        if ($request->hasFile('image')) {
            $data['image'] = ImageTrait::store($request->file('image'), 'Posts');
        }

        $user = Auth::user();
        $comment = new Comment($data);
        $user->comments()->save($comment);

        return response()->json([
            'message' => 'Comment added successfully',
            'comment' => $comment
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function showComments($postId)
    {
        // تحقق من وجود المنشور
        $post = Post::find($postId);
        if (!$post) {
            return response()->json([
                'message' => 'Post not found.'
            ], 404);
        }

        // احصل على جميع التعليقات المتعلقة بالمنشور
        $comments = Comment::where('post_id', $postId)->get();

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

    /**
     * Remove the specified resource from storage.
     */
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

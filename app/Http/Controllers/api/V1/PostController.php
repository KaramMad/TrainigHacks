<?php

namespace App\Http\Controllers\api\V1;
use App\Models\Post;
use App\Models\Meal;
use App\Models\User;
use App\Http\Requests\PostRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Helper\OffensiveWordChecker;

class PostController extends Controller
{

    public function index()
    {
        $posts = Post::all();
        return response()->json([
            'posts' => $posts
        ]);
    }

    public function create()
    {
        //
    }

    public function store(PostRequest $request)
    {
        $data = $request->validated();

        if (OffensiveWordChecker::containsOffensiveWords($data['body'])) {
            return response()->json([
                'message' => 'Your post contains offensive words and cannot be submitted.'
            ], 400);
        }
        $imagePath = null;
        if ($request->hasFile('image')) {
            $data['image'] = ImageController::store($data['image'], 'Posts');
        }

        if ($request->hasFile('video')) {
            $data['video'] = ImageController::store($data['video'], 'Posts');
        }

        $user = Auth::user();
        $post = new Post($data);
        $user->posts()->save($post);

        return response()->json([
            'message' => 'Post added successfully',
            'post' => $post
        ]);
    }


    public function getUserPostsAndBio($userId)
    {
        $user = User::findOrFail($userId);
        $postsWithBio = $user->postsWithBio()->get();

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

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
        $posts = Post::with('images')->get();
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
        $images = $request->file('images');
        $user = Auth::user();
        $post = new Post($data);
        $user->posts()->save($post);

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
        }

        if ($request->hasFile('video')) {
            $videoPath = ImageTrait::store($request->file('video'), 'Posts');
            $post->video = $videoPath;
            $post->save();
        }
        $post->load('images');

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

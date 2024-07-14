<?php

namespace App\Http\Controllers\api\V1;

use Illuminate\Http\Request;
use App\Http\Requests\CommentRequest;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Helper\OffensiveWordChecker;


class CommentController extends Controller
{
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
            $data['image'] = ImageController::store($request->file('image'), 'Posts');
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
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
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

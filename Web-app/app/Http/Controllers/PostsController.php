<?php

namespace App\Http\Controllers;

use App\Models\Posts;
use App\Models\User;
use App\Models\UserPosts;
use App\Models\PostComments;
use Illuminate\Support\Facades\Validator;
use App\Mail\CommentAdded;
use Illuminate\Support\Facades\Mail;


use Illuminate\Http\Request;

class PostsController extends Controller
{
    /**
     * @author Klejdi Arapi
     *
     * 
     */

    public function index(Request $request)
    {
        /**
         * Fetch all posts from the Database
         */        
        $posts = Posts::orderBy('created_at', 'desc')->get();
        return $posts;
    }


    public function userPost($id)
    {
    /**
     * Fetch all posts for the selected user
     */        

        $posts = Posts::where('user_id', $id)
        ->with('comments')
        ->orderBy('created_at', 'desc')
        ->get();
    
        return $posts;
    
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function postSearch(Request $request)
    {
        $term = $request->input('term');

        $posts = Posts::where('title', 'like', "%{$term}%")
                     ->orWhere('description', 'like', "%{$term}%")
                     ->orWhereHas('comments', function($query) use ($term) {
                         $query->where('comment', 'like', "%{$term}%");
                     })
                     ->orderBy('created_at', 'desc')
                     ->get();
    
        return $posts;
    }


     /**
      * 
      *  Store method used to add new posts to the database
      *
      */

    public function store(Request $request)
    {

            $validator = Validator::make($request->all(), [               
                'title' => 'bail|required|string|max:255',
                'description' => 'bail|required|string',
            ]);

            if ($validator->fails()) {    
                return response()->json(["errors" => "Both fields required!"], 406);
            }

            $posts = new Posts([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'user_id' => $request->input('user_id'),
            'user' => $request->input('username'),

        ]);

        $posts->save();

        return response()->json(' Post Added!');
    }

    /**
     * 
     * 
     * 
     */

     public function getPostComments($post_id)
     {
        $comments = PostComments::select('post_comments.*', 'users.username')
        ->join('users', 'post_comments.user_id', '=', 'users.id')
        ->where('post_comments.post_id', $post_id)
        ->get();

        if (count($comments) > 0) {    
            return response()->json(["comments" => $comments], 200);
        } else {
            return response()->json(["comments" => false]);
        } 
     }

         /**
     * 
     * 
     * 
     */

     public function addCommentToPost(Request $request) {

        $comment = new PostComments([
            'user_id' => $request->input('user_id'),
            'post_id' => $request->input('post_id'),
            'comment' => $request->input('comment'),
            'created_at' => now()
        ]);

        $comment->save();

        if($comment) {
            $post = Posts::find($comment->post_id);
            $user = User::find($post->user_id);

            if($comment->user_id != $post->user_id) {
                print_r("SENDING TO: ".$user->email."");

                $comment->post_title = $post->title;
                
                $userComment = User::find($comment->user_id);
                $comment->username = $userComment->username;

                Mail::to($user->email)->send(new CommentAdded($comment));

                if (Mail::failures()) {
                    return response()->json(['error' => 'Failed to send email.'], 500);
                } else {
                    print_r("EMAIL IS SENT TO: ".$user->email."");
                }
            }

            return response()->json('Comment Added!');
        }
     }

    /**
     * 
     * 
     * 
     */

     public function editComment(Request $request, $id)
    {
        $comment = PostComments::findOrFail($id);
        
        // dd($request);
        $comment->comment = $request->input('comment');
        $comment->updated_at = now();
        $comment->save();

        return response()->json(['message' => 'Comment updated successfully!', 'comment' => $comment]);
    }


    public function deleteComment($id)
    {
        $comment = PostComments::findOrFail($id);
        
        $comment->delete();

        return response()->json(['message' => 'Comment deleted successfully!']);
    }

    /**
     * 
     * 
     * 
     */

    public function show($id)
    {
        $post = Posts::find($id)->get();

        return $post->toArray();
    }



    public function editPost(Request $request, $postId)
    {

        $post = Posts::findOrFail($postId);

        $post->title = $request->input('title');
        $post->description = $request->input('description');
        $post->save();

        if($post) return response()->json(['message' => 'Post updated successfully','post' => $post], 200);
        else return response()->json(['message' => 'Post updated unsuccessfully','post' => $post], 406);

    } 
    /**
     * 
     * 
     * 
     * 
     */
    public function destroy($id)
    {
        $post = Posts::find($id);
        
        $post->delete();
    }
}

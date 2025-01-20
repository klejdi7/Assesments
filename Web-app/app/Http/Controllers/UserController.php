<?php

namespace App\Http\Controllers;

use App\Models\User;
use Auth;
use DB;

use App\Mail\VerifyEmail;
use Illuminate\Support\Facades\Mail;

use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * @author Klejdi Arapi
     *
     * 
     */

    public function index()
    {
        if(Auth::check()){

        $user = [
            'id' => Auth::user()->id,
            'name' => Auth::user()->name,
            'email' => Auth::user()->email,
            'username' => Auth::user()->username,
            'is_verified' => Auth::user()->email_verified_at != null ? 1 : 0
        ];

        return response()->json($user);

        } else {
            return response()->json([ 'user_msg' => 'User not logged in', 'logged_in' => false ], 406);

        }
    }

    /**
     * 
     * 
     * 
     * 
     */

    public function show($id)
    {

        $user = User::where('id', $id)->select('name' , 'username', 'created_at', 'email_verified_at')->get();

        return $user->toArray();
    }

    /** 
     * 
     * 
     * 
     * 
    */

    public function emailVerification($id, $action)
    {

        $user = User::find($id);

        if(empty($user->email_verified_at)){

            if($action == 1) {

                    Mail::to($user->email)->send(new VerifyEmail($user));

                    if (Mail::failures()) {
                        return response()->json(['error' => 'Failed to send email.'], 500);
                    } else {
                        print_r("EMAIL IS SENT TO: ".$user->email."");
                    }
            } else {
                $user->email_verified_at = now();
                $user->save();

                return redirect()->route('profile', ['id' => $user->id]);
            }
        } else {
            return response()->json(['error' => 'Email is already verified.'], 200);
        }

    }

}

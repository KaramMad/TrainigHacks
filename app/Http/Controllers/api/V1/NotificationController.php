<?php

namespace App\Http\Controllers\api\V1;

use Illuminate\Http\Request;
use app\Models\User;
use App\Models\Admin;
use App\Models\Coach;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class NotificationController extends Controller
{
    public function before30minutes()
    {
        $user = User::find(Auth::id());
        


    }
}

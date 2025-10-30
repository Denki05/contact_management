<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\User;

class AuthController extends Controller
{
    public function directLogin($userId)
    {
        $user = User::findOrFail($userId);

        // login tanpa password
        Auth::login($user);

        // redirect ke /home
        return redirect()->route('report.doctor.index');
    }
}
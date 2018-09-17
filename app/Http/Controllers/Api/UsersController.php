<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\Api\UserRequest;

class UsersController extends Controller
{
    public function store(UserRequest $request)
    {
        $user = User::create([
            'sid' => $request->sid,
            'password' => bcrypt($request->password),
            'name' => $request->name,
            'email' => $request->email,
            'type' => $request->type,
        ]);

        return $this->response->created();
    }
}

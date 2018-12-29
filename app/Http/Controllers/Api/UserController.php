<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Transformers\UserTransformer;
use App\Http\Requests\Api\UserRequest;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function store(UserRequest $request)
    {
        $user = User::create([
            'sid'        => $request->sid,
            'password'   => bcrypt($request->password),
            'name'       => $request->name,
            'email'      => $request->email,
            'type'       => $request->type,
            'type_num'   => $request->type == 'student' ? 0 : 1,
            'avatar_url' => 'https://f.zzwcdn.com/no-avatar.png'
        ]);

        return $this->response->item($user, new UserTransformer())
            ->setMeta([
                'access_token' => \Auth::guard('api')->fromUser($user),
                'token_type' => 'Bearer',
                'expires_in' => \Auth::guard('api')->factory()->getTTL() * 60
            ])
            ->setStatusCode(201);
    }

    public function storeSpider(Request $request)
    {
        $user = User::create([
            'sid' => $request->sid,
            'password' => bcrypt($request->password),
            'name' => $request->name,
            'email' => $request->email,
            'type' => $request->type,
            'department' => $request->department,
            'intro' => $request->intro,
            'office' => $request->office,
            'fields' => $request->fields,
            'avatar_url' => 'https://f.zzwcdn.com/no-avatar.png',
        ]);

        return $this->response->item($user, new UserTransformer())
            ->setMeta([
                'access_token' => \Auth::guard('api')->fromUser($user),
                'token_type' => 'Bearer',
                'expires_in' => \Auth::guard('api')->factory()->getTTL() * 60
            ])
            ->setStatusCode(201);
    }

    public function update(UserRequest $request)
    {
        $user = $this->user();

        $attributes = $request->only(['name', 'email', 'avatar_url', 'intro', 'department', 'major', 'office', 'fields', 'gpa', 'interested_fields']);

        $user->update($attributes);

        return $this->response->item($user, new UserTransformer());
    }

    public function me()
    {
        return $this->response->item($this->user(), new UserTransformer());
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        return $this->response->item($user, new UserTransformer);
    }

    public function search(Request $request)
    {
        $keyword = $request->keyword;
        $users = User::search($keyword)->paginate(5);
        return $this->response->paginator($users, new UserTransformer());
    }

    public function searchFaculty(Request $request)
    {
        $keyword = $request->keyword;
        $users = User::search($keyword)->where('type_num', 1)->paginate(12);
        return $this->response->paginator($users, new UserTransformer());
    }
}

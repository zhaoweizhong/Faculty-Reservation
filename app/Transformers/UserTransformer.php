<?php

namespace App\Transformers;

use App\Models\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    public function transform(User $user)
    {
        if ($user->type == 'student') {
            return [
                'id'                => $user->id,
                'sid'               => $user->sid,
                'name'              => $user->name,
                'email'             => $user->email,
                'avatar_url'        => $user->avatar_url,
                'type'              => $user->type,
                'department'        => $user->department,
                'major'             => $user->major,
                'intro'             => $user->intro,
                'is_admin'          => $user->is_admin,
                'gpa'               => $user->gpa,
                'interested_fields' => $user->interested_fields,
                'created_at'        => $user->created_at->toDateTimeString(),
                'updated_at'        => $user->updated_at->toDateTimeString(),
            ];
        } else if ($user->type == 'faculty') {
            return [
                'id'             => $user->id,
                'sid'            => $user->sid,
                'name'           => $user->name,
                'email'          => $user->email,
                'avatar_url'     => $user->avatar_url,
                'type'           => $user->type,
                'intro'          => $user->intro,
                'is_admin'       => $user->is_admin,
                'office'         => $user->office,
                'fields'         => $user->fields,
                'available_time' => $user->available_time,
                'created_at'     => $user->created_at->toDateTimeString(),
                'updated_at'     => $user->updated_at->toDateTimeString(),
            ];
        }
    }
}

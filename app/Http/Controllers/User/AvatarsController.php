<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdateAvatar;

class AvatarsController extends Controller
{
    /**
     * Update user's avatar.
     *
     * @param  \App\Http\Requests\User\UpdateAvatar $request
     * @return \Illuminate\Http\Response
     */
    public function store(UpdateAvatar $request)
    {
        $user = $request->user();

        resolve('upload')
            ->handler('avatar')
            ->withFile($request->file('avatar'))
            ->withUser($user)
            ->store();

        flash('Your avatar has been changed.');

        return back();
    }
}

<?php

namespace App\Helpers;

use App\Models\MUser;
use Illuminate\Support\Facades\Auth;

class AuthHelper
{
    /**
     * Get the authenticated user and ensure it is an instance of MUser.
     *
     * @return MUser
     */
    public static function getAuthenticatedUser(): MUser
    {
        $user = Auth::guard('api')->user();
        if (!($user instanceof MUser)) {
            abort(ResponseHelper::Forbidden());
        }
        return $user;
    }
}

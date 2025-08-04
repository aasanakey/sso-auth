<?php

namespace App\Claims;

use App\Models\User;
use CorBosman\Passport\AccessToken;

class CustomClaim
{
    public function handle(AccessToken $token, $next)
    {       
        $user = User::find($token->getUserIdentifier());

            if ($user) {
                $token->addClaim('email', $user->email);
                $token->addClaim('name', $user->name);

                /*$roles = $user->roles()->pluck('name')->toArray();
                $token->addClaim('roles', $roles);*/

                $groups = $user->groups()->pluck('name')->toArray();
                $token->addClaim('groups', $groups);
            }
        return $next($token);
    }
}

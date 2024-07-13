<?php

namespace App\Service;

use App\Exceptions\CustomException;
use App\Models\User;

class UserService
{
    public function getUserWithEmail($email){
        $user = User::where('email', $email)->first();
        if($user){
            return $user;
        }
        throw new CustomException('User Not Found',404);
    }
}

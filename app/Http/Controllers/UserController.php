<?php

namespace App\Http\Controllers;

use App\Service\TableauService;
use App\Service\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    public function __construct(private UserService $userService,
                                private TableauService $tableauService)
    {
    }

    /*
     * If superadmin loggedin, we need to login to tableau as well
     */
    public function login(Request $request){
        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        $user = $this->userService->getUserWithEmail($request->email);

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response([
                'message' => 'Incorrect username or password'
            ], 401);
        }
        $user->token = $user->createToken('apiToken')->plainTextToken;;

        $tableauResponse = $this->tableauService->loginToTableau();

        return response()->json(
            [
                'message' => 'Login Success',
                'user' => $user,
                'tableauDetails' => $tableauResponse
            ]
        );
    }
}

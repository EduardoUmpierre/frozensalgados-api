<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function me(Request $request)
    {
        $user = User::query()->select(['id', 'name', 'role', 'cpf'])->where('id', $request->user()->id)->first();

        return response()->json($user);
    }
}

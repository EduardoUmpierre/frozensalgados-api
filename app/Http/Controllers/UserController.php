<?php

namespace App\Http\Controllers;

use App\Customer;
use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getAll(Request $request)
    {
        return User::query()->select(['id', 'name', 'email', 'role'])->get();
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model
     */
    public function getOne(Request $request, $id)
    {
        return User::query()->findOrFail($id);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|unique:users',
            'cpf' => 'required|unique:users',
            'role' => 'required',
            'password' => 'required'
        ]);

        $user = new User();
        $user->name = $request->get('name');
        $user->email = $request->get('email');
        $user->cpf = $request->get('cpf');
        $user->role = $request->get('role');

        if ($request->get('password') && $request->get('passwordRepeat')) {
            if ($request->get('password') === $request->get('passwordRepeat')) {
                $user->password = app('hash')->make($request->get('password'));
            } else {
                return response()->json(['error' => 'passwordNotEqual'], 405);
            }
        } else {
            return response()->json(['error' => 'passwordNotSent'], 405);
        }

        $user->save();

        return response()->json(null, 201);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        if (!$id) {
            return response()->json(null, 405);
        }

        $this->validate($request, [
            'name' => 'required',
            'email' => 'required',
            'cpf' => 'required',
            'role' => 'required'
        ]);

        $user = User::query()->findOrFail($id);
        $user->name = $request->get('name');
        $user->email = $request->get('email');
        $user->cpf = $request->get('cpf');
        $user->role = $request->get('role');

        if ($request->get('password') || $request->get('passwordRepeat')) {
            if ($request->get('password') === $request->get('passwordRepeat')) {
                $user->password = app('hash')->make($request->get('password'));
            } else {
                return response()->json(['error' => 'passwordNotEqual'], 405);
            }
        }

        $user->save();

        return response()->json(null);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function delete(Request $request, $id)
    {
        if (!$id) {
            return response()->json(null, 405);
        }

        $user = User::query()->findOrFail($id);
        $user->delete();

        return response()->json(null, 204);
    }
}

<?php

namespace App\Http\Controllers;

use App\Repositories\UserRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserController extends Controller
{
    private $userRepository;

    /**
     * UserController constructor.
     * @param UserRepository $ur
     */
    public function __construct(UserRepository $ur)
    {
        $this->userRepository = $ur;
    }

    /**
     * @return Collection
     */
    public function getAll(): Collection
    {
        return $this->userRepository->findAll();
    }

    /**
     * @param $id
     * @return Model
     */
    public function getOne($id): Model
    {
        return $this->userRepository->findOneById($id);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request): JsonResponse
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|unique:users',
            'cpf' => 'required|unique:users',
            'role' => 'required',
            'password' => 'required',
            'passwordRepeat' => 'required'
        ]);

        return $this->userRepository->create($request->all());
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required',
            'cpf' => 'required',
            'role' => 'required'
        ]);

        return $this->userRepository->update($request->all(), $id);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function delete(int $id): JsonResponse
    {
        return response()->json($this->userRepository->delete($id), Response::HTTP_NO_CONTENT);
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Log;

class UsersController extends Controller
{
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    protected function store(CreateUserRequest $request)
    {
        try {
            $data = $request->validated();
            $data['password'] = bcrypt($data['password']);
            $created = $this->user->create($data);
            return !empty($created) ? response()->json($created) : response()->json(['msg' => 'Um erro inesperado occoreu ao criar o usuario'], 500);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return  response()->json(['msg' => 'Um erro inesperado occoreu ao criar o usuario'], 500);
        }
    }
}

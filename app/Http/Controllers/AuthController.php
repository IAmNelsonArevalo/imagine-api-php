<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Create a new user.
     *
     * @param Request $request The request object.
     * @return array The API response array.
     */
    public function createUser(Request $request): array
    {
        $status = false;
        $result = null;
        $user = null;
        DB::beginTransaction();
        try {
            $user = User::create([
                'uid' => Hash::make($request->name),
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
                'document' => $request->document,
                'status_id' => $this->active_status
            ]);

            $user->assignRole('Customer');

            $status = true;
            DB::commit();
        } catch (\Throwable $th) {
            $result = $th->getMessage();
            DB::rollBack();
        }

        if ($status) {
            return $this->responseApi(true, ['type' => 'success', 'content' => 'User created.'], $user);
        } else {
            return $this->responseApi(false, ['type' => 'error', 'content' => 'Error creating user.'], $result);
        }
    }

    /**
     * Authenticate a user and generate a token.
     *
     * @param Request $request The request object.
     * @return array The API response array.
     */
    public function login(Request $request): array
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $activeUser = User::with('roles')->where('email', $request->email)->whereStatusId($this->active_status)->first();
            if (isset($activeUser->id)) {
                $token = $activeUser->createToken('imagine apps')->accessToken;
                return $this->responseApi(false, ['type' => 'error', 'content' => 'Bienvenido'], [
                    'token' => $token,
                    'user' => $activeUser
                ]);
            } else {
                return $this->responseApi(false, ['type' => 'error', 'content' => 'No esta autorizado para el ingreso.'], []);
            }
        } else {
            return $this->responseApi(false, ['type' => 'error', 'content' => 'El usuario no existe en la base de datos.'], []);
        }
    }

    /**
     * Change the password of a user.
     *
     * @param Request $request The request object.
     * @return array The API response array.
     */
    public function changePassword(Request $request): array
    {
        $status = false;
        $result = null;
        $user = null;
        DB::beginTransaction();
        try {
            $user = User::where('email', $request->email)->first();
            $user->password = Hash::make($request->password);
            $user->save();

            $status = true;
            DB::commit();
        } catch (\Throwable $th) {
            $result = $th->getMessage();
            DB::rollBack();
        }

        if ($status) {
            return $this->responseApi(true, ['type' => 'success', 'content' => 'User created.'], $user);
        } else {
            return $this->responseApi(false, ['type' => 'error', 'content' => 'Error creating user.'], $result);
        }
    }

    /**
     * Change the role of a user.
     *
     * @param Request $request The request object.
     * @return array The API response array.
     */
    public function changeRole(Request $request): array
    {
        $status = false;
        $result = null;
        $user = null;
        DB::beginTransaction();
        try {
            $user = User::where('email', $request->email)->first();
            $user->syncRoles([$request->role]);
            $user->save();

            $status = true;
            DB::commit();
        } catch (\Throwable $th) {
            $result = $th->getMessage();
            DB::rollBack();
        }

        if ($status) {
            return $this->responseApi(true, ['type' => 'success', 'content' => 'User created.'], $user);
        } else {
            return $this->responseApi(false, ['type' => 'error', 'content' => 'Error creating user.'], $result);
        }
    }
}

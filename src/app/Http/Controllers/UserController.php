<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class UserController extends BaseController
{
    //
    public function create(Request $request)
    {
        try {
            return User::firstOrCreate([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
                'taxCode' => $request->taxCode,
                'store' => $request->store
            ]);
        } catch (Throwable $t) {
            throw new HttpException(422, 'usuario não pode ser criado');
        }
    }

    public function show(Request $request, $id)
    {
        return User::findOrFail($id);
    }
}

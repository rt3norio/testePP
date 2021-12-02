<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

class UserController extends BaseController
{
    //
    public function create(Request $request)
    {
        return User::firstOrCreate([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'taxCode' => $request->taxCode,
            'store' => $request->store
        ]);
    }

    public function show(Request $request, $id)
    {
        return User::findOrFail($id);
    }
}

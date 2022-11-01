<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator as FacadesValidator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $registrationData = $request->all();

        $validate = FacadesValidator::make($registrationData, [
            'name' => 'required|max:60',
            'username' => 'required|min:6|max:12|unique:users|regex:/^([a-zA-Z0-9@*#]{8,15})$/',
            'image' => 'required|image:jpeg,png,jpg,gif,svg|max:2000',
            'email' => 'required|email:rfc,dns|unique:users',
            'password' => 'required'
        ]);

        if ($validate->fails())
            return response(['message' => $validate->errors()], 400);

        $registrationData['password'] = bcrypt($request->password);
        // cara upload image ke lokal storage sesuai upload di form (?) cari yang betul (?)
        $uploadFolder = 'users';
        $image = $request->file('image');
        $image_uploaded_path = $image->store($uploadFolder, 'public');
        $uploadedImageResponse = array(
            "image_name" => basename($image_uploaded_path),
            "image_url" => Storage::disk('public')->url($image_uploaded_path),
            "mime" => $image->getClientMimeType()
        );
        //
        $user = User::create($registrationData);

        return response([
            'message' => 'Register Success',
            'user' => $user,
            'image' => $uploadedImageResponse
        ], 200);
    }

    public function login(Request $request)
    {
        $loginData = $request->all();

        $validate = FacadesValidator::make($loginData, [
            'email' => 'required|email:rfc,dns',
            'password' => 'required'
        ]);

        if ($validate->fails())
            return response(['message' => $validate->errors()], 400);

        if (!Auth::attempt($loginData))
            return response(['message' => 'Invalid Credentials'], 401);

        $user = Auth::user();
        $token = $user->createToken('Authentication Token')->accessToken;

        return response([
            'message' => 'Authenticated',
            'user' => $user,
            'token_type' => 'Bearer',
            'access_token' => $token
        ]);
    }

    public function logout(Request $request)
    {
        $user = Auth::user()->token();
        $user->revoke();
        return response([
            'message' => 'Authenticated logout'
        ]);
    }
}
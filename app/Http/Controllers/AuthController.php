<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    use ApiResponser;

    /**
     * Login the user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        //Validate data
        $data = $request->only('username', 'password');
        $validator = Validator::make($data, [
            'username' => 'required|string|max:255',
            'password' => 'required|string',
        ]);
        if($validator->fails()){
            return $this->errorResponse($validator->messages(), Response::HTTP_NON_AUTHORITATIVE_INFORMATION);
        }
        $user = User::where('email', $request->username)
            ->orWhere('username', $request->username)->first();
        if (! $user || ! Hash::check($request->password, $user->password)) {
            return $this->errorResponse(['username' => ['The provided credentials are incorrect.']], Response::HTTP_NON_AUTHORITATIVE_INFORMATION);
        }
        $token = $user->createToken($request->username)->plainTextToken;
        $user['authorisation'] = ([
            'token' => $token,
            'type' => 'bearer',
        ]);
        return $this->successResponse($user, 'User Successfully Login');
    }

    /**
     * Logout the specified user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return $this->successResponse(null,'Successfully logged out');
    }

}
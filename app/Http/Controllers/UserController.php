<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{
    use ApiResponser;
    // Store login User id;
    private $loginuserid;

    /**
     * Assign Auth user id to login user id variable.
     *
     */


    /**
     * Get the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::with('roles')->get();
        return $this->successResponse($users);
    }

    /**
     * Store the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Validate data
        $data = $request->only('name', 'email', 'username', 'password');
        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'username' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);
        if($validator->fails()){
            return $this->errorResponse($validator->messages(), Response::HTTP_NON_AUTHORITATIVE_INFORMATION);
        }
        $data['created_user_id'] = $this->loginuserid;
        $data['updated_user_id'] = $this->loginuserid;
        $data['password'] = Hash::make($request->password);
        $user = User::create($data);
        if($user) {
            return $this->successResponse($user, 'User Successfully Created', Response::HTTP_CREATED);
        }
        return $this->errorResponse('Date not store, kindly do this request again', Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * Show the specified resource in storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);
        if($user)
        {
            $user->roles->pluck('name');
            return $this->successResponse($user);
        }
        return $this->errorResponse('Page Not Found',Response::HTTP_NOT_FOUND);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::find($id);
        if($user)
        {
            //Validate data
            $data = $request->only('name', 'email', 'username', 'password');
            $validator = Validator::make($data, [
                'name' => 'nullable|string|max:255',
                'email' => 'nullable|string|email|max:255|unique:users',
                'username' => 'nullable|string|max:255|unique:users',
                'password' => 'nullable|string|min:6',
            ]);
            if($validator->fails()){
                return $this->errorResponse($validator->messages(), Response::HTTP_NON_AUTHORITATIVE_INFORMATION);
            }
            if(isset($data['password']))
                $data['password'] = Hash::make($data['password']);
            $data['updated_user_id'] = $this->loginuserid;
            $status = $user->update($data);
            if($status)
            {
                $user->roles->pluck('name');
                return $this->successResponse($user, 'User Successfully Updated');
            }
            return $this->errorResponse('Date not store, kindly do this request again', Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        return $this->errorResponse('Page Not Found',Response::HTTP_NOT_FOUND);
    }


    /**
     * Remove the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);
        if($user)
        {
            $data = (['updated_user_id' => $this->loginuserid]);
            $user->update($data);
            $user->syncRoles([]);
            $user->delete();
            return $this->successResponse(null,'User Deleted Successfully');
        }
        return $this->errorResponse('Page Not Found',Response::HTTP_NOT_FOUND);
    }


}
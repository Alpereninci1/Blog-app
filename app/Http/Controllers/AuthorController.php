<?php

namespace App\Http\Controllers;

use App\Models\Author;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AuthorController extends Controller
{
    use ApiResponser;
    // Store login User id;
    private $loginuserid;

    /**
     * Assign Auth user id to login user id variable.
     *
     */
    public function __construct()
    {
        $this->middleware(function($request, $next){
            $this->loginuserid = Auth::user()->id;
            return $next($request);
        });
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $authors = Author::all();
        return $this->successResponse($authors);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->only('name','surname');
        $validator = Validator::make($data,[
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
        ]);
        if($validator->fails()){
            return $this->errorResponse($validator->messages(), Response::HTTP_NON_AUTHORITATIVE_INFORMATION);
        }
        $data['created_user_id'] = $this->loginuserid;
        $data['updated_user_id'] = $this->loginuserid;
        $result = Author::create($data);
        if($result) {
            return $this->successResponse($result, 'Author Successfully Created', Response::HTTP_CREATED);
        }
        return $this->errorResponse('Date not store, kindly do this request again', Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $author = Author::find($id);
        if($author){
            return $this->successResponse($author);
        }
        return $this->errorResponse('Page Not Found',Response::HTTP_NOT_FOUND);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $author = Author::find($id);
        if($author){
            $data = $request->only('name','surname');
            $validator = Validator::make($data,[
                'name' => 'required|string|max:255',
                'surname' => 'required|string|max:255',
            ]);
            if($validator->fails()){
                return $this->errorResponse($validator->messages(), Response::HTTP_NON_AUTHORITATIVE_INFORMATION);
            }
            $data['updated_user_id'] = $this->loginuserid;
            $success = $author->update($data);
            if($success){
                return $this->successResponse($author, 'Author Successfully Updated');
            }
            return $this->errorResponse('Date not store, kindly do this request again', Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        return $this->errorResponse('Page Not Found',Response::HTTP_NOT_FOUND);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $author = Author::find($id);
        if($author)
        {
            $data = (['updated_user_id' => $this->loginuserid]);
            $author->update($data);
            $author->delete();
            return $this->successResponse(null,'Author Deleted Successfully');
        }
        return $this->errorResponse('Page Not Found',Response::HTTP_NOT_FOUND);
    }
}

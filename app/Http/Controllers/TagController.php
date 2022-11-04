<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TagController extends Controller
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
        $tags = Tag::all();
        return $this->successResponse($tags);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->only('name');
        $validator = Validator::make($data,[
            'name' => 'required|string|max:255',
        ]);
        if($validator->fails()){
            return $this->errorResponse($validator->messages(), Response::HTTP_NON_AUTHORITATIVE_INFORMATION);
        }
        $data['created_user_id'] = $this->loginuserid;
        $data['updated_user_id'] = $this->loginuserid;
        $result = Tag::create($data);
        if($result) {
            return $this->successResponse($result, 'Tag Successfully Created', Response::HTTP_CREATED);
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
        $tag = Tag::find($id);
        if($tag){
            return $this->successResponse($tag);
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
        $tag = Tag::find($id);
        if($tag){
            $data = $request->only('name');
            $validator = Validator::make($data,[
                'name' => 'required|string|max:255',    
            ]);
            if($validator->fails()){
                return $this->errorResponse($validator->messages(), Response::HTTP_NON_AUTHORITATIVE_INFORMATION);
            }
            $data['updated_user_id'] = $this->loginuserid;
            $success = $tag->update($data);
            if($success){
                return $this->successResponse($tag, 'Tag Successfully Updated');
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
        $tag = Tag::find($id);
        if($tag)
        {
            $data = (['updated_user_id' => $this->loginuserid]);
            $tag->update($data);
            $tag->delete();
            return $this->successResponse(null,'Tag Deleted Successfully');
        }
        return $this->errorResponse('Page Not Found',Response::HTTP_NOT_FOUND);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Traits\ApiResponser;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
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
        $categories = Category::all();
        return $this->successResponse($categories);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->only('tag_id','name');
        $validator = Validator::make($data,[
            'tag_id' => 'required|exists:tags,id',
            'name' => 'required|string|max:255',
        ]);
        if($validator->fails()){
            return $this->errorResponse($validator->messages(), Response::HTTP_NON_AUTHORITATIVE_INFORMATION);
        }
        $data['created_user_id'] = $this->loginuserid;
        $data['updated_user_id'] = $this->loginuserid;
        $result = Category::create($data);
        if($result) {
            return $this->successResponse($result, 'Category Successfully Created', Response::HTTP_CREATED);
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
        $category = Category::find($id);
        if($category){
            return $this->successResponse($category);
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
        $category = Category::find($id);
        if($category){
            $data = $request->only('tag_id','name');
            $validator = Validator::make($data,[
                'tag_id' => 'required|exists:tags,id',
                'name' => 'required|string|max:255',
            ]);
            if($validator->fails()){
                return $this->errorResponse($validator->messages(), Response::HTTP_NON_AUTHORITATIVE_INFORMATION);
            }
            $data['updated_user_id'] = $this->loginuserid;
            $success = $category->update($data);
            if($success){
                return $this->successResponse($category, 'Category Successfully Updated');
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
        $category = Category::find($id);
        if($category)
        {
            $data = (['updated_user_id' => $this->loginuserid]);
            $category->update($data);
            $category->delete();
            return $this->successResponse(null,'Category Deleted Successfully');
        }
        return $this->errorResponse('Page Not Found',Response::HTTP_NOT_FOUND);
    }
}

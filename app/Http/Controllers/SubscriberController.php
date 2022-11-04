<?php

namespace App\Http\Controllers;

use App\Models\Subscriber;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SubscriberController extends Controller
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
        $subscribers = Subscriber::all();
        return $this->successResponse($subscribers);
    }

    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->only('name','email');
        $validator = Validator::make($data,[
            'name' => 'required|string|max:255',
            'email' => 'nullable|string',
        ]);
        if($validator->fails()){
            return $this->errorResponse($validator->messages(), Response::HTTP_NON_AUTHORITATIVE_INFORMATION);
        }
        $data['created_user_id'] = $this->loginuserid;
        $data['updated_user_id'] = $this->loginuserid;
        $result = Subscriber::create($data);
        if($result) {
            return $this->successResponse($result, 'Subscriber Successfully Created', Response::HTTP_CREATED);
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
        $subscriber = Subscriber::find($id);
        if($subscriber){
            return $this->successResponse($subscriber);
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
        $subscriber = Subscriber::find($id);
        if($subscriber){
            $data = $request->only('name','email');
            $validator = Validator::make($data,[
                'name' => 'required|string|max:255',
                'email' => 'nullable|string',
            ]);
            if($validator->fails()){
                return $this->errorResponse($validator->messages(), Response::HTTP_NON_AUTHORITATIVE_INFORMATION);
            }
            $data['updated_user_id'] = $this->loginuserid;
            $success = $subscriber->update($data);
            if($success){
                return $this->successResponse($subscriber, 'Subscriber Successfully Updated');
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
        $subscriber = Subscriber::find($id);
        if($subscriber)
        {
            $data = (['updated_user_id' => $this->loginuserid]);
            $subscriber->update($data);
            $subscriber->delete();
            return $this->successResponse(null,'Subscriber Deleted Successfully');
        }
        return $this->errorResponse('Page Not Found',Response::HTTP_NOT_FOUND);
    }
}

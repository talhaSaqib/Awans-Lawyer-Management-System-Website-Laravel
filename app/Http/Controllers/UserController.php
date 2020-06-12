<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Requests;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function sendRequest(Request $request)
    {
        $validator = Validator::make($request->all(),                   //formData by ajax is only working for this validation, not via validate()
            [
                'desc' => 'required',
                'userid' => 'required',
                'fullname' => 'required',
                'category' => 'required',
                'categoryid' => 'required'
            ]);

        if($validator->fails())
        {
            return response()->json(['error' =>
                'Validation Failed! 
*Description is required'], 200);
        }

        $case_request = new Requests();
        $case_request->request = $request['desc'];
        $case_request->category = $request['category'];
        $case_request->username = $request['fullname'];
        $case_request->user_id = $request['userid'];
        $case_request->category_id = $request['categoryid'];

        if($case_request->save())
        {
            return response()->json(['success' => 'Request sent successfully'], 200);
        }
        else
        {
            return response()->json(['error' => '*There was a problem sending request. Try again later.'], 200);
        }


    }

}

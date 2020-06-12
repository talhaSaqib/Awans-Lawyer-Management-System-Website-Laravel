<?php

namespace App\Http\Controllers;

use App\Cases;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Requests;
use Illuminate\Support\Facades\Auth;

class RequestController extends Controller
{
    public function rejectRequest(Request $request)
    {
        $validator = Validator::make($request->all(),                   //formData by ajax is only working for this validation, not via validate()
            [
                'request_id' => 'required'
            ]);

        if($validator->fails())
        {
            return redirect()->back()->with('message','Request ID missing');
        }

        $request_id = $request['request_id'];

        if(Requests::where('id', $request_id)->delete())
        {
            return redirect()->back();
        }
        else
        {
            return redirect()->back()->with('message','An error occured. Try again later.');
        }

    }

    public function acceptRequest(Request $request)
    {
        $validator = Validator::make($request->all(),                   //formData by ajax is only working for this validation, not via validate()
            [
                'request_id' => 'required',
                'category_id' => 'required'
            ]);

        if($validator->fails())
        {
            return redirect()->back()->with('message','Request ID or Category ID is missing');
        }

        $request_id = $request['request_id'];
        $request_ = Requests::where('id', $request_id)->first();

        $case = new Cases();
        $case->case_category = $request_->category;
        $case->status = "Phase 1";
        $case->client_id = $request_->user_id;
        $case->employee_id = Auth::user()->id;
        $case->category_id = $request['category_id'];
        $case->description = $request_->request;
        $case->client_name = $request_->username;
        $case->employee_name = Auth::user()->fullname;


        if($case->save())
        {
            if(Requests::where('id', $request_id)->delete())
            {
                return redirect()->back();
            }
            else
            {
                return redirect()->back()->with('message','An error occured. Try again later.');
            }
        }
        else
        {
            return redirect()->back()->with('message','An error occured. Try again later.');
        }
    }

}

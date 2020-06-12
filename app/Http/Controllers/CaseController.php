<?php

namespace App\Http\Controllers;

use App\CaseMessages;
use App\Cases;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;


class CaseController extends Controller
{
    public function deleteCase(Request $request)
    {
        $validator = Validator::make($request->all(),                   //formData by ajax is only working for this validation, not via validate()
            [
                'case_id' => 'required'
            ]);

        if($validator->fails())
        {
            return redirect()->back()->with('message','Case ID missing');
        }

        $case_id = $request['case_id'];

        if(Cases::where('id', $case_id)->delete())
        {
            $route = new RouteController();
            return $route->toProfile();
        }
        else
        {
            return redirect()->back()->with('message','An error occurred in deleting case. Try again later.');
        }

    }

    public function updatePhase(Request $request)
    {
        $validator = Validator::make($request->all(),                   //formData by ajax is only working for this validation, not via validate()
            [
                'case_id' => 'required'
            ]);

        if($validator->fails())
        {
            return redirect()->back()->with('message','Case ID missing');
        }

        $case_id = $request['case_id'];
        $case_ = Cases::where('id', $case_id)->first();

        if($case_->status == "Phase 1")
        {
            $case_->status = "Phase 2";
        }
        else if($case_->status == "Phase 2")
        {
            $case_->status = "Phase 3";
        }
        else if($case_->status == "Phase 3")
        {
            $case_->status = "Completed";
        }

        if($case_->update())
        {
            return redirect()->back();
        }
        else
        {
            return redirect()->back()->with('message','An error occurred in updating phase. Try again later.');
        }


    }

    public function revertPhase(Request $request)
    {
        $validator = Validator::make($request->all(),                   //formData by ajax is only working for this validation, not via validate()
            [
                'case_id' => 'required'
            ]);

        if($validator->fails())
        {
            return redirect()->back()->with('message','Case ID missing');
        }

        $case_id = $request['case_id'];
        $case_ = Cases::where('id', $case_id)->first();

        if($case_->status == "Phase 2")
        {
            $case_->status = "Phase 1";
        }
        else if($case_->status == "Phase 3")
        {
            $case_->status = "Phase 2";
        }
        else if($case_->status == "Completed")
        {
            $case_->status = "Phase 3";
        }

        if($case_->update())
        {
            return redirect()->back();
        }
        else
        {
            return redirect()->back()->with('message','An error occurred in updating phase. Try again later.');
        }
    }

    public function sendMessage(Request $request)
    {
        $validator = Validator::make($request->all(),                   //formData by ajax is only working for this validation, not via validate()
            [
                'case_id' => 'required',
                'user_id' => 'required',
                'fullname' => 'required',
                'chat_msg' => 'required'
            ]);

        if($validator->fails())
        {
            return response()->json(['error' => 'Please enter a valid message'], 200);
        }

        $case_msg = new CaseMessages();
        $case_msg->case_id = $request['case_id'];
        $case_msg->user_id = $request['user_id'];
        $case_msg->fullname = $request['fullname'];
        $case_msg->message = $request['chat_msg'];

        if($case_msg->save())
        {
            $msgs = CaseMessages::where('case_id', $request['case_id'])->orderBy('created_at')->get();
            $view = View::make('includes.chat')->with('msgs', $msgs)->render();

            return response()->json(['success' => 'Success', 'html' => $view], 200);
        }
        else
        {
            return response()->json(['error' => 'There was a problem sending message. Try again later.'], 200);
        }
    }

}

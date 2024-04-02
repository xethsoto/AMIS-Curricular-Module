<?php

namespace App\Http\Controllers\Api;

use App\Models\Proposal;
use Illuminate\Http\Request;
use App\Models\ProposalAction;
use Illuminate\Validation\Rule;
use App\Models\CourseInstitution;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ProposalController extends Controller
{
    public function save(Request $request)
    {
        $requestData = $request->all();

        $title = $request->title;
        $action = $request->action;
        $content = $request->content;

        error_log('$requestData = '. print_r($requestData, true));

        // TODO: add a validator

        // Initial validation
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:191',
            'action' => 'required|array',
            'content' => 'required|array'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'title' => $title,
                'message' => $validator->messages()
            ], 422);
        } else {

            // Check if fields in the action variable are not empty
            error_log('$action = '.print_r($action, true));
            error_log('$action = '.print_r($action[0], true));

            $actionValidator = Validator::make($action, [
                '*' => 'array:propTarget,propType,propSubType',
                '*.propTarget' => 'required|alpha',
                '*.propType' => 'required|alpha',
                '*.propSubType' => 'required_if:*.propType,Revision'
            ]);

            error_log("Successfully validated the actions");

            if ($actionValidator->fails()) {
                return response()->json([
                    'status' => 422,
                    'message2' => "Action Validator failed",
                    'message' => $actionValidator->messages()
                ], 422);
            } else {
                
                error_log("Action form is valid!");
                error_log("Validating content form");
                
                //Check if fields in the formContent variable are empty
                $formsValidator = Validator::make($content, [
                    '*.*' => 'required'
                ]);

                if ($formsValidator->fails()) {
                    return response()->json([
                        'status' => 422,
                        'message2' => 'Forms Validator Failed',
                        'message' => $formsValidator->messages()
                    ], 422);
                } else {
                    // $proposalTitle = Proposal::create([
                    //     'name' => $title
                    // ]);

                    // /* Maybe the saving of different forms can be compartmentalized */
        
                    // // prop data
                    // for ($i = 0; $i < count($action); $i++){
                    //     if ($action[$i]->propTarget == 'Course'){
                    //         if ($action[$i]->propType == 'Institution'){
                    //             $courseInstitution = CourseInstitution::create([
                    //                 'code' => $content[$i]->content,
                    //                 'title' => $content[$i]->title,
                    //                 'desc' => $content[$i]->desc,
                    //                 'credit' => $content[$i]->credit,
                    //                 'num_of_hours' => $content[$i]->num_of_hours,
                    //                 'goal' => $content[$i]->goal,
                    //                 'outline' => $content[$i]->outline,
                    //                 'prop_id' => $content[$i]->prop_id,
                    //             ]);
                    //         }
                    //     }
                    // }

                    return response()->json([
                        'status' => 200,
                        'title' => $title,
                        'action' => $action,
                        'content' => $content,
                        'message' => "Successfully created proposal"
                    ], 200);
                }
            }
        }

        /* TODO:
        *   1) Match the request calls depending on what the front-end sends
        *   2) Complete the saving of the data to the appropriate table
        */
    }

    /* For testing data request and response of client and server */
    public function test (Request $request)
    {
        $title = $request->title;
        $action = $request->action;
        $content = $request->content;

        if ($title && $action && $content){
            return response()->json([
                'status' => 200,
                'title' => $title,
                'action' => $action,
                'content' => $content
            ], 200);
        } else {
            return response()->json([
                'status' => 500,
                'message' => 'Server Error Occured',
            ], 500);
        }
    }
}

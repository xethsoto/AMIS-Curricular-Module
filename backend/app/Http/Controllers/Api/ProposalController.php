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
        $title = $request->title;
        $action = $request->action;
        $content = $request->content;
        
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
                'message' => $validator->messages()
            ], 422);
        } else {

            // Check if fields in the action variable are not empty
            $actionValidator = Validator::make($action, [
                'propTarget' => 'required|alpha',
                'propType' => 'required|alpha',
                'propSubType' => Rule::requiredIf($action->propType == 'Revision')
            ]);

            if ($actionValidator->fails()) {
                return response()->json([
                    'status' => 422,
                    'message' => $actionValidator->messages()
                ], 422);
            } else {
                
                
                //Check if fields in the formContent variable are empty
                $rules = [];
                foreach ($content as $form => $formValue){
                    foreach ($formValue as $field) {
                        $rules[$form.$field] = 'required';
                    }
                }
                $formsValidator = Validator::make($content, $rules);

                if ($formsValidator->fails()) {
                    return response()->json([
                        'status' => 422,
                        'message' => $actionValidator->messages()
                    ], 422);
                } else {
                    $proposalTitle = Proposal::create([
                        'name' => $title
                    ]);

                    /* Maybe the saving of different forms can be compartmentalized */
        
                    // prop data
                    for ($i = 0; $i < count($action); $i++){
                        if ($action[$i]->propTarget == 'Course'){
                            if ($action[$i]->propType == 'Institution'){
                                $courseInstitution = CourseInstitution::create([
                                    'code' => $content[$i]->content,
                                    'title' => $content[$i]->title,
                                    'desc' => $content[$i]->desc,
                                    'credit' => $content[$i]->credit,
                                    'num_of_hours' => $content[$i]->num_of_hours,
                                    'goal' => $content[$i]->goal,
                                    'outline' => $content[$i]->outline,
                                    'prop_id' => $content[$i]->prop_id,
                                ]);
                            }
                        }
                    }
                }
            }
        }

        /* TODO:
        *   1) Match the request calls depending on what the front-end sends
        *   2) Complete the saving of the data to the appropriate table
        *
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

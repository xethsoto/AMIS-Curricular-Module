<?php

namespace App\Http\Controllers\Api\ProposalsControllers;

use App\Http\Controllers\Api\ProposalsControllers\CourseControllers\CourseInstitutionController;
use App\Models\Proposal\Proposal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Proposal\ProposalClassification;
use Illuminate\Support\Facades\Validator;

class ProposalController extends Controller
{
    public function save(Request $request)
    {
        $requestData = $request->all();

        $title = $request->title;
        $action = $request->action;
        $content = $request->content;

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
                '*' => 'array:propTarget,propType,propSubType',
                '*.propTarget' => 'required|alpha',
                '*.propType' => 'required|alpha',
                '*.propSubType' => 'required_if:*.propType,Revision'
            ]);

            if ($actionValidator->fails()) {
                return response()->json([
                    'status' => 422,
                    'message' => $actionValidator->messages()
                ], 422);
            } else {
                //Check if fields in the formContent variable are empty
                $rules = [];

                $i  = 0;
                foreach ($content as $form){
                    foreach ($form as $field => $value){
                        if($field == 'prereqs' && $action[$i]['propTarget'] == 'Course' && $action[$i]['propType'] == 'Institution'){
                            continue; //skips prereq in Course Institutions
                        }
                        $rules["$i.$field"] = 'required';
                    }
                    $i++;
                }
                $formsValidator = Validator::make($content, $rules);

                if ($formsValidator->fails()) {
                    return response()->json([
                        'status' => 422,
                        'message' => $formsValidator->messages()
                    ], 422);
                } else {

                    try{
                        DB::beginTransaction();

                        $proposalTitle = Proposal::create([
                            'name' => $title
                        ]);
                        $proposal = json_decode($proposalTitle, true);
    
                        /* Maybe the saving of different forms can be compartmentalized */
                        // prop data
                        for ($i = 0; $i < count($action); $i++){
                            $proposalClassification = ProposalClassification::create([
                                'prop_id' => $proposal['id'],
                                'target' => $action[$i]['propTarget'],
                                'type' => $action[$i]['propType'],
                                'sub_type' => $action[$i]['propSubType'],
                                'rationale' => $content[$i]['rationale']
                            ]);

                            $propTarget = $action[$i]['propTarget'];
                            $propType = $action[$i]['propType'];

                            switch($propTarget){
                                case 'Course':
                                    switch($propType){
                                        case 'Institution':
                                            $controller = new CourseInstitutionController();
                                            $controller->save($proposal, $content[$i]);
                                            break;
                                    }
                                    break;
                            }
                        }

                        DB::commit();
                    } catch (\Exception $e) { 
                        DB::rollback();
                        return response()->json([
                            'status' => 500,
                            'message' => $e->getMessage()
                        ], 500);
                    }

                    return response()->json([
                        'status' => 200,
                        'message' => "Successfully created proposal"
                    ], 200);
                }
            }
        }
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

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

                    try{
                        DB::beginTransaction();

                        $proposalTitle = Proposal::create([
                            'name' => $title
                        ]);
                        $proposal = json_decode($proposalTitle, true);
    
                        /* Maybe the saving of different forms can be compartmentalized */
                        // prop data
                        for ($i = 0; $i < count($action); $i++){
                            error_log("\$action[$i] = ".print_r($action[$i],true));
                            error_log("\$proposal['id'] = ".$proposal['id']);
                            error_log("\$action[$i]['propTarget'] = ".$action[$i]['propTarget']);
                            error_log("\$action[$i]['propType'] = ".$action[$i]['propType']);
                            error_log("\$action[$i]['propSubType'] = ".$action[$i]['propSubType']);
                            error_log("\$content[$i]['rationale'] = ".$content[$i]['rationale']);

                            $proposalClassification = ProposalClassification::create([
                                'prop_id' => $proposal['id'],
                                'target' => $action[$i]['propTarget'],
                                'type' => $action[$i]['propType'],
                                'sub_type' => $action[$i]['propSubType'],
                                'rationale' => $content[$i]['rationale']
                            ]);

                            // if ($action[$i]['propTarget'] == 'Course'){
                            //     if ($action[$i]['propType'] == 'Institution'){
                            //         $courseInstitution = new CourseInstitutionController();
                            //         $courseInstitution->save($i, $proposal, $content);
                            //     }
                            // }

                            $propTarget = $action[$i]['propTarget'];
                            $propType = $action[$i]['propType'];

                            error_log('$propTarget = '.$propTarget);
                            error_log('$propType = '.$propType);
                            switch($propTarget){
                                case 'Course':
                                    switch($propType){
                                        case 'Institution':
                                            error_log("In Before Course Institution Controller");
                                            $controller = new CourseInstitutionController();
                                            $controller->save($proposal, $content[$i]);
                                            error_log("After Course Institution Controller");
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
                        'success' => true,
                        'status' => 200,
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

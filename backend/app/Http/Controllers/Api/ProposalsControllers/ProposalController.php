<?php

namespace App\Http\Controllers\Api\ProposalsControllers;

use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Models\Proposal\Proposal;
use Illuminate\Support\Facades\DB;
use function Laravel\Prompts\error;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use App\Models\Proposal\ProposalClassification;
use App\Models\Proposal\CourseProp\CourseInstitution;

use App\Models\Proposal\DegProgProp\DegProgInstitution;
use App\Http\Controllers\Api\ProposalsControllers\CourseControllers\CourseRevisionController;
use App\Http\Controllers\Api\ProposalsControllers\CourseControllers\CourseInstitutionController;
use App\Http\Controllers\Api\ProposalsControllers\DegProgControllers\DegProgInstitutionController;

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
                'message' => $validator->errors()->all()
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
                    'message' => $actionValidator->errors()->all()
                ], 422);
            } else {
                //Check if fields in the formContent variable are empty
                $rules = [];

                // REFACTOR: Validation can possibly be moved to the respective controllers
                $i  = 0;
                foreach ($content as $form){
                    foreach ($form as $field => $value){
                        if ($action[$i]['propType'] != 'Revision'){
                            // Non-revisions do not allow null values
                            if($field == 'prereqs' && $action[$i]['propTarget'] == 'Course' && $action[$i]['propType'] == 'Institution'){
                                continue; //skips prereq in Course Institutions
                            }
                            $rules["$i.$field"] = 'required';
                        }
                    }
                    $i++;
                }
                $formsValidator = Validator::make($content, $rules);

                if ($formsValidator->fails()) {
                    return response()->json([
                        'status' => 422,
                        'message' => $formsValidator->errors()->all()
                    ], 422);
                } else {

                    try{
                        DB::beginTransaction();

                        $proposalTitle = Proposal::create([
                            'name' => $title
                        ]);
                        $proposal = json_decode($proposalTitle, true);
    
                        // Saving each subproposal of a proposal
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
                            if($action[$i]['propSubType']){
                                $propSubType = $action[$i]['propSubType'];
                            }

                            // Forms Controller
                            switch($propTarget){
                                case 'Course':
                                    switch($propType){
                                        case 'Institution':
                                            $controller = new CourseInstitutionController();
                                            $controller->save($proposal, $content[$i]);
                                            break;
                                        case 'Revision':
                                            $controller = new CourseRevisionController();

                                            //different functions for different subtypes
                                            switch($propSubType){
                                                case 'Change in course number and/or course title':
                                                    $newCourse = $controller->changeCodeTitle($proposal, $content[$i]);
                                                    if ($newCourse['code'] != $content[$i]['selectedCourse']){
                                                        foreach($content as $key => $value){
                                                            // we change the selectedCourse on each content to match the new course code
                                                            $content[$key]['selectedCourse'] = $newCourse['code'];
                                                            error_log("contentItem['selectedCourse'] = ".$content[$key]['selectedCourse']);
                                                        }
                                                    }
                                                    $content[$i]['selectedCourse'] = $newCourse['code'];  //we change the content code to still refer to the same course after its code is updated
                                                    break;
                                                case 'Change in course description':
                                                    $controller->changeDesc($proposal, $content[$i]);
                                                    break;     
                                                case 'Change in prerequisites':
                                                    $controller->changePrereqs($proposal, $content[$i]);
                                                    break;                                      
                                                case 'Change in semester offering':
                                                    $controller->changeSemOffered($proposal, $content[$i]);
                                                    break;
                                                case 'Change in number of hours':
                                                    $controller->changeHours($proposal, $content[$i]);
                                                    break;
                                                case 'Change in course content':
                                                    $controller->changeContent($proposal, $content[$i]);
                                                    break;
                                            }
                                            break;
                                    }
                                    break;
                                case 'Degree Program':
                                    switch($propType){
                                        case 'Institution':
                                            $controller = new DegProgInstitutionController();
                                            $controller->save($proposal, $content[$i]);
                                            break;
                                    }
                                    break;
                            }
                        }


                        DB::commit();
                    } catch (\Exception $e) { 
                        DB::rollback();

                        if ($e instanceof ValidationException) {
                            return response()->json([
                                'status' => 422,
                                'message' => Arr::flatten($e->errors())
                            ], 422);
                        }

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

    // Get all proposals
    public function getProposals ()
    {
        $proposals = Proposal::with('proposalClassification', 'courseInstitutions')->get();
        return response()->json($proposals);
    }

    /*
    * Basic info like the name,
    * date created, and the classification
    * Mainly used for displaying in proposals table
    */
    public function getProposalsBasicInfo()
    {
        $proposals = Proposal::with('proposalClassification')->get();
        return response()->json($proposals->map(function ($proposal) {
            $target = [];
            $type = [];
            $subType = [];

            // flattens multiple classification into a list
            foreach($proposal->proposalClassification as $classification){
                $target[] = $classification['target'];
                $type[] = $classification['type'];
                $subType[] = $classification['sub_type'];
            }

            return [
                'id' => $proposal->id,
                'name' => $proposal->name,
                'date_created' => $proposal->created_at->format('d-m-Y'),
                'target' => $target,
                'type' => $type,
                'sub_type' => $subType
            ];
        }));
    }

    /*
    * Get proposal by id
    */
    public function getProposal($id)
    {
        $proposal = Proposal::with(
            'proposalClassification'
        )->where('id', $id)->first();
        error_log("proposal". $proposal);    
        $proposal->date_created = $proposal->created_at->format('d-m-Y');
        $proposal->subproposals = $proposal->getSubproposals();
        error_log("proposal->created_at: " . $proposal->created_at);
        error_log("proposal". $proposal);    
        return response()->json($proposal);
    }
}

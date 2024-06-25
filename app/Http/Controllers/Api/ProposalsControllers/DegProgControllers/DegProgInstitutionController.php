
<?php

namespace App\Http\Controllers\Api\ProposalsControllers\DegProgControllers;

use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Proposal\DegProgProp\DegProgPropMajors;
use App\Models\Proposal\DegProgProp\DegProgInstitution;
use App\Models\Proposal\DegProgProp\DegProgPropCurricula;

class DegProgInstitutionController extends Controller
{
    public function save($proposal, $content)
    {
        try{
            // Degree Program Proposal
            $degProgInstitution = DegProgInstitution::create([
                'name' => $content['name'],
                'career' => $content['career'],
                'college' => $content['college'],
                'num_of_units' => $content['numOfUnits'],
                'desc' => $content['desc'],
                'prop_id' => $proposal['id'],
            ]);

            // Degree Program
            // TODO: Add Degree Program create

            foreach ($content['majors'] as $major) {
                // Degree Program Proposal Majors
                $degProgPropMajors = DegProgPropMajors::create([
                    'new_deg_prog' => $degProgInstitution->id,
                    'name' => $major
                ]);

                // Degree Program Majors
                // TODO: Add Degree Program Major create
            }

            foreach ($content['curricula'] as $curriculum) {
                $degProgPropCurricula = DegProgPropCurricula::create([
                    'name' => $curriculum->name,
                    'new_deg_prog_major_id' => $curriculum
                    // TODO: Not done
                ]);
            }
        } catch (\Exception $e) {
            error_log($e->getMessage());
            throw new Exception($e->getMessage());
        }

    }
}

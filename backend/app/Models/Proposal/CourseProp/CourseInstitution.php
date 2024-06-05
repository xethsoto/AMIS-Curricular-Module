<?php

namespace App\Models\Proposal\CourseProp;

use App\Models\Course\Course;
use App\Models\Proposal\Proposal;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CourseInstitution extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'course_institutions';

    protected $fillable = [
        'course_id',
        'code',
        'title',
        'desc',
        'credit',
        'num_of_hours',
        'goal',
        'outline',
        'prop_id'
    ];

    public function prereqs()
    {
        return $this->hasMany(CoursePropPrereqs::class, 'new_course_id')->select(['new_course_id', 'prereq_code']);
    }

    public function semOffered()
    {
        return $this->hasMany(CoursePropSemOffered::class, 'new_course_id')->select(['new_course_id','sem_offered']);
    }

    public function getPrereqs()
    {  
        $prereqArray = [];
        $result = CoursePropPrereqs::where('new_course_id', $this->id)->get();
        if($result){
            $prereqCodes = $result->pluck('prereq_code');

            //convert each prereq code to its id, title, and code
            foreach($prereqCodes as $code){
                $prereq = Course::select('id', 'title', 'code')
                    ->where('code', $code)
                    ->first();
                if($prereq){ // for cases where the course doesn't exist yet
                    array_push($prereqArray, $prereq);
                }
            }
        }

        return $prereqArray;
    }

    public function getSemOffered()
    {
        $semOffered = CoursePropSemOffered::where('new_course_id', $this->id)->get();
        return $semOffered->pluck('sem_offered');
    }
}

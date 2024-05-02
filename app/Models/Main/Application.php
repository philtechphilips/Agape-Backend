<?php

namespace App\Models\Main;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;
    protected $table = 'applications';
    protected $fillable = ['fullName', 'dob', 'height', 'weight', 'schoolAttended', 'lastClass', 'otherSchool', 'highestClassBeforeLeaving', 'reasonForLeaving', 'headTeacherOfSchool', 'classToBeAdmitted', 'highestClass', 'academicAbility', 'positionInLastExam', 'introvert', 'troublesome', 'games', 'fathersName', 'mothersName', 'fathersPlaceOfWork', 'fathersHomeAddress', 'mothersHomeAddress', 'mothersPlaceOfWork', 'mothersPhone', 'fathersPhone', 'nameOfFinancer'];
}

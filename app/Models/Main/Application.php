<?php

namespace App\Models\Main;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;
    protected $table = 'applications';
    protected $fillable = ['name', 'surname', 'app_num', 'middlename', 'dob', 'height', 'weight', 'school_attended', 'last_class', 'other_school', 'highest_class_before_leaving', 'reason_for_leaving', 'head_teacher_of_school', 'class_to_be_admitted', 'highest_class', 'academic_ability', 'position_in_last_exam', 'introvert', 'troublesome', 'games', 'fathers_name', 'mothers_name', 'fathers_place_of_work', 'fathers_home_address', 'mothers_home_address', 'mothers_place_of_work', 'mothers_phone', 'fathers_phone', 'name_of_financer', 'imageUrl'];
}

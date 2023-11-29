<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appraisal extends Model
{
    use HasFactory;
    protected $fillable = ['stuId', 'examId', 'termId', 'term', 'surname', 'firstname',  'classId', 'punctuality', 'neatness', 'respect', 'interractions', 'sport', 'initiative', 'session'];
}

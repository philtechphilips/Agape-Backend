<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use HasFactory;
    protected $table = 'exams';
    protected $fillable = ['studId', 'examId', 'section', 'total', 'grade', 'remarks', 'termId', 'term', 'surname', 'firstname', 'subject', 'classId', 'ca', 'exam_mark', 'session'];
}

<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FirstTermResults extends Model
{
    use HasFactory;
    protected $fillable = ['stuId', 'examId', 'section', 'total', 'grade', 'remarks', 'termId', 'term', 'surname', 'firstname', 'subject', 'classId', 'ca', 'exam_mark', 'session'];

    public function exam()
    {
        return $this->belongsTo(Exam::class, 'examId');
    }

    public function session()
    {
        return $this->belongsTo(Session::class, 'session');
    }

    public function class()
    {
        return $this->belongsTo(ClassName::class, 'classId');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'stuId');
    }

    public function term()
    {
        return $this->belongsTo(Student::class, 'termId');
    }
}

<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    use HasFactory;
    protected $fillable = ['stuId', 'classId', 'session', 'termId', 'examId', 'is_released'];

    public function students()
    {
        return $this->belongsTo(Student::class, 'stuId');
    }

    public function class()
    {
        return $this->belongsTo(ClassName::class, 'classId');
    }

    public function session()
    {
        return $this->belongsTo(Session::class, 'session');
    }

    public function term()
    {
        return $this->belongsTo(Term::class, 'termId');
    }

    public function exam()
    {
        return $this->belongsTo(Exam::class, 'examId');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'stuId');
    }
}

<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    use HasFactory;
    protected $fillable = ['stuId', 'classId', 'session', 'termId', 'examId'];

    public function students()
    {
        return $this->belongsTo(Student::class, 'stuId');
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
}

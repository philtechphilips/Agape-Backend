<?php

namespace App\Models;

use App\Models\Admin\ClassName;
use App\Models\Admin\Session;
use App\Models\Admin\Student;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ContinuousAssessment extends Model
{
    use HasFactory;
    protected $fillable = ['stuId', 'section', 'total', 'termId', 'term', 'surname', 'firstname', 'subject', 'classId', 'score', 'session', 'is_released', 'assignment_one', 'assignment_two', 'assignment_three', 'assignment_four', 'assignment_five', 'classwork_one', 'classwork_two', 'classwork_three', 'classwork_four', 'classwork_five', 'test_one', 'test_two', 'test_three'];
    public $incrementing = false;
    protected $keyType = 'string';

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid(); // Generate UUID
            }
        });
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

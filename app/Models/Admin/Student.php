<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;
    protected $fillable = ['surname', 'firstname', 'middlename', 'city', 'gender', 'dob', 'country', 'state', 'lga', 'religion', 'class_name_id', 'section', 'adNum', 'adDate', 'rollNumber', 'address', 'parent_id', 'user_id', 'imageUrl'];

    public function className()
    {
        return $this->belongsTo(ClassName::class, 'class_name_id');
    }

    public function section()
    {
        return $this->belongsTo(Section::class, 'section');
    }

    public function parent()
    {
        return $this->belongsTo(Guardian::class, 'parent_id');
    }
}

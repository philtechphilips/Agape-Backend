<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassName extends Model
{
    use HasFactory;
    protected $fillable = ['classname', 'section', 'teacher'];

    public function sections()
    {
        return $this->belongsTo(Section::class, 'id', 'section');
    }

    public function students()
    {
        return $this->hasMany(Student::class, 'className');
    }
}

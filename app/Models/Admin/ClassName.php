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
        return $this->belongsTo(Section::class, 'section');
    }

    public function teachers()
    {
        return $this->belongsTo(Staff::class, 'teacher');
    }
}

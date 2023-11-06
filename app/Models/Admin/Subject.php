<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;
    protected $fillable = ['subject', 'section', 'teacher'];

    public function sections()
    {
        return $this->belongsTo(Section::class, 'section');
    }

    public function teachers()
    {
        return $this->belongsTo(Staff::class, 'teacher');
    }
}

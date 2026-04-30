<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Traits\HasUuid;

class Exam extends Model
{
    use HasFactory, HasUuid;
    protected $table = 'exams';
}

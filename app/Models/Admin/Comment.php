<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;
    protected $fillable = ['stuId', 'examId', 'termId', 'term', 'surname', 'firstname',  'classId', 'comment', 'comment_type', 'session'];
}

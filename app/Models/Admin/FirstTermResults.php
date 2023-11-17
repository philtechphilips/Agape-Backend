<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FirstTermResults extends Model
{
    use HasFactory;
    protected $fillable = ["stuId"];
}

<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Traits\HasUuid;

class Section extends Model
{
    use HasFactory, HasUuid;
    protected $fillable = ['section'];
}

<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Traits\HasUuid;

class Guardian extends Model
{
    use HasFactory, HasUuid;
    protected $fillable = ['name', 'email', 'phone', 'address', 'role', 'user_id'];
}

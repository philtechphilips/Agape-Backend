<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    use HasFactory;
    protected $table = 'staff';
    protected $fillable = ['surname', 'firstname', 'middlename', 'email', 'phone', 'address', 'role', 'user_id'];
}

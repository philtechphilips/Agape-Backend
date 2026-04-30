<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Traits\HasUuid;

class Staff extends Model
{
    use HasFactory, HasUuid;
    protected $table = 'staff';
    protected $fillable = ['surname', 'firstname', 'middlename', 'email', 'phone', 'address', 'role', 'user_id'];
}

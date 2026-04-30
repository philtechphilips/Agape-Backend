<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Traits\HasUuid;

class Session extends Model
{
    use HasFactory, HasUuid;
    protected $table = 'sessions';
    protected $fillable = ['session', 'term', 'status'];

    public function term()
    {
        return $this->belongsTo(Term::class, 'term');
    }

}

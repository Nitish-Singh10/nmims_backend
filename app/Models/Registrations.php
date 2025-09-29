<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Registrations extends Model
{
    protected $table = 'registrations';
    protected $fillable = ['name', 'phone', 'email', 'program', 'qualification'];
}

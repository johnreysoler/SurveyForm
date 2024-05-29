<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssignHead extends Model
{
    use HasFactory;
    protected $connection = 'hr_portal';
}

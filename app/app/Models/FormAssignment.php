<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Assignment;
use Illuminate\Database\Eloquent\SoftDeletes;

class FormAssignment extends Model
{
    use HasFactory, SoftDeletes;

    public function assignments(){
        return $this->belongsTo(Assignment::class,'assignment_id','id');
    }
}

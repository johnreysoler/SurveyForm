<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Employee;
class Response extends Model
{
    use HasFactory;

    public function employee()
    {
        return $this->hasOne(Employee::class,'user_id','user_id')->select('id','user_id','id_number','employee_number','first_name','last_name','new_cluster','cluster','position','location','area','date_hired');
    }
}

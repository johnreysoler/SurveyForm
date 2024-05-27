<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\DepartmentEmployee; 
use App\Models\EmployeeLocation; 
use App\Models\CompanyEmployee; 

class Employee extends Model
{
    use HasFactory;
    
    protected $connection = 'hr_portal';

    public function departments(){
        return $this->hasOne(DepartmentEmployee::class,'employee_id','id');
    }

    public function locations(){
        return $this->hasOne(EmployeeLocation::class,'employee_id','id');
    }

    public function companies(){
        return $this->hasOne(CompanyEmployee::class,'employee_id','id');
    }
}

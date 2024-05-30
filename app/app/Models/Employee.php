<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\DepartmentEmployee; 
use App\Models\EmployeeLocation; 
use App\Models\CompanyEmployee; 
use App\Models\Company;
use App\Models\Department; 
use App\Models\Location;
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

    public function EmployeeCompany()
    {
        return $this->belongsToMany(Company::class);
    }
    
    public function EmployeeDepartment()
    {
        return $this->belongsToMany(Department::class);
    }
    public function EmployeeLocation()
    {
        return $this->belongsToMany(Location::class);
    }
}

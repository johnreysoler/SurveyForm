<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Status;
use App\Models\Assignment;
use App\Models\OptionType;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        // User::truncate();
        Status::truncate();
        Assignment::truncate();
        OptionType::truncate();
            
        OptionType::create([
            'name' => 'Radio',
            'type' => 'radio',
            'tag' => 'input',
            'multiple_select' => true
        ]);

        OptionType::create([
            'name' => 'Checkboxes',
            'type' => 'checkbox',
            'tag' => 'input',
            'multiple_select' => true
        ]);

        OptionType::create([
            'name' => 'Short Text',
            'type' => 'text',
            'tag' => 'text',
            'multiple_select' => false
        ]);

        OptionType::create([
            'name' => 'Paragraph',
            'type' => 'textarea',
            'tag' => 'text',
            'multiple_select' => false
        ]);

        OptionType::create([
            'name' => 'Dropdown',
            'type' => 'select',
            'tag' => 'select',
            'multiple_select' => false
        ]);

        // User::create([
        //     'name' => 'super user',
        //     'email' => 'superuser@lafilgroup.com',
        //     'password' => Hash::make('password'),
        // ]);

        Status::create([
            'name' => 'Created',
            'allow_update'=>true
        ]);

        Status::create([
            'name' => 'Publish',
            'allow_update'=>false
        ]);

        Status::create([
            'name' => 'Draft',
            'allow_update'=>true
        ]);

        Status::create([
            'name' => 'Locked',
            'allow_update'=>false
        ]);

        Status::create([
            'name' => 'Expired',
            'allow_update'=>false
        ]);

        Assignment::create([
            'name' => 'All',
            'model' => 'App\Models\Employee',
            'employee_function_name'=> null,
            'reference_column'=> 'id'
        ]);

        Assignment::create([
            'name' => 'Company',
            'model' => 'App\Models\Company',
            'employee_function_name'=>'companies',
            'reference_column'=> 'company_id'
        ]);

        Assignment::create([
            'name' => 'Cluster',
            'model' => 'App\Models\Cluster',
            'employee_function_name'=>'clusters',
            'reference_column'=> 'cluster_id'
        ]);

        Assignment::create([
            'name' => 'Employee',
            'model' => 'App\Models\Employee',
            'employee_function_name'=> null,
            'reference_column'=> 'id'
        ]);

        Assignment::create([
            'name' => 'Location',
            'model' => 'App\Models\Location',
            'employee_function_name'=>'locations',
            'reference_column'=> 'location_id'
        ]);

        Assignment::create([
            'name' => 'Department',
            'model' => 'App\Models\Department',
            'employee_function_name'=>'departments',
            'reference_column'=> 'department_id'
        ]);
    }
}

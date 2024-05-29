<?php

namespace App\Http\Controllers;

use App\Models\Form;
use App\Models\Status;
use App\Models\Assignment;
use App\Models\Prerequisite;
use App\Models\Department;
use App\Models\Company;
use App\Models\Cluster;
use App\Models\Employee;
use App\Models\Respondent;
use App\Models\FormAssignment;
use App\Http\Requests\StoreFormRequest;
use App\Http\Requests\UpdateFormRequest;
use Illuminate\Http\Request;
use App\Models\Head;
use App\Models\AssignHead;
use Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
class FormController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.survey.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $form_title = Form::where('id',$request->id)->first()['title'];
        
        return view('pages.survey.surveylayout',compact('form_title'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreFormRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'assign_by' => 'required',
            'respondents'=> Rule::requiredIf($request->assign_by !== 1),//'required_if:assign_by,===,1' ,
        ]);

        $assignment = Assignment::where('id',$request->assign_by)->first();
        
        $employees;
        if($request->assign_by === 1){
            $employees = Employee::where('date_resigned',null)->get();
        }
        else{
        if(empty($assignment->employee_function_name)){
            $employees = Employee::where('date_resigned',null)->whereIn($assignment->reference_column,$request->respondents)->get();
        }
        else{
            $employees = Employee::with([$assignment->employee_function_name=>function($q) use($request,$assignment){
                $q->whereIn($assignment->reference_column,$request->respondents);
            }])->where('date_resigned',null)->whereHas($assignment->employee_function_name,function($q) use($request,$assignment){
                $q->whereIn($assignment->reference_column,$request->respondents);
            })->get();
            }
        }

        $status_id = Status::where('name','Created')->first()['id'];
        $form = new Form;
        $form->title = $request->title;
        $form->description = $request->description;
        $form->status_id = $status_id;
        $form->assignment_id = $request->assign_by;
        $form->start_date = $request->start_date;
        $form->end_date = $request->end_date;
        $form->created_by = Auth::user()->id;
        if($form->save()){
            if(!empty($request->prerequisiteForm)){
                $prerequisite = new Prerequisite;
                $prerequisite->prerequisite_form_id = $request->prerequisiteForm;
                $prerequisite->prerequisite_section_id = $request->prerequisiteQuestion;
                $prerequisite->answer = $request->prerequisiteOption;
                $prerequisite->form_id = $form->id;
                $prerequisite->save();
            }

            foreach($employees as $key=>$employee){   
                $repondent = new Respondent;
                $repondent->form_id = $form->id;
                $repondent->user_id = $employee->user_id;  
                $repondent->save();   
           }

           foreach ($request->respondents as $key => $assignment_id) {
                $formAssignment = new FormAssignment;
                $formAssignment->form_id =  $form->id;
                $formAssignment->respondent_id = $assignment_id;
                $formAssignment->save();
           }
        }

        return ['redirect'=>route('survey-form-layout'),'id'=>$form->id];
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Form  $form
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $users = [Auth::user()->id];
        $isBUHead = false;
        $employees = AssignHead::where([
            ['head_id',Head::where('name','BU Head')->first()['id']],
            ['employee_head_id',$users]
        ])->get();

        if($employees->isNotEmpty()){
            $users = $employees->pluck('employee_id');
            $isBUHead = true;
        }

        return ['surveyForms'=>Form::with(['prerequisites'=> function($query) use($request){
            $query->where('section_id',null);
        },'statuses','assignments','created_bys','published_bys','form_assignmets'
        ])->when($request->search, function ($q) use ($request) {
            $q->orWhere('title', 'LIKE', '%' . $request->search . '%')
              ->orWhere('description', 'LIKE', '%' . $request->search . '%');
        })->whereIn('created_by',$users)->get(),
    'assignments'=>Assignment::all(),
    'isBUHead'=>$isBUHead];
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Form  $form
     * @return \Illuminate\Http\Response
     */
    public function edit(Form $form)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateFormRequest  $request
     * @param  \App\Models\Form  $form
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Form $form)
    {
        $request->validate([
            'title' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'assign_by' => 'required',
            'respondents'=> Rule::requiredIf($request->assign_by !== 1)
        ]);
        
        $assignment = Assignment::where('id',$request->assign_by)->first();
        FormAssignment::where('form_id',$request->form_id)->delete();
        Respondent::where('form_id',$request->form_id)->delete();

        $assignment = Assignment::where('id',$request->assign_by)->first();
        
        $employees;
        if($request->assign_by === 1){
            $employees = Employee::where('date_resigned',null)->get();
        }
        else{
            if(empty($assignment->employee_function_name)){
                $employees = Employee::where('date_resigned',null)->whereIn($assignment->reference_column,$request->respondents)->get();
            }
            else{
                $employees = Employee::with([$assignment->employee_function_name=>function($q) use($request,$assignment){
                    $q->whereIn($assignment->reference_column,$request->respondents);
                }])->where('date_resigned',null)->whereHas($assignment->employee_function_name,function($q) use($request,$assignment){
                    $q->whereIn($assignment->reference_column,$request->respondents);
                })->get();
            }
        }

        $form = Form::where('id',$request->form_id)->first();
        $form->title = $request->title;
        $form->description = $request->description;
        $form->assignment_id = $request->assign_by;
        $form->start_date = $request->start_date;
        $form->end_date = $request->end_date;
        $form->save();
        if($form->save()){
                if(!empty($request->prerequisiteForm)){
                    $prerequisite = Prerequisite::where('id',$request->prerequisiteId)->first();
                    if(empty($prerequisite)){
                        $prerequisite = new Prerequisite;
                    }
                    $prerequisite->prerequisite_form_id = $request->prerequisiteForm;
                    $prerequisite->prerequisite_section_id = $request->prerequisiteQuestion;
                    $prerequisite->answer = $request->prerequisiteOption;
                    $prerequisite->form_id = $form->id;
                    $prerequisite->save();
                }

                foreach ($request->respondents as $key => $repondent_id) {
                    $formAssignment = FormAssignment::where([['form_id',$request->form_id],['respondent_id',$repondent_id]])->withTrashed()->first();
                    if(empty($formAssignment)){
                        $formAssignment = new FormAssignment;
                    }

                    $formAssignment->form_id =  $form->id;
                    $formAssignment->respondent_id = $repondent_id;
                    $formAssignment->deleted_at = null;
                    $formAssignment->save();
               }

               foreach($employees as $key=>$employee){   
                $repondent = Respondent::where([['form_id',$request->form_id],['user_id',$employee->user_id]])->withTrashed()->first();
                    if(empty($repondent)){
                        $repondent = new Respondent;
                    }
                $repondent->form_id = $form->id;
                $repondent->user_id = $employee->user_id;  
                $repondent->deleted_at = null;
                $repondent->save();   
           }
                
        }
        return ['redirect'=>route('survey-form-layout'),'id'=>$form->id];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Form  $form
     * @return \Illuminate\Http\Response
     */
    public function destroy(Form $form)
    {
        //
    }

    public function fetchForm($form_id){
        // return Form::with('prerequisites','statuses','assignments',
        // 'sections','sections.options','sections.options','sections.optionTypes',
        // 'sections.images','sections.prerequisites')->where('id',$form_id)->first();

        return Form::with('sections','sections.options','sections.options','sections.optionTypes',
        'sections.images','sections.prerequisites','sections.section_prerequisites')->where('id',$form_id)->first();
    }
    public function publishForm(Request $request){
        $status_id = Status::where('name','Publish')->first()['id'];
        $form = Form::where('id',$request->form_id)->first();
        $form->status_id = $status_id;
        $form->published_by = Auth::user()->id;
        $form->save();

    }

    public function fetchViewForm($form_id){
        return ['redirect'=>route('view-survey-form'),'id'=>$form_id];
    }

    public function viewForm(Request $request){
        $form = Form::with('sections','sections.options','sections.options','sections.optionTypes',
        'sections.images','sections.prerequisites','sections.section_prerequisites')->where('id',$request->id)->first();

        return view('pages.survey.surveyview',compact('form'));
    }
}

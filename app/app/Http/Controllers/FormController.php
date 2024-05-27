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
use App\Http\Requests\StoreFormRequest;
use App\Http\Requests\UpdateFormRequest;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Validator;
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
        ]);

        $assignment = Assignment::where('id',$request->assign_by)->first();
        $employees;
        if(empty($assignment->employee_function_name)){
            $employees = Employee::where([['date_resigned',null],[$assignment->reference_column,array($request->surveyFormsRespondents)]])->get();
        }
        else{
            $employees = Employee::with([$assignment->employee_function_name => function($q) use($request,$assignment){
                $q->wherein($assignment->reference_column,array($request->surveyFormsRespondents));
            }])->where('date_resigned',null)->get();
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
                $repondent->user_id = $employee->id;       
                    if(!empty($employees[$key][$assignment->employee_function_name]) || empty($assignment->employee_function_name)){
                        $repondent->save();
                    }
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
        return ['surveyForms'=>Form::with(['prerequisites'=> function($query) use($request){
            $query->where('section_id',null);
        },'statuses','assignments','created_bys','published_bys'
        ])->when($request->search, function ($q) use ($request) {
            $q->orWhere('title', 'LIKE', '%' . $request->search . '%')
              ->orWhere('description', 'LIKE', '%' . $request->search . '%');
        })->get(),
    'assignments'=>Assignment::all(),];
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
        ]);
        
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

        return Form::with('sections','sections.options','sections.options','sections.optionTypes','sections.images','sections.prerequisites')->where('id',$form_id)->first();
    }

}

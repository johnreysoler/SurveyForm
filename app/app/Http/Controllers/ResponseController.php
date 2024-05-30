<?php

namespace App\Http\Controllers;

use App\Models\Response;
use App\Http\Requests\StoreResponseRequest;
use App\Http\Requests\UpdateResponseRequest;
use App\Models\Respondent;
use App\Models\Form;
use App\Models\Status;
use App\Models\Option;
use Illuminate\Http\Request;
use App\Models\Prerequisite;
use Carbon\Carbon;
use App\Traits\SurveyForm;
class ResponseController extends Controller
{
    use SurveyForm;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function index($user_id)
    {
        $userform = $this->ValidateSurveyForm($user_id);
        if(count($userform) > 0 ){
            return view('pages.survey.surveyform',compact('userform','user_id'));
        }
        else{
            return redirect(env('HR_PORTAL')); 
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreResponseRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        foreach($request->user_response as $index=>$user_response){
            $response = new Response;
            $response->user_id = $request->user_id;
            $response->form_id = $request->form_id;
            $response->question_id = $user_response['questionid'];
            $response->response = $user_response['text'];
            $response->save();
        }

        $status = Status::where('name','Locked')->first()['id'];
        $form = Form::where('id',$request->form_id)->first();
        $form->status_id = $status;
        $form->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Response  $response
     * @return \Illuminate\Http\Response
     */
    public function show(Response $response)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Response  $response
     * @return \Illuminate\Http\Response
     */
    public function edit(Response $response)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateResponseRequest  $request
     * @param  \App\Models\Response  $response
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateResponseRequest $request, Response $response)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Response  $response
     * @return \Illuminate\Http\Response
     */
    public function destroy(Response $response)
    {
        //
    }

    public function validateUserSurveyForm($user_id){
        $userform = $this->ValidateSurveyForm($user_id);
        $with_survey = false;
        if($userform->isNotEmpty()){
            $with_survey = true;
        }
        return $with_survey;
    }
}

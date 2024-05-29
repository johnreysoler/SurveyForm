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

class ResponseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($user_id)
    {
        $current_date = Carbon::now()->toDateString();   
        $status = Status::whereIn('name',['Publish','Locked'])->pluck('id');
        $userform;

        $forms = Form::whereHas('respondents',function($query) use($user_id){
            $query->where('user_id',$user_id);
        })
        ->with(['respondents'=>function($query) use($user_id){
            $query->where('user_id',$user_id);
        },'prerequisites'
        ])->whereIn('status_id',$status)
        ->where('end_date' ,'>=',$current_date)
        ->where('start_date' ,'<=',$current_date)
        ->wherenotIn('id',Response::where('user_id',$user_id)->get('form_id'))->get();

        foreach($forms as $form){
            if(count($form->prerequisites) > 0){
                $prerequisites = $form->prerequisites->where('form_id',$form->id)->where('section_id',null);
                if(count($prerequisites) > 0){
                    $prerequisite_form_ids = $prerequisites->pluck('prerequisite_form_id')->unique();
                    $prerequisite_question_ids = $prerequisites->pluck('prerequisite_section_id')->unique();
                    if(!empty($prerequisite_question_ids)){
                        foreach($prerequisite_question_ids as $question_id){
                            $prerequisite_answers = $prerequisites->where('prerequisite_section_id',$question_id)->pluck('answer')->toArray();
                            $user_reponses = Response::where([['question_id',$question_id],['user_id',$user_id]])
                            ->get()->pluck('response')->toArray();

                            if(count(array_intersect($prerequisite_answers,$user_reponses)) > 0){
                                $userform = $form;
                                break;
                            }
                        }
                        if(empty($userform)){
                            continue;
                        }
                        $userform = $form;
                        break;
                    }
                }
                else{
                    $userform = $form;
                    break;
                }
            }
            else{
                $userform = $form;
                break;
            }
        }
        if(!empty($userform)){
            return view('pages.survey.surveyform',compact('userform','user_id'));
        }
        else{
            return redirect('http://10.97.70.47:8085/'); 
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
}

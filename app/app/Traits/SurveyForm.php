<?php
namespace App\Traits;
use App\Models\Respondent;
use App\Models\Form;
use App\Models\Status;
use App\Models\Option;
use App\Models\Response;
use Illuminate\Http\Request;
use App\Models\Prerequisite;
use Carbon\Carbon;
use App\Traits\SurveyForm;
trait SurveyForm
{
    public function ValidateSurveyForm($user_id){
      
        $current_date = Carbon::now()->toDateString();   
        $status = Status::whereIn('name',['Publish','Locked'])->pluck('id');
        $userform = [];

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

        return collect($userform);
    }
}

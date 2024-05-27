<?php

namespace App\Http\Controllers;

use App\Models\FormSection;
use App\Models\Form;
use App\Models\Option;
use App\Models\Prerequisite;
use App\Models\Image;
use App\Models\Status;
use App\Http\Requests\StoreFormSectionRequest;
use App\Http\Requests\UpdateFormSectionRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Auth;
class FormSectionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  \App\Http\Requests\StoreFormSectionRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $collection = json_decode($request->collection, true);

        // return $collection = collect($collection)->filter(function ($item) {
        //     return $item['id'] === 1;
        //  })->first()['text'];

        foreach ($collection as $key=>$section) {
            $formSection = new FormSection;
            $formSection->form_id = $section['form_id'];
            $formSection->is_question = $section['is_question'];
            $formSection->text = $section['text'];
            $formSection->html = $section['html'];
            $formSection->option_type_id = empty($section['option_type']) ? null : $section['option_type']['id'];
            $formSection->is_required = $section['is_required'];
            $formSection->is_multiple_select = $section['is_multiple_select'];
            $formSection->with_other_option = $section['with_other_option'];
            $formSection->option_vertical_align = $section['option_position'];
            $formSection->image_position = $section['image_position'];
            $formSection->prerequisite = empty($section['prerequisites']) ? false : true;
            if($formSection->save()){
                if($formSection->prerequisite){
                    foreach ($section['prerequisites'] as $question_prerequisite) {

                        $text = collect($collection)->filter(function ($item) use($question_prerequisite) {
                            return $item['id'] === $question_prerequisite['prerequisite_question'];
                         })->first()['text'];

                       $section_id = FormSection::where([['text',$text],['form_id', $section['form_id']]])->first()['id'];
                       $prerequisite = new Prerequisite;
                       $prerequisite->prerequisite_form_id = $question_prerequisite['prerequisite_form'];
                       $prerequisite->prerequisite_section_id = $section_id;$question_prerequisite['prerequisite_question'];
                       $prerequisite->form_id = $section['form_id'];
                       $prerequisite->section_id = $formSection->id;
                       $prerequisite->answer = $question_prerequisite['prerequisite_option'];
                       if($section_id){
                        $prerequisite->save();
                       }
                       
                    }
                }

                if(!empty($section['options'])){
                    foreach ($section['options'] as $question_option) {
                        $option = new Option;
                        $option->form_id = $section['form_id'];
                        $option->section_id = $formSection->id;
                        $option->text = $question_option['text'];
                        $option->save();
                    }
                }

                if(!empty($request->question_ids)){
                    foreach($request->question_ids as $key=>$section_id){
                       $image = new Image;
                       $image->form_id = $section['form_id'];
                       $image->section_id = $formSection->id;
                       $image->path = Storage::disk('public')->put('images',$request->upload_files[$key]);
                       $image->width = $request->widths[$key] ==='null' ? null : $request->widths[$key];
                       $image->height = $request->heights[$key] ==='null' ? null : $request->heights[$key];
                       if($section['id'] == $section_id){
                            $image->save();
                       }
                       
                    }
                }
            }
        }

        $status_id = Status::where('name',$request->status === 'true' ? 'Publish' : 'Draft')->first()['id'];
        $form = Form::where('id',$collection[0]['form_id'])->first();
        if($request->status === 'true'){
            $form->published_by = Auth::user()->id;
        }
        $form->status_id = $status_id;
        if($form->save()){
            return ['redirect'=>route('survey-form')];
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FormSection  $formSection
     * @return \Illuminate\Http\Response
     */
    public function show(FormSection $formSection)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\FormSection  $formSection
     * @return \Illuminate\Http\Response
     */
    public function edit(FormSection $formSection)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateFormSectionRequest  $request
     * @param  \App\Models\FormSection  $formSection
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $collection = json_decode($request->collection, true);
        FormSection::where('form_id',$collection[0]['form_id'])->delete();
        Option::where('form_id',$collection[0]['form_id'])->delete();
        Image::where('form_id',$collection[0]['form_id'])->delete();

        foreach ($collection as $key=>$section) {
            $formSection = FormSection::where([
                ['form_id',$section['form_id']],
                ['id',$section['section_id']]
            ])->withTrashed()->first();

            
            if(empty($formSection)){
                $formSection = new FormSection;
            }
            
            $formSection->form_id = $section['form_id'];
            $formSection->is_question = $section['is_question'];
            $formSection->text = $section['text'];
            $formSection->html = $section['html'];
            $formSection->option_type_id = empty($section['option_type']) ? null : $section['option_type']['id'];
            $formSection->is_required = $section['is_required'];
            $formSection->is_multiple_select = $section['is_multiple_select'];
            $formSection->with_other_option = $section['with_other_option'];
            $formSection->option_vertical_align = $section['option_position'];
            $formSection->image_position = $section['image_position'];
            $formSection->prerequisite = empty($section['prerequisites']) ? false : true;
            $formSection->deleted_at = null;
            if($formSection->save()){
                
                if($formSection->prerequisite){
                    foreach ($section['prerequisites'] as $question_prerequisite) {
                        $formSection_id = FormSection::where([
                            ['form_id',$section['form_id']],
                            ['text',$section['section_id']]
                        ])->withTrashed()->first();

                       $prerequisite = new Prerequisite;
                       $prerequisite->prerequisite_form_id = $question_prerequisite['prerequisite_form'];
                       $prerequisite->prerequisite_section_id = $question_prerequisite['prerequisite_question'];
                       $prerequisite->form_id = $section['form_id'];
                       $prerequisite->section_id = $formSection->id;
                       $prerequisite->answer = $question_prerequisite['prerequisite_option'];
                       $prerequisite->save();
                       $formsection->prerequisite = true;
                        $formsection->save();
                    }
                }

                if(!empty($section['options'])){
                    foreach ($section['options'] as $question_option) {
                        $option = Option::where([
                            ['form_id',$section['form_id']],
                            ['id',$question_option['option_id']]
                        ])->withTrashed()->first();

                        if(empty($option)){
                            $option = new Option;
                        }
                        $option->form_id = $section['form_id'];
                        $option->section_id = $formSection->id;
                        $option->text = $question_option['text'];
                        $option->deleted_at = null;
                        $option->save();
                    }
                }

                if(!empty($section['images'])){
                    if($request->question_ids){
                        foreach ($request->question_ids as $key => $section_image) {
                            $image = new Image;
                            $image->form_id = $section['form_id'];
                            $image->section_id = $formSection->id;
                            $image->path = Storage::disk('public')->put('images',$request->upload_files[$key]);
                            $image->width = $request->widths[$key] ==='null' ? null : $request->widths[$key];
                            $image->height = $request->heights[$key] ==='null' ? null : $request->heights[$key];
                            $image->deleted_at = null;
                            if($section['id'] == $section_image){
                                $image->save();
                           }
                        }
                    }
                    foreach ($section['images'] as $key => $section_image) {
                        if($section_image['image_id']){
                            $image = Image::where([
                                ['form_id',$section['form_id']],
                                ['id',$section_image['image_id']]
                            ])->withTrashed()->first();

                            if(!empty($image)){
                                $image->deleted_at = null;
                                $image->save();
                            }
                        }
                        
                    }
                }
            }
        }

        $status_id = Status::where('name',$request->status === 'true' ? 'Publish' : 'Draft')->first()['id'];
        $form = Form::where('id',$collection[0]['form_id'])->first();
        $form->status_id = $status_id;
        if($form->save()){
            return ['redirect'=>route('survey-form')];
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FormSection  $formSection
     * @return \Illuminate\Http\Response
     */
    public function destroy(FormSection $formSection)
    {
        //
    }

    public function fetchFormquestion($formid){
        return FormSection::where([
            ['is_question',true],
            ['form_id',$formid]
        ])->get();
    }
}

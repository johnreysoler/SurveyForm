@extends('layouts.app',[
    'page_title' => $form['title']
]) 
@section('form')
<div class="container" >
    <div class="container-fluid my-5" style="min-height:90vh">
        <survey-form-view :form="{{$form}}"></survey-form-view>
    </div>
</div>

@endsection
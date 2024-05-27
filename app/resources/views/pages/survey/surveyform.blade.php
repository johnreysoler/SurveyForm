@extends('layouts.app',[
    'page_title' => $userform['title']
])

@section('content')
<div class="container" >
    <div class="container-fluid my-5" style="min-height:90vh">
        <survey-form :userform="{{$userform}}" :user_id="{{$user_id}}"></survey-form>
    </div>
</div>

@endsection
@extends('layouts.animal')

@section('page-title')
<title>AFH Create Litter</title>
@stop

@section('page-style')
{!! Html::style('css/typeahead.css') !!}
@stop

@section('content')

{!! Form::open(array('route' => 'storeLitter', 'class' => 'form-horizontal')) !!}
<div class="form-group">
    <div class="col-md-4">
        {!! Form::label('litter-name', 'Litter Name:') !!}
        {!! Form::text('litter-name', null, array('placeholder' => 'Litter Name', 'class' => 'form-control')) !!}
    </div>
    <div class="col-md-2">
        {!! Form::label('date_of_birth', 'Date of Birth:') !!}
        {!! Form::text('date_of_birth', null, array(
                        'type' => 'text', 
                        'class' => 'form-control date-input',
                        'placeholder' => 'Date of Birth')) !!}        
    </div>

    <div class="col-md-2">
        {!! Form::label('intake_date', 'Intake Date:') !!}
        {!! Form::text('intake_date', null, array(
                        'type' => 'text',
                        'id' => 'doi',
                        'class' => 'form-control date-input',
                        'placeholder' => 'Intake Date')) !!}        
    </div>
</div>
<div class="form-group">
    <div class="col-md-4">
        {!! Form::select('pri_breed_id', $breeds, $pri_breed_id, array('class' => 'form-control')) !!}
    </div>
    <div class="col-md-4">
        {!! Form::select('sec_breed_id', $breeds, $sec_breed_id, array('class' => 'form-control')) !!}
    </div>
    <div class="col-md-1">
        <div class='checkbox'>
            <label>
                {!! Form::checkbox('mixed_breed', 0, $mixed_breed, array('class' => 'checkbox')) !!} <strong>Mix</strong>
            </label>
        </div>
    </div>
</div>
<div class="form-group">
    <div class="col-md-4">
        {!! Form::text('foster', null, array('placeholder' => 'Foster Name', 'class' => 'form-control typeahead', 'id' => 'foster')) !!}
    </div>
    <div class="col-md-2">
        {!! Form::select('status_id', $status, $default_status, array('class' => 'form-control')) !!}
    </div>
    <div class='col-md-4 btn btn-default col-centered' id='my-dropzone'>Drop files (or click) here to upload a picture</div>
</div>
<div class="form-group">
    <div class="col-md-6">
        {!! Form::textarea('description', null, array(
                    'id' => 'afh-editor', 
                    'style' => 'width: 600px', 
                    'class' => 'form-control textbox',
                    'placeholder' => 'Enter animal description...')) !!}
    </div>
    <div class='col-lg-4' style='text-align: center;'>
        <br />
        {!! Form::hidden('picture', null, array('id' => 'picture')) !!}
        @if(isset($animal))
        <img id='the-picture' src="/{{ $upload_path !!}/{{ $animal->picture !!}" class='col-centered' width='200' />
        @else
        <img id='the-picture' src='/images/afh_logo.svg' width='200' class='col-centered' alt='AFH Logo'/>
        @endif
    </div>
</div>
@for ( $i = 1; $i <= $litter_size; $i++ )
<div class="form-group">
    <div class="col-md-3">
        {!! Form::text('name', null, array('placeholder' => 'Puppy Name', 'class' => 'form-control', 'id' => 'name-'.$i, 'name' => 'name-'.$i)) !!}
    </div>
    <div class="col-md-2">
        {!! Form::select('gender', $gender, null, array('class' => 'form-control', 'id' => 'gender-'.$i, 'name' => 'gender-'.$i)) !!}
    </div>
    <div class="col-md-2">
        <div class='checkbox'>
            <label>
                {!! Form::checkbox('altered', False, null, array('class' => 'checkbox', 'id' => 'altered-'.$i, 'name' => 'altered-'.$i)) !!} <strong>Altered</strong>
            </label>
        </div>
    </div>
</div>
@endfor
<div class="form-group">
    <div class="col-md-1">
        {!! Form::submit('Save', array('class' => 'btn btn-info pull-right')) !!}
    </div>
    <div class="col-md-2">
        {!! Form::reset('Reset', array('class' => 'btn btn-warning center-block')) !!}
    </div>
    <div class="col-md-1">
        {!! link_to(URL::previous(), 'Cancel', ['class' => 'btn btn-danger']) !!}
    </div>
</div>
{!! Form::close() !!}

@if ($errors->any())
<ul>
    {!! implode('', $errors->all('<li class="error">:message</div>')) !!}
</ul>
@endif

@stop

@section('page-script')
{!! Html::script('packages/dropzone/js/dropzone.js') !!}
{!! Html::script('js/typeahead.bundle.min.js') !!}
{!! Html::script('js/animal-edit.js') !!}
@stop
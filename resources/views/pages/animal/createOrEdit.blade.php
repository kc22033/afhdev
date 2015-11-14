@extends('layouts.animal')

@section('page-title')
<title>AFH Animal Create or Edit</title>
@stop

@section('page-style')
{!! Html::style('css/typeahead.css') !!}
<style>
    /*remove spacing between middle columns*/ 
    .row [class*='col-']:not(:first-child):not(:last-child) {
        padding-right:25px;
        padding-left:25px;
    }
    /*remove right padding from first column*/ 
    .row [class*='col-']:first-child {
        padding-right:25px;
    }
    /*remove left padding from first column*/ 
    .row [class*='col-']:last-child {
        padding-left:25px;
    } 
    .row {
        padding-left: 0px;
        padding-right: 0px;
    }
</style>
@stop

@section('content')

@if(isset($animal))
{!! Form::model($animal,array('method' => 'patch', 'class' => 'form-horizontal', 'role' => 'form', 'route' => array('animal.update', $animal->id))) !!}
@else
{!! Form::open(array('route' => 'animal.store', 'class' => 'form-horizontal')) !!}
@endif
<div class="row">
    <div class='col-md-10'>
        <div class="col-md-4">
            <div class='form-group'>
                {!! $errors->first('name', '<span class="error">Required</span>') !!}
                {!! Form::label('name', 'Animal Name:') !!}
                {!! Form::text('name', null, array('placeholder' => 'Animal Name', 'class' => 'form-control')) !!}
            </div>
        </div>
        <div class="col-md-2">
            <div class='form-group'>
                {!! $errors->first('date_of_birth', '<span class="error">Required</span>') !!}
                {!! Form::label('date_of_birth', 'Date of Birth:') !!}
                {!! Form::text('date_of_birth', null, array(
                        'type' => 'text', 
                        'class' => 'form-control date-input',
                        'placeholder' => 'Date of Birth')) !!}        
            </div>
        </div>
        <div class="col-md-2">
            <div class='form-group'>
                {!! Form::label('intake_date', 'Intake Date:') !!}
                {!! Form::text('intake_date', null, array(
                        'type' => 'text',
                        'id' => 'doi',
                        'class' => 'form-control date-input',
                        'placeholder' => 'Intake Date')) !!}        
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                {!! $errors->first('gender', '<span class="error">Required</span>') !!}
                {!! Form::label('gender', 'Gender:') !!}
                {!! Form::select('gender', $gender, null, array('class' => 'form-control')) !!}
            </div>
        </div>
        <div class="col-md-2">
            <div class='checkbox'>
                <label><br />
                    {!! Form::checkbox('altered', null, array('class' => 'checkbox')) !!} <strong>Altered</strong>
                </label>
            </div>
        </div>
    </div>
    <div class='col-md-1'>
        <div class='form-group'>
            {!! Form::label('litter_size', '# Puppies:') !!}
            {!! Form::input('number', 'litter_size', null, array(
                        'id' => 'litter_size',
                        'class' => 'form-control',
                        'placeholder' => 'Litter Size')) !!}        
        </div>
    </div>
    <div class="col-md-1">
        <div class='form-group'>
            {!! Form::label('tag_num', 'AFH Tag:') !!}
            {!! Form::text('tag_num', null, array('placeholder' => 'AFH Tag', 'class' => 'form-control')) !!}
        </div>
    </div>
</div>
<div class="row">
    <div class='col-md-10'>
        <div class="col-md-5">
            <div class="form-group">
                {!! $errors->first('pri_breed_id', '<span class="error">Required</span>') !!}
                {!! Form::label('pri_breed_id', 'Primary Breed:') !!}<br />
                {!! Form::select('pri_breed_id', $breeds, null, array('class' => 'form-control')) !!}
            </div>
        </div>
        <div class="col-md-5">
            <div class="form-group">
                {!! Form::label('sec_breed_id', 'Secondary Breed:') !!}<br />
                {!! Form::select('sec_breed_id', $breeds, null, array('class' => 'form-control')) !!}
            </div>
        </div>
        <div class="col-md-2">
            <div class='checkbox'>
                <label><br>
                    {!! Form::checkbox('mixed_breed', null, array('checked' => 'checked', 'class' => 'checkbox')) !!} <strong>Mix</strong>
                </label>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            {!! Form::label('color', 'Color:') !!}<br />
            {!! Form::text('color', null, array('placeholder' => 'Animal Coat Color', 'class' => 'form-control', 'id' => 'color')) !!}
        </div>
    </div>
</div>
<div class='row'>
    <div class='col-md-10'>
        <div class="col-md-5">
            <div class="form-group">
                {!! Form::label('medical_at', 'Medical Records At:') !!}<br />
                {!! Form::text('medical_at', null, array('placeholder' => 'Medical Records At', 'class' => 'form-control', 'id' => 'medical_at')) !!}
            </div>
        </div>
        <div class="col-md-5">
            <div class="form-group">
                {!! Form::label('rabies_given_by', 'Rabies Vaccination By:') !!}<br />
                {!! Form::text('rabies_given_by', null, array('placeholder' => 'Rabies Vaccination Administered By', 'class' => 'form-control', 'id' => 'rabies_given_by')) !!}
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                {!! Form::label('rabies_tag_num', 'Rabies Tag #:') !!}<br />
                {!! Form::text('rabies_tag_num', null, array('placeholder' => 'Rabies Tag #', 'class' => 'form-control', 'id' => 'rabies_tag_num')) !!}
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            {!! Form::label('origin', 'Origin:') !!}<br />
            {!! Form::text('origin', null, array('placeholder' => 'Origin', 'class' => 'form-control', 'id' => 'origin')) !!}
        </div>
    </div>
</div>
<div class='row'>
    <div class="col-md-10">
        <div class='col-md-4'>
            <div class="form-group">
                {!! Form::label('foster', 'Foster:') !!}<br />
                {!! Form::text('foster', null, array('placeholder' => 'Foster Name', 'class' => 'form-control typeahead', 'id' => 'foster')) !!}
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                {!! $errors->first('status_id', '<span class="error">Required</span>') !!}
                {!! Form::label('status_id', 'Status:') !!}
                {!! Form::select('status_id', $status, null, array('class' => 'form-control')) !!}
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                {!! Form::label('status_date', 'Status Date:') !!}
                {!! Form::text('status_date', null, array(
                        'type' => 'text', 
                        'class' => 'form-control date-input',
                        'placeholder' => 'Status Date')) !!}        
            </div>
        </div>
        <div class="col-md-2">
            <div class='form-group'>
                {!! Form::label('next_vax_date', 'Next Vax Due:') !!}
                {!! Form::text('next_vax_date', null, array(
                        'type' => 'text', 
                        'class' => 'form-control date-input',
                        'placeholder' => 'Next Vax Due')) !!}        
            </div>
        </div>
        <div class="col-md-2">
            <div class='form-group'>
                {!! Form::label('s_n_date', 'Alter Date:') !!}
                {!! Form::text('s_n_date', null, array(
                        'type' => 'text',
                        'id' => 's_n_date',
                        'class' => 'form-control date-input',
                        'placeholder' => 'Alter Date')) !!}        
            </div>
        </div>
    </div>
    <div class='col-md-2'>
        <div class="form-group">
            {!! Form::label('origin_id', 'Origin ID:') !!}<br />
            {!! Form::text('origin_id', null, array('placeholder' => 'Origin ID', 'class' => 'form-control typeahead', 'id' => 'origin_id')) !!}
        </div>
    </div>
</div>
<div class='row'>
    <div class="col-md-10">
        <div class="col-md-12">
            <div class="form-group">
                {!! Form::textarea('description', null, array(
                    'id' => 'afh-editor', 
                    'class' => 'form-control textbox',
                    'placeholder' => 'Enter animal description...')) !!}
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <br />
        {!! link_to(URL::previous(), 'Cancel', ['class' => 'btn btn-danger btn-block']) !!}
        <br />
        {!! Form::reset('Reset', array('class' => 'btn btn-warning btn-block')) !!}
        <br />
        {!! Form::submit('Save', array('class' => 'btn btn-success btn-block')) !!}
    </div> 
</div>
{!! Form::close() !!}
<div class='row'>
	<div class='dropzone col-md-10' id='my-dropzone' style='height:350px;border-style:solid;border-color:rgb(204,204,204);border-radius:4px;border-width:1px;'></div>
</div>
@stop

@section('page-script')
{!! Html::script('packages/dropzone/js/dropzone.js') !!}
{!! Html::script('js/typeahead.bundle.min.js') !!}
{!! Html::script('js/animal-edit.js') !!}
@stop
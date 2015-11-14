@extends('layouts.breed')

@section('content')

<h1>Breed Details</h1>
{!! Form::model($breed, array('method' => 'PATCH', 'route' => array('breed.update', $breed->id))) !!}
    <ul>
        <li>
            {!! Form::label('name', 'Breed:') !!}
            {!! Form::text('name', $breed->name, array('disabled' => 'disabled')) !!}
        </li>
        <li>
            {!! Form::label('species', 'Species:') !!}
            {!! Form::text('species', Input::get('species'), array('disabled' => 'disabled')) !!}
        </li>
        <li>
            {!! Form::label('description_url', 'URL:') !!}
            {!! Form::text('description_url', null, array('style' => 'width:50ch')) !!}
        </li>
        
        <li>
            {!! Form::submit('Update', array('class' => 'btn btn-info')) !!}
            {!! link_to_route('breed.index', 'Cancel', $breed->id, array('class' => 'btn')) !!}
        </li>
    </ul>
{!! Form::close() !!}

@if ($errors->any())
    <ul>
        {!! implode('', $errors->all('<li class="error">:message</li>')) !!}
    </ul>
@endif

@stop


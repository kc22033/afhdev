@extends('layouts.breed')

@section('content')

<h1>Create Breed</h1>

{!! Form::open(array('route' => 'breed.store')) !!}
    <ul>

        <li>
            {!! Form::label('name', 'Name:') !!}
            {!! Form::text('name') !!}
        </li>

        <li>
            {!! Form::label('species', 'Species:') !!}
            {!! Form::text('species') !!}
        </li>

        <li>
            {!! Form::label('description_url', 'URL:') !!}
            {!! Form::text('description_url') !!}
        </li>


        <li>
            {!! Form::submit('Submit', array('class' => 'btn')) !!}
        </li>
    </ul>
{!! Form::close() !!}

@if ($errors->any())
    <ul>
        {!! implode('', $errors->all('<li class="error">:message</li>')) !!}
    </ul>
@endif

@stop
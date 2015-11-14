@extends('layouts.breed')

@section('content')
@if(isset($breed))
    <h3>
        {!! Html::linkRoute('breed.index', 'Breeds List', array('class' => 'btn btn-primary')) !!}
    </h3>
@endif
<h1>Breed Details</h1>
    <ul>
        <li>
            Breed: {{ $breed->name }}
        </li>
        <li>
            Species: {{ $breed->species }}
        </li>
        <li>
            Description: {!! Html::link($breed->description_url) !!}
        </li>
        
    </ul>
    {!! Html::linkRoute('breed.edit','Edit',array('id'=>$breed->id), array('class' => 'btn btn-primary')) !!}

@if ($errors->any())
    <ul>
        {!! implode('', $errors->all('<li class="error">:message</li>')) !!}
    </ul>
@endif

@stop


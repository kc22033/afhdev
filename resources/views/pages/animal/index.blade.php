@extends('layouts.afhmaster')

@section('header-menu')
<li>{!! link_to_route('animal.create', 'Add Animal') !!}<li>
<li class="dropdown">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">{{ $current_status }} <b class="caret"></b></a>
    <ul class="dropdown-menu">
        <li>{!! Html::linkAction('AnimalController@setDefaultStatus', 'Available', array('Available')) !!}</li>
        <li>{!! Html::linkAction('AnimalController@setDefaultStatus', 'Adopted', array('Adopted')) !!}</li>
        <li>{!! Html::linkAction('AnimalController@setDefaultStatus', 'Intake Pending', array('Intake Pending')) !!}</li>
        <li>{!! Html::linkAction('AnimalController@setDefaultStatus', 'Adoption Pending', array('Adoption Pending')) !!}</li>
        <li>{!! Html::linkAction('AnimalController@setDefaultStatus', 'Transferred', array('Transferred')) !!}</li>
        <li>{!! Html::linkAction('AnimalController@setDefaultStatus', 'Deceased', array('Deceased')) !!}</li>
    </ul>
</li>
@stop

@section('page-title')
<title>AFH Animal Management</title>
@stop

@section('search')
<div style='margin:10px 0px 0px 45px'>
    {!! Form::open(array('url' => 'animals/search')) !!} 
    {!! Form::text('search_string', null, array('placeholder'=>'Search&hellip;')) !!} 
    {!! Form::submit('Search', array('class' => 'btn btn-primary btn-sm')) !!} {!! Form::close() !!} 
</div>
@stop

@section('content')

@if ($animals->count())
{!! Form::open(array('url' => 'animals/update_status')) !!}
<table class="table table-striped table-bordered table-hover table-condensed">
    <thead>
        <tr>
            <th>&nbsp;</th>
            <th>Name</th>
            <th>Foster</th>
            <th>Gender</th>
            <th>DOB</th>
            <th>Breed</th>
            <th>Intake Date</th>
            <th>ID</th>
        </tr>
    </thead>

    <tbody>
        @foreach ($animals as $animal)
        <tr>
            <td>{!! Form::checkbox('ids_to_update[]', $animal->id) !!}
            <td>{!! link_to_route('animal.edit', $animal->name, array($animal->id))!!}</td>
            <td>{{ $animal->foster }}</td>
            <td>{{ $animal->gender == 'Female' ? 'F' : 'M' }}
                {{ $animal->altered ? $animal->gender == 'Female' ? '/ S' : '/ N' : '' }}
            <td>{{ $animal->date_of_birth }}</td>
            <td>{{ $animal->priBreed->name }}
                @if ($animal->sec_breed_id == Config::get('rescue.select_breed_id') )
                @else
                / {{ $animal->secBreed->name }}
                @endif
                {{ $animal->mixed_breed ? ' Mix' : '' }}</td>
            <td>{{ $animal->intake_date }}</td>
            <td>{{ $animal->id }}</td>
        </tr>
        @endforeach

    </tbody>
    <tfoot>
        <tr>
            <td>&nbsp;</td>
            <td colspan='6'>New Status: 
                {!! Form::select('status_id', $status, null) !!} 
                {!! Form::submit('Update Status', array('class' => 'btn btn-primary btn-xs')) !!}
                {!! Form::close() !!}
            </td>
            <td colspan='4'>
                {!! Form::open(array('url' => 'animals/set_page_size')) !!} 
                Rows to Display: 
                {!! Form::text('page_size', null, array('placeholder'=>'Page Size&hellip;')) !!} 
                {!! Form::submit('Set', array('class' => 'btn btn-primary btn-sm')) !!}
                {!! Form::close() !!} 
            </td>
        </tr>
    </tfoot>
</table>
<div class="pagination">
    {!! $animals->render() !!}
</div>
@else
There are no animals
@endif

@stop

@section('page-script')
{!! Html::script('packages/datepicker/js/bootstrap-datepicker.js') !!}
{!! Html::script('packages/bootstrap3-wysiwyg/dist/bootstrap3-wysihtml5.all.min.js') !!}
@stop
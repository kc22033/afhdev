@extends('layouts.breed')

@section('content')

<h1>All Breeds</h1>

<p>{!! link_to_route('breed.create', 'Add new breed') !!}</p>
@if ($breeds->count())
<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>Breed Name</th>
            <th>Species</th>
            <th>Description</th>
            <th>Actions</th>
        </tr>
    </thead>

    <tbody>
        @foreach ($breeds as $breed)
        <tr>
            <td>{{ $breed->name }}</td>
            <td>{{ $breed->species }}</td>
            <td>{!! Html::link($breed->description_url, 'Breed Description') !!}</td>
            <td>{!! link_to_route('breed.edit', 'Edit', array($breed->id), array('class' => 'btn btn-info')) !!}</td>
            <td>
                {!! Form::open(array('method' => 'DELETE', 'route' => array('breed.destroy', $breed->id))) !!}                       
                {!! Form::submit('Delete', array('class' => 'btn btn-danger')) !!}
                {!! Form::close() !!}
            </td>
        </tr>
        @endforeach

    </tbody>

</table>
<div class="pagination">
    {!! $breeds->render() !!}
</div>
@else
There are no breeds
@endif

@stop
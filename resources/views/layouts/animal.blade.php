<!doctype html>
<html lang='en'>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta charset="utf-8">
        {!! Html::style('//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css') !!}
        {!! Html::style('//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css') !!}
        {!! Html::style('packages/bootstrap3-wysiwyg/dist/bootstrap3-wysihtml5.css') !!}
        {!! Html::style('packages/datepicker/css/datepicker3.css') !!}
        {!! Html::style('packages/dropzone/css/dropzone.css') !!}
        <style>
            table form { margin-bottom: 0; }
            form ul { margin-left: 0; list-style: none; }
            .error { color: red; font-style: italic; }
            body { padding-top: 10px; }
        </style>
        @yield('page-style')
    </head>

    <body>
        <div class="container">
            @if (Session::has('message'))
            <div class="flash alert">
                <p>{{ Session::get('message') !!}</p>
            </div>
            @endif
            @yield('content')
        </div>
        {!! Html::script('//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js') !!}
        {!! Html::script('//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js') !!}
        {!! Html::script('packages/datepicker/js/bootstrap-datepicker.js') !!}
        {!! Html::script('packages/bootstrap3-wysiwyg/dist/bootstrap3-wysihtml5.all.min.js') !!}
        @yield('page-script')
    </body>
</html>

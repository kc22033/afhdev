<!doctype html>
<html lang='en'>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta charset="utf-8">
        {!! Html::style('//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css') !!}
        {!! Html::style('//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css') !!}
        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
        <link rel="icon" href="../../favicon.ico">

        @yield('page-title')
        @yield('page-styles')
    </head>

    <body>
        <div id="header" class="navbar navbar-default navbar-fixed-top">
            <div class="navbar-header">
                <button class="navbar-toggle collapsed" type="button" data-toggle="collapse" data-target=".navbar-collapse">
                    <i class="icon-reorder"></i>
                </button>
                <a class="navbar-brand" href="/">
                    <img src={{ Config::get('rescue.default_image') }} height='35' class='col-centered' alt='AFH Logo'/>
                </a>
            </div>
            <nav class="collapse navbar-collapse">
                <ul class="nav navbar-nav">
                    <li>{!! Html::linkAction('HomeController@index', 'Dashboard') !!}</li>
                    @yield('header-menu')
                    <li><a href="#">About</a></li>
                </ul>
                <ul class="nav navbar-nav">
                    <li>
                        @yield('search')
                    </li>
                </ul>
                <ul class="nav navbar-nav pull-right">
                    <li class="dropdown">
                        <a href="#" id="nbAcctDD" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-user"></i>
                            @if (null !== Auth::user()) 
                            {{ Auth::user()->username }}
                            @else 
                            Guest
                            @endif <i class="icon-sort-down"></i></a>
                        <ul class="dropdown-menu pull-right">
                            <li><a href="#">Log Out</a></li>
                        </ul>
                    </li>
                </ul>
            </nav>
        </div>
        <div id="wrapper">
            <div id="main-wrapper" class="col-md-12">
                <div id='main'>
                    <div class='page-header'>
                        @yield('page-header')
                    </div>
                    @yield('content')
                </div>
            </div>
        </div>
        <div class="col-md-12 footer">
            Footer Goes Here
        </div>
        {!! Html::script('//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js') !!}
        {!! Html::script('//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js') !!}
        @yield('page-script')
    </body>
</html>

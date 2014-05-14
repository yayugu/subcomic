<nav class="navbar navbar-default navbar-static-top" role="navigation">
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">Subcomic</a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav navbar-left">
                <li>
                    <a href="{{action('favorite')}}">Favorites</a>
                </li>
                <li>
                   <a href="{{action('history')}}">Histories</a>
                </li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">{{{Auth::user()->name}}} <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li><a href="{{action('userCreate')}}">Add new user</a></li>
                        <li class="divider"></li>
                        <li><a href="{{action('logout')}}">Logout</a></li>
                    </ul>
                </li>
            </ul>
            <form action="{{action('comicSearch')}}" method="get" class="navbar-form navbar-right" role="search">
                <div class="form-group">
                    <input type="text" name="q" class="form-control">
                </div>
                <button type="submit" class="btn btn-default">Search</button>
            </form>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>
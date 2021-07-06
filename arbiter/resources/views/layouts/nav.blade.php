<nav class="navbar navbar-expand-md navbar-light navbar-laravel">
    <div class="container">
        <router-link class="navbar-brand" to="/"><a>Golden Company</a></router-link>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Left Side Of Navbar -->
            <ul class="navbar-nav mr-auto">
                <li class="nav-item"><router-link class="nav-link" to="/members"><a>Members</a></router-link></li>
                <li class="nav-item"><router-link class="nav-link" to="/planets"><a>Planets</a></router-link></li>
                <li class="nav-item"><router-link class="nav-link" to="/galaxies"><a>Galaxies</a></router-link></li>
                <li class="nav-item"><router-link class="nav-link" to="/alliances"><a>Alliances</a></router-link></li>
            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ml-auto">
                <li class="nav-item dropdown">
                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                        {{ Auth::user()->name }} <span class="caret"></span>
                    </a>

                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                        <router-link class="dropdown-item" to="/account/settings"><a>Settings</a></router-link>
                        <a class="dropdown-item" href="{{ route('logout') }}"
                         onclick="event.preventDefault();
                                       document.getElementById('logout-form').submit();">
                          {{ __('Logout') }}
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                          @csrf
                        </form>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>

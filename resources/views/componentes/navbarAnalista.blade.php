<div class="nav navbar-nav navbar-right">
    <ul class="nav navbar-nav">
        @if(Auth::check())
            <li class="dropdown"> <a id="dropdown_perfil" name="dropdown_perfil" class="dropdown-toggle" role="button"
                   data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="cursor: pointer;">
                    <b>Olá,</b> {{Auth::user()->name}} <span class="caret"></span>
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdown_perfil">
                    
                    {{-- Verifica se é Bibliotecário ou Analista para mostrar o link de perfil --}}
                    @if(Auth::user()->tipo == 'bibliotecario' || Auth::user()->tipo == 'analistabibliotecario')
                        <a class="dropdown-item" href="{{ route('perfil-bibliotecario') }}">
                           Editar Perfil 
                        </a>
                    @endif

                    <div class="dropdown-divider"></div>

                    <a class="dropdown-item" href="{{ route('logout') }}"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        Sair 
                    </a>
                    
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </li>
        @endif
    </ul>
</div>
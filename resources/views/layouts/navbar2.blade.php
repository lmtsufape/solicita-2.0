<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js" integrity="sha384-w1Q4orYjBQndcko6MimVbzY0tgp4pWB4lZ7lr30WKz0vr/aWKhXdBNmNb5D92v7s"
crossorigin="anonymous"></script>
<!-- Barra de Logos -->
<div id="barra-logos">
    <!-- se o usuário estiver logado -->
    @if(Auth::check())
        <nav class="navbar navbar-expand-lg bg-white"
             style="border-color: #d3e0e9; box-shadow: 0 0 6px rgba(0,0,0,0.5);" role="navigation">
            <a class="navbar-brand" href="{{ route('login') }}" style="color: white; font-weight: bold;">
                <img src="{{asset('images/logo.png')}}" width="100" class="d-inline-block align-top" alt="">

            </a>
            <div class="collapse navbar-collapse">
            </div>
            <!-- Se o usuário for um aluno -->
        @if(Auth::user()->tipo == 'aluno')
            <!-- carrega o componente contendo Navbar do aluno -->
            @component('componentes.navbarAluno')
            @endcomponent
        @endif

        <!-- Se o usuário for um servidor -->
        @if(Auth::user()->tipo == 'servidor')
            <!-- Carrega component contendo navbar2.blade.php do servidor -->
            @component('componentes.navbarServidor')
            @endcomponent
        @endif

        @if(Auth::user()->tipo == 'administrador')
            <!-- Carrega component contendo navbar2.blade.php do administrador -->
            @component('componentes.navbarAdministrador')
            @endcomponent
        @endif

        <!-- Se o usuário for um bibliotecario -->
        @if(Auth::user()->tipo == 'bibliotecario')
            <!-- carrega o componente contendo Navbar do bibliotecario -->
                @component('componentes.navbarBibliotecario')
                @endcomponent
            @endif

        @if(Auth::user()->tipo == 'analistabibliotecario')
                @component('componentes.navbarAnalista')
                @endcomponent
            @endif
        </nav>
    @endif

</div>

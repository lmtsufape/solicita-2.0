@extends('layouts.app')

@section('conteudo')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-4 corpoRequisicao shadow">
                <div class="row mx-1 p-0" style="border-bottom: var(--textcolor) 2px solid">
                    <div class="col-md-12 tituoRequisicao mt-3 p-0">
                        Alterar Informações
                    </div>
                </div>
                <div class="col-md-12 p-0">
                        <form action="{{  route('editar-info')  }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <!-- Form Senha Antiga -->
                                <div class="form-group row justify-content-center mt-3">
                                    <div class="col-md-12">
                                        <label for="password" class="row px-3 textoFicha">Nome Completo:
                                            <input id="name" type="name"
                                                   class="form-control @error('password-old') is-invalid @enderror"
                                                   name="name" required autocomplete="current-password"
                                                   placeholder="Digite seu nome completo." value="{{ old('name', $user->name) }}">
                                        </label>
                                        @error('password-old')
                                        <span class="invalid-feedback" role="alert"
                                              style="overflow: visible; display:block;">
                                  <strong>{{ $message }}</strong>
                                  </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row justify-content-center mt-3">

                                    <div class="col-md-12">
                                        <label for="email" class="row px-3 textoFicha">Email:
                                            <input id="email" type="email"
                                                   class="form-control @error('email') is-invalid @enderror"
                                                   name="email" value="{{ old('email', $user->email) }}" required autocomplete="email"
                                                   autofocus placeholder="Digite seu E-Mail.">
                                        </label>
                                        @error('password-old')
                                        <span class="invalid-feedback" role="alert"
                                              style="overflow: visible; display:block;">
                                  <strong>{{ $message }}</strong>
                                  </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row justify-content-center mt-3">
                                    <div class="col-md-12">
                                        <label for="cpf" class="row px-3 textoFicha">CPF:
                                            <input id="cpf" type="text"
                                                   class="form-control @error('cpf') is-invalid @enderror"
                                                   name="cpf" required
                                                   placeholder="Digite seu CPF." value="{{ old('cpf', $aluno->cpf) }}">
                                        </label>
                                        @error('cpf')
                                            <span class="invalid-feedback" role="alert" style="overflow: visible; display:block;">
                                            <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Botões -->
                                <div class="form-group row mb-0">
                                    <div class="col-md-6 " style="">
                                        <a style="background-color: var(--padrao); border-radius: 0.5rem; color: white; font-size: 17px" class="btn" href="{{route('perfil-aluno')}}">Voltar</a>
                                    </div>

                                    <div class="col-md-6 text-right">
                                        <button  style="background-color: var(--confirmar); border-radius: 0.5rem; color: white; font-size: 17px" type="submit" class="btn">
                                            {{ __('Salvar') }}
                                        </button>
                                    </div>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function($){
            $('#cpf').mask('000.000.000-00');
        });
    </script>


@endsection

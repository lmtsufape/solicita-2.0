@extends('layouts.app')


@section('conteudo')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-7 corpoRequisicao shadow pb-3">
                <div class="row mx-1" style="border-bottom: var(--textcolor) 2px solid">
                    <div class="col-md-12 tituoRequisicao mt-3 p-0">
                        Cadastro de Bibliotecário
                    </div>
                </div>

                <div class="row justify-content-center">
                    <div class="col-md-12">
                        <form action="{{  route('criar-bibliotecario')  }}" method="POST">
                            @csrf
                            <div class="row justify-content-center py-2 mt-2">
                                <div class="form-group col-md-12">
                                    <label class="textoFicha" for="name">Nome</label>
                                    <input id="nomeServidor" type="name"
                                           class="form-control @error('name') is-invalid @enderror backgroundGray"
                                           name="name" value="{{ old('name') }}" required autocomplete="name"
                                           autofocus placeholder="Digite o Nome Completo">
                                    @error('name')
                                    <span class="invalid-feedback" role="alert" style="overflow: visible; display:block">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row justify-content-center py-2">
                                <div class="form-group col-md-12">
                                    <label class="textoFicha" id="email" for="email">E-mail</label>
                                    <input id="emailServidor" type="email"
                                           class="form-control @error('email') is-invalid @enderror backgroundGray"
                                           name="email" value="{{ old('email') }}" required autocomplete="email"
                                           autofocus placeholder="E-Mail">
                                    @error('email')
                                    <span class="invalid-feedback" role="alert" style="overflow: visible; display:block">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row justify-content-center py-2">
                                <div class="form-group col-md-12">
                                    <!-- Bibliotecas-->
                                    <label class="textoFicha" for="bibliotecas">Biblioteca</label>
                                    <select name="biblioteca" id="bibliotecas"
                                            class="form-control @error('email') is-invalid @enderror backgroundGray">
                                        <option value="" disable selected hidden>-- Selecionar biblioteca --</option>
                                        @foreach($bibliotecas as $biblioteca)
                                            <option value="{{$biblioteca->id}}">{{$biblioteca->nome}}</option>
                                        @endforeach
                                        @error('bibliotecas')
                                        <span class="invalid-feedback" role="alert" style="overflow: visible; display:block">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="textoFicha" for="tipo">Tipo de Usuário</label>
                                <select name="tipo" id="tipo" class="form-control" required>
                                    <option value="" disabled selected>-- Selecionar Tipo --</option>
                                    <option value="bibliotecario">Bibliotecário</option>
                                    <option value="analistabibliotecario">Analista Bibliotecário</option>
                                </select>
                            </div>

                            <div class="row form-group justify-content-between py-2">
                                <div class="col-md-6">
                                    <label class="textoFicha" for="name">Matrícula</label>
                                    <input id="matriculaServidor" type="number"
                                           class="form-control @error('matricula') is-invalid @enderror backgroundGray"
                                           name="matricula" value="{{ old('matricula') }}" required
                                           autocomplete="name" placeholder="Matricula">
                                    @error('matricula')
                                    <span class="invalid-feedback" role="alert" style="overflow: visible; display:block">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="textoFicha" for="name">CRB</label>
                                    <input id="crb" type="string"
                                           class="form-control @error('crb') is-invalid @enderror backgroundGray"
                                           name="crb" value="{{ old('crb') }}" required autocomplete="name"
                                           placeholder="Exemplo: CRB-4/1322">
                                    @error('crb')
                                    <span class="invalid-feedback" role="alert" style="overflow: visible; display:block">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row justify-content-between py-2">
                                <div class="col-md-6">
                                    <label class="textoFicha" for="password">Senha
                                    </label>
                                    <input id="password" type="password"
                                           class="form-control @error('password') is-invalid @enderror backgroundGray"
                                           name="password" required autocomplete="current-password"
                                           placeholder="Senha">
                                    @error('password')
                                    <span class="invalid-feedback" role="alert" style="overflow: visible; display:block;">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="textoFicha" for="password-confirm">
                                        Confirmar Senha
                                    </label>
                                    <input id="password-confirm" type="password"
                                           class="form-control @error('password-confirm') is-invalid @enderror backgroundGray"
                                           name="password_confirmation" required autocomplete="new-password"
                                           placeholder="Confirmar Senha">
                                    @error('password')
                                    <span class="invalid-feedback" role="alert" style="overflow: visible; display:block;">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Botões -->
                            <div class="row justify-content-between my-2">
                                <div class="col-md-6">
                                    <a style="background-color: var(--padrao); border-radius: 0.5rem; color: white; font-size: 17px" class="btn" href="{{  route('home')}}">{{ __('Voltar') }}</a>
                                </div>
                                <div class="col-md-6 text-right">
                                    <button style="background-color: var(--confirmar); border-radius: 0.5rem; color: white; font-size: 17px" type="submit" class="btn"
                                            onclick="confirmacaoCadastro();">
                                        {{ __('Salvar') }}
                                    </button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

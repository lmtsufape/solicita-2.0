@extends('layouts.app')

@section('conteudo')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 corpoRequisicao shadow">
                <div class="row mx-1 p-0" style="border-bottom: var(--textcolor) 2px solid">
                    <div class="col-md-12 tituoRequisicao mt-3 p-0 text-center">
                        Verificação de E-mail
                    </div>
                </div>
                <div class="py-4 text-center">
                    <p>Enviamos um link de verificação para o seu novo e-mail.</p>
                    <p>Por favor verifique sua caixa de entrada para confirmar a alteração.</p>
                    <p>Enquanto isso, você pode continuar usando o sistema normalmente.</p>

                    <a href="{{ route('home') }}"
                       style="background-color: var(--padrao); border-radius: 0.5rem; color: white; font-size: 17px"
                       class="btn mt-3">
                        Voltar para Home
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        @page {
            margin: 0;
        }
        body {
            font-family: "Times New Roman", Times, serif;
            font-size: 15px;
            line-height: 20px;
        }
        .header {
            margin-top: 1rem;
            text-align: center;
            font-weight: bold;
        }
        .header p {
            text-align: center;
            font-size: 15px;
            margin: 0;
            padding: 0;
        }
        .header .doc {
            margin-top: 100px;
            text-decoration: underline;
            font-size: 18px;
        }
        .logo-ufape {
            height: 1.27in;
            margin-bottom: 5px;
        }
        .logo-sib {
            float: right;
            width: 2.35cm;
            height: 2.13cm;
        }
        .corpo {
            margin-left: 2.29cm;
            margin-top: 4em;
            margin-right: 2.03cm;
            margin-bottom: 0.49cm;
            text-align: justify;
            text-indent: 50px;
        }
        .assinatura {
            margin-top: 7cm;
            display: flex;
            text-align: center;
            font-weight: bold;
        }
        #linha {
            margin: 0 15%;
            justify-content: center;
            display: flex;
            border-bottom: 1px black solid;
            margin-bottom: 3px;
        }
        .bg {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            opacity: 0.2;
            width: 11.95cm;
        }
        #cargo {
            margin: 0;
            padding: 0;
        }
        .corpo p {
            display: inline;
        }

        #data{
            text-align: right;
            width: 80%;
            font-size: 20px;
            margin-top: 2em;
        }
    </style>
</head>

<body>
    <img src="{{public_path('images/sib.png')}}" alt="" class="bg">
    <div class="header">
        <img class="logo-ufape" src="{{public_path('images/logo_ufape_principal.png')}}" alt="">
        <p>UNIVERSIDADE FEDERAL DO AGRESTE DE PERNAMBUCO</p>
        <p>REITORIA DA UFAPE</p>
        <p>SISTEMA INTEGRADO DE BIBLIOTECAS</p>
        <p class="doc">DECLARAÇÃO DE DEPÓSITO DO TRABALHO <br> ACADÊMICO E NADA CONSTA</p>
    </div>
    <div class="corpo">
        Declaramos que o(a) Discente <strong>{{$discente}}</strong>, portador(a) do CPF <strong>{{$cpf}}</strong>, regularmente matriculado(a) no curso de <strong>{{$curso}}</strong> da <b>Universidade Federal do Agreste de Pernambuco - UFAPE</b> realizou  o depósito do seu trabalho de conclusão de curso, cujo título é: "<b>{!! $tcc !!}</b>"{{ (($registro_patente ?? 'false') == 'true') ? ', com registro de patente' : ', sem registro de patente' }}, bem como a entrega do Termo de Autorização para o depósito no Repositório Institucional, cumprindo todos os requisitos necessários. <br>
        <br>
        Portanto, não constando pendências com este setor. <br> <br>
    </div>
    <div id="data">
        <p>{{ \Carbon\Carbon::now()->format('d/m/Y') }}</p>
    </div>
    <div class="assinatura">
        <div id="linha"></div>
        <span id="cargo">
            SIB/UFAPE
        </span>
    </div>
</body>

</html>

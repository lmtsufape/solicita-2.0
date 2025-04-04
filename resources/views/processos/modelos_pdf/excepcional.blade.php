<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>

        body{
            font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
            font-size: 13pt;
            margin-left: 2cm;
            margin-right: 2cm;
            
        }
        header{
            text-align: center;
        }
        main{
            text-align: justify;
        }

        footer{
            text-align: justify;
        }

        h4{
            text-align: center;
        }
    
        address{
            display: inline;
        }
        img{
            width: 50.93px; 
            height: 55.21px;
        }

    </style>
</head>
<body>
    <header>
        <div>
            <img src="{{public_path('images/Logo_gov.png')}}" >
        </div>
        <p>
            
            MINISTÉRIO DA EDUCAÇÃO <br>
            UNIVERSIDADE FEDERAL DO AGRESTE DE PERNAMBUCO <br>
            <address>AV. BOM PASTOR, S/N, BAIRRO BOA VISTA CEP 55.296-901 GARANHUNS, PE</address>
            E-MAIL: DRCA@UFAPE.EDU.BR
        </p>
    </header>
    <main>
        
            
        <h4>
            REQUERIMENTO DE ANOTAÇÃO DE ATIVIDADE <br>
            CURRICULAR COMPLEMENTAR
        </h4>
        
        <p>À COORDENAÇÃO DO CURSO DE {{$perfil->curso->nome}} DA UFAPE, </p>
        <p style="text-indent: 20px;">
            Eu <strong>{{$user->name}}</strong>, n° de CPF <strong>{{$aluno->cpf}}</strong>, aluno(a) do curso de
            {{$perfil->curso->nome}}, solicito de V. Sa. a análise da
            documentação apresentada em anexo, a essa
            coordenação relativa às atividades complementares
            para cômputo e registro de carga horária pelo
            DRCA/UFAPE, de acordo com a resolução
            CONSU/UFAPE no 015/2020.
        </p>
        <p>
            Garanhuns, {{$data}}
        </p>
        <p>
            Contatos: <br>
            Email: {{$user->email}}
        </p>
        
    </main>
    <footer>
        <p style="font-weight: italic">Obs.: Requerimento realizado pelo(a) discente através do Solicita <a href="www.solicita.ufape.edu.br">(www.solicita.ufape.edu.br)</a>, dispensa assinatura.</p>
    </footer>
</body>
</html>

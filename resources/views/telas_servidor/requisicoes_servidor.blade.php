@extends('layouts.app')

@section('conteudo')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-11">
                <div class="tituloListagem">{{$cursoSelecionado->nome}} - {{$titulo}}</div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-11">
                <div class="col-md-12 my-3" id="selectCursos">
                    <form action="{{ route('listar-requisicoes') }}" method="GET" class="row px-1 justify-content-around">
                        <select onchange="this.form.submit()" name="curso_id" id="cursos" class="browser-default custom-select custom-select col-md-6 my-2" style="background-color: white;border-radius: 0.5rem;">
                            @foreach($cursos as $curso)
                                <option id="optionComOValor" value="{{$curso->id}}">{{$curso->nome}}</option>
                            @endforeach
                        </select>
                        <select name="titulo_id" onchange="this.form.submit()" class="browser-default custom-select custom-select col-md-6 my-2" style="background-color: white;border-radius: 0.5rem;">
                            <option @if($titulo_id == 1) selected @endif value="1">Declaração de Vínculo</option>
                            <option @if($titulo_id == 2) selected @endif value="2">Comprovante de Matrícula</option>
                            <option @if($titulo_id == 3) selected @endif value="3">Histórico</option>
                            <option @if($titulo_id == 4) selected @endif value="4">Programa de Disciplina</option>
                            <option @if($titulo_id == 5) selected @endif value="5">Outros</option>
                            <option @if($titulo_id == 6) selected @endif value="6">Desbloqueio do SIGA</option>
                        </select>
                    </form>
                </div>
                <table class="table table-borderless shadow table-hover mb-2"
                       style="border-radius: 1rem; background-color: white; border: none" id="table">
                    <thead class="lmts-primary table-borderless" style="border-color:#1B2E4F;">
                    <tr>
                        <!-- Checkbox para selecionar todos os documentos -->

                        <th scope="col" class="titleColumn" onclick="sortTable(0)" style="cursor:pointer">#</th>
                        <th scope="col" class="titleColumn text-center">CPF</th>
                        <th scope="col" class="titleColumn" onclick="sortTable(2)" style="cursor:pointer">Nome
                        <th scope="col" class="titleColumn text-center">Curso</th>
                        <th scope="col" class="titleColumn">E-mail</th>
                        <th scope="col" class="titleColumn text-center">Vínculo</th>
                        <th scope="col" class="titleColumn text-center" onclick="sortTable(5)" style="cursor:pointer">
                            Data e Hora
                        {{-- <th scope="col" class="titleColumn">HORA DE REQUISIÇÃO</th> --}}
                        @if($titulo=="Outros" | $titulo=="Programa de Disciplina")
                            <th scope="col">Informações</th>
                        @endif
                        @if($titulo=="Concluídos" || $titulo == "Indeferidos" )
                            <th scope="col">Documento Solicitado</th>
                            <th scope="col">Status</th>
                    @endif <th scope="row" style="width:40px; text-align:center; vertical-align:middle;">
                            <!-- botão de finalizar -->
                            <form id="formularioRequisicao" action="{{  route('listar-requisicoes-post')  }}"
                                  method="POST">
                                @csrf
                                <!-- Checkbox que seleciona todos os outros -->
                                    @if(isset($listaRequisicao_documentos))
                                        @if(sizeof($listaRequisicao_documentos) > 0)
                                            <input type="checkbox" id="selectAll">
                                        @endif
                                    @endif

                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($listaRequisicao_documentos as $requisicao_documento)
                        <tr>
                            <td class="align-middle">{{$loop->iteration}}</td>
                            <td class="text-center align-middle">{{$requisicao_documento['cpf']}}</td>
                            <td class="align-middle">{{$requisicao_documento['nome']}}</td>
                            <td class="text-center align-middle">{{$requisicao_documento['curso']}}</td>
                            <td class="align-middle">{{$requisicao_documento['email']}}</td>
                            <td class="text-center align-middle">{{$requisicao_documento['vinculo']}}</td>
                            <td class="text-center align-middle">{{date_format(date_create($requisicao_documento['status_data']), 'd/m/Y')}}
                                , {{$requisicao_documento['status_hora']}}</td>
                            {{-- <td>{{$requisicao_documento['status_data']}}</td>
                            <td>{{$requisicao_documento['status_hora']}}</td> --}}

                            @if($titulo=="Outros" || $titulo=="Programa de Disciplina")
                                <td class="td-align">
                                    <a data-toggle="tooltip" data-placement="left"
                                       title="Informações:{{$requisicao_documento['detalhes']}} ">
                                <span onclick="exibirAnotacoes({{$requisicao_documento['id']}})"
                                      class="btn p-2 fa fa-eye" aria-hidden="true"></span>
                                        @component('componentes.popup', ["titulo"=>"Informações:", "conteudo"=>$requisicao_documento['detalhes'], "id"=>$requisicao_documento['id']])
                                        @endcomponent
                                    </a>
                                </td>
                            @endif
                            {{-- DOCUMENTOS SOLICITADOS E STATUS - INICIO --}}
                            @if($titulo=="Indeferidos" || $titulo == "Concluídos" )
                                {{-- DOCUMENTOS INDEFERIDOS --}}
                                @if($requisicao_documento['status'] == 'Indeferido' )
                                    <td>
                                        @if($requisicao_documento['requisicoes_documentos']['documento_id'] == 1)
                                            Declaração de Vínculo
                                        @endif

                                        @if($requisicao_documento['requisicoes_documentos']['documento_id'] == 2)
                                            Comprovante de Matrícula
                                        @endif

                                        @if($requisicao_documento['requisicoes_documentos']['documento_id'] == 3)
                                            Histórico Escolar
                                        @endif

                                        @if($requisicao_documento['requisicoes_documentos']['documento_id'] == 4)
                                            Programa de Disciplina
                                            <a data-toggle="tooltip" data-placement="left"
                                               title="Informações:{{$requisicao_documento['requisicoes_documentos']['detalhes']}} ">
                                        <span onclick="exibirAnotacoes({{$requisicao_documento['id']}})"
                                              class="fa fa-eye" aria-hidden="true"></span>
                                                @component('componentes.popup', ["titulo"=>"Informações:", "conteudo"=>$requisicao_documento['requisicoes_documentos']['detalhes'], "id"=>$requisicao_documento['requisicoes_documentos']['id']])
                                                @endcomponent
                                            </a>
                                        @endif
                                        @if($requisicao_documento['requisicoes_documentos']['documento_id'] == 5)
                                            Outros
                                            <a data-toggle="tooltip" data-placement="left"
                                               title="Informações:{{$requisicao_documento['requisicoes_documentos']['detalhes']}} ">
                                        <span onclick="exibirAnotacoes({{$requisicao_documento['id']}})"
                                              class="fa fa-eye" aria-hidden="true"></span>
                                                @component('componentes.popup', ["titulo"=>"Informações:", "conteudo"=>$requisicao_documento['requisicoes_documentos']['detalhes'], "id"=>$requisicao_documento['requisicoes_documentos']['id']])
                                                @endcomponent
                                            </a>
                                        @endif

                                        {{-- {{$requisicao_documento['requisicoes_documentos']['id']}} --}}

                                    </td>

                                    <td class="text-danger">
                                        Requisição: {{$requisicao_documento['status']}}
                                        <a data-toggle="tooltip" data-placement="left"
                                           title="Motivo(s):{{$requisicao_documento['requisicoes_documentos']['anotacoes']}} ">
                                    <span
                                        onclick="exibirAnotacoes({{$requisicao_documento['requisicoes_documentos']['id']+1}})"
                                        class="fa fa-eye" aria-hidden="true"></span>
                                            @component('componentes.popup', ["titulo"=>"Motivo(s):", "conteudo"=>$requisicao_documento['requisicoes_documentos']['anotacoes'], "id"=>$requisicao_documento['requisicoes_documentos']['id']+1])
                                            @endcomponent
                                        </a>
                                    </td>
                                @elseif($titulo=="Concluídos")
                                    {{-- DOCUMENTOS CONCLUIDOS --}}
                                    <td>
                                        @if($requisicao_documento['requisicoes_documentos']['documento_id'] == 1)
                                            Declaração de Vínculo
                                        @endif

                                        @if($requisicao_documento['requisicoes_documentos']['documento_id'] == 2)
                                            Comprovante de Matrícula
                                        @endif

                                        @if($requisicao_documento['requisicoes_documentos']['documento_id'] == 3)
                                            Histórico Escolar
                                        @endif

                                        @if($requisicao_documento['requisicoes_documentos']['documento_id'] == 4)
                                            Programa de Disciplina
                                            <a data-toggle="tooltip" data-placement="left"
                                               title="Informações:{{$requisicao_documento['requisicoes_documentos']['detalhes']}} ">
                                        <span onclick="exibirAnotacoes({{$requisicao_documento['id']}})"
                                              class="fa fa-eye" aria-hidden="true"></span>
                                                @component('componentes.popup', ["titulo"=>"Informações:", "conteudo"=>$requisicao_documento['requisicoes_documentos']['detalhes'], "id"=>$requisicao_documento['requisicoes_documentos']['id']])
                                                @endcomponent
                                            </a>
                                        @endif

                                        @if($requisicao_documento['requisicoes_documentos']['documento_id'] == 5)
                                            Outros
                                            <a data-toggle="tooltip" data-placement="left"
                                               title="Informações:{{$requisicao_documento['requisicoes_documentos']['detalhes']}} ">
                                        <span onclick="exibirAnotacoes({{$requisicao_documento['id']}})"
                                              class="fa fa-eye" aria-hidden="true"></span>
                                                @component('componentes.popup', ["titulo"=>"Informações:", "conteudo"=>$requisicao_documento['requisicoes_documentos']['detalhes'], "id"=>$requisicao_documento['requisicoes_documentos']['id']])
                                                @endcomponent
                                            </a>
                                        @endif

                                        {{-- {{$requisicao_documento['requisicoes_documentos']['id']}} --}}

                                    </td>

                                    <td class="text-success">
                                        Requisição: {{$requisicao_documento['status']}}
                                    </td>
                                @endif
                            @endif
                            {{-- DOCUMENTOS SOLICITADOS E STATUS - FIM --}}
                            <td style="width:40px; text-align:center; vertical-align:middle;">
                                <input class="checkboxLinha" type="checkbox"
                                       name="checkboxLinha[]"
                                       value="{{$requisicao_documento['id']}}">
                            </td>

                        </tr>
                    @endforeach

                    <!-- </div> -->
                    </tbody>
                    </form>
                </table>
                <div class="d-flex gap-2 mt-3">

                    <button id="btnAprovar"
                            onclick="event.preventDefault(); confirmarRequisicao()"
                            class="btn"
                            style="background-color: green; color: white; border-radius: 0.5rem;">
                        Aprovar
                    </button>

                    <button id="btnIndeferir"
                            onclick="event.preventDefault(); abrirModalIndeferir()"
                            class="btn"
                            style="background-color: red; color: white; border-radius: 0.5rem;">
                        Indeferir
                    </button>

                </div>






            </div>
        </div>
    </div>

    <div class="modal fade" id="myModal" tabindex="-1" role="dialog">

        <div class="modal-dialog modal-dialog-centered">

            <div class="modal-content">

                <form method="POST" id="formModal" action="{{ route('indefere-requisicoes-post') }}">
                    @csrf

                    <div id="container-ids"></div>

                    <div class="modal-header">
                        <h5 class="modal-title">Justificativa do Indeferimento</h5>

                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <textarea name="anotacoes" class="form-control" required></textarea>
                    </div>

                    <div class="modal-footer">

                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Fechar
                        </button>

                        <button type="submit" class="btn btn-danger">
                            Indeferir Selecionados
                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

    </div>
    <!-- Modal -->

    <script>

        $('#table').DataTable({
            searching: true,

            "language": {
                "lengthMenu": "Mostrar _MENU_ registros por página",
                "info": "Exibindo página _PAGE_ de _PAGES_",
                "infoEmpty": "Nenhum registro disponível",
                "zeroRecords": "Nenhum registro disponível",
                "search": "",
                "paginate": {
                    "previous": "Anterior",
                    "next": "Próximo",
                }
            },
            "dom": '<"top"f>rt<"bottom"p><"clear">',
            "order": [],
            "columnDefs": [{
                "targets": [5],
                "orderable": false
            }]
        });

        $('.dataTables_filter').addClass('here');
        $('.dataTables_filter').addClass('');
        $('.here').addClass('center');
        $('.here').removeClass('dataTables_filter');
        $('.table-hover').removeClass('dataTable');
        $('.here').find('input').addClass('search-input');
        $('.here').find('input').addClass('align-middle');
        $('.here').find('label').contents().unwrap();
        $('.here').find('input').wrap('<div class="col-md-12 my-3 py-1" style="background-color: #C2C2C2; border-radius: 1rem;"> <div class="col-md-7 my-2"> <div class="col-md-12 p-1 img-search" style="background-color: white; border-radius: 0.5rem;"> </div> </div> </div>');
        $('.img-search').prepend('<img src="{{asset('images/search.png')}}" width="25px">');
    </script>

    <script>
        function mudarId(id) {
            document.getElementById('id_documento').value = id;
            $('#myModal').modal('show');
        }
        function selectClicado(){
            var selectedValue = document.getElementById("cursos").value;
            document.getElementById('cursoIdDeclaracao').value = selectedValue;
            document.getElementById('listar-requisicoes-form').submit();

        }
        $(function(){
            var nomeCurso = {!! json_encode($cursoSelecionado->toArray()) !!};
            var nomeDocumento = {!! json_encode($documentoSelecionado->toArray()) !!};
            document.getElementById('cursos').value = nomeCurso['id'];
            document.getElementById('documentos').value = nomeDocumento['id'];
            console.log(nomeDocumento['id']);
        });


    </script>

    <script>
        $('#myModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget) // Botão que acionou o modal
            var recipient = button.data('whatever')
            var id = button.data('id')
            var curso = button.data('curso')
            var anotacoes = button.data('anotacoes')
            var modal = $(this)
            modal.find('.modal-title').text('Nome do Aluno: ' + recipient)
            modal.find('.modal-body input').val(recipient)
        })

        function exibirAnotacoes(id){
            var s = '#'+id;
            $(s).modal('show');
            console.log(s)

        }

    </script>
    <script>

        var checkedAll = false;
        var checkBoxs;
        document.getElementById("selectAll").addEventListener("click", function(){
            checkBoxs = document.querySelectorAll('input[type="checkbox"]:not([id=selectAll])');
            //"Hack": http://toddmotto.com/ditch-the-array-foreach-call-nodelist-hack/
            [].forEach.call(checkBoxs, function(checkbox) {
                //Verificamos se é a hora de dar checked a todos ou tirar;
                checkbox.checked = checkedAll ? false : true;
            });
            //Invertemos ao final da execução, caso a última tenha sido true para checar todos, tornamos ele false para o próximo clique;
            checkedAll = checkedAll ? false : true;
            //getLinhas();
        });

        //console.log(checkBoxs);

        function confirmarRequisicao() {
            const ids = getLinhas();

            if (ids.length === 0) {
                alert("Selecione pelo menos um documento!");
                return;
            }

            if (!confirm("Confirma aprovação dos documentos selecionados?")) {
                return;
            }

            const form = document.getElementById("formularioRequisicao");

            // limpa inputs antigos
            form.querySelectorAll('input[name="checkboxLinha[]"]').forEach(el => el.remove());

            // adiciona novos
            ids.forEach(id => {
                const input = document.createElement("input");
                input.type = "hidden";
                input.name = "checkboxLinha[]";
                input.value = id;
                form.appendChild(input);
            });

            form.submit();
        }

        function abrirModalIndeferir() {
            const ids = getLinhas();

            if (ids.length === 0) {
                alert("Selecione pelo menos um documento!");
                return;
            }

            const container = document.getElementById("container-ids");
            container.innerHTML = "";

            ids.forEach(id => {
                const input = document.createElement("input");
                input.type = "hidden";
                input.name = "checkboxLinha[]";
                input.value = id;
                container.appendChild(input);
            });

            document.querySelector('[name="anotacoes"]').value = "";

            $('#myModal').modal('show');
        }

        function indeferirRequisicao(){
            const anotacoes = document.getElementById('anotacoes').value.trim();

            if (anotacoes === '') {
                alert('A mensagem é obrigatória');
                return;
            }

            if(confirm("Confirma o indeferimento desta requisição?")== true){
                console.log("indeferir requisição")
                document.getElementById("formModal").submit();
            }
        }
        function getLinhas() {
            const checkboxes = document.querySelectorAll('.checkboxLinha:checked');
            const ids = [];

            checkboxes.forEach(cb => {
                ids.push(cb.value);
            });

            return ids;
        }
        function getIds(dados){
            var arrayDados = dados;
            var newArray = [];//array aux para guardar os ids dos documentos

            for(var i = 0; i <= arrayDados.length; i++){
                if(typeof arrayDados[i]=='object'){
                    if(arrayDados[i].checked){// se o checkbox estiver marcado
                        newArray.push(arrayDados[i].value); //adiciona em um array o id do documento solicitado
                    }
                }
            }
            return newArray;
        }
        function sortTable(n) {
            var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
            table = document.getElementById("table");
            switching = true;
            // Set the sorting direction to ascending:
            dir = "asc";
            /* Make a loop that will continue until
            no switching has been done: */
            while (switching) {
                // Start by saying: no switching is done:
                switching = false;
                rows = table.rows;
                /* Loop through all table rows (except the
                first, which contains table headers): */
                for (i = 1; i < (rows.length - 1); i++) {
                    // Start by saying there should be no switching:
                    shouldSwitch = false;
                    /* Get the two elements you want to compare,
                    one from current row and one from the next: */
                    x = rows[i].getElementsByTagName("TD")[n];
                    y = rows[i + 1].getElementsByTagName("TD")[n];
                    console.log(rows[i].getElementsByTagName("TD")[5],rows[i + 1].getElementsByTagName("TD")[5] )
                    /* Check if the two rows should switch place,
                    based on the direction, asc or desc: */
                    if (dir == "asc") {
                        if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                            // If so, mark as a switch and break the loop:
                            shouldSwitch = true;
                            break;
                        }
                    } else if (dir == "desc") {
                        if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
                            // If so, mark as a switch and break the loop:
                            shouldSwitch = true;
                            break;
                        }
                    }
                }
                if (shouldSwitch) {
                    /* If a switch has been marked, make the switch
                    and mark that a switch has been done: */
                    rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                    switching = true;
                    // Each time a switch is done, increase this count by 1:
                    switchcount ++;
                } else {
                    /* If no switching has been done AND the direction is "asc",
                    set the direction to "desc" and run the while loop again. */
                    if (switchcount == 0 && dir == "asc") {
                        dir = "desc";
                        switching = true;
                    }
                }
            }
        }




        //atualizar pagina
        // var time = 1; // 60s

        // atualizarPagina(){
        //   window.location.reload();
        // };


        function retornarCurso(id){
            if(id == 1){
                return 'Agronomia';
            }else if(id == 2){
                return 'Bacharelado em Ciência da Computação';
            }
            else if(id == 3){
                return 'Engenharia de Alimentos';
            }else if(id == 4){
                return 'Licenciatura em Letras';
            }else if(id == 5){
                return 'Licenciatura em Pedagogia';
            }else if(id == 6){
                return 'Medicina Veterinária';
            }else if(id == 7){
                return 'Zootecnia';
            }
        }

    </script>

@endsection



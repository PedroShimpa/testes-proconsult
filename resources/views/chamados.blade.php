@extends('layouts.app')
@section('content')
<!-- Button trigger modal -->
<button type="button" class="btn btn-success mb-2" data-toggle="modal" data-target="#exampleModal">
    Novo Chamado
</button>
<button class=" btn btn-danger mb-2" id="logout">Sair</button>
<div class="card">
    <table class="table bordered">
        <thead>
            <th>Data</th>
            <th>Status</th>
            <th>Autor</th>
            <th>Titulo</th>
            <th>Descrição</th>
            <th>Ações</th>
        </thead>
        <tbody id="chamados">
        </tbody>
    </table>
</div>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Novo Chamado</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row justify-content-center">
                    <div class="col-md-12">
                        <div id="validation-errors"></div>
                        <form id="createChamadoForm">
                            <div class="form-group">
                                <label for="title">Titulo</label>
                                <input type="text" class="form-control" id="title" name="title" required maxlength="190">
                            </div>
                            <div class="form-group">
                                <label for="description">Descrição</label>
                                <textarea class="form-control" id="description" name="description" required></textarea>
                            </div>
                            <div class="form-group">
                                <label for="files">Anexos (Opcional)</label>
                                <input type="file" class="form-control" id="files" name="anexed_files[]" multiple>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="novoChamado" class="btn btn-primary">Abrir Chamado</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        const token = localStorage.getItem('access_token');
        if (!token) {
            window.location.href = '/';
        } else {
            getChamados();
        }

        $('#logout').on('click', function() {
            localStorage.removeItem('access_token');
            window.location.href = '/';
        });
    });

    $('#novoChamado').click(function(event) {
        const token = localStorage.getItem('access_token');
        const element = $(this);
        element.attr('disabled', 'true');
        event.preventDefault();

        var formData = new FormData($('#createChamadoForm')[0]);
        $.ajax({
            url: '/api/chamados',
            method: 'POST',
            headers: {
                'Authorization': 'Bearer ' + token
            },
            contentType: 'application/json',
            dataType: 'json',
            data: JSON.stringify({
                title: $('#title').val(),
                description: $('#description').val(),
            }),
            success: function(response) {
                //enviar os arquivos dos chamados
                sendFiles(response.id, formData)
                $('#exampleModal').modal('hide');

                getChamados();
                element.attr('disabled', false);
            },
            error: function(xhr) {
                $('#validation-errors').html('');
                element.attr('disabled', false);
                $.each(xhr.responseJSON.errors, function(key, value) {
                    $('#validation-errors').append('<div class="alert alert-danger">' + value + '</div>');
                });
            }
        });
    });

    function sendFiles(chamadoId, files) {
        const token = localStorage.getItem('access_token');
        $.ajax({
            url: '/api/chamados/files/' + chamadoId,
            method: 'POST',
            contentType: false,
            processData: false,
            headers: {
                'Authorization': 'Bearer ' + token
            },
            data: {
                title: $('#title').val(),
                description: $('#description').val(),
            },
            success: function(response) {},
            error: function(xhr) {}
        });
    }

    function getChamados() {
        const token = localStorage.getItem('access_token');
        $.ajax({
            url: '/api/chamados',
            method: 'GET',
            headers: {
                'Authorization': 'Bearer ' + token
            },
            success: function(response) {
                var chamadosHtml = "";
                $.each(response, function(index, chamado) {
                    // chamadosHtml += `<tr>
                    //     <td>${chamado.data}</td>
                    //     <td>${chamado.status}</td>
                    //     <td>${chamado.autor}</td>
                    //     <td>${chamado.titulo}</td>
                    //     <td>${chamado.descricao}</td>
                    //     <td>Ações</td>
                    // </tr>`;
                });
                $('#chamados').html(chamadosHtml);
            },
            error: function(xhr) {
                localStorage.removeItem('access_token');
                window.location.href = '/';
            }
        });
    }
</script>
@endsection
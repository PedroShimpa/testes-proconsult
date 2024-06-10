@extends('layouts.app')
@section('content')
<button class=" btn btn-danger mb-2" id="logout">Sair</button>
<div id="infoChamado">
</div>
<div id="validation-errors mt-3"></div>
@endsection

@section('scripts')
<script>
	$(document).ready(function() {
		const token = localStorage.getItem('access_token');
		if (!token) {
			window.location.href = '/';
		} else {
			getChamado();
		}

		$('#logout').on('click', function() {
			localStorage.removeItem('access_token');
			window.location.href = '/';
		});
	});


	function getChamado() {
		const token = localStorage.getItem('access_token');
		$.ajax({
			url: '/api/chamados/' + "{{ $chamadoId }}",
			method: 'GET',
			headers: {
				'Authorization': 'Bearer ' + token
			},
			success: function(chamado) {
				var chamadosHtml = "";
				chamadosHtml += `
                    <div class="card">
                    <div class="card-header">Nº do Chamado: ${chamado.id}</div>
                    <div class="card-body">
                     <div>Data do Chamado: ${formataData(chamado.created_at)}</div>
                     <div>Status: ${chamado.status}</div>
                     <div>Autor: ${chamado.creator}</div>
                     <div>Titulo: ${chamado.title}</div>
                     <div>Descrição: ${chamado.description}</div>
                     </div>
                     </div>
					 <br>
					<div class="card">
                    <div class="card-header">Arquivos do chamado:</div>
					<div class="card-body">
                        `

				$.each(chamado.arquivos, function(index, value) {
					chamadosHtml += `<a href="${value.file}" class="btn" download>${value.filename}</a><hr>`
				})

				chamadosHtml += `</div></div><br>	<div class="card">
                    <div class="card-header">Respotas do chamado:</div><div class="card-body">`
				$.each(chamado.respostas, function(index, value) {
					chamadosHtml += `<div> <b>${value.user} </b> em  ${formataData(value.created_at)}: ${value.content}</div><hr>`
				})


				chamadosHtml += `</div></div><textarea id="resposta" placeholder="Digite aqui a resposta do chamado" class="form-control mt-3 mb-2"></textarea><button class="btn btn-primary mt-2" id="enviarResposta">Enviar Resposta</button>`
				$('#infoChamado').html(chamadosHtml);
			},
			error: function(xhr) {
				// localStorage.removeItem('access_token');
				// window.location.href = '/';
			}
		});
	}

	$(document).on('click', '#enviarResposta', function() {
		resposta = $('#resposta').val()
		const token = localStorage.getItem('access_token');
		const element = $(this);
		element.attr('disabled', 'true');
		event.preventDefault();

		$.ajax({
			url: '/api/chamados/reply/' + "{{$chamadoId}}",
			method: 'POST',
			headers: {
				'Authorization': 'Bearer ' + token
			},
			contentType: 'application/json',
			dataType: 'json',
			data: JSON.stringify({
				content: $('#resposta').val(),
			}),
			success: function(response) {
				getChamado()
				$('#resposta').val('')
			},
			error: function(xhr) {
				$('#validation-errors').html('');
				element.attr('disabled', false);
				if (xhr.responseJSON.msg) {
					$('#validation-errors').append('<div class="alert alert-danger">' + xhr.responseJSON.msg + '</div>');
				}
				$.each(xhr.responseJSON.errors, function(key, value) {
					alert(value)
					$('#validation-errors').append('<div class="text text-danger">' + value + '</div>');
				});
			}
		});
	})

	function formataData(data) {
		data = new Date(data)
		var dia = ("0" + data.getDate()).slice(-2);
		var mes = ("0" + (data.getMonth() + 1)).slice(-2);
		var ano = data.getFullYear();
		var hora = ("0" + data.getHours()).slice(-2);
		var minuto = ("0" + data.getMinutes()).slice(-2);
		return dia + "/" + mes + "/" + ano + " " + hora + ":" + minuto;
	}

	function limparFormulario(formularioId) {
		var formulario = document.getElementById(formularioId);
		var elementos = formulario.elements;
		for (var i = 0; i < elementos.length; i++) {
			var elemento = elementos[i];

			if (elemento.tagName === 'INPUT' || elemento.tagName === 'SELECT' || elemento.tagName === 'TEXTAREA') {
				elemento.value = '';
			}
		}
	}
</script>
@endsection
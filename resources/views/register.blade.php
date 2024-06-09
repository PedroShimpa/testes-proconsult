@extends('layouts.app')
@section('content')
<div class="row justify-content-center">
	<div class="col-md-6">
		<div class="card">
			<div class="card-header">Cadastro</div>
			<div class="card-body">
				<div id="validation-errors">
				</div>
				<div class="form-group">
					<label for="name">Nome Completo</label>
					<input type="text" class="form-control" id="name" name="name" required maxlength="190">
				</div>
				<div class="form-group">
					<label for="cpf">CPF (somente numeros) </label>
					<input type="text" class="form-control" id="cpf" name="cpf" required maxlength="11">
				</div>
				<div class="form-group">
					<label for="email">Email</label>
					<input type="email" class="form-control" id="email" name="email" required maxlength="190">
				</div>
				<div class="form-group">
					<label for="password">Senha</label>
					<input type="password" class="form-control" id="password" name="password" required maxlength="30">
				</div>
				<button id="sendRegister" class="btn btn-primary">Cadastrar</button>
				<a href="/">Login</a>
			</div>
		</div>
	</div>
</div>
@endsection
@section('scripts')
<script type="text/javascript">
	$(document).ready(function() {
		const token = localStorage.getItem('access_token');
		if (token) {
			window.location.href = '/chamados';
		}
		$('#sendRegister').click(function(event) {
			element = $(this)
			element.attr('disabled', 'true')
			event.preventDefault();

			$.ajax({
				url: '/api/register',
				method: 'POST',
				contentType: 'application/json',
				dataType: 'json',
				data: JSON.stringify({
					name: $('#name').val(),
					cpf: $('#cpf').val(),
					email: $('#email').val(),
					password: $('#password').val(),
				}),
				success: function(response) {
					alert('Registrado com sucesso, redirecionando para o login')
					window.location.href = '/';
					element.attr('disabled', false)

				},
				error: function(xhr) {
					$('#validation-errors').html('');
			element.attr('disabled', false)

					$.each(xhr.responseJSON.errors, function(key, value) {
						$('#validation-errors').append('<div class="alert alert-danger">' + value + '</div>');
					});
				},
			});
		
		});
	});

	$('#cpf').on('input', function() {
                this.value = this.value.replace(/[^0-9]/g, '');
            });
</script>
@endsection
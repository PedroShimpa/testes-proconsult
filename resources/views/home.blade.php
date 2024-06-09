@extends('layouts.app')
@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">Login</div>
            <div class="card-body">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required maxlength="190">
                </div>
                <div class="form-group">
                    <label for="password">Senha</label>
                    <input type="password" class="form-control" id="password" name="password" required maxlength="30">
                </div>
                <button id="sendLogin" class="btn btn-primary">Login</button>
                <a href="/register">Registrar</a>
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
        $('#sendLogin').click(function(event) {
            element = $(this)
            element.attr('disabled', 'true')
            event.preventDefault();

            $.ajax({
                url: '/api/auth/login',
                method: 'POST',
                contentType: 'application/json',
                dataType: 'json',
                data: JSON.stringify({
                    email: $('#email').val(),
                    password: $('#password').val(),
                }),
                success: function(response) {
                    localStorage.setItem('access_token', response.access_token);
                    window.location.href = '/chamados';
                    element.attr('disabled', false)

                },
                error: function(xhr) {
                    alert('Login failed: ' + xhr.responseJSON.error);
                    element.attr('disabled', false)
                },
            });

        });
    });
</script>
@endsection
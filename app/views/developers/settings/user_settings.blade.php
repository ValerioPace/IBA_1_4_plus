<?php
	$user = User::find($userId);
?>

@if(Session::get('errors'))
<div class="alert alert-danger" id="passwordErrorAlert">
  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
  <h5>Sono presenti errori:</h5>
  @foreach($errors->all('<li>:message</li>') as $message)
    {{$message}}
  @endforeach
</div>
@endif
{{ Form::open(array('url' => 'edit_password', 'class' => 'form-horizontal', 'id' => 'edit_password')) }}

	<label for="inputDescription"><h3>Cambia password&nbsp;</h3></label>
	<div class="form-group">
		<label for="inputPassword" class="col-sm-4 control-label"><p class="text-left">Nuova password</p></label>
		<div class="col-sm-8">
			{{ Form::password('password', array('class'=>'form-control','id'=>'inputPassword', 'placeholder'=>'Minimo 8 caratteri')) }}
		</div>
	</div>
	<div class="form-group">
		<label for="inputPasswordConfirm" class="col-sm-4 control-label"><p class="text-left">Conferma password</p></label>
		<div class="col-sm-8">
			{{ Form::password('password_confirmation', array('class'=>'form-control','id'=>'inputPasswordConfirm', 'placeholder'=>'Ripeti la password')) }}
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-offset-4 col-sm-8">
			<p class="text-right">{{ Form::submit('Modifica', array('name' => 'update', 'class'=>'btn btn-primary')) }}</p>
		</div>
	</div>
{{ Form::close() }}

<script type="text/javascript">

	$(function () {
		$('[data-toggle="tooltip"]').tooltip({
			delay: { "show": 700, "hide": 100 }
		});
	});

	$('#edit_password').submit(function() {
		$.ajax({
			data: $(this).serialize(),
			type: $(this).attr('method'),
			url: $(this).attr('action'),
			success: function(response) {
				$('#user_settings').html(response);
				if (!$('#passwordErrorAlert').length > 0 )
					alert('Password modificata!');
			},
			error: function(output) {
				if (output.status === 401)
					window.location.replace("{{url('/')}}");
			}
		});
		return false;
	});
</script>
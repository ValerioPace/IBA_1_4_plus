<?php
	$company = Company::where('user_id', $userId)->first();
?>

@if(Session::get('errors'))
<div class="alert alert-danger" id="configErrorAlert">
  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
  <h5>Sono presenti errori:</h5>
  @foreach($errors->all('<li>:message</li>') as $message)
    {{$message}}
  @endforeach
</div>
@endif
{{ Form::open(array('url' => 'update_settings', 'class' => 'form-horizontal', 'id' => 'update_settings')) }}
	
	
	<label for="inputDescription"><h3>Dati del referente&nbsp;</h3></label><span class="glyphicon glyphicon-info-sign" data-toggle="tooltip" data-placement="auto" title="Questi dati verranno riproposti di default per ogni nuovo evento." aria-hidden="true"></span>
	
	<div class="form-group">
		<label for="inputName" class="col-sm-4 control-label"><p class="text-left">Nome</p></label>
		<div class="col-sm-8">
			{{ Form::text('contact', $company->contact, array('class'=>'form-control','id'=>'inputName', 'autofocus', 'placeholder'=>'Nome e cognome')) }}
		</div>
	</div>
	<div class="form-group">
		<label for="inputPhone" class="col-sm-4 control-label"><p class="text-left">Telefono</p></label>
		<div class="col-sm-8">
			<?php $phone = $company->phone ? $company->phone : '+39'; ?>
			{{ Form::text('phone', $phone, array('class'=>'form-control','id'=>'inputPhone')) }}
		</div>
	</div>
	<div class="form-group">
		<label for="inputEmail" class="col-sm-4 control-label"><p class="text-left">Email</p></label>
		<div class="col-sm-8">
			{{ Form::text('email', $company->email, array('class'=>'form-control','id'=>'inputEmail')) }}
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-offset-4 col-sm-8">
			<p class="text-right">{{ Form::submit('Aggiorna', array('class'=>'btn btn-primary')) }}</p>
		</div>
	</div>
{{ Form::close() }}

<script type="text/javascript">

	$('#update_settings').submit(function() {
		$.ajax({
			data: $(this).serialize(),
			type: $(this).attr('method'),
			url: $(this).attr('action'),
			success: function(response) {
				$('#company_data').html(response);
				if (!$('#configErrorAlert').length > 0 )
					alert('Dati modificati!');
			},
			error: function(output) {
				if (output.status === 401)
					window.location.replace("{{url('/')}}");
			}
		});
		return false;
	});
</script>
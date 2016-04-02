<?php
	$company = Company::where('user_id', $userId)->first();

	$event = isset($event_id) ? Evento::find($event_id) : null;
?>

@if(Session::get('errors'))
	<div class="alert alert-danger" id="errorAlert">
	  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
	  <h5>Sono presenti errori:</h5>
	  @foreach($errors->all('<li>:message</li>') as $message)
	    {{$message}}
	  @endforeach
	</div>
@endif
{{ Form::open(array('url' => 'update_event', 'class' => 'form-horizontal', 'id' => 'edit_event', 'files' => true)) }}

	<?php
		$id = Input::has('event_id') ? Input::get('event_id') : null;
	?>

	{{ Form::hidden('event_id', $id) }}

	<div class="form-group">
		<label for="inputTitle" class="col-sm-2 control-label">Titolo</label>
		<div class="col-sm-10">
			<?php $title = $event ? $event->title : null; ?>
			{{ Form::text('title', $title, array('class'=>'form-control count-char', 'maxlength'=>'60','id'=>'inputTitle', 'placeholder'=>'Max 60 caratteri...')) }}
		</div>
	</div>
	<div class="form-group">
		<label for="inputDescription" class="col-sm-2 control-label">Descrizione</label>
		<div class="col-sm-10">
			<?php $description = $event ? $event->description : null; ?>
		  	{{ Form::textarea('description', $description, array('class' => 'form-control count-char', 'maxlength'=>'2000','id'=>'inputDescription', 'placeholder'=>'Max 2000 caratteri...')) }}
		</div>
	</div>
	<div class="form-group">
		<label for="inputImage" class="col-sm-2 control-label">Locandina (jpeg e png; Max 6MB)</label>
		<div class="col-sm-10">
		  	{{ Form::file('flyer', array('class' => 'form-control', 'id'=>'inputImage')) }}
		</div>
	</div>


    <div class="form-group">
		<label for="datetimepicker_start" class="col-sm-2 control-label">Data inizio evento</label>
		<div class="col-sm-10">
            <div class='input-group date' id='datetimepicker_start'>
            	<?php  
            		$activated_at = $event && $event->activated_at ? date("d/m/Y", strtotime($event->activated_at)) : null;
            	?>
            	{{ Form::text('activated_at', $activated_at, array('class'=>'form-control')) }}
                <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                </span>
            </div>
        </div>
    </div>


    <div class="form-group">
		<label for="datetimepicker_end" class="col-sm-2 control-label">Data fine evento</label>
		<div class="col-sm-10">
            <div class='input-group date' id='datetimepicker_end'>
            	<?php 
            		$expire_on = $event && $event->expire_on ? date("d/m/Y", strtotime($event->expire_on)) : null;
            	?>
                {{ Form::text('expire_on', $expire_on, array('class'=>'form-control')) }}
                <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                </span>
            </div>
        </div>
    </div>



	<div class="form-group">
		<label for="inputContact" class="col-sm-2 control-label">Referente</label>
		<div class="col-sm-10">
			<?php  $contact = $event ? $event->contact : null; ?>
			{{ Form::text('contact', $contact, array('class'=>'form-control','id'=>'inputContact')) }}
		</div>
	</div>
	<div class="form-group">
		<label for="inputPhone" class="col-sm-2 control-label">Telefono</label>
		<div class="col-sm-10">
			<?php  $phone = $event ? $event->phone : null; ?>
			{{ Form::text('phone', $phone, array('class'=>'form-control','id'=>'inputPhone')) }}
		</div>
	</div>
	<div class="form-group">
		<label for="inputEmail" class="col-sm-2 control-label">Email</label>
		<div class="col-sm-10">
			<?php  $email = $event ? $event->email : null; ?>
			{{ Form::text('email', $email, array('class'=>'form-control','id'=>'inputEmail')) }}
		</div>
	</div>
	@if ($company->has_ecommerce)
	<hr>
	<div class="form-group">
		<label for="inputProductId" class="col-sm-2 control-label">ID Prodotto</label>
		<div class="col-sm-10">
			<?php  $productId = $event ? $event->product_id : null; ?>
			{{ Form::text('product_id', $productId, array('class'=>'form-control','id'=>'inputProductId')) }}
		</div>
	</div>
	<div class="form-group">
		<label for="inputProductName" class="col-sm-2 control-label">Nome Prodotto</label>
		<div class="col-sm-10">
			<?php  $productName = $event ? $event->product_name : null; ?>
			{{ Form::text('product_name', $productName, array('class'=>'form-control','id'=>'inputProductName')) }}
		</div>
	</div>
	@endif
	<div class="form-group">
		<div class="col-sm-offset-2 col-sm-10">
		  <div class="checkbox">
			    <label>
			    	{{ Form::checkbox('push', 'yes', false) }} Invia notifiche Push
			    </label>
			    <span class="glyphicon glyphicon-info-sign" data-toggle="tooltip" data-placement="auto" title="Inviando una notifica push il contatore dei dispositivi che hanno letto questo evento (presente nella pagina delle statistiche) verrà azzerato." aria-hidden="true"></span>
		  </div>
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-offset-2 col-sm-10">
		  	<button type="submit" name="update" class="btn btn-primary" data-loading-text="Modifica..." autocomplete="off">Aggiorna</button>
		  	<button type="submit" name="delete" class="btn btn-default btn-danger" data-loading-text="Eliminazione..." autocomplete="off">Elimina</button>
			<!-- {{ Form::submit('Aggiorna', array('name' => 'update', 'class'=>'btn btn-primary')) }}
			{{ Form::submit('Elimina', array('name' => 'delete', 'class'=>'btn btn-default btn-danger')) }} -->
			<!-- <button id="submit" type="submit" name="submit" value="un valore" class="btn btn-default">Aggiorna</button> -->
			{{ Form::hidden('action_button', null, array('id' => 'action_button')) }}
		</div>
	</div>
{{ Form::close() }}


<script type="text/javascript">

	$('input.count-char, textarea.count-char').maxlength({
        alwaysShow: true,
		warningClass: "label label-success",
		limitReachedClass: "label label-danger",
		separator: ' caratteri su ',
		preText: 'Hai utilizzato ',
		postText: ' .',
		validate: true
    });

	$(function () {
		$('[data-toggle="tooltip"]').tooltip({
			delay: { "show": 700, "hide": 100 }
		});
	});
	
	var button;

	$('button[type="submit"]').on('click', function(){
		button = $(this);
		$('#action_button').attr('value', button.attr('name'));
	});

	$('#edit_event').submit(function() {
		button.button('loading');
		var formData = new FormData(this);
		if (button.attr('name') == 'delete')
			if (!confirm("Sei sicuro di voler cancellare questo evento?")){
				button.button('reset');
				return false;
			}
		$.ajax({
		    cache: false,
			data: formData,
	        contentType: false,
	        processData: false,
			type: $(this).attr('method'),
			url: $(this).attr('action'),
			success: function(response) {
	        	button.button('reset');
				if (button.attr('name') == 'delete'){
					$.ajax({
						url: 'console/user.active.events.active.list',
						data: {page: 'active_events'},
						type: 'post',
						success: function(output) {
			            	$('#active_events_list').html(output);
							alert('Evento eliminato!');
			            },
						error: function(output) {
							if (output.status === 401)
								window.location.replace("{{url('/')}}");
						}
			        });
				} else{
					$('#active_event_details').html(response);
					if (!$('#errorAlert').length > 0 ){
						$.ajax({
							url: 'console/user.active.events.active.list',
							data: {page: 'active_events'},
							type: 'post',
							success: function(output) {
				            	$('#active_events_list').html(output);
								if (!$('#errorAlert').length > 0 )
									alert('Evento modificato!');
				            },
							error: function(output) {
								if (output.status === 401)
									window.location.replace("{{url('/')}}");
							}
				        });
					}
					/*$.ajax({
						url: 'console/user.active.events.active.list',
						data: {page: 'active_events'},
						type: 'post',
						success: function(output) {
			            	$('#active_events_list').html(output);
							loadImageByTag($('#imageTag').attr('value'));
							if (!$('#errorAlert').length > 0 )
								alert('Evento modificato!');
			            }
			        });*/
				}
			},
			error: function(output) {
				if (output.status === 401)
					window.location.replace("{{url('/')}}");
			}
		});
		return false;
	});

    $(function () {
        $('#datetimepicker_start').datetimepicker({
                    locale: 'it',
                    format: 'DD/MM/YYYY',
                    showClear: true
                });
        $('#datetimepicker_end').datetimepicker({
                    locale: 'it',
                    format: 'DD/MM/YYYY',
                    showClear: true
                });
        $("#datetimepicker_start").on("dp.change", function (e) {
            $('#datetimepicker_end').data("DateTimePicker").hide(e.date);
        });
        $("#datetimepicker_end").on("dp.change", function (e) {
            $('#datetimepicker_start').data("DateTimePicker").maxDate(e.date);
        });
    });
</script>
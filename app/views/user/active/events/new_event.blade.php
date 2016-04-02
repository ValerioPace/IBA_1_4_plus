<?php  
	$company = Company::where('user_id', $userId)->first();
?>

<div class="panel panel-default">
	<div class="panel-heading">Crea e condividi un nuovo evento</div>
	<div class="panel-body">
		<div class="row">
			<div class="col-xs-12 col-md-offset-2 col-md-8">
				@if(Session::get('errors'))
					<div class="alert alert-danger">
					  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
					  <h5>Sono presenti errori:</h5>
					  @foreach($errors->all('<li>:message</li>') as $message)
					    {{$message}}
					  @endforeach
					</div>
				@endif
			</div>
		</div>
		{{ Form::open(array('url' => 'new_event', 'id' => 'newEvent', 'files' => true)) }}

		<div class="row">
			<div class="col-xs-12 col-md-4 col-md-offset-2">
					<div class="form-group">
							<!-- <span class="glyphicon glyphicon-info-sign" rel="popover" data-trigger="click" data-container="body" data-placement="auto left" data-content="Body Text" aria-hidden="true"></span><label for="inputTitle"> Titolo</label> -->
							<label for="inputTitle">Titolo&nbsp;</label><span class="glyphicon glyphicon-info-sign" data-toggle="tooltip" data-placement="auto" title="Inserisci un titolo che descriva al meglio l'evento che stai creando." aria-hidden="true"></span>
							{{ Form::text('title', null, array('class'=>'form-control count-char', 'maxlength'=>'60','id'=>'inputTitle', 'autofocus', 'placeholder'=>'Max 60 caratteri...')) }}
					</div>
					<div class="form-group">
							<label for="inputDescription">Descrizione&nbsp;</label>
						  	{{ Form::textarea('description', null, array('class' => 'form-control count-char', 'maxlength'=>'2000','id'=>'inputDescription', 'placeholder'=>'Max 2000 caratteri...')) }}
					</div>
					<div class="form-group">
							<label for="inputImage">Locandina&nbsp;</label><span class="glyphicon glyphicon-info-sign" data-toggle="tooltip" data-placement="auto" title="Inserisci immagini JPEG o PNG di dimensione massima 6MB." aria-hidden="true"></span>
						  	{{ Form::file('flyer', array('class' => 'form-control', 'id'=>'inputImage')) }}
					</div>
					<img src="" alt="" class="img-rounded">
			</div>

			<div class="col-xs-12 col-md-3 col-md-offset-1">

			    <div class="form-group">
					<label for="datetimepicker_start">Data inizio evento&nbsp;</label>
		            <div class='input-group date' id='datetimepicker_start'>
		            	{{ Form::text('activated_at', null, array('class'=>'form-control')) }}
		                <span class="input-group-addon">
		                    <span class="glyphicon glyphicon-calendar"></span>
		                </span>
		            </div>
			    </div>


			    <div class="form-group">
					<label for="datetimepicker_end">Data fine evento&nbsp;</label><span class="glyphicon glyphicon-info-sign" data-toggle="tooltip" data-placement="auto" title="Se si inserisce una data di fine, alla sua scadenza, l'evento verrà spostato nella sezione 'Storico' e non sarà più visibile all'interno dell'applicazione." aria-hidden="true"></span>
		            <div class='input-group date' id='datetimepicker_end'>
		                {{ Form::text('expire_on', null, array('class'=>'form-control')) }}
		                <span class="input-group-addon">
		                    <span class="glyphicon glyphicon-calendar"></span>
		                </span>
		            </div>
			    </div>



				<div class="form-group">
					<label for="inputContact">Referente&nbsp;</label><span class="glyphicon glyphicon-info-sign" data-toggle="tooltip" data-placement="auto" title="La persona responsabile per questo evento" aria-hidden="true"></span>
						{{ Form::text('contact', $company->contact, array('class'=>'form-control','id'=>'inputContact')) }}
				</div>
				<div class="form-group">
					<label for="inputPhone">Telefono&nbsp;</label><span class="glyphicon glyphicon-info-sign" data-toggle="tooltip" data-placement="auto" title="Un contatto telefonico per questo evento" aria-hidden="true"></span>
						{{ Form::text('phone', $company->phone, array('class'=>'form-control','id'=>'inputPhone')) }}
				</div>
				<div class="form-group">
					<label for="inputEmail"Email>Email&nbsp;</label><span class="glyphicon glyphicon-info-sign" data-toggle="tooltip" data-placement="auto" title="Un contatto email per questo evento" aria-hidden="true"></span>
						{{ Form::text('email', $company->email, array('class'=>'form-control','id'=>'inputEmail')) }}
				</div>
				@if ($company->has_ecommerce)
				<hr>
				<div class="form-group">
					<label for="inputProductId"Email>ID Prodotto&nbsp;</label>
						{{ Form::text('product_id', null, array('class'=>'form-control','id'=>'inputProductId')) }}
				</div>
				<div class="form-group">
					<label for="inputProductName"Email>Nome Prodotto&nbsp;</label>
						{{ Form::text('product_name', null, array('class'=>'form-control','id'=>'inputProductName')) }}
				</div>
				@endif
				<div class="form-group">
					  <div class="checkbox">
						    <label>
				    			{{ Form::checkbox('push', 'yes', true) }} Invia notifiche Push
						    </label>
						    &nbsp;
						    <span class="glyphicon glyphicon-info-sign" data-toggle="tooltip" data-placement="auto" title="Lascia la spunta se vuoi che tutti i dispositivi del tuo network ricevano una notifica dell'evento appena creato." aria-hidden="true"></span>
					  </div>
				</div>

				<div class="form-group">
					  	<button type="submit" class="btn  btn-primary" data-loading-text="Caricamento..." autocomplete="off" id="submitButton">Crea</button>
				</div>
			</div>
		</div>
	</div>
</div>
{{ Form::close() }}

<script type="text/javascript" src="dist/js/moment.js"></script>
<script type="text/javascript" src="dist/js/transition.js"></script>
<script type="text/javascript" src="dist/js/collapse.js"></script>
<script type="text/javascript" src="dist/js/bootstrap-datetimepicker.js"></script>
<link rel="stylesheet" href="dist/css/bootstrap-datetimepicker.css" />

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

	$('#newEvent').submit(function() {
		var $btn = $('#submitButton').button('loading');
		var formData = new FormData(this);
	    $.ajax({
	        data: formData,
	        contentType: false,
	        processData: false,
	        type: $(this).attr('method'),
	        url: $(this).attr('action'),
	        success: function(response) {
	        	$btn.button('reset');
	            $('#content').html(response);
	            $(".nav").find(".active").removeClass("active");
	            $('#eventDropdown').parent().addClass("active");
	            $('#active_events').parent().addClass("active");
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
                    showClear: true,
                    defaultDate: $.now()
                });
        $('#datetimepicker_end').datetimepicker({
                    locale: 'it',
                    format: 'DD/MM/YYYY',
                    showClear: true
                });
        $("#datetimepicker_start").on("dp.change", function (e) {
            $('#datetimepicker_end').data("DateTimePicker").minDate(e.date);
        });
        $("#datetimepicker_end").on("dp.change", function (e) {
            $('#datetimepicker_start').data("DateTimePicker").maxDate(e.date);
        });
    });


    /*$('#myButton').on('click', function () {
		var $btn = $(this).button('loading')
		$btn.button('reset')
	})*/
</script>
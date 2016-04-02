<?php
	$lock = '';

    /*  perchè la variabile non è inizializzataaaaaaaa?
    $userAppId = '';
    if(Input::get('userAppId') != null)
       $userAppId = Input::get('userAppId');
    else
       $userAppId = Session::get('userAppId');
        
    echo "userId: " . $userAppId;
    */
    
    
    $companyData = CompanyData::whereHas('company', function($query){
		    $query->whereHas('user', function($query){
                
                if(Input::get('userAppId') != null){
                
			      $query->where('id', Input::get('userAppId'));
                }
                else{
                    $query->where('id', Session::get('userAppId'));
                }
                   
		    });
	    })                 
	  ->with('company')
      ->with('developer')
	  ->first();

	if (count($companyData) > 0) {

        $companyTag = $companyData->company()->first()->download_link_tag;

        $link = StoreLink::whereHas('company', function($query) use ($companyTag){
			$query->where('download_link_tag', $companyTag);
		})->first();

        $androidLink = $link->android;
        $iosLink = $link->ios;

        $publishedApp = PublishedMobileApp::whereHas('developer', function($query) {
					$query->where('developer_id', Auth::user()->id);
					$query->orderBy('created_at', 'desc');
				})->first();

            if($publishedApp == null)
			    $publishedApp = new PublishedMobileApp;


     }


?>
@if (count($companyData) > 0)

    <div class="row">
        <div class="col-xs-2 col-xs-offset-2">
            <form method="get" action="admin/downloadCompanyData/{{Input::get('userId')}}">
                <button type="submit" class="btn btn-default" style="background: #66FF33;" data-loading-text="Attendere..." autocomplete="off">Scarica</br>contenuti</button>
            </form>
        </div>
        <div class="col-xs-2">
            <button id="sendTestPushNotificationButton" type="button" class="btn btn-default" style="background: #6699FF;" data-loading-text="Attendere..." autocomplete="off"><b>Invia</br>notifica</b></button>
        </div>
    </div>
    <hr>


    @if($companyData->company->company_status_id == 5)
    <div class="row">
		<div class="col-md-9 col-md-offset-2">
			<div class="progress">
				<div class="progress-bar progress-bar-warning" style="width: 33%">
					<span class="sr-only">33% Complete (warning)</span>
				</div>
			</div>
			<div class="btn-group btn-group-justified" role="group" aria-label="...">
				<a id="5" href="#" class="setStatusButtons btn btn-warning btn-sm disabled" role="button" data-loading-text="Salvataggio..." autocomplete="off">Abilita Modifica</a>
				<a id="3" href="#" class="setStatusButtons btn btn-success btn-sm" role="button" data-loading-text="Salvataggio..." autocomplete="off">Revisione</a>
				<a id="2" href="#" class="setStatusButtons btn btn-info btn-sm" role="button" data-loading-text="Salvataggio..." autocomplete="off">Sviluppo</a>
			</div>
		</div>
	</div>


	@elseif($companyData->company->company_status_id == 3)
	<div class="row">
        <div class="col-md-9 col-md-offset-2">
			<div class="progress">
				<div class="progress-bar progress-bar-warning" style="width: 33%">
					<span class="sr-only">33% Complete (warning)</span>
				</div>
				<div class="progress-bar progress-bar-success" style="width: 33%">
					<span class="sr-only">33% Complete (success)</span>
				</div>
			</div>
			<div class="btn-group btn-group-justified" role="group" aria-label="...">
				<a id="5" href="#" class="setStatusButtons btn btn-warning btn-sm" role="button" data-loading-text="Salvataggio..." autocomplete="off">Abilita Modifica</a>
				<a id="3" href="#" class="setStatusButtons btn btn-success btn-sm disabled" role="button" data-loading-text="Salvataggio..." autocomplete="off">Revisione</a>
				<a id="2" href="#" class="setStatusButtons btn btn-info btn-sm" role="button" data-loading-text="Salvataggio..." autocomplete="off">Sviluppo</a>
			</div>
		</div>
	</div>
	@elseif($companyData->company->company_status_id == 2)
	<div class="row">
        <div class="col-md-9 col-md-offset-2">
			<div class="progress">
				<div class="progress-bar progress-bar-warning" style="width: 33%">
					<span class="sr-only">33% Complete (warning)</span>
				</div>
				<div class="progress-bar progress-bar-success" style="width: 33%">
					<span class="sr-only">33% Complete (success)</span>
				</div>
				<div class="progress-bar progress-bar-info" style="width: 34%">
					<span class="sr-only">34% Complete (info)</span>
				</div>
			</div>
			<div class="btn-group btn-group-justified" role="group" aria-label="...">
				<a id="5" href="#" class="setStatusButtons btn btn-warning btn-sm" role="button" data-loading-text="Salvataggio..." autocomplete="off">Abilita Modifica</a>
				<a id="3" href="#" class="setStatusButtons btn btn-success btn-sm" role="button" data-loading-text="Salvataggio..." autocomplete="off">Revisione</a>
				<a id="2" href="#" class="setStatusButtons btn btn-info btn-sm disabled" role="button" data-loading-text="Salvataggio..." autocomplete="off">Sviluppo</a>
			</div>
		</div>
	</div>
	@endif




<div class="clearfix"></div>
<br>



<div class="row">
    <div class="col-xs-12" id="company_data">
        @if(Session::get('errors'))
            <div class="alert alert-danger" id="errorAlert">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h5>Sono presenti errori:</h5>
                @foreach($errors->all('<li>:message</li>') as $message)
                    {{$message}}
                @endforeach
            </div>
        @elseif(isset($success))
            <div class="alert alert-success" id="successAlert">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h5>{{$success}}</h5>
            </div>
        @endif

        <!-- Modal per modifica status -->
        <div class="modal fade" id="sendMessageOnStatusChangeModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog modal-sm" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Modifica status</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <div class="col-xs-11 col-xs-offset-1">
                            <label for="checkPending">Invia notifica email </label>
                            {{ Form::checkbox('sendNotificationMail', true, false, ['id' => 'checkSendNotificationMail']) }}
                        </div></div>

                        <div class="form-group" id="stateChangedMessage" hidden>
                            {{ Form::textarea('stateChangedMessage', null, array('class'=>'form-control','id'=>'inputStateChangedMessage', 'placeholder'=>'Inserisci qui informazioni dettagliate sul cambio di status')) }}
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Annulla</button>
                        <button id="confirmStateChangeButton" type="button" class="btn btn-primary">Conferma</button>
                    </div>
                </div>
            </div>
        </div>


        <!-- Modal per invio di notifiche push per test-->
        <div class="modal fade" id="sendTestPushNotificationModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
             <div class="modal-dialog modal-sm" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Invia notifica push per [<b>{{ $companyData->company->name }}</b>]</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            
                            <label for="developerSelector">Titolo </label>
                            {{ Form::text('testPushTitle', null, array('id' => 'testPushTitleInput', 'class'=>'form-control', 'placeholder'=>'Notifica Test')) }}
                            
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Annulla</button>
                        <button id="confirmSendTestPushNotificationButton" type="button" class="btn btn-primary">Conferma</button>
                    </div>
                </div>
            </div>
        </div>

        {{ Form::open(array('url' => 'publish_company_app', 'class' => 'form-horizontal', 'id' => 'publishAppForm')) }}

            {{ Form::hidden('userAppId', Input::get("userAppId") != null ? Input::get("userAppId") : Session::get("userAppId") ) }}

            <br>

            <label><h3>Dati applicazione&nbsp;</h3></label>
            <div class="form-group">
                <label for="inputAppName" class="col-sm-offset-1 col-sm-2 control-label"><p class="text-left">Nome applicazione</p></label>
                <div class="col-sm-8">
                            <span class="glyphicon glyphicon-info-sign" data-toggle="tooltip" data-placement="auto" title="Il nome con il quale l'applicazione verrà indicizzata negli stores." aria-hidden="true"></span>
                    {{ Form::text('app_name', $publishedApp->name != null ? $publishedApp->name : $companyData->app_name, array('class'=>'form-control count-char','id'=>'inputAppName', 'maxlength'=>'30', 'placeholder'=>'Massimo 30 caratteri...', $lock)) }}
                </div>
            </div>

            <div class="form-group">
                <label for="androidVersionCode" class="col-sm-offset-1 col-sm-2 control-label"><p class="text-left">Android Version Code</p></label>
                <div class="col-sm-8">
                    <span class="glyphicon glyphicon-info-sign" data-toggle="tooltip" data-placement="auto" title="Specificare Android Version Code dell'applicazione su Google Play." aria-hidden="true"></span>
                    {{ Form::text('android_version_code', $publishedApp->android_version_code, array('class'=>'form-control','id'=>'androidVersionCode', 'placeholder'=>'', $lock)) }}
                </div>
            </div>
            <div class="form-group">
                <label for="androidVersionNumber" class="col-sm-offset-1 col-sm-2 control-label"><p class="text-left">Android Version Number</p></label>
                <div class="col-sm-8">
                    <span class="glyphicon glyphicon-info-sign" data-toggle="tooltip" data-placement="auto" title="Specificare Android Version number dell'applicazione (es: 2.0.1)." aria-hidden="true"></span>
                    {{ Form::text('android_version_number', $publishedApp->android_version_number, array('class'=>'form-control','id'=>'androidVersionNumber', 'placeholder'=>'', $lock)) }}
                </div>
            </div>

            <div class="form-group">
                <label for="googleApiKey" class="col-sm-2 col-sm-offset-1 control-label"><p class="text-left">Google Cloud API Key</p></label>
                <div class="col-sm-8">
                    <span class="glyphicon glyphicon-info-sign" data-toggle="tooltip" data-placement="auto" title="Google API Key necessario per le notifiche push su Android." aria-hidden="true"></span>
                    {{ Form::text('google_cloud_apikey', $publishedApp->google_cloud_apikey, array('class'=>'form-control count-char','id'=>'googleApiKey', 'maxlength'=>'42', 'placeholder'=>'Massimo 42 caratteri...', $lock)) }}
                </div>
            </div>

            <!-- IOS CERT -->
            <div class="form-group">
                <label for="inputCertIos" class="col-sm-offset-1 col-sm-2 control-label"><p class="text-left">Ios Store Certificate</p></label>
                <div class="col-sm-8">
                    <span class="glyphicon glyphicon-info-sign" data-toggle="tooltip" data-placement="auto" title="Inserire il Certificato iTunes in formato PEM per le notifiche push" aria-hidden="true"></span>
                    <input id="inputCertIos" name="ios_cert_file" type="file" class="file-loading">
                </div>
            </div>

            <label><h3>App Stores&nbsp;</h3></label>
                            <span class="glyphicon glyphicon-info-sign" data-toggle="tooltip" data-placement="auto" title="Inserisci (opzionalmente) fino a 4 link di video Youtube." aria-hidden="true"></span>

            <!-- YouTube Links -->
                <div class="form-group">
                    <label class="col-sm-offset-1 col-sm-2 control-label"><p class="text-left">Google Play</p></label>
                    <div class="col-sm-8">
                        <div class="input-group">
                            <span class="input-group-btn">
                                <button class="btn btn-danger googlePlayButton" type="button">Apri</button>
                            </span>
                            {{ Form::text('googleplay_link', $link->android ? $link->android : '', array('class'=>'form-control', 'placeholder'=>'', $lock)) }}
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-offset-1 col-sm-2 control-label"><p class="text-left">Itunes App Store</p></label>
                    <div class="col-sm-8">
                        <div class="input-group">
                            <span class="input-group-btn">
                                <button class="btn btn-danger appleStoreButton" type="button">Apri</button>
                            </span>
                            {{ Form::text('applestore_link', $link->ios ? $link->ios : '', array('class'=>'form-control', 'placeholder'=>'', $lock)) }}
                        </div>
                    </div>
                </div>

            <hr>
            @if (!$lock)
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-9">
                        <p class="text-right">
                            <button type="submit" name="save" class="submitButtons btn btn-primary" data-loading-text="Salvataggio..." autocomplete="off">Salva</button>
                        </p>
                    </div>
                </div>
            @endif
        {{ Form::close() }}

    </div>
</div>

<script type="text/javascript">

/*vuol dire che si è vi è stato un cambiamento di status e 
la relativa voce nella lista utenti va aggiornata. Vedi evento "onClick"
di '.setStatusButtons'*/
@if (Input::has('updateListStatus'))

	<?php		
		if ($companyData->company->company_status_id == 5){
			$status = 'Sospeso';
			$label = 'label-warning';
		}
		else if ($companyData->company->company_status_id == 3){
			$status = 'Revisione';
			$label = 'label-success';
		}
        else if ($companyData->company->company_status_id == 4){
			$status = 'In Pubblicazione';
			$label = 'label-info';
		}
		else if ($companyData->company->company_status_id == 2){
			$status = 'Sviluppo';
			$label = 'label-info';
		}
		else if ($companyData->company->company_status_id == 1){
			$status = 'Attivo';
			$label = 'label-danger';
		}		
	?>

	var row = $('tr[id="{{Input::get("userAppId") != null ? Input::get("userAppId") : Session::get("userAppId")}}"] td:eq(2) > span');


	row.attr('class', 'label {{$label}}');
	row.text('{{$status}}');

@endif

	
	$(function () {
		$('[data-toggle="tooltip"]').tooltip({
			delay: { "show": 500, "hide": 100 }
		});
	});

	var button;

	$('button[type="submit"]').on('click', function(){
		button = $(this);
	});

	$('#publishAppForm').submit(function() {
		button.button('loading');
		var formData = new FormData(this);
		formData.append('action', button.attr('name'))
		
		$.ajax({
		    cache: false,
			data: formData,
	        contentType: false,
	        processData: false,
			type: $(this).attr('method'),
			url: $(this).attr('action'),
			success: function(response) {
	        	button.button('reset');
	        	$('#details').html(response);
			},
            error: function(output) {
                if (output.status === 401)
                    window.location.replace("{{url('/')}}");
            }
		});
		return false;
		
	});
        
        

	$('.setStatusButtons').on('click', function() {
        newStatusButton = $(this);
		newStatusButton.button('loading');
        $('#sendMessageOnStatusChangeModal').modal('toggle');

        $('#sendMessageOnStatusChangeModal').on('hidden.bs.modal', function (e) {
            newStatusButton.button('reset');
        });


        $("#checkSendNotificationMail").change(function() {
            if(this.checked){
                $("#stateChangedMessage").show();
            } else {
                $("#stateChangedMessage").hide();
            }            
        });

        $('#confirmStateChangeButton').on('click', function() {
            $('#sendMessageOnStatusChangeModal').modal('hide');
            $.ajax({ url: 'company/setStatus',
                data: {companyId: {{$companyData->company->id}}, userId: {{Input::get('userAppId') != null ? Input::get('userAppId') : Session::get('userAppId')}}, status: newStatusButton.attr('id'), sendNotificationMail:  $('#checkSendNotificationMail').prop('checked'), notificationMessage: $('#inputStateChangedMessage').val() },
                type: 'post',
                success: function(output) {
                    $.ajax({
                        url: 'console/developers.clients.publishing.form',
                        data: {userAppId: {{Input::get('userAppId') != null ? Input::get('userAppId') : Session::get('userAppId')}}, updateListStatus: true},
                        type: 'get',
                        success: function(output) {
                            newStatusButton.button('reset');
                            $('#details').html(output);
                        },
                        error: function(output) {
                            if (output.status === 401)
                                window.location.replace("{{url('/')}}");
                        }
                    });
                },
                error: function(output) {
                    if (output.status === 401)
                        window.location.replace("{{url('/')}}");
                }
            });
            return false;
        });
		
		
	});

    $('.googlePlayButton').on('click', function() {
        $(this).button('loading');

        url = $(this).parent().next().val();

        if (!url) {
            window.open('https://play.google.com/store/apps/');/*alert('Nessun link presente. Incolla l\'url di Google Play nel rispettivo campo.');*/
        } else if (!/^(https?\:\/\/)?(play\.google\.com)\/.+$/i.test(url)) {
            alert('Il link inserito non è valido.');
        } else {
            window.open(url);
        }

        $(this).button('reset');

    });

    $('.appleStoreButton').on('click', function() {
        $(this).button('loading');

        url = $(this).parent().next().val();

        if (!url) {
            window.open('http://www.apple.com/itunes/download/');/*alert('Nessun link presente. Incolla l\'url di Google Play nel rispettivo campo.');*/
        } else if (!/^(https?\:\/\/)?(www\.apple\.com|install\.diawi\.com)\/.+$/i.test(url)) {
            alert('Il link inserito non è valido.');
        } else {
            window.open(url);
        }

        $(this).button('reset');

    });

    $("#inputCertIos").fileinput({
    	language: 'it',
        uploadUrl: "{{url('/')}}/upload/companyData/iosCert", // server upload action
	    uploadAsync: false,
	    showUpload: false,
	    showRemove: false,
    	showCancel: false,
	    minFileCount: 1,
	    maxFileCount: 1,
	    uploadExtraData: {userId: {{Input::get('userAppId') != null ? Input::get('userAppId') : Session::get('userAppId')}},companyTag: '{{ $companyTag}}' },
	    append: false
	}).on("filebatchselected", function(event, files) {
	    $(".submitButtons").attr("disabled", "disabled");
	    $("#inputCertIos").fileinput("upload");
	}).on("filebatchuploadsuccess", function(event, files){
		$(".submitButtons").removeAttr("disabled");
	});

    $('#sendTestPushNotificationButton').click(function() {

        $('#sendTestPushNotificationModal').modal('toggle');


        $('#confirmSendTestPushNotificationButton').on('click', function() {

            $('#sendTestPushNotificationModal').modal('hide');

            $.ajax({
                url: '/developer/sendTestPushNotification',
                data: {
                    companyId: {{ $companyData->company->id }},
                    title: $('#testPushTitleInput').val(),
                },
                type: 'post',
                success: function(developer) {
                    alert('Notifica inviata!');
                    $.ajax({
                        url: 'console/developers.clients.activating.details',
                        data: {userId: {{Input::get('userAppId') != null ? Input::get('userAppId') : Session::get('userAppId')}}, updateListStatus: false},
                        type: 'get',
                        success: function(output) {
                            $('#details').html(output);
                        },
                        error: function(output) {
                            if (output.status === 401)
                                window.location.replace("{{url('/')}}");
                        }
                    });
                },
                error: function(output) {
                    if (output.status === 401)
                        window.location.replace("{{url('/')}}");
                }
            });
            return false;
        });
    });

	window.scrollTo(0,0);

	
</script>

@else
	
	<div class="alert alert-info" role="alert">
		Nessun contenuto disponibile per questa azienda.
	</div>
@endif
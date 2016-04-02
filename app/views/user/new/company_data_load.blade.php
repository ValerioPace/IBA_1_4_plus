<?php

	$lock = Auth::user()->company()->first()->company_status_id != 5 ? 'disabled' : '';

	$companyData = CompanyData::whereHas('company', function($query){
		$query->where('user_id', Auth::user()->id);
	})
	->with('company')
	->first();


	$companyImages = CompanyDataImage::where('company_data_id', $companyData->id)->get();

	$youtubeVideo1 = CompanyDataLink::where('company_data_id', $companyData->id)->where('type_id', 2)->where('network', 'Youtube1')->first();
	$youtubeVideo2 = CompanyDataLink::where('company_data_id', $companyData->id)->where('type_id', 2)->where('network', 'Youtube2')->first();
	$youtubeVideo3 = CompanyDataLink::where('company_data_id', $companyData->id)->where('type_id', 2)->where('network', 'Youtube3')->first();
	$youtubeVideo4 = CompanyDataLink::where('company_data_id', $companyData->id)->where('type_id', 2)->where('network', 'Youtube4')->first();

	$facebookSocial = CompanyDataLink::where('company_data_id', $companyData->id)->where('type_id', 1)->where('network', 'Facebook')->first();
	$googleSocial = CompanyDataLink::where('company_data_id', $companyData->id)->where('type_id', 1)->where('network', 'Google+')->first();
	$twitterSocial = CompanyDataLink::where('company_data_id', $companyData->id)->where('type_id', 1)->where('network', 'Twitter')->first();
	$linkedinSocial = CompanyDataLink::where('company_data_id', $companyData->id)->where('type_id', 1)->where('network', 'Linkedin')->first();


?>
<h4 class="text-center">
	La tua richiesta di attivazione è nello stato:
	@if($companyData->company->company_status_id == 5)
		<span class="label label-warning">
			in sospeso
		</span>
		&nbsp;<span class="glyphicon glyphicon-info-sign" data-toggle="tooltip" data-placement="auto" title="Devi compilare il seguente modulo e quando sarai pronto potrai inviarlo all'amministratore." aria-hidden="true"></span>
		<hr>
	</h4>
	<div class="progress">
		<div class="progress-bar progress-bar-warning" style="width: 25%">
			<span class="sr-only">25% Complete (danger)</span>
		</div>
	</div>
	@elseif($companyData->company->company_status_id == 3)
		<span class="label label-success">
			in revisione
		</span>
		&nbsp;<span class="glyphicon glyphicon-info-sign" data-toggle="tooltip" data-placement="auto" title="La richiesta è stata presa in consegna e si sta verificando la validità dei contenuti inseriti." aria-hidden="true"></span>
		<hr>
	</h4>
	<div class="progress">
		<div class="progress-bar progress-bar-warning" style="width: 25%">
			<span class="sr-only">25% Complete (danger)</span>
		</div>
		<div class="progress-bar progress-bar-success" style="width: 25%">
			<span class="sr-only">25% Complete (warning)</span>
		</div>
	</div>
	@elseif($companyData->company->company_status_id == 2)
		<span class="label label-info">
			sviluppo
		</span>
		&nbsp;<span class="glyphicon glyphicon-info-sign" data-toggle="tooltip" data-placement="auto" title="I contenuti sono stati approvati e il reparto tecnico sta sviluppando la tua app!" aria-hidden="true"></span>
		<hr>
	</h4>
	<div class="progress">
		<div class="progress-bar progress-bar-warning" style="width: 25%">
			<span class="sr-only">25% Complete (danger)</span>
		</div>
		<div class="progress-bar progress-bar-success" style="width: 25%">
			<span class="sr-only">25% Complete (warning)</span>
		</div>
		<div class="progress-bar progress-bar-info" style="width: 25%">
			<span class="sr-only">25% Complete (success)</span>
		</div>
	</div>
	@elseif($companyData->company->company_status_id == 1)
		<span class="label label-danger">
			pubblicata
		</span>
		&nbsp;<span class="glyphicon glyphicon-info-sign" data-toggle="tooltip" data-placement="auto" title="Servizio attivo!" aria-hidden="true"></span>
		<hr>
	</h4>
	<div class="progress">
		<div class="progress-bar progress-bar-warning" style="width: 25%">
			<span class="sr-only">25% Complete (danger)</span>
		</div>
		<div class="progress-bar progress-bar-success" style="width: 25%">
			<span class="sr-only">25% Complete (warning)</span>
		</div>
		<div class="progress-bar progress-bar-info" style="width: 25%">
			<span class="sr-only">25% Complete (success)</span>
		</div>
		<div class="progress-bar progress-bar-danger" style="width: 25%">
			<span class="sr-only">25% Complete</span>
		</div>
	</div>
	<!-- <hr>
	<h5 class="text-center">Effettua il <a href="{{url('/')}}" class="alert-link">Login</a></h5> -->
	@endif


<div class="clearfix"></div>
<br>


<div class="panel panel-default">
    <div class="panel-heading">Inserimento contenuti</div>
    <div class="panel-body">
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
                {{ Form::open(array('url' => 'save_company_data', 'class' => 'form-horizontal', 'id' => 'loadCompanyDataForm')) }}
                    
                	<label><h3>Dati utente&nbsp;</h3></label>
		            <span class="glyphicon glyphicon-info-sign" data-toggle="tooltip" data-placement="auto" title="Nome e cognome della persona associata a questa utenza." aria-hidden="true"></span>
                    

                   
                    <div class="form-group">
                        <label for="inputName" class="col-sm-offset-1 col-sm-2 control-label"><p class="text-left">Nome</p></label>
                        <div class="col-sm-8">
                        	{{ Form::text('name', $companyData->name, array('class'=>'form-control','id'=>'inputName', 'placeholder'=>'Il tuo nome', $lock)) }}
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputLastName" class="col-sm-offset-1 col-sm-2 control-label"><p class="text-left">Cognome</p></label>
                        <div class="col-sm-8">
                        	{{ Form::text('last_name', $companyData->last_name, array('class'=>'form-control','id'=>'inputLastName', 'placeholder'=>'Il tuo cognome', $lock)) }}
                        </div>
                    </div>

		            <label><h3>Contatti&nbsp;</h3></label>
		            <span class="glyphicon glyphicon-info-sign" data-toggle="tooltip" data-placement="auto" title="Questi dati saranno presenti nella sezione contatti della tua applicazione." aria-hidden="true"></span>
                    
		            
                    <div class="form-group">
                        <label for="inputPhone" class="col-sm-offset-1 col-sm-2 control-label"><p class="text-left">Telefono</p></label>
                        <div class="col-sm-8">
                        	{{ Form::text('phone', $companyData->phone, array('class'=>'form-control','id'=>'inputPhone', 'placeholder'=>'', $lock)) }}
                        </div>
                    </div>
                     <div class="form-group">
                        <label for="inputMobilePhone" class="col-sm-offset-1 col-sm-2 control-label"><p class="text-left">Telefono cellulare</p></label>
                        <div class="col-sm-8">
                        	{{ Form::text('mobile', $companyData->mobile, array('class'=>'form-control','id'=>'inputMobilePhone', 'placeholder'=>'', $lock)) }}
                        </div>
                    </div>

		            
                    <div class="form-group">
                        <label for="inputFax" class="col-sm-offset-1 col-sm-2 control-label"><p class="text-left">Fax</p></label>
                        <div class="col-sm-8">
                        	{{ Form::text('fax', $companyData->fax, array('class'=>'form-control','id'=>'inputFax', 'placeholder'=>'', $lock)) }}
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputAddress" class="col-sm-offset-1 col-sm-2 control-label"><p class="text-left">Indirizzo</p></label>
                        <div class="col-sm-8">
                        	{{ Form::text('address', $companyData->address, array('class'=>'form-control','id'=>'inputAddress', 'placeholder'=>'', $lock)) }}
                        </div>
                    </div>

		            
                    <div class="form-group">
                        <label for="inputEmail" class="col-sm-offset-1 col-sm-2 control-label"><p class="text-left">Email</p></label>
                        <div class="col-sm-8">
                        	{{ Form::text('email', $companyData->email, array('class'=>'form-control','id'=>'inputEmail', 'placeholder'=>'Il tuo indirizzo email', $lock)) }}
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail2" class="col-sm-offset-1 col-sm-2 control-label"><p class="text-left">Email secondaria</p></label>
                        <div class="col-sm-8">
                        	{{ Form::text('email_2', $companyData->email_2, array('class'=>'form-control','id'=>'inputEmail2', 'placeholder'=>'', $lock)) }}
                        </div>
                    </div>

		            
                    <div class="form-group">
                        <label for="inputWebSite" class="col-sm-offset-1 col-sm-2 control-label"><p class="text-left">Sito web</p></label>
                        <div class="col-sm-8">
                        	{{ Form::text('web_site', $companyData->web_site, array('class'=>'form-control','id'=>'inputWebSite', 'placeholder'=>'', $lock)) }}
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputWebSite2" class="col-sm-offset-1 col-sm-2 control-label"><p class="text-left">Sito web secondario</p></label>
                        <div class="col-sm-8">
                        	{{ Form::text('web_site_2', $companyData->web_site_2, array('class'=>'form-control','id'=>'inputWebSite2', 'placeholder'=>'', $lock)) }}
                        </div>
                    </div>
		            
		            <br>

                    <label><h3>Dati aziendali&nbsp;</h3></label>

                    <div class="form-group">
                        <label for="inputAppName" class="col-sm-offset-1 col-sm-2 control-label"><p class="text-left">Nome applicazione</p></label>
                        <div class="col-sm-8">
		                        	<span class="glyphicon glyphicon-info-sign" data-toggle="tooltip" data-placement="auto" title="Il nome con il quale l'applicazione verrà indicizzata negli stores." aria-hidden="true"></span>
                            {{ Form::text('app_name', $companyData->app_name, array('class'=>'form-control count-char','id'=>'inputAppName', 'maxlength'=>'12', 'placeholder'=>'Massimo 12 caratteri...', $lock)) }}
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="inputCompanyName" class="col-sm-offset-1 col-sm-2 control-label"><p class="text-left">Nome azienda</p></label>
                        <div class="col-sm-8">
		                        	<span class="glyphicon glyphicon-info-sign" data-toggle="tooltip" data-placement="auto" title="In caso di persona fisica indicare 'Nome' e 'Cognome'." aria-hidden="true"></span>
                            {{ Form::text('company_name', $companyData->company_name, array('class'=>'form-control count-char','id'=>'inputCompanyName', 'maxlength'=>'60', 'placeholder'=>'Massimo 60 caratteri...', $lock)) }}
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="inputPosition" class="col-sm-offset-1 col-sm-2 control-label"><p class="text-left">Titolo/Nome attività</p></label>
                        <div class="col-sm-8">
		                        	<span class="glyphicon glyphicon-info-sign" data-toggle="tooltip" data-placement="auto" title="Indicare il titolo/ruolo ricoperto all'interno dell'azienda o la tipologia dell'attività, es.: Albergo, Ristorante, Negozio, Societa, Studio, etc… Questo verrà inserito in prima pagina all'interno dell'app (a destra dell'immagine)." aria-hidden="true"></span>
                            {{ Form::text('position', $companyData->position, array('class'=>'form-control count-char','id'=>'inputPosition', 'maxlength'=>'40', 'placeholder'=>'Massimo 40 caratteri...', $lock)) }}
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="inputCompanyDescription" class="col-sm-offset-1 col-sm-2 control-label"><p class="text-left">Descrizione azienda</p></label>
                        <div class="col-sm-8">
		                        	<span class="glyphicon glyphicon-info-sign" data-toggle="tooltip" data-placement="auto" title="I primi 350 caratteri di questa descrizione saranno presenti nella pagina di presentazione dell'azienda. Sarà possibile leggere il testo completo cliccando sul pulsante 'Leggi altro', presente nell'applicazione." aria-hidden="true"></span>
                            {{ Form::textarea('company_description', $companyData->company_description, array('class'=>'form-control count-char','id'=>'inputCompanyDescription', 'maxlength'=>'1000', 'placeholder'=>'Massimo 1000 caratteri...', $lock)) }}
                        </div>
                    </div>

		                               		
                    <div class="form-group">
                        <label for="inputTopSlogan" class="col-sm-2 col-sm-offset-1 control-label"><p class="text-left">Slogan (alto)</p></label>
                        <div class="col-sm-8">
                        	<span class="glyphicon glyphicon-info-sign" data-toggle="tooltip" data-placement="auto" title="Si tratta di una frase di sintesi che sarà inserita sopra la descrizione dell'azienda (sotto il logo)." aria-hidden="true"></span>
                            {{ Form::text('top_slogan', $companyData->top_slogan, array('class'=>'form-control count-char','id'=>'inputTopSlogan', 'maxlength'=>'42', 'placeholder'=>'Massimo 42 caratteri...', $lock)) }}
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="inputBottomSlogan" class="col-sm-2 col-sm-offset-1 control-label"><p class="text-left">Slogan (basso)</p></label>
                        <div class="col-sm-8">
                        	<span class="glyphicon glyphicon-info-sign" data-toggle="tooltip" data-placement="auto" title="Si tratta di una frase di sintesi che sarà inserita sotto la descrizione dell'azienda." aria-hidden="true"></span>
                            {{ Form::text('bottom_slogan', $companyData->bottom_slogan, array('class'=>'form-control count-char','id'=>'inputBottomSlogan', 'maxlength'=>'42', 'placeholder'=>'Massimo 42 caratteri...', $lock)) }}
                        </div>
                    </div>

		            <br>
                    <hr>
		            <br>

                    
            		<!-- LOGO -->
                    <div class="form-group">
                        <label for="inputLogo" class="col-sm-offset-1 col-sm-2 control-label"><p class="text-left">Logo</p></label>
                        <div class="col-sm-8">
                        	<span class="glyphicon glyphicon-info-sign" data-toggle="tooltip" data-placement="auto" title="Il vostro brand." aria-hidden="true"></span>
                            <input id="inputLogo" name="logo" type="file" class="file-loading">
                        </div>
                    </div>
                   	<!-- COVER IMAGE -->
                    <div class="form-group">
                        <label for="inputCover" class="col-sm-offset-1 col-sm-2 control-label"><p class="text-left">Cover</p></label>
                        <div class="col-sm-8">
                        	<span class="glyphicon glyphicon-info-sign" data-toggle="tooltip" data-placement="auto" title="Immagine che verrà mostrata in prima pagina." aria-hidden="true"></span>
                            <input id="inputCover" name="cover" type="file" class="file-loading">
                        </div>
                    </div>
                   	<!-- ICON IMAGE -->
                   	<div class="form-group">
                        <label for="inputIconImage" class="col-sm-offset-1 col-sm-2 control-label"><p class="text-left">Icona</p></label>
                        <div class="col-sm-8">
		                    <span class="glyphicon glyphicon-info-sign" data-toggle="tooltip" data-placement="auto" title="Carica un immagine cha abbia il lato di almeno 512px (formati accettati PNG e JPG)." aria-hidden="true"></span>
                            <input id="inputIconImage" name="icon" type="file" class="file-loading">
                        </div>
                    </div>

		            <br>
		            
                    
                    <!-- GALLERY IMAGES -->
                    <div class="form-group">
                        <label for="inputGalleryImages" class="col-sm-offset-1 col-sm-2 control-label"><p class="text-left">Galleria immagini</p></label>
                        <div class="col-sm-8">
		                    <span class="glyphicon glyphicon-info-sign" data-toggle="tooltip" data-placement="auto" title="Carica fino a 20 immagini che saranno inserite nella galleria fotografica della tua business card." aria-hidden="true"></span>
                            <input id="inputGalleryImages" name="galleryImages[]" type="file" multiple class="file-loading">
                        </div>
                    </div>  

					<hr>

                    <label><h3>Video Youtube&nbsp;</h3></label>
		                        	<span class="glyphicon glyphicon-info-sign" data-toggle="tooltip" data-placement="auto" title="Inserisci (opzionalmente) fino a 4 link di video Youtube." aria-hidden="true"></span>

                    <!-- YouTube Links -->
                    	<div class="form-group">
		                    <label class="col-sm-offset-1 col-sm-2 control-label"><p class="text-left">Link 1</p></label>
		                    <div class="col-sm-8">                     
		                        <div class="input-group">
		                            <span class="input-group-btn">
		                                <button class="btn btn-danger youtubeButton" type="button">Apri</button>
		                            </span>
		                            {{ Form::text('youtube_video1', $youtubeVideo1 ? $youtubeVideo1->link : '', array('class'=>'form-control', 'placeholder'=>'', $lock)) }}                        
		                        </div>
		                    </div>
		                </div>
		                <div class="form-group">
		                    <label class="col-sm-offset-1 col-sm-2 control-label"><p class="text-left">Link 2</p></label>
		                    <div class="col-sm-8">                          
		                        <div class="input-group">
		                            <span class="input-group-btn">
		                                <button class="btn btn-danger youtubeButton" type="button">Apri</button>
		                            </span>
		                            {{ Form::text('youtube_video2', $youtubeVideo2 ? $youtubeVideo2->link : '', array('class'=>'form-control', 'placeholder'=>'', $lock)) }}                        
		                        </div>
		                    </div>
		                </div>
		                <div class="form-group">
		                    <label class="col-sm-offset-1 col-sm-2 control-label"><p class="text-left">Link 3</p></label>
		                    <div class="col-sm-8">                          
		                        <div class="input-group">
		                            <span class="input-group-btn">
		                                <button class="btn btn-danger youtubeButton" type="button">Apri</button>
		                            </span>
		                            {{ Form::text('youtube_video3', $youtubeVideo3 ? $youtubeVideo3->link : '', array('class'=>'form-control', 'placeholder'=>'', $lock)) }}                        
		                        </div>
		                    </div>
		                </div>
		                <div class="form-group">
		                    <label class="col-sm-offset-1 col-sm-2 control-label"><p class="text-left">Link 4</p></label>
		                    <div class="col-sm-8">                          
		                        <div class="input-group">
		                            <span class="input-group-btn">
		                                <button class="btn btn-danger youtubeButton" type="button">Apri</button>
		                            </span>
		                            {{ Form::text('youtube_video4', $youtubeVideo4 ? $youtubeVideo4->link : '', array('class'=>'form-control', 'placeholder'=>'', $lock)) }}                        
		                        </div>
		                    </div>
		                </div>

					<hr>

                    <label><h3>Social link&nbsp;</h3></label>
		            <span class="glyphicon glyphicon-info-sign" data-toggle="tooltip" data-placement="auto" title="Inserisci (opzionalmente) i link alle pagine della tua azienda dei social network più famosi." aria-hidden="true"></span>

                    <!-- SOCIALS -->
		                <div class="form-group">
		                    <label class="col-sm-offset-1 col-sm-2 control-label"><p class="text-left">Facebook</p></label>
		                    <div class="col-sm-8">                          
		                        <div class="input-group">
		                            <span class="input-group-btn">
		                                <button class="btn btn-primary socialButton" type="button">Apri</button>
		                            </span>
		                            {{ Form::text('facebook_social', $facebookSocial ? $facebookSocial->link : '', array('class'=>'form-control', 'placeholder'=>'', $lock)) }}                       
		                        </div>
		                    </div>
		                </div>
		                <div class="form-group">
		                    <label class="col-sm-offset-1 col-sm-2 control-label"><p class="text-left">Google+</p></label>
		                    <div class="col-sm-8">                          
		                        <div class="input-group">
		                            <span class="input-group-btn">
		                                <button class="btn btn-primary socialButton" type="button">Apri</button>
		                            </span>
		                            {{ Form::text('google_social', $googleSocial ? $googleSocial->link : '', array('class'=>'form-control', 'placeholder'=>'', $lock)) }}                        
		                        </div>
		                    </div>
		                </div>
		                <div class="form-group">
		                    <label class="col-sm-offset-1 col-sm-2 control-label"><p class="text-left">Twitter</p></label>
		                    <div class="col-sm-8">                          
		                        <div class="input-group">
		                            <span class="input-group-btn">
		                                <button class="btn btn-primary socialButton" type="button">Apri</button>
		                            </span>
		                            {{ Form::text('twitter_social', $twitterSocial ? $twitterSocial->link : '', array('class'=>'form-control', 'placeholder'=>'', $lock)) }}                       
		                        </div>
		                    </div>
		                </div>
		                <div class="form-group">
		                    <label class="col-sm-offset-1 col-sm-2 control-label"><p class="text-left">Linkedin</p></label>
		                    <div class="col-sm-8">                          
		                        <div class="input-group">
		                            <span class="input-group-btn">
		                                <button class="btn btn-primary socialButton" type="button">Apri</button>
		                            </span>
		                            {{ Form::text('linkedin_social', $linkedinSocial ? $linkedinSocial->link : '', array('class'=>'form-control', 'placeholder'=>'', $lock)) }}                        
		                        </div>
		                    </div>
		                </div>
		            <!-- <div class="row">
                    	<div class="col-xs-5 col-xs-offset-1">                    		
		                    <div class="form-group">
		                        <div class="embed-responsive embed-responsive-16by9">
									<iframe class="embed-responsive-item" src="//www.youtube.com/embed/ePbKGoIGAXY" frameborder="0" allowfullscreen></iframe>
								</div>
		                    </div>
                    	</div>
                    	<div class="col-xs-5">
		                    <div class="form-group">
		                        <div class="embed-responsive embed-responsive-16by9">
									<iframe class="embed-responsive-item" src="https://www.youtube.com/embed/zpOULjyy-n8"></iframe>
								</div>		                        
		                    </div>
		                </div>
		            </div> --> 

					<hr>

                    <label><h3>Note&nbsp;</h3></label>
		            <span class="glyphicon glyphicon-info-sign" data-toggle="tooltip" data-placement="auto" title="Campo libero per l'inserimento di note aggiuntive per il reparto tecnico." aria-hidden="true"></span>


                    <div class="form-group">
                        <label for="inputNote" class="col-sm-offset-1 col-sm-2 control-label"><p class="text-left"></p></label>
                        <div class="col-sm-8">
		                    {{ Form::textarea('note', $companyData->note, array('class'=>'form-control count-char','id'=>'inputNote', 'maxlength'=>'500', 'placeholder'=>'Massimo 500 caratteri...', $lock)) }}
                        </div>
                    </div>
                    

                    <hr>
                    

                    
		            @if (!$lock)
	                    <div class="form-group">
	                        <div class="col-sm-offset-2 col-sm-9">
	                            <p class="text-right">
								  	<button type="submit" name="save" class="submitButtons btn btn-primary" data-loading-text="Salvataggio..." autocomplete="off">Salva</button>
								  	<button type="submit" name="confirm" class="submitButtons btn btn-default btn-danger" data-loading-text="Invio..." autocomplete="off">Salva e Invia</button>
	                            </p>
	                        </div>
	                    </div>
	                @endif
                {{ Form::close() }}

            </div>
        </div>
    </div>
</div>

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
		//$('#action_button').attr('value', button.attr('name'));
	});

	$('#loadCompanyDataForm').submit(function() {
		button.button('loading');
		var formData = new FormData(this);
		formData.append('action', button.attr('name'))
		/*alert(formData.get('name'));*/
		if (button.attr('name') == 'confirm'){
			if (!confirm("Una volta inviato il modulo non avrai più modo di modificare i dati inseriti, confermi?")){
				button.button('reset');
				return false;
			}
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
	        	$('#content').html(response);
			},
			error: function(output) {
				if (output.status === 401)
					window.location.replace("{{url('/')}}");
			}
		});
		return false;
		
	});



    $('.youtubeButton').on('click', function() {
        $(this).button('loading');

        url = $(this).parent().next().val();
        
        if (!url) {
            window.open('https://www.youtube.com/');/*alert('Nessun link presente. Incolla l\'url del video nel rispettivo campo.');*/
        } else if (!/^(https?\:\/\/)?(www\.youtube\.com)\/.+$/i.test(url)) {
            alert('Il link inserito non è valido.');
        } else {
            window.open(url);
        }
        
        $(this).button('reset');
        
    });

    $('.socialButton').on('click', function() {
        $(this).button('loading');

        socialName = $(this).parent().next().attr('name');
        url = $(this).parent().next().val();

        switch(socialName) {
        	case 'facebook_social':
	        	regex = /^(http\:\/\/|https\:\/\/)?(?:www\.)?facebook\.com\/(?:(?:\w\.)*#!\/)?(?:pages\/)?(?:[\w\-\.]*\/)*([\w\-\.]*)/;
	        	defaultUrl = 'https://www.facebook.com/';
	        	break;
        	case 'google_social':
	        	regex = /((http|https):\/\/)?(www[.])?plus\.google\.com\/.?\/?.?\/?([0-9]*)/;
	        	defaultUrl = 'https://plus.google.com/';
	        	break;
        	case 'twitter_social':
	        	regex = /http(s)?:\/\/twitter\.com\/(#!\/)?[a-zA-Z0-9_]+/;
	        	defaultUrl = 'https://twitter.com/';
	        	break;
        	case 'linkedin_social':
	        	regex = /^(http(s)?:\/\/)?([\w]+\.)?linkedin\.com\/(pub|in|profile)/;
	        	defaultUrl = 'https://www.linkedin.com/';
	        	break;
        	default:
        		return;
        }


        if (!url) {
            window.open(defaultUrl);
        } else if (!regex.test(url)) {
            alert('Il link inserito non è valido.');
        } else {
            window.open(url);
        }
        
        $(this).button('reset');
        
    });

	

    $("#inputGalleryImages").fileinput({
    	@if ($lock)
    		initialPreviewShowDelete: false,
    	@endif
    	language: 'it',
        uploadUrl: "{{url('/')}}/upload/companyImages/gallery", // server upload action
	    uploadAsync: false,
	    showUpload: false,
	    showRemove: false,
    	showCancel: false,
	    minFileCount: 1,
	    maxFileCount: 20,
	    initialPreview: [
	    	@foreach ($companyImages as $image)
	        	"<img src='{{url('/')}}/companyImages/gallery/full_size/by_id/"+ {{$image->id}} + "' class='file-preview-image' alt=' ' title=' '>",
	        @endforeach
	    ],
	    initialPreviewConfig: [
	    	@foreach ($companyImages as $image)
	        	{
	        		url: "{{url('/')}}/delete/companyImages/gallery",
	        		key: {{$image->id}}
	        	},
	        @endforeach		        
	    ],
	    append: true
	}).on("filebatchselected", function(event, files) {
	    // trigger upload method immediately after files are selected
	    $(".submitButtons").attr("disabled", "disabled");
	    $("#inputGalleryImages").fileinput("upload");
	}).on("filebatchuploadsuccess", function(event, files){
		$(".submitButtons").removeAttr("disabled");
	});

    $("#inputCover").fileinput({
    	@if ($lock)
    		initialPreviewShowDelete: false,
    	@endif
    	language: 'it',
        uploadUrl: "{{url('/')}}/upload/companyImages/cover", // server upload action
	    uploadAsync: false,
	    showUpload: false,
	    showRemove: false,
    	showCancel: false,
	    minFileCount: 1,
	    maxFileCount: 1,
	    @if ($companyData->cover_image)
		    initialPreview: [	    	
		        	"<img src='{{url('/')}}/companyImages/cover/full_size/by_id/"+ {{$companyData->id}} + "?" + (new Date).getTime() + "' class='file-preview-image' alt=' ' title='cover'>"
		    ],
		    initialPreviewConfig: [
		        {
		        	caption: 'Cover',
		        	url: "{{url('/')}}/delete/companyImages/cover",
		        	key: {{$companyData->id}}
		        }
		    ],
		@endif	        
	    append: false
	}).on("filebatchselected", function(event, files) {
	    $(".submitButtons").attr("disabled", "disabled");
	    $("#inputCover").fileinput("upload");
	}).on("filebatchuploadsuccess", function(event, files){
		$(".submitButtons").removeAttr("disabled");
	});

    $("#inputLogo").fileinput({
    	@if ($lock)
    		initialPreviewShowDelete: false,
    	@endif
    	language: 'it',
    	/*allowedFileTypes: ['image'],*/
        uploadUrl: "{{url('/')}}/upload/companyImages/logo", // server upload action
	    uploadAsync: false,
	    showUpload: false,
	    showRemove: false,
    	showCancel: false,
	    minFileCount: 1,
	    maxFileCount: 1,
	    @if ($companyData->logo)
		    initialPreview: [	    	
		        	"<img src='{{url('/')}}/companyImages/logo/full_size/by_id/"+ {{$companyData->id}} + "?" + (new Date).getTime() + "' class='file-preview-image' alt=' ' title='logo'>"
		    ],
		    initialPreviewConfig: [
		        {
		        	caption: 'Logo',
		        	url: "{{url('/')}}/delete/companyImages/logo",
		        	key: {{$companyData->id}}
		        }
		    ],
		@endif	        
	    append: false
	}).on("filebatchselected", function(event, files) {
	    $(".submitButtons").attr("disabled", "disabled");
	    $("#inputLogo").fileinput("upload");
	}).on("filebatchuploadsuccess", function(event, files){
		$(".submitButtons").removeAttr("disabled");
	});

    $("#inputIconImage").fileinput({
    	@if ($lock)
    		initialPreviewShowDelete: false,
    	@endif
    	language: 'it',
    	/*allowedFileTypes: ['image'],*/
        uploadUrl: "{{url('/')}}/upload/companyImages/icon", // server upload action
	    uploadAsync: false,
	    showUpload: false,
	    showRemove: false,
    	showCancel: false,
	    minFileCount: 1,
	    maxFileCount: 1,
        minImageWidth: 512,
        msgFilesTooLess: 'Devi inserire almeno 1 immagine. Verifica che la dimensione del lato sia di almeno 512px.',
	    @if ($companyData->icon_image)
		    initialPreview: [	    	
		        	"<img src='{{url('/')}}/companyImages/icon/full_size/by_id/"+ {{$companyData->id}} + "?" + (new Date).getTime() + "' class='file-preview-image' alt=' ' title='icon'>"
		    ],
		    initialPreviewConfig: [
		        {
		        	caption: 'Icona',
		        	url: "{{url('/')}}/delete/companyImages/icon",
		        	key: {{$companyData->id}}
		        }
		    ],
		@endif	        
	    append: false
	}).on("filebatchselected", function(event, files) {
	    $(".submitButtons").attr("disabled", "disabled");
	    $("#inputIconImage").fileinput("upload");
	}).on("filebatchuploadsuccess", function(event, files){
		$(".submitButtons").removeAttr("disabled");
	});

	@if ($lock)
		$("#inputLogo").fileinput('lock');
		$("#inputGalleryImages").fileinput('lock');
		$("#inputCover").fileinput('lock');
		$("#inputIconImage").fileinput('lock');
	@endif

	window.scrollTo(0,0);

	
</script>
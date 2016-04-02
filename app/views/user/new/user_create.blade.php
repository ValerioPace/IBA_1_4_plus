<div class="panel panel-default">
    <div class="panel-heading">Attivazione servizio</div>
    <div class="panel-body">
        <div class="row">
            <div class="col-xs-12 col-md-6 col-md-offset-3" id="company_data">
                @if(Session::get('errors'))
                    <div class="alert alert-danger" id="configErrorAlert">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h5>Sono presenti errori:</h5>
                        @foreach($errors->all('<li>:message</li>') as $message)
                            {{$message}}
                        @endforeach
                    </div>
                @endif
                {{ Form::open(array('url' => 'do_create_user', 'class' => 'form-horizontal', 'id' => 'createUserForm')) }}
                    
                    <div class="list-group-item">
                        <div class="form-group">
                            <label for="inputCode" class="col-sm-4 control-label"><p class="text-left">Codice di attivazione</p></label>
                            <div class="col-sm-8">
                                {{ Form::text('code', '', array('class'=>'form-control','id'=>'inputCode', 'autofocus', 'placeholder'=>'Digita il codice')) }}
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <br>
                    <div class="form-group">
                        <label for="inputName" class="col-sm-4 control-label"><p class="text-left">Nome</p></label>
                        <div class="col-sm-8">
                            {{ Form::text('name', '', array('class'=>'form-control','id'=>'inputName', 'placeholder'=>'Il tuo nome')) }}
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputLastName" class="col-sm-4 control-label"><p class="text-left">Cognome</p></label>
                        <div class="col-sm-8">
                            {{ Form::text('last_name', '', array('class'=>'form-control','id'=>'inputLastName', 'placeholder'=>'Il tuo cognome')) }}
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputPhone" class="col-sm-4 control-label"><p class="text-left">Telefono</p></label>
                        <div class="col-sm-8">
                            {{ Form::text('phone', '', array('class'=>'form-control','id'=>'inputPhone', 'placeholder'=>'Un recapito telefonico')) }}
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail" class="col-sm-4 control-label"><p class="text-left">Email</p></label>
                        <div class="col-sm-8">
                            {{ Form::text('email', '', array('class'=>'form-control','id'=>'inputEmail', 'placeholder'=>'Il tuo indirizzo email')) }}
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <br>
                    <div class="form-group">
                        <label for="inputUserName" class="col-sm-4 control-label"><p class="text-left">Nome utente</p></label>
                        <div class="col-sm-8">
                            {{ Form::text('user_name', '', array('class'=>'form-control','id'=>'inputUserName', 'placeholder'=>'Scegli un nome utente')) }}
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputPassword" class="col-sm-4 control-label"><p class="text-left">Scegli una password</p></label>
                        <div class="col-sm-8">
                            {{ Form::password('password', array('class'=>'form-control','id'=>'inputPassword', 'placeholder'=>'Minimo 8 caratteri')) }}
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputPasswordConfirm" class="col-sm-4 control-label"><p class="text-left">Ripeti la password</p></label>
                        <div class="col-sm-8">
                            {{ Form::password('password_confirmation', array('class'=>'form-control','id'=>'inputPasswordConfirm', 'placeholder'=>'Digita nuovamente la password')) }}
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-offset-4 col-sm-8">
                            <p class="text-right">
                                {{ Form::submit('Registra', array('class'=>'btn btn-primary')) }}
                            </p>
                        </div>
                    </div>
                {{ Form::close() }}

            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $('#createUserForm').submit(function() {
        var formData = new FormData(this);
        $.ajax({
            data: formData,
            contentType: false,
            processData: false,
            type: $(this).attr('method'),
            url: $(this).attr('action'),
            success: function(response) {
                $('#content').html(response);
            },
            error: function(output) {
                
            }
        });
        return false;
    });
</script>
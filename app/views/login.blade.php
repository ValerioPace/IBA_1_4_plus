<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="images/logo.ico">

    <title>IBA</title>

    <!-- Bootstrap core CSS -->
    <link href="//maxcdn.bootstrapcdn.com/bootswatch/3.3.5/spacelab/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">
    <link href="dist/css/signin.css" rel="stylesheet">

    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>

</head>

<body>
    <div class="container" style="margin-top:40px">
        <div class="row">
            <div class="col-sm-6 col-md-4 col-md-offset-4">
                @if(Session::get('error'))
                <div class="alert alert-danger">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h5>Errore:</h5>
                    {{Session::get('error')}}
                </div>
                @elseif(Session::get('errors'))
                <div class="alert alert-danger">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h5>Sono presenti errori:</h5>
                    @foreach($errors->all('<li>:message</li>') as $message)
                    {{$message}}
                    @endforeach
                </div>
                @elseif(Session::get('success'))
                <div class="alert alert-success" id="successAlert">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h5>{{Session::get('success')}}</h5>
                </div>
                @endif

                <!-- Modal -->
                <div class="modal fade" id="passwordResetModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                    <div class="modal-dialog modal-sm" role="document">
                        <div class="modal-content">
                            {{ Form::open(array('url' => 'reset_password', 'method' => 'POST', 'class' => 'form-horizontal')) }}
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="myModalLabel">Reset password</h4>
                            </div>
                            <div class="modal-body">
                                <em>Confermando invieremo le nuove credenziali all'indirizzo mail specificato.</em>
                                <hr>
                                <div class="form-group">
                                    <label for="inputEmail">Indirizzo email: </label>
                                    {{ Form::text('email', null, array('class'=>'form-control','id'=>'inputEmail')) }}                                
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Annulla</button>
                                {{ Form::submit('Conferma', array('id' => 'confirmEmailResetButton', 'class'=>'submitButtons btn btn-primary')) }}
                                <!-- <button id="confirmEmailResetButton" type="submit" class="submitButtons btn btn-primary" autocomplete="off">Conferma</button> -->
                            </div>
                            {{ Form::close() }}
                        </div>
                    </div>
                </div>


                <div class="panel panel-default">
                    <div class="panel-heading">
                        <strong> Accedi alla console</strong>
                    </div>
                    <div class="panel-body">
                        {{ Form::open(array('url'=>'', 'class' => 'form-signin')) }}
                        <fieldset>
                            <div class="row">
                                <div class="center-block">
                                    <img class="profile-img" src="images/logo.png" alt="">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12 col-md-10  col-md-offset-1 ">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="glyphicon glyphicon-user"></i>
                                            </span> 
                                            {{ Form::text('user_name', null, array('class'=>'form-control','id'=>'inputUserName', 'autofocus', 'placeholder'=>'User Name')) }}
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="glyphicon glyphicon-lock"></i>
                                            </span>
                                            {{ Form::password('password', array('class'=>'form-control', 'id'=>'inputPassword', 'placeholder'=>'Password')) }}
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        {{ Form::submit('Accedi', array('class'=>'btn btn-lg btn-primary btn-block')) }}
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                        {{ Form::close() }}
                    </div>
                    <div class="panel-footer-new">
                        Sei in possesso di una licenza? <a href="{{url('/')}}/createUser" onClick=""> Attiva il servizio! </a>
                    </div>
                    <hr>
                    <div class="panel-footer-pswreset">
                        <a href="#" onClick="" id="passwordResetLink"> Password dimenticata? </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">

       $('#passwordResetLink').click(function() {
            
            $('#passwordResetModal').modal('toggle');


            $('#confirmEmailResetButton').on('click', function() {

                $('#passwordResetModal').modal('hide');

            });

            $('#passwordResetModal').on('hidden.bs.modal', function (e) {
                $('#inputEmail').val('');
            });
        });
        
    </script>
</body>
</html>
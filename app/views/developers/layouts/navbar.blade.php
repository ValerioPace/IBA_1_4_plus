@extends('layout.admin')

@section('navbar')

  <?php

    $user = Auth::user();
    
  ?>
  <nav class="navbar navbar-default navbar-fixed-top">
    <div class="container">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="#">
          <img style="max-height:48px; margin-top: -15px;" alt="IBA" src="images/logo3_trasp.png">
        </a>
        <!-- <a class="navbar-brand" href="#">IBA</a> -->
      </div>
      <div id="navbar" class="navbar-collapse collapse">
        <ul class="nav navbar-nav">
          <li><a href="#" id="home" class="main-bar">Home</a></li>
          <li class="active"><a href="#" id="activationProc" class="main-bar">Procedure di attivazione</a></li>
		  <li><a href="#" id="publishingProc" class="main-bar">Procedure di pubblicazione</a></li>
          <!-- <li class="dropdown">
            <a href="#" class="dropdown-toggle main-bar" data-toggle="dropdown" aria-expanded="false" id="clientDropdown">Clienti <span class="caret"></span></a>
            <ul class="dropdown-menu" role="menu">
              <li><a href="#" id="activationProc">Procedure di attivazione</a></li>
              <li class="divider"></li>
              <li><a href="#" id="activeClients">Clienti attivi</a></li>
            </ul>
          </li> -->
          </ul>

          <ul class="nav navbar-nav navbar-right">
            <li><a href="#" id="settings" class="main-bar"><span class="glyphicon glyphicon-user" aria-hidden="true"></span>{{' '.$user->name}}</a></li>
            <li><a href="#" id="logout" class="main-bar">Logout</a></li>
          </ul>
      </div>
    </div>
  </nav>
  <div class="container" id="content">
    @yield('page')
  </div>
  <script type="text/javascript">
    
    $('#home').click(function() {
      $.ajax({ url: 'console/developers.home',
         data: {page: 'home'},
         type: 'post',
         success: function(output) {
           $('#content').html(output);
          },
          error: function(output) {
            if (output.status === 401)
              window.location.replace("{{url('/')}}");
          }
      });
    });
    $('#activationProc').click(function() {
      $.ajax({ url: 'console/developers.clients.activating.container',
         data: {page: 'activation_procedures'},
         type: 'post',
         success: function(output) {
           $('#content').html(output);
          },
          error: function(output) {
            if (output.status === 401)
              window.location.replace("{{url('/')}}");
          }
      });
    });
	$('#publishingProc').click(function() {
      $.ajax({ url: 'console/developers.clients.publishing.container',
         data: {page: 'publishing_procedures'},
         type: 'post',
         success: function(output) {
           $('#content').html(output);
          },
          error: function(output) {
            if (output.status === 401)
              window.location.replace("{{url('/')}}");
          }
      });
    });
    $('#settings').click(function() {
      $.ajax({ url: 'console/developers.settings.container',
         data: {page: 'settings'},
         type: 'post',
         success: function(output) {
           $('#content').html(output);
          },
          error: function(output) {
            if (output.status === 401)
              window.location.replace("{{url('/')}}");
          }
      });
    });

    $('#logout').click(function() {
      $.ajax({ url: 'do_logout',
         data: {page: 'logout'},
         type: 'post',
         success: function(output) {
          window.location.replace("{{url('/')}}");
          }
      });
    });

    $(document).on('click','.navbar-collapse.in',function(e) {
        if( ($(e.target).is('a') || $(e.target).is('span')) && $(e.target).attr('class') != 'dropdown-toggle' ) {
            $(this).collapse('hide');
        }
    });

    $(".nav a").on("click", function(){
      if ($(this).attr('id') != 'clientDropdown') {
        if ($(this).attr('id') == 'activationProc') {
          $(".nav").find(".active").removeClass("active");
          $('#clientDropdown').parent().addClass("active");
          $(this).parent().addClass("active");
        } else {
          $(".nav").find(".active").removeClass("active");
          $(this).parent().addClass("active");
        }
      }
    });


    $('#cookie_info').click(function() {
      $.ajax({ url: 'console/cookie_info',
         data: {page: 'cookie_info'},
         type: 'post',
         success: function(output) {
           $('#content').html(output);
          }
      });
    });
  </script>
@stop
@extends('layout.main')

@section('navbar')

  <?php

    $company = Company::whereHas('user', function ($query) {
      $query->where('id', Auth::user()->id);
    })->first();
    
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
          <li class="active"><a href="#" id="home" class="main-bar">Home</a></li>
          <li class="dropdown">
            <a href="#" class="dropdown-toggle main-bar" data-toggle="dropdown" aria-expanded="false" id="eventDropdown">Eventi <span class="caret"></span></a>
            <ul class="dropdown-menu" role="menu">
              <li><a href="#" id="new_event">Nuovo Evento</a></li>
              <li class="divider"></li>
              <li><a href="#" id="active_events">Eventi Attivi</a></li>
              <li><a href="#" id="deleted_events">Storico</a></li>
            </ul>
          </li>
          @if ($company->has_ecommerce)
          <li class="dropdown">
            <a href="#" class="dropdown-toggle main-bar" data-toggle="dropdown" aria-expanded="false" id="cartDropdown"><span class="glyphicon glyphicon-shopping-cart" data-placement="auto" aria-hidden="true"></a>
            <ul class="dropdown-menu" role="menu">
              <li><a href="#" id="cart_orders">Ordini</a></li>
              <li><a href="#" id="cart_catalogue">Catalogo</a></li>
              <li><a href="#" id="cart_products">Prodotti</a></li>
              <li><a href="#" id="cart_users">Utenti</a></li>
            </ul>
          </li>
          @endif
          <li><a href="#" id="statics" class="main-bar"><span class="glyphicon glyphicon-stats" aria-hidden="true"></span></a></li>
        </ul>

        <ul class="nav navbar-nav navbar-right">
          <!-- <li><a href="#" id="settings" class="main-bar">Configurazione</a></li> -->
          <li><a href="#" id="settings" class="main-bar"><span class="glyphicon glyphicon-user" aria-hidden="true"></span>{{' '.$company->name}}</a></li>
          <li><a href="#" id="logout" class="main-bar">Logout</a></li>
        </ul>
      </div>
    </div>
  </nav>
  <input type="hidden" id="companyId" value="{{$company->id}}">
  <div class="container" id="content">
    @yield('page')
  </div>
    <script type="text/javascript">

      $(function () {
        $('[data-toggle="tooltip"]').tooltip({
          delay: { "show": 700, "hide": 100 }
        });
      });

      /*$(document).ready(function() {
        $.ajax({ url: 'console/home',
           data: {page: 'home'},
           type: 'post',
           success: function(output) {
             $('#content').html(output);
            }
        });
      });*/

      $('#home').click(function() {
        $.ajax({ url: 'console/user.active.home',
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
      $('#new_event').click(function() {
        $.ajax({ url: 'console/user.active.events.new_event',
           data: {page: 'new_event'},
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
      $('#active_events').click(function() {
        $.ajax({ url: 'console/user.active.events.active.container',
           data: {page: 'active_events'},
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
      $('#deleted_events').click(function() {
        $.ajax({ url: 'console/user.active.events.expired.container',
           data: {page: 'deleted_events'},
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
      $('#cart_orders').click(function() {
        $.ajax({ url: 'console/user.active.ecommerce.cart_orders',
           data: {page: 'cart_orders'},
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
      $('#cart_catalogue').click(function() {
        $.ajax({ url: 'console/user.active.ecommerce.cart_catalogue',
           data: {page: 'cart_catalogue'},
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
      $('#cart_products').click(function() {
        $.ajax({ url: 'console/user.active.ecommerce.cart_products',
           data: {page: 'cart_products'},
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
      $('#cart_users').click(function() {
        $.ajax({ url: 'console/user.active.ecommerce.cart_users',
           data: {page: 'cart_users'},
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
      $('#statics').click(function() {
        $.ajax({ url: 'console/user.active.statics',
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
      $('#settings').click(function() {
        $.ajax({ url: 'console/user.active.settings.container',
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
      /*$('#appCount').click(function() {
        $.ajax({ url: 'console/appCount/reload',
           data: {page: 'appCount'},
           type: 'post',
           success: function(output) {
              $('#appCount').html(output);
            }
        });
      });*/
      $(document).on('click','.navbar-collapse.in',function(e) {
          if( ($(e.target).is('a') || $(e.target).is('span')) && $(e.target).attr('class') != 'dropdown-toggle' ) {
              $(this).collapse('hide');
          }
      });

      $(".nav a").on("click", function(){
        if ($(this).attr('id') != 'eventDropdown' && $(this).attr('id') != 'cartDropdown') {
          if ($(this).attr('id') == 'new_event' || $(this).attr('id') == 'active_events' || $(this).attr('id') == 'deleted_events') {
            $(".nav").find(".active").removeClass("active");
            $('#eventDropdown').parent().addClass("active");
            $(this).parent().addClass("active");
            //$(this).collapse('hide');
          } else {
            $(".nav").find(".active").removeClass("active");
            $(this).parent().addClass("active");
            //$(this).collapse('hide');
          }
        }
      });

      $(".nav a").on("click", function(){
        if ($(this).attr('id') != 'cartDropdown' && $(this).attr('id') != 'eventDropdown') {
          if ($(this).attr('id') == 'cart_orders' || $(this).attr('id') == 'cart_catalogue' || $(this).attr('id') == 'cart_products' || $(this).attr('id') == 'cart_users') {
            $(".nav").find(".active").removeClass("active");
            $('#cartDropdown').parent().addClass("active");
            $(this).parent().addClass("active");
            //$(this).collapse('hide');
          } else {
            $(".nav").find(".active").removeClass("active");
            $(this).parent().addClass("active");
            //$(this).collapse('hide');
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
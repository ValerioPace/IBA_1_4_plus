@extends('layout.new_user')

@section('navbar')

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
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav navbar-right">
                <li><a href="#" id="logout" class="main-bar">Logout</a></li>
            </ul>
        </div>
    </div>
  </nav>

  <div class="container" id="content">
    @yield('page')
  </div>

  <script type="text/javascript">


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
        if ($(this).attr('id') != 'eventDropdown') {
            if ($(this).attr('id') == 'new_event' || $(this).attr('id') == 'active_events' || $(this).attr('id') == 'deleted_events') {
                $(".nav").find(".active").removeClass("active");
                $('#eventDropdown').parent().addClass("active");
                $(this).parent().addClass("active");
            } else {
                $(".nav").find(".active").removeClass("active");
                $(this).parent().addClass("active");
            }
        }
    });

  </script>
@stop
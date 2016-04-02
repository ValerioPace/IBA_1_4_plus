
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="images/logo.ico">

    <title>IBA</title>

    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
    <script src="dist/js/fileinput.min.js"></script>
    <script src="dist/js/fileinput_locale_it.js"></script>
    <script src="dist/js/bootstrap-maxlength.min.js"></script>
    <link href="https://maxcdn.bootstrapcdn.com/bootswatch/3.3.5/spacelab/bootstrap.min.css" rel="stylesheet">
    <link href="dist/css/navbar-fixed-top.css" rel="stylesheet">
    <link href="dist/css/footer.css" rel="stylesheet">
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">
    <link href="dist/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css" />
  </head>

  <body>
    <div id="wrap">

        <!-- <div class="CoverImage" style="background-image:url(http://placeimg.com/1000/20/any)"></div> -->
        <!-- <img src="http://placeimg.com/1000/80/any" style="position: relative; top: 0px;"> -->
        @yield('navbar')

      <div id="push"></div>
    </div>

        <div id="footer">
          <div class="container">
            <p class="muted credit text-center" style="padding:10px;">Copyright <span class="glyphicon glyphicon-copyright-mark" aria-hidden="true"></span> 2015 Ermes Italia s.r.l.</p>
          </div>
        </div>
        @include('cookie_info')
        
<script type="text/javascript">
        // Creare's 'Implied Consent' EU Cookie Law Banner v:2.4
        // Conceived by Robert Kent, James Bavington & Tom Foyster
        // Modified by Simon Freytag for syntax, namespace, jQuery and Bootstrap

        C = {
            // Number of days before the cookie expires, and the banner reappears
            cookieDuration : 3650,

            // Name of our cookie
            cookieName: 'complianceCookie',

            // Value of cookie
            cookieValue: 'on',

            // Message banner title
            //bannerTitle: "cookie:",

            // Message banner message
            bannerMessage: "Questo sito utilizza i <strong>cookie</strong>, se vuoi saperne di più ",

            // Message banner dismiss button
            bannerButton: "Chiudi",

            // Link text
            bannerLinkText: "leggi l'infomativa",

            createDiv: function () {
                var banner = $(
                    '<div class="alert alert-info alert-dismissible fade in" ' +
                    'role="alert" style="position: fixed; bottom: 0; width: 100%; ' +
                    'margin-bottom: 0">' + this.bannerMessage + ' <a href="#" data-toggle="modal" data-target=".bs-example-modal-lg">' +
                    this.bannerLinkText + '</a>.&nbsp;&nbsp;<button type="button" class="btn ' +
                    'btn-primary" onclick="C.createCookie(C.cookieName, C.cookieValue' +
                    ', C.cookieDuration)" data-dismiss="alert" aria-label="Close">' +
                    this.bannerButton + '</button></div>'
                )
                $("body").append(banner)
            },

            createCookie: function(name, value, days) {
                console.log("Create cookie")
                var expires = ""
                if (days) {
                    var date = new Date()
                    date.setTime(date.getTime() + (days*24*60*60*1000))
                    expires = "; expires=" + date.toGMTString()
                }
                document.cookie = name + "=" + value + expires + "; path=/";
            },

            checkCookie: function(name) {
                var nameEQ = name + "="
                var ca = document.cookie.split(';')
                for(var i = 0; i < ca.length; i++) {
                    var c = ca[i]
                    while (c.charAt(0)==' ')
                        c = c.substring(1, c.length)
                    if (c.indexOf(nameEQ) == 0) 
                        return c.substring(nameEQ.length, c.length)
                }
                return null
            },

            init: function() {
                if (this.checkCookie(this.cookieName) != this.cookieValue)
                    this.createDiv()
                else
                    this.createCookie(C.cookieName, C.cookieValue, C.cookieDuration)
            }
        }

        $(document).ready(function() {
            C.init()
        })

</script>

  </body>
</html>
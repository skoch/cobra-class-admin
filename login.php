<?php

    if( $_COOKIE['username'] )
    {
        header( 'Location: http://skoch.local/cobra-admin' );
    }

    $mongo  = new Mongo();
    $db = $mongo->cobra_classes;
    $collection = $db->content;

    if( ! session_id() )
    {
        session_name( "CobraClub-Admin" );
        session_start();
    }

    $session_id = session_id();

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Cobra Club Yoga Classes</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <style>
      body {
        padding-top: 60px; /* 60px to make the container go all the way to the bottom of the topbar */
      }
    </style>
    <link href="css/bootstrap-responsive.min.css" rel="stylesheet">

    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <!-- Le fav and touch icons -->
    <link rel="shortcut icon" href="ico/favicon.ico">
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="ico/apple-touch-icon-57-precomposed.png">
  </head>

  <body>

    <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <a class="brand" href="./">Cobra Club Yoga Classes</a>
          <!-- <div class="nav-collapse collapse">
            <ul class="nav">
              <li><a href="./">Home</a></li>
              <li class="active"><a id="logout-btn" href="#">Logout</a></li>
            </ul>
          </div> --><!--/.nav-collapse -->
        </div>
      </div>
    </div>

    <div class="container">

        <div class="row">
            <div class="span4 offset4 well">
                <legend>Please Sign In</legend>
                <!-- alert goes here on failure -->
                <div id="alert-container"></div>
                <form method="POST" action="" accept-charset="UTF-8" id="login-form">
                    <input type="text" id="username" class="span4" name="username" placeholder="Username">
                    <input type="password" id="password" class="span4" name="password" placeholder="Password">
                    <!--
                    <label class="checkbox">
                        <input type="checkbox" name="remember" value="0" id="remember_me"> Remember Me
                    </label>
                    -->
                    <input type="hidden" name="id" id="id" value="<?= $session_id; ?>" />
                    <button type="submit" name="submit" class="btn btn-info btn-block">Sign in</button>
                </form>
            </div>
        </div>

    </div> <!-- /container -->

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="http://code.jquery.com/jquery-latest.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/cookie.min.js"></script>
    <!--
    <script src="js/bootstrap-transition.js"></script>
    <script src="js/bootstrap-alert.js"></script>
    <script src="js/bootstrap-modal.js"></script>
    <script src="js/bootstrap-dropdown.js"></script>
    <script src="js/bootstrap-scrollspy.js"></script>
    <script src="js/bootstrap-tab.js"></script>
    <script src="js/bootstrap-tooltip.js"></script>
    <script src="js/bootstrap-popover.js"></script>
    <script src="js/bootstrap-button.js"></script>
    <script src="js/bootstrap-collapse.js"></script>
    <script src="js/bootstrap-carousel.js"></script>
    <script src="js/bootstrap-typeahead.js"></script>
    -->
    <script>
        jQuery(function(){

            cookie.remove( 'username', 'login', 'id', 'remembered_name' );

            // if( cookie( 'username' ) )
            // {
            //     window.location.href = "http://skoch.local/cobra-admin";
            // }

            $( '#login-form' ).submit( _submitLogin );

            // if( cookie( 'remembered_name' ) )
            // {
            //     $( '#username' ).val( cookie( 'remembered_name' ) );
            //     $( '#remember_me' ).prop( "checked", true );
            // };

            function _submitLogin( $evt )
            {
                $evt.preventDefault();

                var username = $( '#username' ).val();
                var password = $( '#password' ).val();

                var data =
                {
                    login: username,
                    pwd: password,
                    id: $( '#id' ).val(),
                    timestamp: new Date().getTime()
                };

                $.post(
                    "http://skoch.local/cobra-admin/php/login.php",
                    { data: data },
                    function( $data )
                    {
                        // console.log( $data );
                        if( $data.result == 'user logged in' )
                        {
                            cookie.set({
                                cobra_login: $data.login,
                                cobra_username: $data.username,
                                cobra_sid: $data.id
                            });
                            // if( $( '#remember_me' ).is( ':checked' ) )
                            // {
                            //     cookie.set( {remembered_name: username} );
                            // }

                            window.location.href = "http://skoch.local/cobra-admin";
                        }else
                        {
                            // alert( 'incorrect. register?' );
                            var alert = '<div class="alert alert-error"><a class="close" data-dismiss="alert" href="#">×</a>Incorrect Username or Password!</div>';
                            $( '#alert-container' ).append( alert );
                        }
                    },
                    "json"
                );
            };
        });
    </script>
  </body>
</html>

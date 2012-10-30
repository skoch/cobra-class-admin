<?php
	$mongo  = new Mongo();
	$db = $mongo->cobra_classes;
	$teachers = $db->teachers;
	$classes = $db->classes;
	$students = $db->students;

	// if( ! session_id() )
	// {
	//     session_name( "CobraClub-Admin" );
	//     session_start();
	// }

	// $session_id = session_id();

	if( ! $_COOKIE['cobra_username'] && ! $_COOKIE['cobra_login'] )
	{
		header( 'Location: http://skoch.local/cobra-admin/login.php' );
	}else
	{
		$username = $_COOKIE['cobra_username'];
		$login = $_COOKIE['cobra_login'];
	}


	$classes = $classes->find(
		array( "paid" => false, "is_active" => false )
	);

	$all_classes = array();
	while( $classes->hasNext() )
	{
		$class = $classes->getNext();
		$all_classes[] = $class;
	}

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
		  <div class="nav-collapse collapse">
			<ul class="nav">

			  <li><a id="logout-btn" href="#">Logout</a></li>
			</ul>
		  </div><!--/.nav-collapse -->
		</div>
	  </div>
	</div>

	<div class="container">
		<div class="row">
			<div class="span9">
				<!--Body content-->

				<table class="table table-striped table-bordered">
					<thead>
						<tr>
							<th>Teacher</th>
							<th>Number of Students</th>
							<th>Student Details</th>
							<th></th>
						</tr>
					</thead>
					<?php foreach( $all_classes as $class ): ?>
					<tbody id="students" id="class-<?= $class['_id']; ?>">
						<td><?= $class['teacher']; ?></td>
						<td><?= count( $class['students'] ); ?></td>
						<td>
							<?php foreach( $class['students'] as $student_data ): ?>
							<p>
								<?= $student_data['name']; ?>
								paid with <?= $student_data['payment']; ?>
								<br>also bought <?= substr( $student_data['goods_display'], 0, -2 ); ?>
								and paid with <?= $student_data['goods_payment']; ?>
							</p>
						  <?php endforeach; ?>
						</td>
						<td>
						  <button
							  class="btn btn-mini btn-success pay-class"
							  data-class-id="<?= $class['_id']; ?>"
							  type="button">
							  Mark as Paid
						  </button>
						</td>
					</tbody>
					<?php endforeach; ?>
				</table>

			</div>
		</div>
	</div> <!-- /container -->

	<!-- Le javascript
	================================================== -->
	<!-- Placed at the end of the document so the pages load faster -->
	<script src="http://code.jquery.com/jquery-latest.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/cookie.min.js"></script>
	<script src="js/main.js"></script>
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
			// cookie.remove( 'class_id' );
			Main.init( 'class-admin' );
		});
	</script>
  </body>
</html>

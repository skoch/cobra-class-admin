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

	$class = $classes->findOne(
		array( "teacher" => $login, "paid" => false, "is_active" => true )
	);

	// echo "<pre>";
	// print_r($class);
	// echo "</pre>";
	$teacher = $teachers->findOne( array( "login" => $login ) );

	$cursor = $students->find();
	$all_students = array();
	while( $cursor->hasNext() )
	{
		$student = $cursor->getNext();
		$all_students[] = $student;
	}
	if( ( $class && ! $_COOKIE['cobra_class_id'] ) || ( $class && $class['teacher'] === $login ) )
	{
		// sk: so we have a class and it's by this teacher and they're on another machine because there is no cookie
		// OR we have a class and the logged in teacher matches the class teacher
		// cobra_class_id: $data.login + '-yoga-' + _currentClassID
		// echo "<pre>";
		$id = $class['teacher'] . '-' . $class['type'] . '-' . $class['_id'] . '';
		setcookie( "cobra_class_id", $id, time() + 60 * 60 * 24 * 7 );
		// echo "</pre>";
	}
	// echo "<pre>";
	// print_r($_COOKIE);
	// echo "</pre>";
	// list Class Payment from low to high (1 of X) for 1st class
	// classes.php - add date/time

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

			  <li>
				<div class="btn-group">
				  <a class="btn dropdown-toggle" data-toggle="dropdown" href="#" id="add-class">
					  Add Class
					  <span class="caret"></span>
				  </a>
				  <ul class="dropdown-menu">
					  <li><a class="creation" id="yoga" href="#">Yoga</a></li>
					  <li><a class="creation" id="pilates" href="#">Pilates</a></li>
					  <li><a class="creation" id="special" href="#">Special</a></li>
					  <!-- <li class="divider"></li> -->
					  <!-- <li><a href="#">Separated link</a></li> -->
				  </ul>
				</div>
			  </li>

			  <?php if( $teacher['is_admin'] ): ?>
			  <li><a id="view-classes-btn" href="classes.php">View Classes</a></li>
			  <?php endif; ?>
			  <li><a id="logout-btn" href="#">Logout</a></li>
			</ul>
		  </div><!--/.nav-collapse -->
		</div>
	  </div>
	</div>

	<div class="container">
		<div class="row-fluid">

			<?php if ( ! $class ): ?>
			<div id="students-dropdown" class="span2" style="display:none;">
			<?php else: ?>
			<div id="students-dropdown" class="span2">
			<?php endif; ?>

			  <div class="btn-group">
				  <a class="btn dropdown-toggle btn-primary" data-toggle="dropdown" href="#">
					  Students
					  <span class="caret"></span>
				  </a>
				  <ul class="dropdown-menu">
					  <?php foreach( $all_students as $student ): ?>
					  <li>
						  <a class="add-student" href="#"
							  data-username="<?= $student['username'] ?>"
							  data-id="<?= $student['_id'] ?>"
							  data-total-classes-taken="<?= $student['total_classes_taken'] ?>"
							  data-card="<?= $student['card'] ?>"
							  data-classes-taken-with-card="<?= $student['classes_taken_with_card'] ?>"
							  data-fullname="<?= $student['fullname'] ?>"
							  ><?= $student['fullname'] ?>
							  <i class="icon-chevron-right pull-right"></i>
						  </a>
					  </li>
					  <?php endforeach; ?>
					  <!-- <li class="divider"></li> -->
					  <!-- <li><a href="#">Separated link</a></li> -->
				  </ul>
			  </div>
			</div>
			<div class="span10">
				<!--Body content-->

				<div id="form-errors"></div>

				<form class="form-horizontal hidden" id="student-form">
				  <fieldset>
					<!-- <div id="legend" class="">
					  <legend class="">Add Student to Class</legend>
					</div> -->
				  <div class="control-group">

						<!-- Text input-->
						<label class="control-label" for="input01">Student</label>
						<div class="controls">
						  <input type="text" placeholder="Full Name" class="input-xlarge" id="student-name">
						  <!-- <p class="help-block"></p> -->
						</div>
					  </div>

				<div class="control-group">
					<label class="control-label">Goods</label>
					<div class="controls">
						<select class="input-xlarge" multiple="multiple" id="goods">
							<option value="2">Water ($2)</option>
							<option value="5">Yoga Mat - rental ($5)</option>
							<option value="15">Yoga Mat - purchase ($15)</option>
							<option value="20">Class Card - 5 classes ($20)</option>
							<option value="35">Class Card - 10 classes ($35)</option>
							<option value="50">Class Card - 15 classes ($50)</option>
							<option value="60">Class Card - 20 classes ($60)</option>
						</select>
					</div>
				</div>

				<div class="control-group hidden" id="class-card">
					<label class="control-label">Use Class Card?</label>
					<div class="controls">
						<!-- Inline Radios -->
						<label class="radio inline">
							<input type="radio" value="Yes" name="use-class-card">Yes
						</label>
						<label class="radio inline">
							<input type="radio" value="No" name="use-class-card" checked="checked">No
						</label>
					</div>
				</div>

				  <div class="control-group">
						<label class="control-label">Payment</label>
						<div class="controls">
							<!-- Inline Radios -->
							<label class="radio inline">
							  <input type="radio" value="cash" name="payment" checked="checked">
							  Cash
							</label>
							<label class="radio inline">
							  <input type="radio" value="credit" name="payment">
							  Credit
							</label>
						</div>
				</div>

				  <div class="control-group">
						<!-- Form Actions -->
						  <div class="form-actions">
							<button type="submit" class="btn btn-primary" id="add-student-to-class">Add Student to Class</button>
							<!-- <button type="button" class="btn">Cancel</button> -->
						  </div>
					  </div>

				  </fieldset>
				  <input type="hidden" id="student-id" value="">
				</form>

				<h5 id="class-caption"></h5>
				<form class="form-horizontal hidden" id="class-form">
				  <fieldset>
					<table class="table table-striped table-bordered" id="class-details">
						<!-- <caption id="class-caption"></caption> -->
						<thead>
							<tr>
								<th>Student</th>
								<th>Class Card</th>
								<th>Purchases</th>
								<th>Payment</th>
								<th>Total</th>
								<th></th>
							</tr>
						</thead>
						<tbody id="students">
							<?php foreach( $class['students'] as $student_data ): ?>
								<tr id="<?= $student_data['id']; ?>">
									<td><?= $student_data['name']; ?></td>
									<td><?= ($student_data['use_card']) ? 'yes' : '&mdash;'; ?></td>
									<?php if( $student_data['goods_display'] ): ?>
									<td><?= substr( $student_data['goods_display'], 0, -2 ); ?></td>
									<?php else: ?>
									<td>&mdash;</td>
									<?php endif; ?>
									<td><?= $student_data['payment']; ?></td>
									<?php // determine the total based on which type of class as well as if they're using a class card ?>
									<?php
										// if( $student_data['class_payment'] === 'class card' )
										// {
										//     $total = $student_data['total'];
										// }else
										// {
										//     if( $class['type'] === 'yoga' )
										//     {
										//         $total = $student_data['total'] + 12;
										//     }else
										//     {
										//         $total = $student_data['total'] + 15;
										//     }
										// }
									?>
									<td><?= $student_data['total']; ?></td>
									<td>
										<button
											class="btn btn-mini btn-danger delete-from-class"
											data-id="<?= $student_data['id']; ?>"
											data-payment="<?= $student_data['payment']; ?>"
											type="button">
											Delete
										</button>
									</td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				  <div class="control-group">
						<!-- Form Actions -->
						  <div class="alert alert-info">
							<strong>Heads up!</strong> If you do not Save this class, you will not get paid!
						  </div>
						  <div class="form-actions">
							<button type="submit" class="btn btn-primary" id="save-class">Save Class</button>
							<button type="button" class="btn">Cancel</button>
						  </div>
					  </div>

				  </fieldset>
				</form>

				<div class="alert alert-success hidden" id="success-message">
					<button type="button" class="close" data-dismiss="alert">×</button>
					<strong>Success!</strong> Class saved.
				</div>

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
			// cookie.remove( 'cobra_class_id' );
			// cookie.remove( 'cobra_sid' );
			// cookie.remove( 'cobra_login' );
			// cookie.remove( 'cobra_username' );
			// cookie.remove( 'cobra_username', 'cobra_login', 'cobra_sid', 'cobra_class_id' );
			Main.init( 'index' );
		});
	</script>
  </body>
</html>

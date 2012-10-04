<?php

	if( isset( $_POST['data'] ) )
	{
		$data = $_POST['data'];

		$mongo  = new Mongo();
		$db = $mongo->cobra_classes;
		$classes = $db->classes;
		$students = $db->students;

		$success = $classes->update(
			array( "_id" => new MongoId( $data['class_id'] ) ),
			array( '$pull' => array(
				"students" => array( "id" => $data['student_id'] )
			))
		);

		if( $success )
		{
			// sk: no need, since this only happens
			// once the teacher closes the class
			// if( $data['class_payment'] === 'class card' )
			// {
			// 	$students->update(
			// 		array( "_id" => new MongoId( $data['student_id'] ) ),
			// 		array( '$inc' => array( 'classes_taken_with_card' => -1 ) )
			// 	);
			// }

			echo json_encode( array(
					"success" => true,
					"student_id" => $data['student_id']
				)
			);
		}else
		{
			echo json_encode( array( "success" => false ) );
		}

	}

	function getShortName( $name )
	{
		$parts = explode( ' ', $name );
		$fchar = strtolower( substr( $parts[0], 0, 1 ) );
		$lname = strtolower( $parts[1] );
		return $fchar . $lname;
	}

?>


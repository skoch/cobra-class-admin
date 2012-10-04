<?php

	if( isset( $_POST['class_id'] ) )
	{
		$class_id = $_POST['class_id'];

		$mongo  = new Mongo();
		$db = $mongo->cobra_classes;
		$classes = $db->classes;
		$students = $db->students;
		$return_ids = array();

		$class = $classes->findOne(
			array( "_id" => new MongoId( $class_id ) )
		);

		foreach( $class['students'] as $student )
		{
			if( $student['class_payment'] === 'class card' )
			{
				$students->update(
					array( "_id" => new MongoId( $student['id'] ) ),
					array( '$inc' => array( 'classes_taken_with_card' => 1 ) )
				);
				// todo: if the number now matches what the 'card' value is, then kill it
			}

			$students->update(
				array( "_id" => new MongoId( $student['id'] ) ),
				array( '$inc' => array( 'total_classes_taken' => 1 ) )
			);
			$return_ids[] = $student['id'];
		}


		$success = $classes->update(
			array( "_id" => new MongoId( $class_id ) ),
			array( '$set' => array( "is_active" => false ) )
		);

		if( $success )
		{
			echo json_encode( array(
					"success" => true,
					"student_ids" => $return_ids,
					"class_id" => $class_id
				)
			);
		}else
		{
			echo json_encode( array( "success" => false ) );
		}
	}

?>


<?php

	if( isset( $_POST['data'] ) )
	{
		$data = $_POST['data'];

		$mongo  = new Mongo();
		$db = $mongo->cobra_classes;
		$classes = $db->classes;
		$students = $db->students;

		// $class = $classes->findOne(
		// 	array( "_id" => new MongoId( $data['class_id'] ) )
		// );

		// sk: add a new student
		if( $data['existing_student'] === 'false' )
		{
			// sk: baseline...no card, 1st class
			$student = array(
				"card" => 0,
				"classes_taken_with_card" => 0,
				"fullname" => $data['fullname'],
				"total_classes_taken" => 0,
				"username" => getShortName( $data['fullname'] )
			);

			if( $data['purchased_class_card'] === 'true' )
			{
				$student["card"] = (int) $data['card_value'];
				// todo: this should happen when the teacher closes the class!
				// $student["classes_taken_with_card"] = 1;
			}

			$students->insert( $student );

			$student_id = $student['_id'] . "";
		}else if( $data['existing_student'] === 'true' )
		{
			$student_id = $data['student_id'];
		}

		$new_student = array(
			'name'        	=> $data['fullname'],
			'id'        	=> $student_id,
			'payment'		=> $data['payment'],
			'use_card'		=> (boolean) $data['use_class_card'],
			'goods_display' => $data['goods_display'],
			'total'     	=> $data['total']
		);

		$success = $classes->update(
			array( "_id" => new MongoId( $data['class_id'] ) ),
			array( '$addToSet' => array( "students" => $new_student ) )
		);

		if( $success )
		{
			echo json_encode( array(
					"success" => true,
					"student_id" => $student_id
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


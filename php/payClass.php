<?php

	if( isset( $_POST['class_id'] ) )
	{
		$class_id = $_POST['class_id'];

		$mongo  = new Mongo();
		$db = $mongo->cobra_classes;
		$classes = $db->classes;

		$success = $classes->update(
			array( "_id" => new MongoId( $class_id ) ),
			array( '$set' => array( "paid" => true ) )
		);

		if( $success )
		{
			echo json_encode( array(
					"result" => "class paid",
					"class_id" => $class_id
				)
			);
		}else
		{
			echo json_encode( array( "result" => "incorrect" ) );
		}
	}

?>


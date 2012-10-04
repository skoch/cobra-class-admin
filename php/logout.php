<?php

	if( isset( $_POST['data'] ) )
	{
		$data = $_POST['data'];

		$mongo  = new Mongo();
		$db = $mongo->cobra_classes;
		$teachers = $db->teachers;

		$user = $teachers->findOne(
			array( "name" => $data['name'] )
		);

		if( $user['name'] == $data['name'] )
		{
			// setcookie( "username", "", time() - 3600, '/' );
			$teachers->update(
				array(
					"name" => $data['name']
				),
				array( '$set' => array(
					'session_id' => ""
				)
			));
			echo json_encode( array( "result" => "user logged out" ) );
		}else
		{
			echo json_encode( array( "result" => "incorrect" ) );
		}
	}

?>


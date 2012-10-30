<?php

	if( isset( $_POST['data'] ) )
	{
		$data = $_POST['data'];

		$mongo  = new Mongo();
		$db = $mongo->cobra_classes;
		$teachers = $db->teachers;
		$classes = $db->classes;

		$user = $teachers->findOne(
			array( "login" => $data['login'] )
		);
/*
login: cookie( 'login' ),
type: "yoga",
id: cookie( 'id' ),
timestamp: new Date().getTime()

 */
		if( $user['session_id'] === $data['sid'] )
		{
			$classData = array(
				"teacher" => $data['login'],
				"type" => $data['type'],
				"paid" => false,
				"is_active" => true,
				"timestamp" => $data['timestamp']
			);

			$classes->insert( $classData );

			echo json_encode( array(
					"result" => "class added",
					"username" => $user['name'],
					"login" => $user['login'],
					"type" => $data['type'],
					"class_id" => $classData['_id']
				)
			);
		}else
		{
			echo json_encode( array( "result" => "incorrect" ) );
		}
	}

?>


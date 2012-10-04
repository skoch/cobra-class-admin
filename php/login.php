<?php

	if( isset( $_POST['data'] ) )
	{
		$data = $_POST['data'];

		// $expire = time() + 60 * 60 * 24 * 30;// 60 sec * 60 min * 24 hours * 30 days

		$mongo  = new Mongo();
		$db = $mongo->cobra_classes;
		$teachers = $db->teachers;

		$user = $teachers->findOne(
			array( "login" => $data['login'] )
		);

		if( $user['pwd'] == $data['pwd'] )
		{
			// sk: attempting to use JS cookie instead
			// setcookie( "username", $data['username'], $expire, '/' );
			$teachers->update(
				array(
					"login" => $data['login']
				),
				array( '$set' => array(
						'session_id' => $data['id'],
						'last_login' => $data['timestamp']
					)
				)
			);
			echo json_encode( array(
				"result" => "user logged in",
				"username" => $user['name'],
				"id" => $data['id'],
				"login" => $user['login'] )
			);
		}else
		{
			echo json_encode( array( "result" => "incorrect" ) );
		}
	}

?>


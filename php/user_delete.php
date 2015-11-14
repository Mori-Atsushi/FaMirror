<?php
$api_key = '64f0b8d4729734b49f231e5b0c1f4523';
$api_secret = 'bABwx_lmF99mpbGy9M3ZSzsJqiiAoNpb';
$api = '?api_key=' . $api_key . '&api_secret=' . $api_secret;

$url = 'https://apius.faceplusplus.com';

session_start();
$conn = mysql_connect('localhost', 'famirror', 'famirrorproject');
if($conn) {
	mysql_select_db('famirror', $conn);
	$sql = 'SELECT img FROM user WHERE family_id = "' . $_SESSION['family'] . '" AND user_id = "' . $_POST['user_id'] . '"';
	$old = mysql_fetch_assoc(mysql_query($sql));
	$old_path = '../icon/' . $old['img'];
	unlink($old_path);
	
	$sql = 'DELETE FROM user WHERE family_id = "' . $_SESSION['family'] . '" AND user_id = "' . $_POST['user_id'] . '"';
	mysql_query($sql);

	$person_name = $_SESSION['family'] . ':' . $_POST['user_id'];
	$req = $url . '/person/delete' .  $api . '&person_name=' . $person_name;
	$res = file_get_contents($req);

	$sql = 'SELECT user_id FROM user WHERE family_id = ' . $_SESSION['family'] . ' ORDER BY user_id ASC';
	$user = mysql_query($sql);
	$num = 1;
	while($row = mysql_fetch_assoc($user)) {
		if($row['user_id'] != $num) {
			$sql = 'UPDATE user SET user_id = "' . $num . '" WHERE family_id = "' . $_SESSION['family'] . '" AND user_id = "' . $row['user_id'] . '"';
			mysql_query($sql);

			$person_name = $_SESSION['family'] . ':' . $row['user_id'];
			$name = $_SESSION['family'] . ':' . $num;
			$req = $url . '/person/set_info' .  $api . '&person_name=' . $person_name . '&name=' . $name;
			$res = file_get_contents($req);
		}
		$num++;
	}
	if($num == 1) {
		$req = $url . '/group/delete' .  $api . '&group_name=' . $_SESSION['family'];
		$res = file_get_contents($req);

		$_SESSION = array();
		if(ini_get("session.use_cookies")) {
			$params = session_get_cookie_params();
			setcookie(session_name(), '', time() - 42000,
				$params["path"], $params["domain"],
				$params["secure"], $params["httponly"]
			);
		}
		session_destroy();
	}	
}

header('Location: ../');
?>
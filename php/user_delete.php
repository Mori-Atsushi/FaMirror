<?php
//$api_key = '64f0b8d4729734b49f231e5b0c1f4523';
$api_key = 'b81fd92a7779b24eddf6b556ccb9baa9';
//$api_secret = 'bABwx_lmF99mpbGy9M3ZSzsJqiiAoNpb';
$api_secret = 'Pq9T3A_pboK4ANRSAnK7ea9XQZdTbVpH';
$api = '?api_key=' . $api_key . '&api_secret=' . $api_secret;

//$url = 'https://apius.faceplusplus.com';
$url = 'https://apicn.faceplusplus.com';

session_start();
$conn = mysql_connect('localhost', 'famirror', 'famirrorproject');
if($conn) {
	mysql_select_db('famirror', $conn); mysql_query('SET NAMES utf8', $conn );
	$sql = 'SELECT img FROM user WHERE family_id = "' . $_SESSION['family'] . '" AND user_id = "' . $_POST['user_id'] . '"';
	$old = mysql_fetch_assoc(mysql_query($sql));
	$old_path = '../icon/' . $old['img'];
	unlink($old_path);
	
	$sql = 'DELETE FROM user WHERE family_id = "' . $_SESSION['family'] . '" AND user_id = "' . $_POST['user_id'] . '"';
	mysql_query($sql);

	$person_name = $_SESSION['family'] . ':' . $_POST['user_id'];
	$req = $url . '/person/delete' .  $api . '&person_name=' . $person_name;
	$res = file_get_contents($req);

	$sql = 'SELECT user_id, img FROM user WHERE family_id = ' . $_SESSION['family'] . ' ORDER BY user_id ASC';
	$user = mysql_query($sql);
	$num = 1;
	while($row = mysql_fetch_assoc($user)) {
		if($row['user_id'] != $num) {
			$extension = pathinfo($row['img'], PATHINFO_EXTENSION);
			$old_path = '../icon/' . $row['img'];
			$new = $_SESSION['family'] . '_' . $num . '.' . $extension;
			$new_path = '../icon/' . $new;
			rename($old_path, $new_path);
			$sql = 'UPDATE user SET user_id = "' . $num . '", img = "' . $new . '" WHERE family_id = "' . $_SESSION['family'] . '" AND user_id = "' . $row['user_id'] . '"';
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
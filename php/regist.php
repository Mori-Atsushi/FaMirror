<?php
$data = array('user_id' => '1');
$name = htmlspecialchars($_POST['name'], ENT_QUOTES);
$name_p = htmlspecialchars($_POST['name_p'], ENT_QUOTES);
session_start();

$conn = mysql_connect('localhost', 'famirror', 'famirrorproject');

if($conn) {
	mysql_select_db('famirror', $conn);
	if($_SESSION['family'] == '') {
		$sql = 'SELECT max(family_id) FROM user';
		$famiy_num = mysql_fetch_assoc(mysql_query($sql, $conn));
		$_SESSION['family'] = $famiy_num['max(family_id)'] + 1;
	} else {
		$sql = 'SELECT * FROM user WHERE family_id = ' . $_SESSION['family'];
		$member_num = mysql_num_rows(mysql_query($sql, $conn));
		$data['user_id'] = $member_num + 1;		
	}
	$data['family_id'] = $_SESSION['family'];
	$sql = "INSERT INTO user (user_mail, family_id, user_id, refresh_token, user_name, user_name_p) VALUES ('" . $_SESSION['email'] . "', '" . $data['family_id'] . "', '" . $data['user_id'] . "', '" . $_SESSION['refresh_token'] . "', '" . $name . "', '" . $name_p . "')";
	mysql_query($sql, $conn);

	unset($_SESSION['email']);
	unset($_SESSION['name']);
	unset($_SESSION['refresh_token']);

	header('Content-Type: application/json; charset=utf-8');
	echo json_encode($data);
}
?>
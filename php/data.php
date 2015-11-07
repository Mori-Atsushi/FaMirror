<?php
session_start();
$sign_up_flag = !empty($_SESSION['email']);

$conn = mysql_connect('localhost', 'famirror', 'famirrorproject');

if($conn) {
	mysql_select_db('famirror', $conn);
	$sql = 'SELECT user_name, user_name_p, img FROM user WHERE family_id = ' . $_SESSION['family'] . ' ORDER BY user_id ASC';
	$user = mysql_query($sql);
	$num = 0;
	while($row = mysql_fetch_assoc($user)) {
		$member[$num] = $row;
		$num++;
	}
	if($sign_up_flag) {
		$member[$num]['user_name'] = $_SESSION['name'];
	}
}

header('Content-Type: application/json; charset=utf-8');
echo json_encode($member);
?>
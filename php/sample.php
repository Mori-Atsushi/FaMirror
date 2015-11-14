<?php
require 'collect.php';

//$_POST['type'] = 'calendar';
//$_POST['user_id'] = 1;

session_start();
$time = date(H); $manth = date(n); $day = date(j); $week = date(w);
$conn = mysql_connect('localhost', 'famirror', 'famirrorproject');

if($conn && $_POST['user_id'] !== '') {
	mysql_select_db('famirror', $conn);
	$sql = 'SELECT * FROM user WHERE user_id = ' . $_POST['user_id'] . ' AND family_id = ' . $_SESSION['family'];
	$user = mysql_fetch_assoc(mysql_query($sql));
}

switch ($_POST['type']) {
	case 'weather':
		echo weather($user);
		break;
	case 'trash':
		echo trash($user, $conn, $day, $week);
		break;
	case 'calendar':
		echo calendar($user);
		break;
	default:
		echo 'error';
}
?>
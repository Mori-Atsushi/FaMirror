<?php
require 'collect.php';

session_start();
$time = date(H); $manth = date(n); $day = date(j); $week = date(w);
$conn = mysql_connect('localhost', 'famirror', 'famirrorproject');

if($conn && $_POST['user_id'] !== '') {
	mysql_select_db('famirror', $conn); mysql_query('SET NAMES utf8', $conn );
	$sql = 'SELECT * FROM user WHERE user_id = ' . $_POST['user_id'] . ' AND family_id = ' . $_SESSION['family'];
	$user = mysql_fetch_assoc(mysql_query($sql));
}

switch ($_POST['type']) {
	case 'weather':
		$echo = weather($user);
		break;
	case 'trash':
		$echo = trash($user, $conn, $day, $week);
		break;
	case 'calendar':
		$echo = calendar($user);
		break;
	case 'timetable':
		$echo = timetable($user, $conn, $week);
		break;
	case 'bus':
		$echo = bus($user, $conn);
		break;
	case 'horoscope':
		$echo = horoscope($user);
		break;
	case 'lunch':
		$echo = lunch($user, $conn);
		break;
	default:
		echo 'error';
}

header('Content-Type: application/json; charset=utf-8');
echo json_encode($echo);
?>
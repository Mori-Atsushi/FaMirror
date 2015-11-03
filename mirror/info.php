<?php
session_start();
$conn = mysql_connect('localhost', 'famirror', 'famirrorproject');

if($conn && $_POST['user_id'] !== '') {
	mysql_select_db('famirror', $conn);
	$sql = 'SELECT * FROM user WHERE user_id = ' . $_POST['user_id'] . ' AND family_id = ' . $_SESSION['family'];
	$user = mysql_fetch_assoc(mysql_query($sql));
	echo 'おはようございます、' . $user['user_name_p'] . 'さん';
}
?>
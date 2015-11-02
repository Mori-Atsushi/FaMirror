<?php
$data = array('user_id' => 1);
session_start();

$conn = mysql_connect('localhost', 'famirror', 'famirrorproject');

if($conn) {
	mysql_select_db('famirror', $conn);
	if($_SESSION['family'] === '') {
		$sql = 'SELECT max(family_id) FROM user';
		$famiy_num = mysql_fetch_assoc(mysql_query($sql, $conn));
		$_SESSION['family'] = $famiy_num['max(family_id)'];
	} else {
		$sql = 'SELECT * FROM user WHERE family_id = ' . $_SESSION['family'];
		$member_num = mysql_num_rows(mysql_query($sql, $conn));
		$data['user_id'] = $member_num;		
	}
	$data['family_id'] = $_SESSION['family'];
	$sql = 'INSERT INTO user (user_mail, user_name, family_id, user_id) VALUES ("' . $_SESSION['mail'] . '", NULL, ' . $data['family_id'] . ',' . $data['user_id'] .')';
	mysql_query($sql, $conn);
	header( "Content-Type: application/json; charset=utf-8" ) ;
	echo json_encode($data);
}
?>
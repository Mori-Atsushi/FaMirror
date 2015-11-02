<?php
session_start();
$conn = mysql_connect('localhost', 'famirror', 'famirrorproject');

if($conn && $_POST['user_id'] !== '') {
	if($_POST['name'] === '') {
		echo 'エラー：名前を記入してください。';
	} else {
		$name = htmlspecialchars($_POST['name'], ENT_QUOTES);
		mysql_select_db('famirror', $conn);
		$sql = 'UPDATE user SET user_name = "' . $_POST['name'] . '" WHERE family_id = ' . $_SESSION['family'] . ' AND user_id = ' . $_POST['user_id'];
		mysql_query($sql);
		echo true;
	}
}
?>
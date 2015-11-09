<?php
session_start();
$conn = mysql_connect('localhost', 'famirror', 'famirrorproject');
if($conn) {
	mysql_select_db('famirror', $conn);
	for($i = 0; $i < count($_POST['setting']); $i++) {
		if($i != 0)
			$data = $data . ', ';
		if($_POST['data'][$i] == 'null')
			$data = $data . $_POST['setting'][$i] . '=NULL';
		else
			$data = $data . $_POST['setting'][$i] . '="' . $_POST['data'][$i] . '"';
	}
	$sql = 'UPDATE user SET ' . $data . ' WHERE family_id = "' . $_SESSION['family'] . '" AND user_id = "' . $_POST['user_id'] . '"';
	mysql_query($sql);
}
echo $sql;
?>
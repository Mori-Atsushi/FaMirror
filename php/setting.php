<?php
session_start();
$conn = mysql_connect('localhost', 'famirror', 'famirrorproject');
if($conn) {
	mysql_select_db('famirror', $conn);
	for($i = 0; $i < count($_POST['set']); $i++) {
		if($i != 0)
			$data = $data . ', ';
		if($_POST['data'][$i] == 'null')
			$data = $data . $_POST['set'][$i] . '=NULL';
		else
			$data = $data . $_POST['set'][$i]['name'] . '="' . $_POST['set'][$i]['data'] . '"';
	}
	$sql = 'UPDATE user SET ' . $data . ' WHERE family_id = "' . $_SESSION['family'] . '" AND user_id = "' . $_POST['user_id'] . '"';
	mysql_query($sql);
}
echo $sql;
?>
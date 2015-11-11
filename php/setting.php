<?php
session_start();
$conn = mysql_connect('localhost', 'famirror', 'famirrorproject');
if($conn) {
	mysql_select_db('famirror', $conn);
	for($i = 0; $i < count($_POST['set']); $i++) {
		if($i != 0)
			$data = $data . ', ';
		switch($_POST['set'][$i]['data']) {
			case 'null':
				$data = $data . $_POST['set'][$i]['name'] . '=NULL';
				break;
			case 'false';
				$data = $data . $_POST['set'][$i]['name'] . '=0';
				break;
			case 'true';
				$data = $data . $_POST['set'][$i]['name'] . '=1';
				break;
			default :
				$data = $data . $_POST['set'][$i]['name'] . '="' . $_POST['set'][$i]['data'] . '"';
		}
	}
	
	$sql = 'UPDATE user SET ' . $data . ' WHERE family_id = "' . $_SESSION['family'] . '" AND user_id = "' . $_POST['user_id'] . '"';
	mysql_query($sql);
}
echo $sql;
?>
<?php
if (isset($_POST['email']))
{
	$conn = mysql_connect('localhost', 'famirror', 'famirrorproject');

	if($conn) {
		mysql_select_db('famirror', $conn);
		$sql = "INSERT INTO `family` (`family_num`, `user_id1`) VALUES ('1', '". $_POST['email'] ."')";
		mysql_query($sql, $conn);
		$sql = "SELECT * FROM `family`";
		$num = mysql_num_rows(mysql_query($sql, $conn));
		echo $num;
	}
} else {
	echo 'The parameter of "request" is not found.';
}
?>
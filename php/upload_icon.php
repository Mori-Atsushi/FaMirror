<?php
session_start();
$max_width = 512;

$extension = pathinfo($_FILES['img']['name'], PATHINFO_EXTENSION);
$file_name = $_SESSION['family'] . '_' . $_POST['user_id'] . '.' . $extension;
$file_path = '../icon/' . $file_name;
$conn = mysql_connect('localhost', 'famirror', 'famirrorproject');
if($conn) {
	mysql_select_db('famirror', $conn); mysql_query('SET NAMES utf8', $conn );
	$sql = 'SELECT img FROM user WHERE family_id = "' . $_SESSION['family'] . '" AND user_id = "' . $_POST['user_id'] . '"';
	$old = mysql_fetch_assoc(mysql_query($sql));
	$old_path = '../icon/' . $old['img'];
	unlink($old_path);
	$sql = 'UPDATE user SET img = "' . $file_name . '" WHERE family_id = "' . $_SESSION['family'] . '" AND user_id = "' . $_POST['user_id'] . '"';
	mysql_query($sql);
}
$size = getimagesize($_FILES['img']['tmp_name']);

if($size[0] < $size[1]) {
	$width = $max_width;
	$height = round(($width / $size[0]) * $size[1]);
} else {
	$height= $max_width;
	$width = round(($height / $size[1]) * $size[0]);
}
switch($_FILES['img']['type']) {
	case 'image/jpeg':
		$image = imagecreatefromjpeg($_FILES['img']['tmp_name']);
		$image_s = imagecreatetruecolor($width, $height);
		$result = imagecopyresampled($image_s, $image, 0, 0, 0, 0, $width, $height, $size[0], $size[1]);
		if($result)
			$result = imagejpeg($image_s, $file_path);
		break;
	case 'image/png':
		$image = imagecreatefrompng($_FILES['img']['tmp_name']);
		$image_s = imagecreatetruecolor($width, $height);
		$result = imagecopyresampled($image_s, $image, 0, 0, 0, 0, $width, $height, $size[0], $size[1]);
		if($result)
			$result = imagepng($image_s, $file_path);
		break;
	case 'image/gif':
		$image = imagecreatefromgif($_FILES['img']['tmp_name']);
		$image_s = imagecreatetruecolor($width, $height);
		$result = imagecopyresampled($image_s, $image, 0, 0, 0, 0, $width, $height, $size[0], $size[1]);
		if($result)
			$result = imagegif($image_s, $file_path);
		break;
	default :
		exit('error');
}

if($result)
	echo $file_name;
else
	echo 'error';
?>
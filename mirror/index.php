<?php
session_start();
if(empty($_SESSION['family'])) {
	header('Location: ../');
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
	<link rel="stylesheet" href="../css/style.css">
	<link rel="stylesheet" href="../css/test.css">
	<title>FaMirror | 鏡</title>
</head>
	<h1 id="auth">認証</h1>
	<p id="message"></p>
	<video id="mirror" class="mirror" autoplay></video>
	<canvas id="canvas" class="temp_pic"></canvas>
	<?php
	$conn = mysql_connect('localhost', 'famirror', 'famirrorproject');

	if($conn) {
		mysql_select_db('famirror', $conn);
		$sql = 'SELECT * From user WHERE family_id = ' . $_SESSION['family'];
		$family_member = mysql_query($sql);
		$member = mysql_fetch_assoc($family_member);
		for($i = 0; $i < mysql_num_rows($family_member); $i++) {
			if($member[i]['user_name'] == NULL) {
				echo '<div class="start_setting">';
				echo '<h2>初期設定</h2>';
				echo '<label for="name">表示名：</label>';
				echo '<input type="text" name="name" id="name">';
				echo '<div id="submit">送信</div>';
				echo '</div>';
			}

		}

	}	
	?>

	<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>	
	<script type="text/javascript" src="../js/mirror.js"></script>
	<script type="text/javascript" src="../js/facepp.js"></script>
	<script type="text/javascript" src="../js/script.js"></script>
<body>

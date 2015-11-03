<?php
session_start();
if($_SESSION['family'] === '') {
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
	<div class="main">
		<h1 id="auth">認証</h1>
		<p id="message"></p>
	</div>
	<video id="mirror" class="mirror" autoplay></video>
	<canvas id="canvas" class="temp_pic"></canvas>
	<?php
	$conn = mysql_connect('localhost', 'famirror', 'famirrorproject');

	if($conn) {
		mysql_select_db('famirror', $conn);
		$sql = 'SELECT * FROM user WHERE family_id = ' . $_SESSION['family'] . ' AND user_name IS NULL';
		$user = mysql_query($sql);
		if(mysql_num_rows($user) > 0) {
			$member = mysql_fetch_assoc($user);
			echo '<div id="' . $member['family_id'] . '" class="start_setting">';
			echo '<h2>初期設定（' . $member['user_mail'] . '）</h2>';
			echo '<label for="name">表示名：</label><br>';
			echo '<input type="text" name="name" class="name"><br>';
			echo '<label for="name">読み方（ひらがな）：</label><br>';
			echo '<input type="text" name="name_p" class="name_p">';
			echo '<div class="submit">送信</div>';
			echo '</div>';		
		}
	}	
	?>

	<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>	
	<script type="text/javascript" src="../js/mirror.js"></script>
	<script type="text/javascript" src="../js/facepp.js"></script>
	<script type="text/javascript" src="../js/speak.js"></script>
	<script type="text/javascript" src="../js/script.js"></script>
<body>

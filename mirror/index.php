<?php
session_start();
if($_SESSION['family'] === '')
	header('Location: ../');

$conn = mysql_connect('localhost', 'famirror', 'famirrorproject');
if($conn) {
	mysql_select_db('famirror', $conn);
	$sql = 'SELECT * FROM user WHERE family_id = ' . $_SESSION['family'];
	$user = mysql_query($sql);
	$num = 0;
	while($row = mysql_fetch_assoc($user)) {
		$member[$num] = $row;
		$num++;
	}
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
		<div id="setting">設定</div>
	</div>

	<div id="user_select" class="user_select">
		<div id="back_top">戻る</div>
		<ul>
		<?php
		for($i = 0; $i < $num; $i++)
			echo '<li id="' . $member[$i]['user_id'] . '_select" class="user">' . $member[$i]['user_name'] . '</li>';		
		?>
		<li onclick="location.href='../OAuth2.php'">ユーザー追加</li>
		</ul>
	</div>

	<?php
	for($i = 0; $i < $num; $i++) {
		echo '<div id="' . $member[$i]['user_id'] . '_setting" class="user_setting">';
		echo '<div class="back_select">戻る</div>';
		echo '<h2>設定（' . $member[$i]['user_name'] . '）</h2>';
		echo '<label for="name">表示名；</label><br>';
		echo '<input type="text" name="name" class="name" value="' . $member[$i]['user_name'] . '"><br>';
		echo '<label for="name_p">読み方（ひらがな）：</label><br>';
		echo '<input type="text" name="name_p" class="name_p" value="' . $member[$i]['user_name_p'] . '"><br>';
		echo '<div class="submit">送信</div>';
		echo '</div>';
	}
	?>
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
			echo '<div id="' . $member['user_id'] . '_start" class="start_setting">';
			echo '<h2>初期設定（' . $member['user_mail'] . '）</h2>';
			echo '<label for="name">表示名：</label><br>';
			echo '<input type="text" name="name" class="name"><br>';
			echo '<label for="name_p">読み方（ひらがな）：</label><br>';
			echo '<input type="text" name="name_p" class="name_p">';
			echo '<div class="start_submit">送信</div>';
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

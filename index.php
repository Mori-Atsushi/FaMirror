<?php

session_start();
$sign_up_flag = !empty($_SESSION['email']);
$mirror_flag = !empty($_SESSION['family']);

$top = '<section class="top">
		<header class="siteheader"></header>
		<div class="main">
			<h2>一瞬の時間もムダにしない、<br>新しい朝を。</h2>
		</div>
		<footer class="sitefooter">
			<button onclick="location.href=\'php/OAuth.php\'"></button>
		</footer>
	</section>';

$sign_up = '<section id="sign_up" class="sign_up">
		<header class="page_header">
			<h1>顔登録</h1>
		</header>
		
		<div class="main">
			<div class="square"></div>
		</div>
		<div class="exp">
			<p id="message">四角形の中に顔を入れてカメラボタンを押してください。</p>
			<div class="status"><div id="progress_gage" class="gage"></div><span id="progress"></span></div>
			<div id="shot" class="shot"></div>
		</div>
	</section>

	<div id="black_screen" class="black_screen">
		<div id="get_name_popup" class="get_name_popup">
			<h2>表示名と読み方を入力してください</h2>
			<label for="name">表示名</label>
			<input type="text" name="name" class="name" value="' . $_SESSION['name'] . '">
			<label for="name_p">読み方(ひらがな)</label>
			<input type="text" name="name_p" class="name_p" value="">
			<button id="get_name_submit">完了</button>
		</div>
	</div>';

$base = '<section id="base" class="base">
		<header class="page_header">
			<h1></h1>
			<button class="gear" id="setting_b"></button>
		</header>

		<div class="main">
			<div class="square"></div>
		</div>

		<div class="exp">
			<p id="message">四角形の中に顔を入れて、認証ボタンを押してください。</p>
			<button id="auth">認証</button>
		</div>

	</section>';


$setting = '<section id="setting" class="setting">
		<header class="page_header">
			<h1>設定</h1>
			<button id="setting_back" class="back"></button>
		</header>
		<div class="main">
			<ul id="user_list">
				<!-- <li id="member_1" class="member" ><div></div></li> -->
			</ul>
			<button onclick="location.href=\'php/OAuth.php?id=add\'" class="add"></button>
		</div>
	</section>';

$detail = '<section id="detail" class="detail">
		<header class="siteheader">
			<div class="operate">
				<button id="detail_back" class="back"></button>
				<button class="gear"></button>
			</div>
			<div class="icon"><div id="detail_icon"></div></div>
			<h1 id="detail_user_name"></h1>
		</header>
		<div class="main">
			<ul id="detail_list">
				<li id="item_weather"><div></div><span>天気</span></li>
				<li id="item_trash"><div></div>ごみ</li>
				<li id="item_calendar"><div></div>カレンダー</li>
			</ul>
		</div>
	</section>';

$setting_weather = '<section id="setting_weather" class="setting_weather settings">
		<header class="page_header">
			<h1>天気</h1>
			<button id="weather_back" class="back"></button>
		</header>
		<div class="main">
			<div class="area">
				<h2>エリア</h2>
				<label>都道府県</label>
				<select id="weather_prefecture">
					<option value="-1">選択してください</option>
				</select>
				<label>地区</label>
				<select id="weather_area">
					<option value="-1">先に都道府県を選択してください</option>
				</select>
			</div>

			<div class="notification">
				<h2>通知設定</h2>
				<label>詳細情報</label>
				<select id="weather_detail">
					<option value="1">通知する</option>
					<option value="0">通知しない</option>
				</select>
				<label>最高気温/最低温度</label>
				<select id="weather_temperature">
					<option value="1">通知する</option>
					<option value="0">通知しない</option>
				</select>
				<label>明日の天気</label>
				<select id="weather_tomorrow">
					<option value="1">通知する</option>
					<option value="0">通知しない</option>
				</select>
			</div>
		</div> <!-- .main -->
	</section>';

$video = '<video id="mirror" class="mirror" autoplay></video>
	<canvas id="canvas" class="temp_pic"></canvas>';
?>

<!DOCTYPE html>
<html lang="ja">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="keywords" content="">
	<meta name="description" content="">
	<meta name="author" content="">
	<meta name="viewport" content="width=device-width,maximum-scale=1.0">
	<link rel="stylesheet" href="css/style.css">
	<link rel="stylesheet" href="css/test.css">
	<title>FaMirror</title>
</head>

<body>
	<?php
	if($sign_up_flag || $mirror_flag) {
		if($sign_up_flag) {
			echo '<style type="text/css"><!-- #base { display : none; } --></style>';
			echo $sign_up;
		}
		echo $video;
		echo $base;
		echo $setting;
		echo $detail;
		echo $setting_weather;
	} else {
		echo $top;
	}

	if($sign_up_flag || $mirror_flag) {
		echo '<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>';
		echo '<script type="text/javascript" src="./js/mirror.js"></script>';
		echo '<script type="text/javascript" src="./js/db.js"></script>';
		echo '<script type="text/javascript" src="./js/facepp.js"></script>';
		echo '<script type="text/javascript" src="./js/speak.js"></script>';
		echo '<script type="text/javascript" src="./js/weather.js"></script>';
		echo '<script type="text/javascript" src="./js/script.js"></script>';
		if($sign_up_flag) {
			echo '<script type="text/javascript" src="./js/sign_up.js"></script>';
		}
	}
	?>
</body>
</html>

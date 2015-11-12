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
			<!-- <div class="square"></div> -->
		</div>
		<div class="exp">
			<p id="message"><!-- 四角形の中に顔を入れて -->画面に顔が映るようにして、カメラボタンを押してください。</p>
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
			<!-- <div class="square"></div> -->
		</div>

		<div class="exp">
			<p id="message"><!-- 四角形の中に顔を入れて、 -->画面に顔が映るようにして、認証ボタンを押してください。</p>
			<button class="text_button" id="auth">認証</button>
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
		</div>
		<button onclick="location.href=\'php/OAuth.php?id=add\'" class="add"></button>
	</section>';

$detail = '<section id="detail" class="detail">
		<header class="siteheader">
			<div class="operate">
				<button id="detail_back" class="back"></button>
				<button id="item_profile" class="gear"></button>
			</div>
			<div class="icon"><div id="detail_icon"></div></div>
			<h1 id="detail_user_name"></h1>
		</header>
		<div class="main">
			<ul id="detail_list">
				<li id="item_weather"><div></div><span>天気</span></li>
				<li id="item_trash"><div></div>ごみ</li>
				<li id="item_calendar"><div></div>カレンダー</li>
				<li id="item_gmail"><div></div>Gmail</li>
				<li id="item_timetable"><div></div>時間割</li>
				<li id="item_transportation"><div></div>交通機関</li>
			</ul>
		</div>
	</section>';

$setting_profile = '<section id="setting_profile" class="setting_profile settings">
		<header class="page_header">
			<h1>ユーザ設定</h1>
			<button id="profile_back" class="back"></button>
		</header>
		<div class="main">
			<div class="area">
				<!-- <h2>エリア</h2> -->
				<label>表示名</label>
				<input id="name" type="text">

				<label>読み方(ひらがな)</label>
				<input id="name_p" type="text">

				<label>アイコン画像</label>
				<img id="profile_icon" class="profile_icon" alt="画像が設定されていません">
				<div class="file">
					画像を参照...
					<input id="profile_icon_file" type="file"/>
				</div>
				<span id="profile_icon_file_name"></span>
			</div>
			<div class="delete_button">
				<button id="delete_button" class="text_button">ユーザ削除</button>
			</div>
		</div> <!-- .main -->
	</section>';

$setting_weather = '<section id="setting_weather" class="setting_weather settings">
		<header class="page_header">
			<h1>天気</h1>
			<button class="settings_back back"></button>
		</header>
		<div class="main">
			<h2>通知</h2>
			<div class="onoff">
				<input id="weather_notification" class="settings_notification" type="checkbox">
				<div></div>
			</div>
			<div class="area">
				<h2>地域設定</h2>
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
				<ul>
					<li>
						<label>詳細情報
							<input id="weather_detail" type="checkbox">
							<p>天気の詳細をお知らせします。</p>
						</label>
					</li>
					<li>
						<label>最高気温/最低温度
							<input id="weather_temperature" type="checkbox">
							<p>天気の詳細をお知らせします。</p>
						</label>
					</li>
					<li>
						<label>明日の天気
							<input id="weather_tomorrow" type="checkbox">
							<p>天気の詳細をお知らせします。</p>
						</label>
					</li>
				</ul>
			</div>
			<div class="sample play">
				<h2>サンプル音声</h2>
				<button class="listen_sample"></button>
			</div>

		</div> <!-- .main -->
	</section>';

$setting_trash = '<section id="setting_trash" class="setting_trash settings">
		<header class="page_header">
			<h1>ゴミ</h1>
			<button class="settings_back back"></button>
		</header>
		<div class="main">
			<h2>通知</h2>
			<div class="onoff">
				<input class="settings_notification" type="checkbox" checked>
				<div></div>
			</div>
			<div class="area">
				<h2>地域設定</h2>
				<label>都道府県</label>
				<select>
					<option value="-1">選択してください</option>
				</select>

				<label>市区町村</label>
				<select>
					<option value="-1">先に都道府県を選択してください</option>
				</select>

				<label>地区&#9312;</label>
				<select>
					<option value="-1">先に都道府県を選択してください</option>
				</select>

				<label>地区&#9313;</label>
				<select>
					<option value="-1">先に都道府県を選択してください</option>
				</select>
			</div>
			<div class="notification">
				<h2>通知設定</h2>
				<ul>
					<li>
						<label>明日のごみ回収
							<input id="trash_tomorrow" type="checkbox">
							<p>翌日にごみ回収がある場合お知らせします。</p>
						</label>
					</li>
				</ul>
			</div>
			<div class="sample play">
				<h2>サンプル音声</h2>
				<button class="listen_sample"></button>
			</div>

		</div> <!-- .main -->
	</section>';

$setting_calendar = '<section id="setting_calendar" class="setting_calendar settings">
		<header class="page_header">
			<h1>カレンダー</h1>
			<button class="settings_back back"></button>
		</header>
		<div class="main">
			<h2>通知</h2>
			<div class="onoff">
				<input id="calendar_notification" class="settings_notification" type="checkbox">
				<div></div>
			</div>
			<div class="notification">
				<h2>通知設定</h2>
				<ul>
					<li>
						<label>開始時刻
							<input type="checkbox" id="calendar_start">
							<p>カレンダーに登録されているスケジュールの開始時刻をお知らせします。</p>
						</label>
					</li>
					<li>
						<label>終了時刻
							<input type="checkbox" id="calendar_end">
							<p>カレンダーに登録されているスケジュールの終了時刻をお知らせします。</p>
						</label>
					</li>
					<li>
						<label>場所
							<input type="checkbox" id="calendar_location">
							<p>カレンダーに登録されているスケジュールの場所をお知らせします。</p>
						</label>
					</li>
					<li>
						<label>詳細説明
							<input type="checkbox" id="calendar_description">
							<p>カレンダーに登録されているスケジュールの詳細説明をお知らせします。</p></label>
					</li>
				</ul>
			</div>
			<div class="sample play">
				<h2>サンプル音声</h2>
				<button class="listen_sample"></button>
			</div>

		</div> <!-- .main -->
	</section>';

$video = '<section id="video" class="video">
		<video id="mirror" class="mirror" autoplay></video>
		<canvas id="canvas" class="temp_pic"></canvas>
	</section>';
?>

<!DOCTYPE html>
<html lang="ja">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="keywords" content="">
	<meta name="description" content="">
	<meta name="author" content="">
	<meta name="viewport" content="width=device-width,maximum-scale=1.0">
	<link rel="stylesheet" href="css/forKappa.css">
	<link rel="stylesheet" href="css/style.css">
	<link rel="stylesheet" href="css/header.css">
	<link rel="stylesheet" href="css/top.css">
	<link rel="stylesheet" href="css/sign_up.css">
	<link rel="stylesheet" href="css/base.css">
	<link rel="stylesheet" href="css/detail.css">
	<link rel="stylesheet" href="css/setting.css">
	<link rel="stylesheet" href="css/settings.css">
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
		echo $setting_weather;
		echo $setting_trash;
		echo $setting_calendar;
		echo $setting_profile;
		echo $detail;
		echo $setting;
		echo $base;
	} else {
		echo $top;
	}

	if($sign_up_flag || $mirror_flag) {
		echo '<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>';
		echo '<script type="text/javascript" src="./js/mirror.js"></script>';
		echo '<script type="text/javascript" src="./js/db.js"></script>';
		echo '<script type="text/javascript" src="./js/facepp.js"></script>';
		echo '<script type="text/javascript" src="./js/speak.js"></script>';
		echo '<script type="text/javascript" src="./js/profile.js"></script>';
		echo '<script type="text/javascript" src="./js/setting.js"></script>';
		echo '<script type="text/javascript" src="./js/script.js"></script>';
		if($sign_up_flag) {
			echo '<script type="text/javascript" src="./js/sign_up.js"></script>';
		}
	}
	?>
</body>
</html>

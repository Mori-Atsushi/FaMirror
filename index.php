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
	</section>';

$base = '<section id="base" class="base">
		<header class="page_header">
			<h1></h1>
			<button id="fullscreen" class="volume"></button>
			<button class="gear" id="setting_b"></button>
			<button class="info"></button>
		</header>

		<div class="main">
			<!-- <div class="square"></div> -->
		</div>

		<div class="exp">
			<p id="message"><!-- 四角形の中に顔を入れて、 -->画面に顔が映るようにして、認証ボタンを押してください。</p>
			<button class="text_button" id="auth">認証</button>
		</div>

		<div class="bottom show">
			<button class="text_button" id="auth">認証解除</button>
			<div class="showhide"></div>
			<div class="message">
				<p>おはようございます、○○さん。</p>
				<div class="list">
					<p>今日の予定は</p>
					<ul>
						<li>可燃ごみの回収</li>
						<li>ホリケンさんの誕生日</li>
						<li>しゃべくり0007の記録</li>
						<li>ジャンプの発売日</li>
					</ul>
				</div>
				<p>今日の天気は、晴れのち曇りです。</p>
				<p>バスは…</p>
			</div>
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
				<li id="item_weather"><div></div>天気</li>
				<li id="item_trash"><div></div>ごみ</li>
				<li id="item_calendar"><div></div>カレンダー</li>
				<li id="item_timetable"><div></div>時間割</li>
				<li id="item_bus"><div></div>バス</li>
				<li id="item_horoscope"><div></div>星座占い</li>
				<li id="item_lunch"><div></div>給食・学食</li>
				<li id="item_alarm"><div></div>あと何分</li>
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

$setting_weather = '<section id="setting_weather" class="settings">
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
				<select id="weather_prefecture" class="setting_select necessary">
					<option value="-1">選択してください</option>
				</select>
				<label>地区</label>
				<select id="weather_area" class="necessary">
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

$setting_trash = '<section id="setting_trash" class="settings">
		<header class="page_header">
			<h1>ゴミ</h1>
			<button class="settings_back back"></button>
		</header>
		<div class="main">
			<h2>通知</h2>
			<div class="onoff">
				<input id="trash_notification" class="settings_notification" type="checkbox" checked>
				<div></div>
			</div>
			<div class="area">
				<h2>地域設定</h2>
				<label>都道府県</label>
				<select id="trash_prefecture" class="setting_select necessary">
					<option value="-1">選択してください</option>
				</select>

				<label>市区町村</label>
				<select id="trash_city" class="setting_select necessary">
				</select>

				<label>地区&#9312;</label>
				<select id="trash_area1" class="setting_select necessary">
				</select>

				<label>地区&#9313;</label>
				<select id="trash_area2" class="necessary">
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

$setting_calendar = '<section id="setting_calendar" class="settings">
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

$setting_timetable = '<section id="setting_timetable" class="settings">
		<header class="page_header">
			<h1>時間割</h1>
			<button class="settings_back back"></button>
		</header>
		<div class="main">
			<h2>通知</h2>
			<div class="onoff">
				<input id="timetable_notification" class="settings_notification" type="checkbox" checked>
				<div></div>
			</div>
			<div class="area">
				<h2>学校設定</h2>
				<label>学校名</label>
				<select id="timetable_school" class="setting_select necessary">
					<option value="-1">選択してください</option>
				</select>
				<label>学年</label>
				<select id="timetable_grade" class="setting_select necessary">
				</select>
				<label>クラス・学科</label>
				<select id="timetable_class" class="necessary">
				</select>
			</div>

			<div class="notification">
				<h2>通知設定</h2>
				<ul>
					<li>
						<label>開始時刻
							<input id="timetable_start" type="checkbox">
							<p>授業の開始時刻をお知らせします。</p>
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

$setting_bus = '<section id="setting_bus" class="settings">
		<header class="page_header">
			<h1>バス</h1>
			<button class="settings_back back"></button>
		</header>
		<div class="main">
			<h2>通知</h2>
			<div class="onoff">
				<input id="bus_notification" class="settings_notification" type="checkbox" checked>
				<div></div>
			</div>
			<div class="area">
				<h2>バス設定</h2>
				<label>バス名</label>
				<select id="bus_busname" class="setting_select necessary">
					<option value="-1">選択してください</option>
				</select>
				<label>バス停名</label>
				<select id="bus_stop" class="setting_select necessary">
				</select>
				<label>ルート・方面</label>
				<select id="bus_route" class="necessary">
				</select>
				<label>通知する本数</label>
				<select id="bus_howmany">
					<option value="1">1本</option>
					<option value="2">2本</option>
					<option value="3">3本</option>
					<option value="4">4本</option>
					<option value="5">5本</option>
				</select>
			</div>

			<div class="sample play">
				<h2>サンプル音声</h2>
				<button class="listen_sample"></button>
			</div>

		</div> <!-- .main -->
	</section>';

$setting_horoscope = '<section id="setting_horoscope" class="settings">
		<header class="page_header">
			<h1>占い</h1>
			<button class="settings_back back"></button>
		</header>
		<div class="main">
			<h2>通知</h2>
			<div class="onoff">
				<input id="horoscope_notification" class="settings_notification" type="checkbox">
				<div></div>
			</div>
			<div class="area">
				<h2>星座設定</h2>
				<label>星座</label>
				<select id="horoscope_star" class="necessary">
					<option value="-1">選択してください</option>
					<option value="0">牡羊座</option>
					<option value="1">牡牛座</option>
					<option value="2">双子座</option>
					<option value="3">蟹座</option>
					<option value="4">獅子座</option>
					<option value="5">乙女座</option>
					<option value="6">天秤座</option>
					<option value="7">蠍座</option>
					<option value="8">射手座</option>
					<option value="9">山羊座</option>
					<option value="10">水瓶座</option>
					<option value="11">魚座</option>
				</select>
			</div>

			<div class="notification">
				<h2>通知設定</h2>
				<ul>
					<li>
						<label>コメント
							<input id="horoscope_detail" type="checkbox">
							<p>コメントをお知らせします。</p>
						</label>
					</li>
					<li>
						<label>ラッキーアイテム
							<input id="horoscope_item" type="checkbox">
							<p>ラッキーアイテムをお知らせします。</p>
						</label>
					</li>
					<li>
						<label>ラッキーカラー
							<input id="horoscope_color" type="checkbox">
							<p>ラッキーカラーをお知らせします。</p>
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

$setting_lunch = '<section id="setting_lunch" class="settings">
		<header class="page_header">
			<h1>給食</h1>
			<button class="settings_back back"></button>
		</header>
		<div class="main">
			<h2>通知</h2>
			<div class="onoff">
				<input id="lunch_notification" class="settings_notification" type="checkbox">
				<div></div>
			</div>
			<div class="area">
				<h2>学校設定</h2>
				<label>学校名</label>
				<select id="lunch_school" class="necessary">
					<option value="-1">選択してください</option>
				</select>
			</div>

			<div class="notification">
				<h2>通知設定</h2>
				<ul>
					<li>
						<label>摂取カロリー
							<input id="lunch_calorie" type="checkbox">
							<p>摂取カロリーをお知らせします。</p>
						</label>
					</li>
					<li>
						<label>明日の給食
							<input id="lunch_tomorrow" type="checkbox">
							<p>明日の給食もお知らせします。</p>
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

$setting_alarm = '<section id="setting_alarm" class="settings setting_alarm">
		<header class="page_header">
			<h1>残り時間</h1>
			<button class="settings_back back"></button>
		</header>
		<div class="main">
			<h2>通知</h2>
			<div class="onoff">
				<input id="alarm_notification" class="settings_notification" type="checkbox">
				<div></div>
			</div>
			<div class="area">
				<h2>時刻と内容</h2>
				<input id="alarm_time" class="necessary" type="time">
				<input id="alarm_content" class="necessary" type="text" placeholder="例) 家を出る時間">
			</div>

			<div class="notification">
				<h2>通知設定</h2>
				<ul>
					<li>
					<li>
						<label>日曜日
							<input id="alarm_sun" type="checkbox">
						</label>
					</li>
						<label>月曜日
							<input id="alarm_mon" type="checkbox">
						</label>
					</li>
					<li>
						<label>火曜日
							<input id="alarm_tue" type="checkbox">
						</label>
					</li>
					<li>
						<label>水曜日
							<input id="alarm_wed" type="checkbox">
						</label>
					</li>
					<li>
						<label>木曜日
							<input id="alarm_thu" type="checkbox">
						</label>
					</li>
					<li>
						<label>金曜日
							<input id="alarm_fri" type="checkbox">
						</label>
					</li>
					<li>
						<label>土曜日
							<input id="alarm_sat" type="checkbox">
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

$popup = '	<div id="black_screen" class="black_screen">
			<div id="get_name_popup" class="get_name_popup">
			<h2>表示名と読み方を入力してください</h2>
			<label for="name">表示名</label>
			<input type="text" name="name" class="name" value="' . $_SESSION['name'] . '">
			<label for="name_p">読み方(ひらがな)</label>
			<input type="text" name="name_p" class="name_p" value="">
			<button id="get_name_submit">完了</button>
		</div>
		<div id="user_delete" class="OK_Cancel_popup">
			<h2>次のユーザを削除してよろしいですか</h2>
			<p></p>
			<div class="OK_button">
				<button id="user_delete_ok">OK</button>
			</div>
			<div class="Cancel_button">
				<button id="user_delete_cancel">Cancel</button>
			</div>
		</div>
		<div id="deta_error" class="OK_popup">
			<h2>未記入項目があります</h2>
			<p>未記入項目 : <span></span></p>
			<button id="data_error_ok">OK</button>
		</div>
 
	</div>';

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
	<link rel="shortcut icon" href="img/favicon.png">
	<link rel="apple-touch-icon" href="img/webclipicon.png">
	<meta name="viewport" content="width=device-width,maximum-scale=1.0">
	<link rel="stylesheet" href="css/style.css">
	<link rel="stylesheet" href="css/header.css">
	<link rel="stylesheet" href="css/top.css">
	<link rel="stylesheet" href="css/sign_up.css">
	<link rel="stylesheet" href="css/base.css">
	<link rel="stylesheet" href="css/detail.css">
	<link rel="stylesheet" href="css/setting.css">
	<link rel="stylesheet" href="css/settings.css">
	<link rel="stylesheet" href="css/popup.css">
	<link rel="stylesheet" href="css/forKappa.css">
	<title>FaMirror</title>
</head>

<body>
	<?php
	if($sign_up_flag || $mirror_flag) {
		echo $popup;
		if($sign_up_flag) {
			echo '<style type="text/css"><!-- #base { display : none; } --></style>';
			echo $sign_up;
		}
		echo $video;
		echo $setting_weather;
		echo $setting_trash;
		echo $setting_calendar;
		echo $setting_timetable;
		echo $setting_bus;
		echo $setting_horoscope;
		echo $setting_lunch;
		echo $setting_alarm;
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
		echo '<script type="text/javascript" src="./js/alarm.js"></script>';
		echo '<script type="text/javascript" src="./js/script.js"></script>';
		if($sign_up_flag) {
			echo '<script type="text/javascript" src="./js/sign_up.js"></script>';
		}
	}
	?>
</body>
</html>

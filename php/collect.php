<?php
$access_token = ''; //google用

function google($user) {
	global $access_token;
	// アプリケーション設定
	define('CONSUMER_KEY', '482245107839-ltqcd5n13loc9oerhh00gpaehsn6okt3.apps.googleusercontent.com');
	define('CONSUMER_SECRET', '56bapESDOFaQgDdeBg-M4-_W');

	// URL
	define('TOKEN_URL', 'https://accounts.google.com/o/oauth2/token');

	$params = array(
		'grant_type' => 'refresh_token',
		'refresh_token' => $user['refresh_token'],
		'client_id' => CONSUMER_KEY,
		'client_secret' => CONSUMER_SECRET,
	);

	// POST送信
	$options = array('http' => array(
		'method' => 'POST',
		'content' => http_build_query($params)
	));
	$res = file_get_contents(TOKEN_URL, false, stream_context_create($options));

	// レスポンス取得
	$token = json_decode($res, true);

	if(isset($token['error'])){
		echo 'エラー発生';
		exit;
	}

	$access_token = $token['access_token'];
}

function weather($user) {
	$url = 'http://weather.livedoor.com/forecast/webservice/json/v1?city=' . $user['weather_area'];
	$res = json_decode(file_get_contents($url), true );

	$array = array('name' => '天気（' . $res['location']['city'] . '）');
	$list[] = array('name' => '今日の天気', 'content' => $res['forecasts'][0]['telop']);

	$return = '今日の' . $res['location']['city'] . 'の天気は、' . $res['forecasts'][0]['telop'] . '、です。';


	if($user['weather_temperature']) {
		$min = $res['forecasts'][0]['temperature']['min'];
		$max = $res['forecasts'][0]['temperature']['max'];

		if($min) {
			$list[] = array('name' => '最低気温', 'content' => $min['celsius'] . '℃');
			$return = $return . '最低気温は、' . $min['celsius'] . '度、';
		}
		if($max) {
			$list[] = array('name' => '最高気温', 'content' => $max['celsius'] . '℃');
			$return = $return . '最高気温は、' . $max['celsius'] . '度';
		}
		if($min || $max)
			$return = $return . 'の、予報です。';
	}

	if($user['weather_detail']) {
		$list[] = array('name' => '詳細情報', 'content' => $res['description']['text']);
		$return = $return . $res['description']['text'];
	}

	if($user['weather_tomorrow']) {
		$list[] = array('name' => '明日の天気', 'content' => $res['forecasts'][0]['telop']);
		$return = $return . '明日の天気は、' . $res['forecasts'][0]['telop'] . 'でしょう。';
	}

	$array += array('speak' => $return, 'list' => $list);
	return $array;
}

function trash($user, $conn, $day, $week) {

	if($user['trash_area2'] == 'ok')
		$trash_id = $user['trash_area1'];
	else
		$trash_id = $user['trash_area2'];


	if($conn) {
		mysql_select_db('famirror', $conn); mysql_query('SET NAMES utf8', $conn );
		$sql = 'SELECT area1, area2 FROM trashes WHERE id = ' . $trash_id;
		$result = mysql_fetch_assoc(mysql_query($sql));

		$area = $result['area1'];
		if($result['area2'] != '')
			$area = $area . ' ' . $result['area2'];

		$array = array('name' => 'ゴミ（' . $area .  '）');

		$return = '今日回収されるゴミは、';

		if(($m = check_trash($conn, $day, $week, $trash_id)) == '') {
			$list[] = array('name' => '今日のゴミ', 'content' => 'なし');
			$return = $return . 'ありません。';
		} else {
			$list[] = array('name' => '今日のゴミ', 'content' => $m);
			$return = $return . $m . 'です。';
		}

		if($user['trash_tomorrow']) {
			$return = $return . '明日回収されるゴミは、';
			if(($m = check_trash($conn, $day + 1, $week + 1, $trash_id)) == '') {
				$list[] = array('name' => '明日のゴミ', 'content' => 'なし');
				$return = $return . 'ありません。';		
			} else {
				$list[] = array('name' => '明日のゴミ', 'content' => $m);
				$return = $return . $m . 'です。';
			}
		}

		$array += array('speak' => $return, 'list' => $list);
		return $array;	
	}
}

function check_trash($conn, $day, $week, $trash_id) {
	$week_ja = array('日', '月', '火', '水', '木', '金', '土');
	$week_num = ceil($day / 7);
	$week_to = $week_ja[$week];
	$return = '';

	if($conn) {
		mysql_select_db('famirror', $conn); mysql_query('SET NAMES utf8', $conn );
		$sql = 'SELECT type, wday FROM trash_types WHERE trash_id = ' . $trash_id;
		$result = mysql_query($sql);
		while($row = mysql_fetch_assoc($result)) {
			if(strpos($row['wday'], $week_to) !== false) {
				if(strpos($row['wday'], $week_to . '2') !== false) {
					$return = $return . $row['type'] . '、';
				} else {
					for($i = 1; $i <= 5; $i++) {
						if(strpos($row['wday'], $week_to . $i) !== false) {
							break;
						}
					}
					if($i > 5)
						$return = $return . $row['type'] . '、';
				}
			}
		}
	}

	return $return;	
}

function calendar($user) {
	global $access_token;
	if($access_token == '') {
		google($user);
	}

	$year = date(Y); $month = date(m); $day = date(d);
	$today = $year . '-' . $month . '-' . $day;
	$url = 'https://www.googleapis.com/calendar/v3/calendars/' . $user['user_mail'] . '/events';
	$url = $url . '?access_token=' . $access_token;
	$url = $url . '&timeMin=' . $today . 'T00:00:00%2b0900&timeMax=' . $today .'T23:59:59%2b0900';
	$res = file_get_contents($url);
	$cal = json_decode($res, true);
	if(isset($cal['error'])){
		echo 'エラー発生';
		exit;
	}

	$num = count($cal['items']);

	$array = array('name' => 'カレンダー（' . $user['user_mail'] .  '）');

	$list[] = array('name' => '予定件数', 'content' => $num . '件');

	if($num == 0) {
		$return = '今日の予定はありません。';
	} else {
		$return = '今日の予定は' . $num . '件です。';

		//ソート処理
		for($i = 1; $i < count($cal['items']); $i++) {
			$min = $cal['items'][$i - 1]['start']['dateTime'];
			$k = $i - 1;
			for($j = $i; $j < count($cal['items']); $j++) {
				if(strnatcmp($cal['items'][$j]['start']['dateTime'], $min) == '-1') {
					$min = $cal['items'][$j]['start']['dateTime'];
					$k = $j;
				}
			}
			if(($i - 1) != $k) {
				$tmp = $cal['items'][$i - 1];
				$cal['items'][$i - 1] = $cal['items'][$k];
				$cal['items'][$k] = $tmp;
			}
		}

		for($i = 0; $i < count($cal['items']); $i++) {
			$content = '';
			if($user['calendar_start'] || $user['calendar_end']) {
				$time = '〜';
				if($user['calendar_start']) {
					if(isset($cal['items'][$i]['start']['dateTime'])) {
						$start = change_time($cal['items'][$i]['start']['dateTime'], $month, $day);
						$time = $start . $time;
						$return = $return . $start . 'から';				
					}
				}
				if($user['calendar_end']) {
					if(isset($cal['items'][$i]['start']['dateTime'])) {
						$end = change_time($cal['items'][$i]['end']['dateTime'], $month, $day);
						$time = $time . $end;
						$return = $return . $end . 'まで';
					} else {
						$end = change_date($cal['items'][$i]['end']['date'], $month);
						$time = $time . $end;
						$return = $return . $end . 'まで';				
					}
				}
				$content = '（' . $time . '）';
			}
			if($user['calendar_location'] && isset($cal['items'][$i]['location'])) {
				$pleace = $cal['items'][$i]['location'];
				$content = '（場所：' . $pleace . '）' . $content;
				$return = $return . $pleace . 'で、';	
			}
			$return = $return . $cal['items'][$i]['summary'] . 'です。';
			$content = $cal['items'][$i]['summary'] . $content;

			if($user['calendar_description'] && isset($cal['items'][$i]['description'])) {
				$return = $return . $cal['items'][$i]['description'] . '。';	
				$content = $content . '</br>' . $cal['items'][$i]['description'];
			}
			$list[] = array('name' => ($i + 1) . '件目', 'content' => $content);
		}

		$return = $return . '今日の予定は以上です。';
	}

	$array += array('speak' => $return, 'list' => $list);
	return $array;
}

function change_time($time, $month, $day) {
	$m = $time[5] . $time[6];
	$d = $time[8] . $time[9];
	$hour = $time[11] . $time[12];
	$min = $time[14] . $time[15];

	if($m != $month)
		$return = check_zero($m) . '月';
	if($d != $day)
		$return = $return . check_zero($d) . '日';	
	$return = $return . check_zero($hour) . '時';
	if($min != '00')
		$return = $return . check_zero($min) . '分';

	return $return;
}

function change_date($time, $month) {
	$m = $time[5] . $time[6];
	$d = $time[8] . $time[9];

	if($m != $month)
		$return = check_zero($m) . '月';
	$return = $return . check_zero($d) . '日';	

	return $return;
}

function check_zero($str) {
	if($str[0] == '0')
		return $str[1];
	return $str;
}

function horoscope($user) {
	$year = date(Y); $month = date(m); $day = date(d);
	$today = $year . '/' . $month . '/' . $day;
	$url = 'http://api.jugemkey.jp/api/horoscope/free/' . $today;
	$res = json_decode(file_get_contents($url), true);
	$res = $res['horoscope'][$today][$user['horoscope_star']];

	$array = array('name' => '星座占い（' . $res['sign'] . '）');
	$list[] = array('name' => '順位', 'content' => $res['rank'] . '位');
	$message = '今日の' . $res['sign'] . 'の運勢は、第' . $res['rank'] . '位です。';
	if($user['horoscope_detail']) {
		$message = $message . $res['content'];
		$list[] = array('name' => '詳細', 'content' => $res['content']);
	}
	if($user['horoscope_item']) {
		$message = $message . 'ラッキーアイテムは、' . $res['item'] . 'です。';
		$list[] = array('name' => 'ラッキーアイテム', 'content' => $res['item']);
	}
	if($user['horoscope_color']) {
		$message = $message . 'ラッキーカラーは、' . $res['color'] . 'です。';
		$list[] = array('name' => 'ラッキーカラー', 'content' => $res['color']);
	}

	$array += array('speak' => $message, 'list' => $list);
	return $array;
}

function timetable($user, $conn, $week) {
	if($conn) {
		mysql_select_db('famirror', $conn); mysql_query('SET NAMES utf8', $conn );
		$sql = 'SELECT department, grade FROM timetables WHERE id = ' . $user['timetable_class'];
		$result = mysql_fetch_assoc(mysql_query($sql));

		$array = array('name' => '時間割（' . $result['grade'] . '年 ' . $result['department'] . '）');

		$sql = 'SELECT name, start_time FROM subjects WHERE timetable_id = ' . $user['timetable_class'] . ' AND wday = ' . $week . ' ORDER BY start_time ASC';
		$result = mysql_query($sql);
		$message = '今日の授業は、';
		$i = 0; $temp = '';
		while($row = mysql_fetch_assoc($result)) {
			$content = $row['name'];
			if($user['timetable_start']) {
				$start = timetable_change_time($row['start_time']);
				$message = $message . $start . 'から、';
				$content = $content . '（' . $start . '〜）';
			}
			$list[] = array('name' => ($i + 1) . '限目', 'content' => $content);
			$message = $message . $row['name'] . '。';
			$i++;
		}
		if($i == 0)
			$message = $message . 'ありません。';
		else
			$message = $message . 'です。';

		$array += array('speak' => $message, 'list' => $list);	}
	return $array;
}

function timetable_change_time($time) {
	$h = $time[0] . $time[1];
	$m = $time[3] . $time[4];

	$return = check_zero($h) . '時';
	if($m != '00')
		$return = $return . check_zero($m) . '分';	

	return $return;
}

function bus($user, $conn) {
	$h = date(H); $m = date(i);
	$now = $h . ':' . $m . ':00';
	if($conn) {
		mysql_select_db('famirror', $conn); mysql_query('SET NAMES utf8', $conn );
		$sql = 'SELECT route, stop FROM bus_stops WHERE id = ' . $user['bus_route'];
		$result = mysql_fetch_assoc(mysql_query($sql));
		$array = array('name' => 'バス（' . $result['route'] . ' ' . $result['stop'] . '）');

		$sql = 'SELECT start_time FROM bus_tables WHERE bus_stop_id = ' . $user['bus_route'] . ' ORDER BY start_time ASC';
		$result = mysql_query($sql);
		$message = '次に来るバスは、';

		$i = 0;
		while($row = mysql_fetch_assoc($result)) {
			if(strnatcmp($now, $row['start_time']) == -1) {
				$time = timetable_change_time($row['start_time']);
				$list[] = array('name' => ($i + 1) . '本目', 'content' => $time);
				if($i == 1)
					$message = $message . '続いて、';
				$message = $message . $time . '、';
				$i++;
				if($i >= $user['bus_howmany']) 
					break;
			}
		}
		if($i == 0) {
			$list[] = array('name' => '注意', 'content' => '今日来るバスはもうありません。');
			$message = $message . 'もうありません。';
		} else {
			$message = $message . 'です。';
		}

		$array += array('speak' => $message, 'list' => $list);
		return $array;
	}
}

function lunch($user, $conn) {
	$month = date(m); $day = date(d);
	$today = $year . '-' . $month . '-' . $day;
	$array = array('name' => '給食（明石工業高等専門学校）');
	$message = '今日の給食は、';
	$temp = get_lunch($user, $conn, $month, $day);
	$message = $message . $temp['speak'];
	$list = array();
	$list = array_merge($list, $temp['menu']);
	if($user['lunch_tomorrow']) {
		$day = $day + 1;
		$temp = get_lunch($user, $conn, $month, $day);
		$message = $message . '明日の給食は、';
		$message = $message . $temp['speak'];
		$list = array_merge($list, $temp['menu']);
	}

	$array += array('speak' => $message, 'list' => $list);
	return $array;
}

function get_lunch($user, $conn, $month, $day) {
	if($conn) {
		$year = date(Y);
		$today = $year . '-' . $month . '-' . $day;
		mysql_select_db('famirror', $conn); mysql_query('SET NAMES utf8', $conn );
		$sql = 'SELECT menu, calorie FROM cafemenus WHERE school_id = ' . $user['lunch_school'] . ' AND date = "' . $today . '"';
		$result = mysql_query($sql);
		$i = 0;
		while($row = mysql_fetch_assoc($result)) {
			$content = $row['menu'];
			if($i > 0)
				$message = $message . 'もしくは、';
			$message = $message . $row['menu'] . '、';
			if($user['lunch_calorie']) {
				$message = $message . 'カロリーは、' . $row['calorie'] . 'キロカロリー、';
				$content = $content . '（' . $row['calorie'] . 'kcal）';
			}
			$menu[] = array('name' => $month . '/' . $day . ' メニュー' . ($i + 1), 'content' => $content);
			$i++;
		}
		if($i == 0) {
			$message = $message . 'ありません。';
			$menu[] = array('name' => $month . '/' . $day . ' メニュー', 'content' => 'ありません');

		} else {
			$message = $message . 'です。';
		}
		$array = array('speak' => $message, 'menu' => $menu);
		return $array;
	}
}
?>
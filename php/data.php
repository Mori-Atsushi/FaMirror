<?php
session_start();
$sign_up_flag = !empty($_SESSION['email']);

$conn = mysql_connect('localhost', 'famirror', 'famirrorproject');

if($conn) {
	mysql_select_db('famirror', $conn);
	$take_info = 'user_name, user_name_p, img, weather_notification, weather_prefecture, weather_area, weather_detail, weather_temperature, weather_tomorrow, trash_notification, calendar_notification';
	$sql = 'SELECT ' . $take_info . ' FROM user WHERE family_id = ' . $_SESSION['family'] . ' ORDER BY user_id ASC';
	$user = mysql_query($sql);
	$num = 0;
	while($row = mysql_fetch_assoc($user)) {
		$member[$num] = $row;
		$member[$num]['setting'] = array(
			array(
				'name' => 'weather',
				'notification' => $row['weather_notification'],
				'config' => array(
					'prefecture' => $row['weather_prefecture'],
					'area' => $row['weather_area'],
					'detail' => $row['weather_detail'],
					'temperature' => $row['weather_temperature'],
					'tomorrow' => $row['weather_tomorrow']
					)
				),
			array(
				'name' => 'trash',
				'notification' => $row['trash_notification']
				),
			array(
				'name' => 'calendar',
				'notification' => $row['calendar_notification']
				)
		);
		$num++;
	}
	if($sign_up_flag) {
		$member[$num]['user_name'] = $_SESSION['name'];
	}
}

header('Content-Type: application/json; charset=utf-8');
echo json_encode($member);
?>
<?php
session_start();
$sign_up_flag = !empty($_SESSION['email']);

$conn = mysql_connect('localhost', 'famirror', 'famirrorproject');

if($conn) {
	mysql_select_db('famirror', $conn);
	$sql = 'SELECT * FROM user WHERE family_id = ' . $_SESSION['family'] . ' ORDER BY user_id ASC';
	$user = mysql_query($sql);
	$num = 0;
	while($row = mysql_fetch_assoc($user)) {
		$member[$num]['user_name'] = $row['user_name'];
		$member[$num]['user_name_p'] = $row['user_name_p'];
		$member[$num]['img'] = $row['img'];
		$member[$num]['setting'] = array(
			array(
				'name' => 'weather',
				'notification' => $row['weather_notification'],
				'config' => array(
					'select' => array(
						array(
							'name' => 'prefecture',
							'val' => $row['weather_prefecture']
						),
						array(
							'name' => 'area',
							'val' => $row['weather_area']
						)
					),
					'onof' => array(
						array(
							'name' => 'detail',
							'notification' => $row['weather_detail']
						),
						array(
							'name' => 'temperature',
							'notification' => $row['weather_temperature']
						),
						array(
							'name' => 'tomorrow',
							'notification' => $row['weather_tomorrow']
						)
					)
				)
			),
			array(
				'name' => 'trash',
				'notification' => $row['trash_notification']
			),
			array(
				'name' => 'calendar',
				'notification' => $row['calendar_notification'],
				'config' => array(
					'onof' => array(
						array(
							'name' => 'start',
							'notification' => $row['calendar_start']
						),
						array(
							'name' => 'end',
							'notification' => $row['calendar_end']
						),
						array(
							'name' => 'location',
							'notification' => $row['calendar_location']
						),
						array(
							'name' => 'description',
							'notification' => $row['calendar_description']
						)
					)
				)
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
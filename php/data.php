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
				'notification' => $row['trash_notification'],
				'config' => array(
					'select' => array(
						array(
							'name' => 'prefecture',
							'val' => $row['trash_prefecture']
						),
						array(
							'name' => 'city',
							'val' => $row['trash_city']
						),
						array(
							'name' => 'area1',
							'val' => $row['trash_area1']
						),
						array(
							'name' => 'area2',
							'val' => $row['trash_area2']
						),
					),
					'onof' => array(
						array(
							'name' => 'tomorrow',
							'notification' => $row['trash_tomorrow']
						)
					)
				)
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
			),
			array(
				'name' => 'timetable',
				'notification' => $row['timetable_notification'],
				'config' => array(
					'select' => array(
						array(
							'name' => 'school',
							'val' => $row['timetable_school']
						),
						array(
							'name' => 'grade',
							'val' => $row['timetable_grade']
						),
						array(
							'name' => 'class',
							'val' => $row['timetable_class']
						)
					),
					'onof' => array(
						array(
							'name' => 'start',
							'notification' => $row['timetable_start']
						)
					)
				)
			),
			array(
				'name' => 'bus',
				'notification' => $row['bus_notification'],
				'config' => array(
					'select' => array(
						array(
							'name' => 'busname',
							'val' => $row['bus_busname']
						),
						array(
							'name' => 'stopname',
							'val' => $row['bus_stopname']
						),
						array(
							'name' => 'route',
							'val' => $row['bus_route']
						)
					),
					'choose' => array(
						array(
							'name' => 'howmany',
							'val' => $row['bus_howmany']
						)
					)
				)
			),
			array(
				'name' => 'alarm',
				'notification' => $row['alarm_notification'],
				'config' => array(
				)
			),
			array(
				'name' => 'horoscope',
				'notification' => $row['horoscope_notification'],
				'config' => array(
					'choose' => array(
						array(
							'name' => 'star',
							'val' => $row['horoscope_star']
						)
					),
					'onof' => array(
						array(
							'name' => 'detail',
							'notification' => $row['horoscope_detail']
						),
						array(
							'name' => 'item',
							'notification' => $row['horoscope_item']
						),
						array(
							'name' => 'color',
							'notification' => $row['horoscope_color']
						)
					)
				)
			),
			array(
				'name' => 'lunch',
				'notification' => $row['lunch_notification'],
				'config' => array(
					'select' => array(
						array(
							'name' => 'school',
							'val' => $row['lunch_school']
						)
					),
					'onof' => array(
						array(
							'name' => 'calorie',
							'notification' => $row['lunch_calorie']
						),
						array(
							'name' => 'tomorrow',
							'notification' => $row['lunch_tomorrow']
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
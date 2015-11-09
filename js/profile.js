var profile = function() {
	//プロフィールを開く
	$('#item_profile').click( function() {
		$('#name').val(user_data[user_id]['user_name']);
		$('#name_p').val(user_data[user_id]['user_name_p']);
		$('#profile_icon').attr('src', 'icon/' + user_data[user_id]['img']);

		$('#setting_profile').animate({'left': '0%'}, speed);
		$('#detail').animate({'left': '-100%'}, speed);
	});

	//プロフィールを閉じる
	$('#profile_back').click( function() {
		$('#setting_profile').animate({'left': '100%'}, speed);
		$('#detail').animate({'left': '0%'}, speed);
	});
}
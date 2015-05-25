/**
 * Формы для проверки валидатором и запуска AJAX
 *
 * @version 0.5 25.05.2015
 * @author Дмитрий Щербаков <atomcms@ya.ru>
 */

$('body').on('click', '.forma_submit', function() {
	var button = $(this);
	var forma = button.attr('data-forma');

	if ($('#' + forma + '_forma').validationEngine('validate')) {
		switch (forma) {
			// Регистрация пользователя
			case 'user_reg':
				ajax_waiter('show');

				$.ajax({
					url: pathRoot + '/user/add',
					data: {
						'ajax': true,
						'name': $('#forma_reg_name').val(),
						'email': $('#forma_reg_email').val()
					},
					success: function(data, textStatus, jqXHR) {
						windowClose('user_reg');
						showMessage(data.code, data.text);
					},
					complete: function() {
						ajax_waiter('hide');
					},
					error: function(jqXHR, textStatus, errorThrown) {
						ajax_error(textStatus, errorThrown);
					}
				});
			break;

			// Напоминание пароля
			case 'user_lost':
				ajax_waiter('show');

				$.ajax({
					url: pathRoot + '/user/lost_password',
					data: {
						'ajax': true,
						'email': $('#forma_lost_email').val()
					},
					success: function(data, textStatus, jqXHR) {
						windowClose('user_lost');
						showMessage(data.code, data.text);
					},
					complete: function() {
						ajax_waiter('hide');
					},
					error: function(jqXHR, textStatus, errorThrown) {
						ajax_error(textStatus, errorThrown);
					}
				});
			break;

			// Сохранение личных данных
			case 'user_save':
				ajax_waiter('show');

				$.ajax({
					url: pathRoot + '/user/save',
					data: {
						'ajax': true,
						'name': $('#forma_users_save_name').val(),
						'password': $('#forma_users_save_password').val(),
						'newpassword': $('#forma_users_save_newpassword').prop('checked')
					},
					success: function(data, textStatus, jqXHR) {
						$('.userinfo_name').text($('#forma_users_save_name').val());
						showMessage(data.code, data.text);
					},
					complete: function() {
						ajax_waiter('hide');
					},
					error: function(jqXHR, textStatus, errorThrown) {
						ajax_error(textStatus, errorThrown);
					}
				});
			break;
		}
	}
});
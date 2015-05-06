/**
 * Функции JavaScript для AJAX
 *
 * @version 0.2 06.05.2015
 * @author Дмитрий Щербаков <atomcms@ya.ru>
 */

//
//
// 	      db        `7MMF'    db      `YMM'   `MP'
// 	     ;MM:         MM     ;MM:       VMb.  ,P
// 	    ,V^MM.        MM    ,V^MM.       `MM.M'
// 	   ,M  `MM        MM   ,M  `MM         MMb
// 	   AbmmmqMA       MM   AbmmmqMA      ,M'`Mb.
// 	  A'     VML (O)  MM  A'     VML    ,P   `MM.
// 	.AMA.   .AMMA.Ymmm9 .AMA.   .AMMA..MM:.  .:MMa.
//
//

// Настройки
$.ajaxSetup({
	timeout: 20000,
	type: 'POST',
	dataType: 'json',
	cache: false
});

/**
 * Отображение ошибки AJAX
 *
 * @param string status Код ошибки
 * @param string text   Текст сообщения
 *
 * @return null
 *
 * @version 0.1 27.04.2015
 * @author Дмитрий Щербаков <atomcms@ya.ru>
 */
function ajax_error(status, text) {
	switch (status)
	{
		case 'timeout': showMessage('warning', 'Время ожидания истекло. Попробуйте ещё раз.'); break;
		case 'parsererror': showMessage('warning', 'Ошибка парсера. Попробуйте ещё раз.'); break;
		case 'abort': showMessage('info', 'Запрос был отменён.'); break;
		case 'error': showMessage('danger', 'Произошла ошибка сервера: ' + text + '. Передайте администрации и попробуйте ещё раз.'); break;
		default: showMessage('danger', 'Неизвестная ошибка. Попробуйте ещё раз.'); break;
	}
};

/**
 * Блок ожидания
 *
 * @param string action Показать или скрыть блок ожидания (show|hide)
 *
 * @return null
 *
 * @version 0.2 06.05.2015
 * @author Дмитрий Щербаков <atomcms@ya.ru>
 */
function ajax_waiter(action) {
	if (action == 'show') {
		// Показ блока ожидания
		$('#ajax_waiter').show();
		$('#ajax_overlay').show();

		// Скрываем старые ошибки
		$('#alerts-container .alert').hide();
	} else {
		// Скрываем блок ожидания
		$('#ajax_waiter').hide();
		$('#ajax_overlay').hide();
	}
};

//
//
// 	`7MMF'     A     `7MF' db      `7MMF'MMP""MM""YMM `7MM"""YMM  `7MM"""Mq.
// 	  `MA     ,MA     ,V  ;MM:       MM  P'   MM   `7   MM    `7    MM   `MM.
// 	   VM:   ,VVM:   ,V  ,V^MM.      MM       MM        MM   d      MM   ,M9
// 	    MM.  M' MM.  M' ,M  `MM      MM       MM        MMmmMM      MMmmdM9
// 	    `MM A'  `MM A'  AbmmmqMA     MM       MM        MM   Y  ,   MM  YM.
// 	     :MM;    :MM;  A'     VML    MM       MM        MM     ,M   MM   `Mb.
// 	      VF      VF .AMA.   .AMMA..JMML.   .JMML.    .JMMmmmmMMM .JMML. .JMM.
//
//

var cSpeed = 4;
var cWidth = 100;
var cHeight = 103;
var cTotalFrames = 20;
var cFrameWidth = 100;
var cImageSrc = pathRoot + '/images/loader.gif';

var cImageTimeout = false;
var cIndex = 0;
var cXpos = 0;
var cPreloaderTimeout = false;
var SECONDS_BETWEEN_FRAMES = 0;

/**
 * Запустить анимацию
 *
 * @return null
 *
 * @version 0.1 27.04.2015
 * @author Дмитрий Щербаков <atomcms@ya.ru>
 */
function startAnimation() {
	document.getElementById('ajax_loader').style.backgroundImage = 'url(' + cImageSrc + ')';
	document.getElementById('ajax_loader').style.width = cWidth + 'px';
	document.getElementById('ajax_loader').style.height = cHeight + 'px';

	FPS = Math.round(100 / cSpeed);
	SECONDS_BETWEEN_FRAMES = 1 / FPS;

	cPreloaderTimeout = setTimeout('continueAnimation()', SECONDS_BETWEEN_FRAMES / 1000);
};

/**
 * Продолжить анимацию
 *
 * @return null
 *
 * @version 0.1 27.04.2015
 * @author Дмитрий Щербаков <atomcms@ya.ru>
 */
function continueAnimation() {
	cXpos += cFrameWidth;
	cIndex += 1;

	if (cIndex >= cTotalFrames) {
		cXpos = 0;
		cIndex = 0;
	}

	if(document.getElementById('ajax_loader'))
		document.getElementById('ajax_loader').style.backgroundPosition = (-cXpos) + 'px 0';

	cPreloaderTimeout = setTimeout('continueAnimation()', SECONDS_BETWEEN_FRAMES * 1000);
};

/**
 * Остановить анимацию
 *
 * @return null
 *
 * @version 0.1 27.04.2015
 * @author Дмитрий Щербаков <atomcms@ya.ru>
 */
function stopAnimation() {
	clearTimeout(cPreloaderTimeout);
	cPreloaderTimeout = false;
};

/**
 * Загрузка изображения прелоадера
 *
 * @param array s   Путь до изображения
 * @param array fun Функция при успешном выполнении
 *
 * @return null
 *
 * @version 0.1 27.04.2015
 * @author Дмитрий Щербаков <atomcms@ya.ru>
 */
function imageLoader(s, fun) {
	clearTimeout(cImageTimeout);
	cImageTimeout = 0;
	genImage = new Image();
	genImage.onload = function (){cImageTimeout = setTimeout(fun, 0)};
	genImage.onerror = new Function('alert(\'Could not load the image\')');
	genImage.src = s;
};

//The following code starts the animation
new imageLoader(cImageSrc, 'startAnimation()');

//
//
// 	`7MM"""YMM   .g8""8q. `7MM"""Mq.  `7MMM.     ,MMF' .M"""bgd
// 	  MM    `7 .dP'    `YM. MM   `MM.   MMMb    dPMM  ,MI    "Y
// 	  MM   d   dM'      `MM MM   ,M9    M YM   ,M MM  `MMb.
// 	  MM""MM   MM        MM MMmmdM9     M  Mb  M' MM    `YMMNq.
// 	  MM   Y   MM.      ,MP MM  YM.     M  YM.P'  MM  .     `MM
// 	  MM       `Mb.    ,dP' MM   `Mb.   M  `YM'   MM  Mb     dM
// 	.JMML.       `"bmmd"' .JMML. .JMM..JML. `'  .JMML.P"Ybmmd"
//
//

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
						'newpassword': $('#forma_users_save_newpassword:checked').val()
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
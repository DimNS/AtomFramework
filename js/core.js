/**
 * Общие функции JavaScript
 *
 * @version 0.5 25.05.2015
 * @author Дмитрий Щербаков <atomcms@ya.ru>
 */

//
//
// 	`7MMF'     A     `7MF'`7MMF'`7MN.   `7MF'`7MM"""Yb.     .g8""8q.`7MMF'     A     `7MF'.M"""bgd
// 	  `MA     ,MA     ,V    MM    MMN.    M    MM    `Yb. .dP'    `YM.`MA     ,MA     ,V ,MI    "Y
// 	   VM:   ,VVM:   ,V     MM    M YMb   M    MM     `Mb dM'      `MM VM:   ,VVM:   ,V  `MMb.
// 	    MM.  M' MM.  M'     MM    M  `MN. M    MM      MM MM        MM  MM.  M' MM.  M'    `YMMNq.
// 	    `MM A'  `MM A'      MM    M   `MM.M    MM     ,MP MM.      ,MP  `MM A'  `MM A'   .     `MM
// 	     :MM;    :MM;       MM    M     YMM    MM    ,dP' `Mb.    ,dP'   :MM;    :MM;    Mb     dM
// 	      VF      VF      .JMML..JML.    YM  .JMMmmmdP'     `"bmmd"'      VF      VF     P"Ybmmd"
//
//

// Позиция скролла основного окна, для работы всплывюащих окон
iScrolled = 0;

// Активное всплывающее окно
windowActive = 'NaN';

$('body').on('click', '.wnd_open', function() {
	windowOpen($(this).attr('data-id'));
});

$('body').on('click', '.wnd_close', function() {
	windowClose($(this).attr('data-id'));
});

$('body').on('click', '#window_overlay, .window_layer', function() {
	if (windowActive != 'NaN') {
		windowClose(windowActive);
	}
});

/**
 * Открыть окно по идентификатору
 *
 * @param string wndID Идентификатор окна
 *
 * @return null
 *
 * @version 0.1 27.04.2015
 * @author Дмитрий Щербаков <atomcms@ya.ru>
 */
function windowOpen(wndID) {
	if ($('#' + wndID).hasClass('window_layer')) {
		// Всплывающее окно как Вконтакте
		iScrolled = $(window).scrollTop();
		$(window).scrollTop(0);
		$('#container_all').css('position', 'fixed');
		$('#container_all').css('margin-top', '-' + iScrolled + 'px');
		$('#' + wndID).css('display', 'block').show();
	} else {
		// Обычное всплывающее окно
		$('#' + wndID).css({
			'top': Math.max(0, (($(window).height() - $('#' + wndID).outerHeight()) / 2)) + 'px',
			'left': Math.max(0, (($(window).width() - $('#' + wndID).outerWidth()) / 2)) + 'px'
		}).show();
	}

	$('#window_overlay').show();
	windowActive = wndID;
}

/**
 * Закрыть окно по идентификатору
 *
 * @param string wndID Идентификатор окна
 *
 * @return null
 *
 * @version 0.1 27.04.2015
 * @author Дмитрий Щербаков <atomcms@ya.ru>
 */
function windowClose(wndID) {
	switch (wndID) {
		case 'changelog':
			// Отмечаем пользователю просмотр последних изменений
			$.ajax({
				url: pathRoot + '/changelog/check'
			});
		break;
	}

	if ($('#' + wndID).hasClass('window_layer')) {
		// Всплывающее окно как Вконтакте
		$('#container_all').css('margin-top', '0px');
		$('#container_all').css('position', 'absolute');
		$(window).scrollTop(iScrolled);
		$('#' + wndID).css('display', 'none');
	} else {
		// Обычное всплывающее окно
		$('#' + wndID).hide();
	}

	$('#window_overlay').hide();
	windowActive = 'NaN';
}

//
//
// 	  .g8"""bgd   .g8""8q. `7MN.   `7MF'`7MM"""YMM `7MMF'`7MM"""Mq.  `7MMM.     ,MMF'
// 	.dP'     `M .dP'    `YM. MMN.    M    MM    `7   MM    MM   `MM.   MMMb    dPMM
// 	dM'       ` dM'      `MM M YMb   M    MM   d     MM    MM   ,M9    M YM   ,M MM
// 	MM          MM        MM M  `MN. M    MM""MM     MM    MMmmdM9     M  Mb  M' MM
// 	MM.         MM.      ,MP M   `MM.M    MM   Y     MM    MM  YM.     M  YM.P'  MM
// 	`Mb.     ,' `Mb.    ,dP' M     YMM    MM         MM    MM   `Mb.   M  `YM'   MM
// 	  `"bmmmd'    `"bmmd"' .JML.    YM  .JMML.     .JMML..JMML. .JMM..JML. `'  .JMML.
//
//

/**
 * Показать подтверждение (confirm)
 *
 * @param string htmlTitle  Текст заголовка
 * @param string htmlBody   Текст подтверждения
 * @param string funcOK     Код функции если нажали OK
 * @param string funcCancel Код функции если нажали Отмена
 *
 * @return null
 *
 * @version 0.1 27.04.2015
 * @author Дмитрий Щербаков <atomcms@ya.ru>
 */
function showConfirm(htmlTitle, htmlBody, funcOK, funcCancel) {
	windowActive = 'atom_confirm';
	$('#atom_confirm .modal-title').html(htmlTitle);
	$('#atom_confirm .modal-body').html(htmlBody);
	$('#atom_confirm .btn-ok').unbind('click').bind('click', function() {
		funcOK();
		windowClose('atom_confirm');
	});
	$('#atom_confirm .btn-cancel').unbind('click').bind('click', function() {
		funcCancel();
		windowClose('atom_confirm');
	});
	$('#atom_confirm').css({
		'top': Math.max(0, (($(window).height() - $('#atom_confirm').outerHeight()) / 2)) + 'px',
		'left': Math.max(0, (($(window).width() - $('#atom_confirm').outerWidth()) / 2)) + 'px'
	}).show();
	$('#window_overlay').show();
}

//
//
// 	`7MMM.     ,MMF'`7MM"""YMM   .M"""bgd  .M"""bgd      db       .g8"""bgd `7MM"""YMM
// 	  MMMb    dPMM    MM    `7  ,MI    "Y ,MI    "Y     ;MM:    .dP'     `M   MM    `7
// 	  M YM   ,M MM    MM   d    `MMb.     `MMb.        ,V^MM.   dM'       `   MM   d
// 	  M  Mb  M' MM    MMmmMM      `YMMNq.   `YMMNq.   ,M  `MM   MM            MMmmMM
// 	  M  YM.P'  MM    MM   Y  , .     `MM .     `MM   AbmmmqMA  MM.    `7MMF' MM   Y  ,
// 	  M  `YM'   MM    MM     ,M Mb     dM Mb     dM  A'     VML `Mb.     MM   MM     ,M
// 	.JML. `'  .JMML..JMMmmmmMMM P"Ybmmd"  P"Ybmmd" .AMA.   .AMMA. `"bmmmdPY .JMMmmmmMMM
//
//

/**
 * Показать ошибку (alert)
 *
 * @param string type    Код ошибки
 * @param string message Текст сообщения
 *
 * @return null
 *
 * @version 0.1 27.04.2015
 * @author Дмитрий Щербаков <atomcms@ya.ru>
 */
function showMessage(type, message) {
	$('#alerts-container .alert').hide();
	$('#alerts-container .alert-' + type + ' span').html(message);
	$('#alerts-container .alert-' + type).show();
}

$('#alerts-container .close').click(function() {
	$(this).parent().hide();
});

//
//
// 	`7MM"""Mq.  `7MM"""YMM    .g8"""bgd      `7MM"""YMM   .g8""8q. `7MM"""Mq.  `7MMM.     ,MMF'
// 	  MM   `MM.   MM    `7  .dP'     `M        MM    `7 .dP'    `YM. MM   `MM.   MMMb    dPMM
// 	  MM   ,M9    MM   d    dM'       `        MM   d   dM'      `MM MM   ,M9    M YM   ,M MM
// 	  MMmmdM9     MMmmMM    MM                 MM""MM   MM        MM MMmmdM9     M  Mb  M' MM
// 	  MM  YM.     MM   Y  , MM.    `7MMF'      MM   Y   MM.      ,MP MM  YM.     M  YM.P'  MM
// 	  MM   `Mb.   MM     ,M `Mb.     MM        MM       `Mb.    ,dP' MM   `Mb.   M  `YM'   MM
// 	.JMML. .JMM..JMMmmmmMMM   `"bmmmdPY      .JMML.       `"bmmd"' .JMML. .JMM..JML. `'  .JMML.
//
//

// Показать \ Скрыть пароль
$('#show_hide_password').click(function() {
	if ($(this).hasClass('fa-unlock-alt')) {
		// Скрываем
		$('input[name="password"]').attr('type', 'password');
		$(this).removeClass('fa-unlock-alt').addClass('fa-lock');
	} else {
		// Показываем
		$('input[name="password"]').attr('type', 'text');
		$(this).removeClass('fa-lock').addClass('fa-unlock-alt');
	}
});

//
//
// 	`7MMF'`7MN.   `7MF'`7MMF'MMP""MM""YMM
// 	  MM    MMN.    M    MM  P'   MM   `7
// 	  MM    M YMb   M    MM       MM
// 	  MM    M  `MN. M    MM       MM
// 	  MM    M   `MM.M    MM       MM
// 	  MM    M     YMM    MM       MM
// 	.JMML..JML.    YM  .JMML.   .JMML.
//
//

// Только не мобильным браузерам
if (!mobileBrowser) {
	// Находим все select кроме тех у которых есть класс select2
	$('select').each(function() {
		if ($(this).hasClass('select2_wtags')) {
			$(this).select2({
				tags: true,
				width: '100%'
			});
		} else {
			$(this).select2({
				width: '100%'
			});
		}
	});
}

// Показываем сообщение, если необходимо
if (messageText != '') showMessage(messageCode, messageText);

// Подключем плагин iCheck
$('.icheck').iCheck({
	checkboxClass: 'icheckbox_square-blue',
	radioClass: 'iradio_square-blue',
	increaseArea: '20%' // optional
});

// Подключаем плагин Bootstrap Toggle
$('.bootstrap-toggle').bootstrapToggle();

// Отслеживаем галочку "Новый пароль: Да" в профиле пользователя
$('#forma_users_save_newpassword').change(function() {
	if ($(this).prop('checked')) {
		$('#user_profile_newpwd_block').removeClass('hide');
	} else {
		$('#user_profile_newpwd_block').addClass('hide');
	}
});
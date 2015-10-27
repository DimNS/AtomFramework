<?php
/**
 * Email
 *
 * Класс для отправки писем на почту
 *
 * @version 0.6 27.10.2015
 * @author Дмитрий Щербаков <atomcms@ya.ru>
 */

namespace AtomFramework\Utility;

use AtomFramework\Configs\Config;

class Email {
	/**
	 * Получение информации о клиенте, фактически авторизация
	 *
	 * @param string $type  Тип письма (reg|reset|lost)
	 * @param string $email Адрес для отправки
	 * @param array  $param Массив данных уникальных для каждого типа писем
	 *
	 * Типы писем:
	 * reg   (email, password) - Успешная регистрация
	 * reset (email, hash)     - Запрос на сброс пароля
	 * lost  (email, password) - Напоминание пароля
	 *
	 * @return array
	 *
	 * @version 0.6 27.10.2015
	 * @author Дмитрий Щербаков <atomcms@ya.ru>
	 */
	static function send($type, $email, $param) {
		if (!Config::$global['production']) {
			return true;
		} else {
			$mail = new \PHPMailer();
			$mail->setLanguage('ru', Config::$global['path_home_root'] . '/vendor/phpmailer/phpmailer/language/');
			$mail->IsHTML(true);
			$mail->CharSet = 'windows-1251';
			$mail->From = Config::$global['mail_from_email'];
			$mail->FromName = iconv('utf-8', 'windows-1251', Config::$global['mail_from_name']);
			$mail->AddEmbeddedImage(Config::$global['path_home_root'] . '/img/logo-mail.png', 'logotype', 'logo-mail.png', 'base64', 'image/png');

			switch ($type) {
				// Успешная регистрация
				case 'reg':
					$subject = 'Новый пользователь: ' . $param['email'];
					$message = Email::msg_reg($param['email'], $param['password']);
				break;

				// Уведомление о новом пользователе
				case 'newuser':
					$subject = 'Новый пользователь';

					$message = '
						<tr>
							<td>Зарегистрирован новый пользователь: <strong>' . $param['email'] . '</strong></td>
						</tr>
					';
				break;

				// Сброс пароля
				case 'reset':
					$subject = 'Восстановление пароля';
					$message = Email::msg_reset($param['email'], $param['hash']);
				break;

				// Напоминание пароля
				case 'lost':
					$subject = 'Новый пароль';
					$message = Email::msg_lost($param['email'], $param['password']);
				break;

				default:
					$message = '';
				break;
			}

			$mail->Subject = iconv('utf-8', 'windows-1251', $subject);
			$mail->MsgHTML(iconv('utf-8', 'windows-1251', Email::head_mail() . $message . Email::foot_mail()));
			$mail->AddAddress($email);

			if (!$mail->Send()) {
				return false;
			} else {
				return true;
			}
		}
	}

	/**
	 * Шапка письма
	 *
	 * @return string
	 *
	 * @version 0.1 27.04.2015
	 * @author Дмитрий Щербаков <atomcms@ya.ru>
	 */
	static function head_mail() {
		if (!Config::$global['production']) {
			$logo = Config::$global['path_short_root'] . '/img/logo-mail.png';
		} else {
			$logo = 'cid:logotype';
		}

		return '
		<table border="0" cellpadding="0" cellspacing="0" style="width:100%;background:#eee;">
			<tr>
				<td style="padding:20px;">
					<table border="0" cellpadding="10" cellspacing="0" align="center" style="width:600px;color:#444444;font-size:14px;font-family:arial,helvetica,sans-serif;line-height:1.5;border:5px #aed0ea solid;background:#fff;">
						<tbody>
							<tr>
								<td align="center"><img src="' . $logo . '" height="100"></td>
							</tr>
		';
	}

	/**
	 * Подвал письма
	 *
	 * @return string
	 *
	 * @version 0.1 27.04.2015
	 * @author Дмитрий Щербаков <atomcms@ya.ru>
	 */
	static function foot_mail() {
		return '
							<tr>
								<td>
									<strong>С уважением,</strong><br>
									Дмитрий Щербаков<br>
									CEO по работе с ключевыми клиентами<br>
									<a href="mailto:info@bestion.ru" style="color:#3baae3;">info@bestion.ru</a>
								</td>
							</tr>
						</tbody>
					</table>
				</td>
			</tr>
		</table>
		';
	}

	/**
	 * Успешная регистрация
	 *
	 * @param string $email    Адрес электронной почты
	 * @param array  $password Пароль
	 *
	 * @return string
	 *
	 * @version 0.1 27.04.2015
	 * @author Дмитрий Щербаков <atomcms@ya.ru>
	 */
	static function msg_reg($email, $password) {
		return '
			<tr>
				<td>Добро пожаловать!</td>
			</tr>
			<tr>
				<td>Вы <strong>успешно зарегистрировались</strong> в сервисе удобной статистики <span>' . Config::$global['proj_name'] . '</span>.</td>
			</tr>
			<tr>
				<td style="text-align:center;">
					<table border="0" cellpadding="20" cellspacing="0" align="center" style="background:#d7ebf9;line-height:2;">
						<tr>
							<td>Почта для входа: <strong>' . $email . '</strong><br>Пароль для входа: <strong>' . $password . '</strong></td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td><strong>Что делать дальше?</strong></td>
			</tr>
			<tr>
				<td>
					<ul>
						<li>Войти в свой аккаунт по ссылке внизу.</li>
						<li>Получить ключи для доступа к статистике.</li>
						<li>Добавить полученные ключи в систему.</li>
						<li>Добавить необходимые счётчики.</li>
						<li>Произвести необходимые настройки.</li>
						<li>Выдать доступ для просмотра статистики вашим клиентам.</li>
						<li>Собирать восторженные отклики клиентов, о том как теперь стало удобно смотреть статистику.</li>
					</ul>
				</td>
			</tr>
			<tr>
				<td>Для начала работы, пройдите по этой ссылке:</td>
			</tr>
			<tr>
				<td align="center">
					<a href="' . Config::$global['path_http_root'] . '/" style="background:#3baae3;color:#ffffff;font-size:24px;padding:10px 40px;text-decoration:none;">Начать работу</a>
				</td>
			</tr>
		';
	}

	/**
	 * Запрос на сброс пароля
	 *
	 * @param string $email Адрес электронной почты
	 * @param array  $hash  Хеш для сброса пароля
	 *
	 * @return string
	 *
	 * @version 0.1 27.04.2015
	 * @author Дмитрий Щербаков <atomcms@ya.ru>
	 */
	static function msg_reset($email, $hash) {
		return '
			<tr>
				<td>Мы получили запрос на <strong>восстановление пароля</strong> к вашему аккаунту <strong>' . $email . '</strong> в сервисе удобной статистики <span>' . Config::$global['proj_name'] . '</span>.</td>
			</tr>
			<tr>
				<td>В целях безопасности мы не храним оригинальный пароль, а только можем сменить его на новый.</td>
			</tr>
			<tr>
				<td>Пожалуйста, перейдите по ссылке, чтобы установить новый пароль:</td>
			</tr>
			<tr>
				<td align="center">
					<a href="' . Config::$global['path_http_root'] . '/user/reset_password/?key=' . $hash . '" style="background:#3baae3;color:#ffffff;font-size:24px;padding:10px 40px;text-decoration:none;">Получить новый пароль</a>
				</td>
			</tr>
		';
	}

	/**
	 * Напоминание пароля
	 *
	 * @param string $email    Адрес электронной почты
	 * @param array  $password Пароль
	 *
	 * @return string
	 *
	 * @version 0.1 27.04.2015
	 * @author Дмитрий Щербаков <atomcms@ya.ru>
	 */
	static function msg_lost($email, $password) {
		return '
			<tr>
				<td>Пароль к вашему аккаунту <strong>успешно изменен</strong>.</td>
			</tr>
			<tr>
				<td style="text-align:center;">
					<table border="0" cellpadding="20" cellspacing="0" align="center" style="background:#d7ebf9;line-height:2;">
						<tr>
							<td>Почта для входа: <strong>' . $email . '</strong><br>Пароль для входа: <strong>' . $password . '</strong></td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td>С возвращением. Для продолжения работы воспользуйтесь ссылкой:</td>
			</tr>
			<tr>
				<td align="center">
					<a href="' . Config::$global['path_http_root'] . '/" style="background:#3baae3;color:#ffffff;font-size:24px;padding:10px 40px;text-decoration:none;">Продолжить работу</a>
				</td>
			</tr>
		';
	}
}
?>
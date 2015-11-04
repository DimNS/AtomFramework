<?php
/**
 * User
 *
 * Модель для работы с пользователями
 *
 * @version 0.6.4 04.11.2015
 * @author Дмитрий Щербаков <atomcms@ya.ru>
 */

namespace AtomFramework\Models;

use AtomFramework\Configs\Config;
use AtomFramework\Utility\Email;
use AtomFramework\Utility\Error;
use AtomFramework\Utility\Func;
use AtomFramework\Utility\Protocol;

class User {
    //
    //                 ,,        ,,
    //               `7MM      `7MM
    //                 MM        MM
    //   ,6"Yb.   ,M""bMM   ,M""bMM
    //  8)   MM ,AP    MM ,AP    MM
    //   ,pm9MM 8MI    MM 8MI    MM
    //  8M   MM `Mb    MM `Mb    MM
    //  `Moo9^Yo.`Wbmd"MML.`Wbmd"MML.
    //
    //

    /**
     * Добавление нового пользователя
     *
     * @param string $name     Имя пользователя
     * @param string $email    Почтовый адрес
     * @param string $password Пароль
     *
     * @return array
     *
     * @version 0.6.4 04.11.2015
     * @author Дмитрий Щербаков <atomcms@ya.ru>
     */
    static function add($name, $email, $password) {
        $db_result = query("SELECT * FROM `" . Config::$global['db_prefix'] . "user`
            WHERE email = :email
            LIMIT 1
        ", [
            'email' => $email,
        ], __FILE__, __LINE__);
        if ($db_result != -1) {
            if (count($db_result) === 0) {
                $password = Func::generate_password();

                $db_result = query("INSERT INTO `" . Config::$global['db_prefix'] . "user` SET
                    email = :email,
                    password = :password,
                    name = :name,
                    version = :version,
                    created_at = :created_at
                ", [
                    'email'      => $email,
                    'password'   => Func::collect_password($password),
                    'name'       => $name,
                    'version'    => Config::$global['version'],
                    'created_at' => Config::$global['currtime_format'],
                ], __FILE__, __LINE__);
                if ($db_result >= 0) {
                    Protocol::ins('Добавлен новый администратор: ' . $email, __FILE__, __LINE__, __FUNCTION__);

                    if (!Email::send('reg', $email, ['email' => $email, 'password' => $password])) {
                        return [
                            'code' => 'warning',
                            'text' => 'При отправке пароля произошла ошибка. Свяжитесь с администрацией.',
                        ];
                    } else {
                        Email::send('newuser', Config::$global['mail_from_email'], ['email' => $email]);

                        return [
                            'code' => 'success',
                            'text' => 'Для входа воспользуйтесь паролем отправленным на почту.',
                        ];
                    }
                } else {
                    return [
                        'code' => 'warning',
                        'text' => 'При добавлении произошла ошибка. Попробуйте ещё раз.',
                    ];
                }
            } else {
                return [
                    'code' => 'info',
                    'text' => 'Клиент с email "' . $email . '" уже добавлен.',
                ];
            }
        } else {
            return [
                'code' => 'danger',
                'text' => 'Ошибка в запросе. Попробуйте ещё раз.',
            ];
        }
    }

    //
    //    ,,                       ,,
    //  `7MM                       db
    //    MM
    //    MM  ,pW"Wq.   .P"Ybmmm `7MM  `7MMpMMMb.
    //    MM 6W'   `Wb :MI  I8     MM    MM    MM
    //    MM 8M     M8  WmmmP"     MM    MM    MM
    //    MM YA.   ,A9 8M          MM    MM    MM
    //  .JMML.`Ybmd9'   YMMMMMb  .JMML..JMML  JMML.
    //                 6'     dP
    //                 Ybmmmd'

    /**
     * Авторизация пользователя по логину и паролю
     *
     * @param string $email    Почтовый адрес для авторизации
     * @param string $password Пароль для авторизации
     *
     * @return integer Статус авторизации (#: id_user; 0: wrong_email_or_password; -1: user_blocked; -2: user_not_found)
     *
     * @version 0.6 27.10.2015
     * @author Дмитрий Щербаков <atomcms@ya.ru>
     */
    static function login($email, $password) {
        // Удаляем устаревшие попытки входа
        // 7 дней
        $db_result = query("DELETE FROM `" . Config::$global['db_prefix'] . "user_block`
            WHERE date < :date
        ", [
            'date' => Config::$global['currtime'] - 604800,
        ], __FILE__, __LINE__);

        $db_result = query("SELECT * FROM `" . Config::$global['db_prefix'] . "user`
            WHERE email = :email
                AND `active` = '1'
            LIMIT 1
        ", [
            'email' => $email,
        ], __FILE__, __LINE__);
        if ($db_result != -1) {
            if (count($db_result) === 1) {
                $user_info = $db_result[0];

                $db_result = query("SELECT * FROM `" . Config::$global['db_prefix'] . "user`
                    WHERE id = :id
                    LIMIT 1
                ", [
                    'id' => $user_info['id'],
                ], __FILE__, __LINE__);
                if ($db_result != -1) {
                    if (count($db_result) < 10) {
                        if (crypt($password, $user_info['password']) === $user_info['password']) {
                            // Записываем браузер пользователя
                            $db_result = query("INSERT INTO `" . Config::$global['db_prefix'] . "browsers` SET
                                date = :date,
                                user_id = :user_id,
                                user_agent = :user_agent
                            ", [
                                'date'       => Config::$global['currtime'],
                                'user_id'    => $user_info['id'],
                                'user_agent' => $_SERVER['HTTP_USER_AGENT'],
                            ], __FILE__, __LINE__);

                            return $user_info['id'];
                        } else {
                            $db_result = query("INSERT INTO `" . Config::$global['db_prefix'] . "user_block` SET
                                user_id = :user_id,
                                date = :date
                            ", [
                                'user_id' => $user_info['id'],
                                'date'    => time(),
                            ], __FILE__, __LINE__);
                            if ($db_result >= 0) {
                                Error::ins(0, 'SQL', '', 'Пользователь не прошел авторизацию (передан параметр email: ' . $email . ')', __FILE__, __FUNCTION__, __LINE__);
                                return 0;
                            }
                        }
                    } else {
                        Error::ins(0, 'ERR', 'Пользователь заблокирован. Обратитесь к администратору', 'Пользователь заблокирован (передан параметр email: ' . $email . ')', __FILE__, __FUNCTION__, __LINE__);
                        return -1;
                    }
                } else {
                    return 0;
                }
            } else {
                Error::ins(0, 'SQL', '', 'Пользователь не существует (передан параметр email: ' . $email . ')', __FILE__, __FUNCTION__, __LINE__);
                return -2;
            }
        } else {
            return 0;
        }
    }

    //
    //
    //                     mm
    //                     MM
    //   .P"Ybmmm .gP"Ya mmMMmm
    //  :MI  I8  ,M'   Yb  MM
    //   WmmmP"  8M""""""  MM
    //  8M       YM.    ,  MM
    //   YMMMMMb  `Mbmmd'  `Mbmo
    //  6'     dP
    //  Ybmmmd'

    /**
     * Получение данных пользователя по его ИД
     *
     * @param integer $id
     *
     * @return array Массив данных пользователя
     *
     * @version 0.3 08.05.2015
     * @author Дмитрий Щербаков <atomcms@ya.ru>
     */
    static function get($id) {
        if (preg_match("/^[0-9]+$/", $id)) {
            $db_result = query("SELECT * FROM `" . Config::$global['db_prefix'] . "user`
                WHERE id = :id
                LIMIT 1
            ", [
                'id' => $id,
            ], __FILE__, __LINE__);

            return $db_result[0];
        }
    }

    //
    //
    //
    //
    //  ,pP"Ybd  ,6"Yb.`7M'   `MF'.gP"Ya
    //  8I   `" 8)   MM  VA   ,V ,M'   Yb
    //  `YMMMa.  ,pm9MM   VA ,V  8M""""""
    //  L.   I8 8M   MM    VVV   YM.    ,
    //  M9mmmP' `Moo9^Yo.   W     `Mbmmd'
    //
    //

    /**
     * Сохранение личных данных
     *
     * @param string $name        Имя пользователя
     * @param string $password    Новый пароль, если указан
     * @param string $newpassword Статус смены пароля
     *
     * @return array
     *
     * @version 0.6.4 04.11.2015
     * @author Дмитрий Щербаков <atomcms@ya.ru>
     */
    static function save($name, $password, $newpassword) {
        $db_result = query("SELECT * FROM `" . Config::$global['db_prefix'] . "user`
            WHERE id = :id
            LIMIT 1
        ", [
            'id' => Config::$userinfo['id'],
        ], __FILE__, __LINE__);
        if ($db_result != -1 AND count($db_result) > 0) {
            if ($newpassword) {
                if ($password != '' AND mb_strlen($password) > 3) {
                    $db_result = query("UPDATE `" . Config::$global['db_prefix'] . "user` SET
                        name = :name,
                        password = :password,
                        updated_at = :updated_at
                        WHERE id = :id
                        LIMIT 1
                    ", [
                        'name'       => $name,
                        'password'   => Func::collect_password($password),
                        'id'         => Config::$userinfo['id'],
                        'updated_at' => Config::$global['currtime_format'],
                    ], __FILE__, __LINE__);
                    Protocol::ins('Сохранение личных данных с изменением пароля', __FILE__, __LINE__, __FUNCTION__);
                } else {
                    return [
                        'code' => 'info',
                        'text' => '|Укажите новый пароль. Длина пароля не менее 3 символов.'
                    ];
                }
            } else {
                $db_result = query("UPDATE `" . Config::$global['db_prefix'] . "user` SET
                    name = :name,
                    updated_at = :updated_at
                    WHERE id = :id
                    LIMIT 1
                ", [
                    'name'       => $name,
                    'id'         => Config::$userinfo['id'],
                    'updated_at' => Config::$global['currtime_format'],
                ], __FILE__, __LINE__);
                Protocol::ins('Сохранение личных данных', __FILE__, __LINE__, __FUNCTION__);
            }

            if ($db_result >= 0) {
                return [
                    'code' => 'success',
                    'text' => 'Данные изменены.',
                ];
            } else {
                return [
                    'code' => 'warning',
                    'text' => 'При изменении произошла ошибка. Попробуйте ещё раз.',
                ];
            }
        } else {
            return [
                'code' => 'warning',
                'text' => 'Произошла ошибка. Попробуйте ещё раз.',
            ];
        }
    }

    //
    //    ,,                                                                                                           ,,
    //  `7MM                     mm                                                                                  `7MM
    //    MM                     MM                                                                                    MM
    //    MM  ,pW"Wq.  ,pP"Ybd mmMMmm      `7MMpdMAo.  ,6"Yb.  ,pP"Ybd ,pP"Ybd `7M'    ,A    `MF',pW"Wq.`7Mb,od8  ,M""bMM
    //    MM 6W'   `Wb 8I   `"   MM          MM   `Wb 8)   MM  8I   `" 8I   `"   VA   ,VAA   ,V 6W'   `Wb MM' "',AP    MM
    //    MM 8M     M8 `YMMMa.   MM          MM    M8  ,pm9MM  `YMMMa. `YMMMa.    VA ,V  VA ,V  8M     M8 MM    8MI    MM
    //    MM YA.   ,A9 L.   I8   MM          MM   ,AP 8M   MM  L.   I8 L.   I8     VVV    VVV   YA.   ,A9 MM    `Mb    MM
    //  .JMML.`Ybmd9'  M9mmmP'   `Mbmo       MMbmmd'  `Moo9^Yo.M9mmmP' M9mmmP'      W      W     `Ybmd9'.JMML.   `Wbmd"MML.
    //                                       MM
    //                             mmmmmmm .JMML.

    /**
     * Отправка запроса для напоминания пароля
     *
     * @param string $email Почтовый адрес
     *
     * @return array
     *
     * @version 0.6.4 04.11.2015
     * @author Дмитрий Щербаков <atomcms@ya.ru>
     */
    static function lost_password($email) {
        $db_result = query("SELECT * FROM `" . Config::$global['db_prefix'] . "user`
            WHERE email = :email
            LIMIT 1
        ", [
            'email' => $email,
        ], __FILE__, __LINE__);
        if ($db_result != -1) {
            if (count($db_result) > 0) {
                $hash = md5($email);

                $db_result = query("UPDATE `" . Config::$global['db_prefix'] . "user` SET
                    reset_password = :reset_password,
                    updated_at = :updated_at
                    WHERE email = :email
                ", [
                    'reset_password' => $hash,
                    'email'          => $email,
                    'updated_at'     => Config::$global['currtime_format'],
                ], __FILE__, __LINE__);
                if ($db_result >= 0) {
                    Protocol::ins('Запрос на сброс пароля для пользователя: ' . $email, __FILE__, __LINE__, __FUNCTION__);

                    if (!Email::send('reset', $email, ['email' => $email, 'hash' => $hash])) {
                        return [
                            'code' => 'warning',
                            'text' => 'При отправке письма произошла ошибка. Свяжитесь с администрацией.',
                        ];
                    } else {
                        return [
                            'code' => 'success',
                            'text' => 'Запрос на изменение пароля успешно выслан на указанную почту.',
                        ];
                    }
                } else {
                    return [
                        'code' => 'warning',
                        'text' => 'При внесении запроса произошла ошибка. Попробуйте ещё раз.',
                    ];
                }
            } else {
                return [
                    'code' => 'info',
                    'text' => 'Клиент с email "' . $email . '" не найден.',
                ];
            }
        } else {
            return [
                'code' => 'danger',
                'text' => 'Ошибка в запросе. Попробуйте ещё раз.',
            ];
        }
    }

    //
    //                                                                                                                           ,,
    //                                     mm                                                                                  `7MM
    //                                     MM                                                                                    MM
    //  `7Mb,od8 .gP"Ya  ,pP"Ybd  .gP"Ya mmMMmm      `7MMpdMAo.  ,6"Yb.  ,pP"Ybd ,pP"Ybd `7M'    ,A    `MF',pW"Wq.`7Mb,od8  ,M""bMM
    //    MM' "',M'   Yb 8I   `" ,M'   Yb  MM          MM   `Wb 8)   MM  8I   `" 8I   `"   VA   ,VAA   ,V 6W'   `Wb MM' "',AP    MM
    //    MM    8M"""""" `YMMMa. 8M""""""  MM          MM    M8  ,pm9MM  `YMMMa. `YMMMa.    VA ,V  VA ,V  8M     M8 MM    8MI    MM
    //    MM    YM.    , L.   I8 YM.    ,  MM          MM   ,AP 8M   MM  L.   I8 L.   I8     VVV    VVV   YA.   ,A9 MM    `Mb    MM
    //  .JMML.   `Mbmmd' M9mmmP'  `Mbmmd'  `Mbmo       MMbmmd'  `Moo9^Yo.M9mmmP' M9mmmP'      W      W     `Ybmd9'.JMML.   `Wbmd"MML.
    //                                                 MM
    //                                       mmmmmmm .JMML.

    /**
     * Сброс пароля для пользователя
     *
     * @param integer $key Ключ из письма
     *
     * @return string Результат работы
     *
     * @version 0.6.4 04.11.2015
     * @author Дмитрий Щербаков <atomcms@ya.ru>
     */
    static function reset_password($key) {
        $db_result = query("SELECT * FROM `" . Config::$global['db_prefix'] . "user`
            WHERE reset_password = :reset_password
            LIMIT 1
        ", [
            'reset_password' => $key,
        ], __FILE__, __LINE__);
        if ($db_result != -1 AND count($db_result) > 0) {
            $email = $db_result[0]['email'];
            $password = Func::generate_password();

            $db_result = query("UPDATE `" . Config::$global['db_prefix'] . "user` SET
                password = :password,
                reset_password = '',
                updated_at = :updated_at
                WHERE reset_password = :reset_password
            ", [
                'password'       => Func::collect_password($password),
                'reset_password' => $key,
                'updated_at'     => Config::$global['currtime_format'],
            ], __FILE__, __LINE__);
            if ($db_result >= 0) {
                Protocol::ins('Сброс пароля для пользователя: ' . $email, __FILE__, __LINE__, __FUNCTION__);

                if (!Email::send('lost', $email, ['email' => $email, 'password' => $password])) {
                    return [
                        'code' => 'warning',
                        'text' => 'При отправке пароля произошла ошибка. Свяжитесь с администрацией.',
                    ];
                } else {
                    $db_result = query("DELETE FROM `" . Config::$global['db_prefix'] . "user_block`
                        WHERE user_id = :user_id
                    ", [
                        'user_id' => Config::$userinfo['id'],
                    ], __FILE__, __LINE__);

                    return [
                        'code' => 'success',
                        'text' => 'Новый пароль успешно отправлен на вашу почту.',
                    ];
                }
            } else {
                return [
                    'code' => 'warning',
                    'text' => 'При создании нового пароля произошла ошибка. Обновите это окно.',
                ];
            }
        } else {
            return [
                'code' => 'warning',
                'text' => 'Не найден запрос на изменение пароля.',
            ];
        }
    }
}
?>
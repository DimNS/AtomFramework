<?php
/**
 * Changelog
 *
 * Модель для работы с файлом изменений
 *
 * @version 0.6.4 04.11.2015
 * @author Дмитрий Щербаков <atomcms@ya.ru>
 */

namespace AtomFramework\Models;

use AtomFramework\Configs\Config;

class Changelog {
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
     * Получение списка изменений
     *
     * @return array
     *
     * @version 0.6 27.10.2015
     * @author Дмитрий Щербаков <atomcms@ya.ru>
     */
    static function get() {
        // Подключаем класс для парсинга .md
        $Parsedown = new \Parsedown();

        $file = Config::$global['path_home_root'] . '/CHANGELOG.md';

        // Получаем html-код содержимого файла
        if (is_readable($file)) {
            $content = file_get_contents($file);

            if ($content === false) {
                $content = '';
            } else {
                $content = $Parsedown->text($content);
            }
        }

        return [
            'code'    => 'success',
            'text'    => 'ok',
            'content' => $content,
        ];
    }

    //
    //           ,,
    //         `7MM                       `7MM
    //           MM                         MM
    //   ,p6"bo  MMpMMMb.  .gP"Ya   ,p6"bo  MM  ,MP'
    //  6M'  OO  MM    MM ,M'   Yb 6M'  OO  MM ;Y
    //  8M       MM    MM 8M"""""" 8M       MM;Mm
    //  YM.    , MM    MM YM.    , YM.    , MM `Mb.
    //   YMbmd'.JMML  JMML.`Mbmmd'  YMbmd'.JMML. YA.
    //
    //

    /**
     * Отметка пользователя о новой версии
     *
     * @return array
     *
     * @version 0.6.4 04.11.2015
     * @author Дмитрий Щербаков <atomcms@ya.ru>
     */
    static function check() {
        query("UPDATE `" . Config::$global['db_prefix'] . "user` SET
            version = :version,
            updated_at = :updated_at
            WHERE id = :id
            LIMIT 1
        ", [
            'id'         => Config::$userinfo['id'],
            'version'    => Config::$global['version'],
            'updated_at' => Config::$global['currtime_format'],
        ], __FILE__, __LINE__);

        return [
            'code' => 'success',
            'text' => 'ok',
        ];
    }
}
?>
<?php
/**
 * Main
 *
 * Модель для работы главной страницы
 *
 * @version 0.6.3 04.11.2015
 * @author Дмитрий Щербаков <atomcms@ya.ru>
 */

namespace AtomFramework\Models;

class Main {
    //
    //       ,,                             ,,    ,,
    //     `7MM                           `7MM    db
    //       MM                             MM
    //       MM   ,6"Yb.  `7MMpMMMb.   ,M""bMM  `7MM  `7MMpMMMb.  .P"Ybmmm
    //       MM  8)   MM    MM    MM ,AP    MM    MM    MM    MM :MI  I8
    //       MM   ,pm9MM    MM    MM 8MI    MM    MM    MM    MM  WmmmP"
    //       MM  8M   MM    MM    MM `Mb    MM    MM    MM    MM 8M
    //     .JMML.`Moo9^Yo..JMML  JMML.`Wbmd"MML..JMML..JMML  JMML.YMMMMMb
    //                                                           6'     dP
    //                                                           Ybmmmd'

    /**
     * Получение данных для главной страницы (перед авторизацией)
     *
     * @return array
     *
     * @version 0.6.3 04.11.2015
     * @author Дмитрий Щербаков <atomcms@ya.ru>
     */
    static function landing() {
        return [
            'code' => 'success',
            'text' => 'Данные успешно получены',
        ];
    }

    //
    //            ,,                    ,,        ,,                                          ,,
    //          `7MM                  `7MM       *MM                                        `7MM
    //            MM                    MM        MM                                          MM
    //       ,M""bMM   ,6"Yb.  ,pP"Ybd  MMpMMMb.  MM,dMMb.   ,pW"Wq.   ,6"Yb.  `7Mb,od8  ,M""bMM
    //     ,AP    MM  8)   MM  8I   `"  MM    MM  MM    `Mb 6W'   `Wb 8)   MM    MM' "',AP    MM
    //     8MI    MM   ,pm9MM  `YMMMa.  MM    MM  MM     M8 8M     M8  ,pm9MM    MM    8MI    MM
    //     `Mb    MM  8M   MM  L.   I8  MM    MM  MM.   ,M9 YA.   ,A9 8M   MM    MM    `Mb    MM
    //      `Wbmd"MML.`Moo9^Yo.M9mmmP'.JMML  JMML.P^YbmdP'   `Ybmd9'  `Moo9^Yo..JMML.   `Wbmd"MML.
    //
    //

    /**
     * Получение данных для главной страницы (после авторизации)
     *
     * @return array
     *
     * @version 0.6.3 04.11.2015
     * @author Дмитрий Щербаков <atomcms@ya.ru>
     */
    static function dashboard() {
        return [
            'code' => 'success',
            'text' => 'Данные успешно получены',
        ];
    }
}
?>
<?php
/**
 * AtomException
 *
 * Класс для работы с исключениями
 *
 * @version 0.6 27.10.2015
 * @author Дмитрий Щербаков <atomcms@ya.ru>
 */

namespace AtomFramework\Utility;

use Exception;

class AtomException extends Exception {
    /**
     * Переопределим конструктор добавив необязательную передачу имени файла и номера строки
     *
     * @param string  $message    Строка с сообщением
     * @param integer $errorLevel Номер ошибки
     * @param string  $errorFile  Путь к файлу где произошел сбой
     * @param integer $errorLine  Номер строки файла
     *
     * @return string|array
     *
     * @version 0.3 08.05.2015
     * @author Дмитрий Щербаков <atomcms@ya.ru>
     */
    public function __construct($message, $errorLevel = 0, $errorFile = '', $errorLine = '') {
        parent::__construct($message, $errorLevel);

        if ($errorFile != '') {
            $this->file = $errorFile;
        }

        if ($errorLine != '') {
            $this->line = $errorLine;
        }
    }

    /**
     * Обработка исключения
     *
     * @param boolean $ajax Откуда пришел запрос
     * @param object  $e    Объект с данными об исключении
     *
     * @return string|array
     *
     * @version 0.6 27.10.2015
     * @author Дмитрий Щербаков <atomcms@ya.ru>
     */
    static function report($ajax, $e) {
        $data = [
            'code' => 'danger',
            'text' => '',
            'code_number' => $e->getCode(),
            'message_admin' => $e->getMessage(),
        ];

        switch ($e->getCode()) {
            // Ошибка в запросе SQL
            case '10':
                $data['text'] = 'Произошла ошибка при работе с базой данных';
            break;

            // Критическая ошибка
            case '20':
                $data['text'] = 'Произошла ошибка в коде';
            break;

            // Неизвестная ошибка
            default:
                $data['text'] = 'Неизвестная ошибка';
            break;
        }

        \AtomFramework\Utility\Error::ins($data['code_number'], $data['message_admin'], $e->getFile(), $e->getLine());

        // В зависимости откуда пришел запрос, делаем правильный вывод
        if ($ajax === 'true') {
            // Возвращаем результат в виде массива
            return json_encode($data);
        } else {
            // Отображаем содержимое страницы
            return \AtomFramework\Views\AtomException::show($data);
        }
    }
}
?>
<?php
/**
 * Migration
 *
 * Класс для применения изменений в БД
 *
 * @version 0.6 27.10.2015
 * @author Дмитрий Щербаков <atomcms@ya.ru>
 */

namespace AtomFramework\Controllers;

use AtomFramework\Configs\Config;

class Migration {
    /**
     * Запуск контроллера
     *
     * @param array $params Массив маршрута
     *
     * @return content
     *
     * @version 0.6 27.10.2015
     * @author Дмитрий Щербаков <atomcms@ya.ru>
     */
    static function start($params) {
        if ($params['params']['token'] === Config::$global['db_migration_token']) {
            switch ($params['action']) {
                case 'install': // Первичное развертывание системы, или переразвертывание на начало
                case 'change': // Применение изменений
                    $result = call_user_func(['\\AtomFramework\\Models\\Migration', $params['action']]);

                    if (Config::$global['production'] AND $result['data_success'] === $result['data_count']) {
                        // Все запросы успешно выполнены, удаляем файл
                        @unlink(Config::$global['path_home_root'] . '/' . $params['action'] . '.sql');
                    }

                    return \AtomFramework\Views\Migration::show($result);
                break;

                default:
                    return \AtomFramework\Controllers\Error404::start();
                break;
            }
        } else {
            return \AtomFramework\Controllers\Main::error('info', 'Неверный или отсутствующий ключ');
        }
    }
}
?>
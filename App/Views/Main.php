<?php
/**
 * Main
 *
 * Класс для представления главной страницы
 *
 * @version 0.6.7 07.11.2015
 * @author Дмитрий Щербаков <atomcms@ya.ru>
 */

namespace AtomFramework\Views;

use AtomFramework\Configs\Config;
use AtomFramework\Utility\Func;
use AtomFramework\Utility\Template;

class Main {
    /**
     * Показать Landing
     *
     * @param array $data Массив данных
     *
     * @return null
     *
     * @version 0.1 27.04.2015
     * @author Дмитрий Щербаков <atomcms@ya.ru>
     */
    static function landing($data) {
        Template::show(['Header', 'AuthLogin', 'Footer']);
    }

    /**
     * Показать Dashboard
     *
     * @param array $data Массив данных
     *
     * @return null
     *
     * @version 0.6.5 07.11.2015
     * @author Дмитрий Щербаков <atomcms@ya.ru>
     */
    static function dashboard($data) {
        Template::show(['Header', 'HeaderInner']);
        ?>
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>Главная</h1>
        </section>

        <!-- Main content -->
        <section class="content">
            <?php
            Func::debug($data);
            ?>
        </section><!-- /.content -->
        <?php
        Config::$global['page_js'] = 'main';
        Config::$global['page_js_vars'] = "

        ";
        Template::show(['FooterInner', 'Footer']);
    }
}
?>
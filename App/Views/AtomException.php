<?php
/**
 * AtomException
 *
 * Класс для представления страницы исключения
 *
 * @version 0.6 27.10.2015
 * @author Дмитрий Щербаков <atomcms@ya.ru>
 */

namespace AtomFramework\Views;

use AtomFramework\Configs\Config;
use AtomFramework\Utility\Template;

class AtomException {
    /**
     * Показать страницу
     *
     * @param array $data Массив с данными об исключении
     *
     * @return null
     *
     * @version 0.3 08.05.2015
     * @author Дмитрий Щербаков <atomcms@ya.ru>
     */
    static function show($data) {
        Template::show(['Header']);
        ?>
        <!-- Main content -->
        <section class="content light_bg">
            <div class="exception-page">
                <h2 class="headline text-yellow">Ошибка</h2>
                <div class="exception-content">
                    <h3><i class="fa fa-warning text-yellow"></i> Номер ошибки: <?php echo $data['code_number'] . '-' . Config::$global['currtime']; ?></h3>
                    <p><?php echo $data['text']; ?></p>
                    <p>Вернитесь <a href=<?php echo Config::$global['path_short_root']; ?>/>на главную</a> и попробуйте снова</p>
                </div><!-- /.exception-content -->
            </div><!-- /.exception-page -->
        </section><!-- /.content -->
        <?php
        Template::show(['Footer']);
    }
}
?>
<?php
/**
 * Migration
 *
 * Класс для представления страницы результата работы миграции
 *
 * @version 0.6 27.10.2015
 * @author Дмитрий Щербаков <atomcms@ya.ru>
 */

namespace AtomFramework\Views;

use AtomFramework\Configs\Config;
use AtomFramework\Utility\Template;

class Migration {
    /**
     * Показать результат
     *
     * @param array $data Массив данных
     *
     * @return null
     *
     * @version 0.6 27.10.2015
     * @author Дмитрий Щербаков <atomcms@ya.ru>
     */
    static function show($data) {
        Template::show(['Header']);
        ?>
        <!-- Main content -->
        <section class="content light_bg">
            <?php
            if ($data['data_success'] === $data['data_count']) {
                ?>
                <h2 class="text-green">Все запросы успешно выполнены</h2>
                <?php
            } else {
                ?>
                <h2 class="text-red">Один или несколько запросов привели к ошибке</h2>
                <?php
            }

            if (count($data['data']) > 0) {
                ?>
                <ol>
                    <?php
                    foreach ($data['data'] as $sql_string_result) {
                        if ($sql_string_result['code'] === 'success') {
                            ?>
                            <li class="text-muted"><?php echo $sql_string_result['sql']; ?>;</li>
                            <?php
                        } else {
                            ?>
                            <li class="text-yellow"><?php echo $sql_string_result['sql']; ?>;</li>
                            <?php
                        }
                    }
                    ?>
                </ol>
                <?php
            }
            ?>
            <p>
                <a href="<?php echo Config::$global['path_short_root']; ?>" class="btn btn-primary">Перейти на главную</a>
            </p>
        </section><!-- /.content -->
        <?php
        Template::show(['Footer']);
    }
}
?>
<?php
/**
 * Подвал страницы
 *
 * @version 0.8.0 28.01.2016
 * @author Дмитрий Щербаков <atomcms@ya.ru>
 */

use AtomFramework\Configs\Config;
?>
        </div><!-- /.content-wrapper -->

        <!-- Main Footer -->
        <footer class="main-footer">
            <!-- To the right -->
            <div class="pull-right hidden-xs">
                <a href="http://bestion.ru">Лучшее решение</a>
            </div>

            <!-- Default to the left -->
            <?php echo Config::$global['start_year']; ?> &copy; <?php echo Config::$global['proj_name']; ?> <a id="changelog_link" href="javascript:;" class="wnd_open" data-id="changelog">v<?php echo $_ENV['VERSION']; ?></a>
        </footer>
    </div><!-- ./wrapper -->
</div><!-- ./container_all -->
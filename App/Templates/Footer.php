<?php
/**
 * Конечные определения страницы
 *
 * @version 0.6.5 07.11.2015
 * @author Дмитрий Щербаков <atomcms@ya.ru>
 */

use AtomFramework\Configs\Config;
use AtomFramework\Utility\Func;
?>
        <!-- Confirm -->
        <div id="atom_confirm" class="window">
            <div class="modal-content">
                <div class="modal-header">
                    <a href="javascript:windowClose('atom_confirm');" class="close"><i class="fa fa-times"></i></a>
                    <h4 class="modal-title">NaN</h4>
                </div>
                <div class="modal-body">
                    <p>NaN</p>
                </div>
                <div class="modal-footer">
                    <a href="javascript:;" id="atom_confirm_cancel">Отмена</a>
                    <a href="javascript:;" id="atom_confirm_ok"></a>
                    <a href="javascript:;" id="atom_confirm_third">Третья кнопка</a>
                </div>
            </div>
        </div>

        <!-- Changelog -->
        <div id="changelog" class="window_layer">
            <div class="window_layer_box">
                <div class="modal-content">
                    <div class="modal-header">
                        <a href="javascript:;" class="wnd_close close" data-id="changelog"><i class="fa fa-times"></i></a>
                        <h4 class="modal-title">Последние обновления</h4>
                    </div>
                    <div class="modal-body">
                        <!-- Здесь будет список последних изменений -->
                    </div>
                    <div class="modal-footer">
                        <a href="javascript:;" class="btn btn-default wnd_close" data-id="changelog">Закрыть</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- User registration -->
        <div id="user_reg" class="window">
            <div class="modal-content">
                <div class="modal-header">
                    <a href="javascript:;" class="wnd_close close" data-id="user_reg"><i class="fa fa-times"></i></a>
                    <h4 class="modal-title">Регистрация</h4>
                </div>
                <div class="modal-body">
                    <form id="user_reg_forma" class="p10">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-fw fa-user"></i></span>
                            <input type="text" id="forma_reg_name" class="form-control validate[required]" placeholder="Ваше имя">
                        </div>
                        <br>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-fw fa-envelope"></i></span>
                            <input type="text" id="forma_reg_email" class="form-control validate[required,custom[email]]" placeholder="Электронная почта">
                        </div>
                        <br>
                        <div>
                            <label><input type="checkbox" class="icheck validate[required]" value="1" name="forma_reg_offer"> Я принимаю все условия <a href="<?php echo Config::$global['path_short_root']; ?>/stat-offer.pdf" target="_blank">публичной офёрты</a> и соглашаюсь с <a href="javascript:;" target="_blank">политикой конфиденциальности</a>.</label>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <a href="javascript:;" class="btn btn-default wnd_close pull-left" data-id="user_reg">Закрыть</a>
                    <a href="javascript:;" class="btn btn-primary forma_submit" data-forma="user_reg">Получить пароль</a>
                </div>
            </div>
        </div>

        <!-- User lost password -->
        <div id="user_lost" class="window">
            <div class="modal-content">
                <div class="modal-header">
                    <a href="javascript:;" class="wnd_close close" data-id="user_lost"><i class="fa fa-times"></i></a>
                    <h4 class="modal-title">Забыли пароль?</h4>
                </div>
                <div class="modal-body">
                    <form id="user_lost_forma" class="p10">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-fw fa-envelope"></i></span>
                            <input type="text" id="forma_lost_email" class="form-control validate[required,custom[email]]" placeholder="Электронная почта">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <a href="javascript:;" class="btn btn-default wnd_close pull-left" data-id="user_lost">Закрыть</a>
                    <a href="javascript:;" class="btn btn-primary forma_submit" data-forma="user_lost">Выслать новый пароль</a>
                </div>
            </div>
        </div>

        <!-- Overlays -->
        <div id="ajax_overlay"></div>
        <div id="window_overlay"></div>
        <div id="ajax_waiter" title="Идёт обмен данными..."><div id="ajax_loader"></div></div>

        <!-- jQuery -->
        <script src="<?php echo Config::$global['path_short_root']; ?>/assets/plugins/jquery-2.1.3.min.js" type="text/javascript"></script>
        <!-- Bootstrap 3.3.2 JS -->
        <script src="<?php echo Config::$global['path_short_root']; ?>/assets/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
        <!-- AdminLTE App -->
        <script src="<?php echo Config::$global['path_short_root']; ?>/assets/adminlte/js/app.min.js" type="text/javascript"></script>
        <!-- AjaxUpload -->
        <script src="<?php echo Config::$global['path_short_root']; ?>/assets/plugins/ajaxupload.js" type="text/javascript"></script>
        <!-- jsDeferred -->
        <script src="<?php echo Config::$global['path_short_root']; ?>/assets/plugins/jquery.jsdeferred.js" type="text/javascript"></script>
        <!-- Bootstrap Toggle -->
        <script src="<?php echo Config::$global['path_short_root']; ?>/assets/plugins/bootstrap-toggle/bootstrap-toggle.min.js" type="text/javascript"></script>
        <!-- Bootstrap Tour -->
        <script src="<?php echo Config::$global['path_short_root']; ?>/assets/plugins/bootstrap-tour/bootstrap-tour.min.js" type="text/javascript"></script>
        <!-- iCheck -->
        <script src="<?php echo Config::$global['path_short_root']; ?>/assets/plugins/iCheck/icheck.min.js" type="text/javascript"></script>
        <!-- Select2 -->
        <script src="<?php echo Config::$global['path_short_root']; ?>/assets/plugins/Select2/js/i18n/ru.js" type="text/javascript"></script>
        <script src="<?php echo Config::$global['path_short_root']; ?>/assets/plugins/Select2/js/select2.full.min.js" type="text/javascript"></script>
        <!-- validationEngine -->
        <script src="<?php echo Config::$global['path_short_root']; ?>/assets/plugins/validationEngine/jquery.validationEngine-ru.js" type="text/javascript"></script>
        <script src="<?php echo Config::$global['path_short_root']; ?>/assets/plugins/validationEngine/jquery.validationEngine.js" type="text/javascript"></script>

        <script type="text/javascript">
            var pathRoot = '<?php echo Config::$global['path_http_root']; ?>';
            var messageCode = '<?php print(isset(Config::$global['message_code']) ? Config::$global['message_code'] : ''); ?>';
            var messageText = '<?php print(isset(Config::$global['message_text']) ? Config::$global['message_text'] : ''); ?>';
            var mobileBrowser = <?php print(Config::$global['mobile'] ? 'true' : 'false'); ?>;
            var pageLogin = <?php print(Func::is_login() ? 'true' : 'false'); ?>;
            var firstLogin = <?php if (isset(Config::$userinfo['version']) AND Config::$userinfo['version'] === '0') { echo 'true'; } else { echo 'false'; } ?>;
            var changelog = <?php if (isset(Config::$userinfo['version']) AND Config::$userinfo['version'] < Config::$global['version']) { echo 'true'; } else { echo 'false'; } ?>;
            <?php print(isset(Config::$global['page_js_vars']) ? Config::$global['page_js_vars'] : ''); ?>
        </script>

        <?php $min = (Config::$global['production'] ? '.min' : ''); ?>
        <script src="<?php echo Config::$global['path_short_root']; ?>/js/core<?php echo $min; ?>.js?v=<?php echo md5_file(Config::$global['path_home_root'] . '/js/core.min.js'); ?>" type="text/javascript"></script>
        <script src="<?php echo Config::$global['path_short_root']; ?>/js/ajax<?php echo $min; ?>.js?v=<?php echo md5_file(Config::$global['path_home_root'] . '/js/ajax.min.js'); ?>" type="text/javascript"></script>
        <script src="<?php echo Config::$global['path_short_root']; ?>/js/forms<?php echo $min; ?>.js?v=<?php echo md5_file(Config::$global['path_home_root'] . '/js/forms.min.js'); ?>" type="text/javascript"></script>
        <script src="<?php echo Config::$global['path_short_root']; ?>/js/app<?php echo $min; ?>.js?v=<?php echo md5_file(Config::$global['path_home_root'] . '/js/app.min.js'); ?>" type="text/javascript"></script>

        <?php print(isset(Config::$global['page_js']) ? '<script src="' . Config::$global['path_short_root'] . '/js/page_' . Config::$global['page_js'] . $min . '.js?v=' . md5_file(Config::$global['path_home_root'] . '/js/page_' . Config::$global['page_js'] . '.min.js') . '" type="text/javascript"></script>' : ''); ?>
    </body>

</html>
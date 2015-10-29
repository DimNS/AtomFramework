<?php
/**
 * User
 *
 * Представление пользователей
 *
 * @version 0.6 27.10.2015
 * @author Дмитрий Щербаков <atomcms@ya.ru>
 */

namespace AtomFramework\Views;

use AtomFramework\Utility\Template;

class User {
    /**
     * Показать профиль
     *
     * @param array $data Массив данных
     *
     * @return null
     *
     * @version 0.5 25.05.2015
     * @author Дмитрий Щербаков <atomcms@ya.ru>
     */
    static function profile($data) {
        Template::show(['Header', 'HeaderInner']);
        ?>
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Профиль
                <small>мои настройки</small>
            </h1>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">Личная информация</h3>
                </div><!-- /.box-header -->

                <!-- form start -->
                <form role="form" id="user_save_forma">
                    <div class="box-body">
                        <div class="form-group">
                            <label>Электронная почта (для входа)</label>
                            <input type="email" class="form-control" value="<?php echo $data['email']; ?>" disabled="disabled">
                        </div>
                        <div class="form-group">
                            <label>Имя</label>
                            <input type="text" class="form-control validate[required]" id="forma_users_save_name" value="<?php echo $data['name']; ?>">
                        </div>
                        <div class="form-group">
                            <label>Новый пароль</label>
                            <label><input type="checkbox" id="forma_users_save_newpassword" class="bootstrap-toggle" data-on="Да" data-off="Нет" data-size="mini"></label>
                            <div id="user_profile_newpwd_block" class="form-group input-group has-feedback hide">
                                <input type="password" id="forma_users_save_password" class="form-control" name="password">
                                <span class="input-group-btn">
                                    <button class="btn btn-info" type="button">
                                        <span id="show_hide_password" class="fa fa-fw fa-lock"></span>
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div><!-- /.box-body -->

                    <div class="box-footer">
                        <a href="javascript:;" class="forma_submit btn btn-primary" data-forma="user_save"><i class="fa fa-save"></i> Сохранить</a>
                    </div>
                </form>
            </div>
        </section><!-- /.content -->
        <?php
        Template::show(['FooterInner', 'Footer']);
    }
}
?>
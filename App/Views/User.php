<?php
/**
 * User
 *
 * Представление пользователей
 *
 * @version 0.1 27.04.2015
 * @author Дмитрий Щербаков <atomcms@ya.ru>
 */

namespace App\Views;

use App\Utility\Template;

class User {
	/**
	 * Показать профиль
	 *
	 * @param array $data Массив данных
	 *
	 * @return null
	 *
	 * @version 0.1 27.04.2015
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
						<div id="user_profile_newpwd_block" class="form-group hide">
							<label>Новый пароль</label>
							<div class="form-group input-group has-feedback">
								<input type="password" class="form-control" id="forma_users_save_password">
								<span class="input-group-btn">
									<button class="btn btn-info" type="button">
										<span id="show_hide_password" class="fa fa-fw fa-lock"></span>
									</button>
								</span>
							</div>
						</div>
						<div class="form-group">
							<label><input type="checkbox" id="forma_users_save_newpassword" class="minimal"> Новый пароль?</label>
							<p class="help-block">Отметьте для смены пароля.</p>
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
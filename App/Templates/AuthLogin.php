<?php
/**
* Показывает блок с формой входа
*
* @version 0.1 27.04.2015
* @author Дмитрий Щербаков <atomcms@ya.ru>
*/

use App\Configs\Config;
use App\Utility\Template;
?>
<div class="login-page">
	<div class="login-box">
		<div class="login-logo">
			<a href="<?php echo Config::$global['path_short_root']; ?>"><strong><?php echo Config::$global['proj_name']; ?></strong></a>
		</div><!-- /.login-logo -->
		<div class="login-box-body">
			<?php
			Template::show(['Alerts']);
			?>

			<p class="login-box-msg">Заполните форму для входа</p>

			<form action="<?php echo Config::$global['path_short_root']; ?>/user/signin" method="post">
				<div class="form-group input-group has-feedback">
					<input type="text" class="form-control" name="email" placeholder="Электронная почта">
					<span class="input-group-btn">
						<button class="btn btn-info disabled" type="button">
							<span class="fa fa-fw fa-envelope"></span>
						</button>
					</span>
				</div>
				<div class="form-group input-group has-feedback">
					<input type="password" class="form-control" name="password" placeholder="Пароль">
					<span class="input-group-btn">
						<button class="btn btn-info" type="button">
							<span id="show_hide_password" class="fa fa-fw fa-lock"></span>
						</button>
					</span>
				</div>
				<div class="form-group row">
					<div class="col-xs-4">
						<button type="submit" class="btn btn-primary btn-block">Войти</button>
					</div><!-- /.col -->
					<div class="col-xs-8">
						&nbsp;
					</div><!-- /.col -->
				</div>
			</form>

			<a href="javascript:;" class="wnd_open" data-id="user_lost">Я забыл(-а) свой пароль</a><br>
			<a href="javascript:;" class="wnd_open text-center" data-id="user_reg">Зарегистрироваться</a>
		</div><!-- /.login-box-body -->
	</div><!-- /.login-box -->
</div><!-- /.login-page -->
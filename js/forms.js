/**
 * Формы для проверки валидатором и запуска AJAX
 *
 * @version 0.6.5 07.11.2015
 * @author Дмитрий Щербаков <atomcms@ya.ru>
 */

var atomForms = (function() {
    /**
     * Инициализация объекта
     *
     * @return null
     *
     * @version 0.6.5 07.11.2015
     * @author Дмитрий Щербаков <atomcms@ya.ru>
     */
    var init = function() {
        $('body').on('click', '.forma_submit', function() {
            var button = $(this);
            var forma  = button.attr('data-forma');

            if ($('#' + forma + '_forma').validationEngine('validate')) {
                switch (forma) {
                    //
                    //
                    //     `7MMF'   `7MF'.M"""bgd `7MM"""YMM  `7MM"""Mq.
                    //       MM       M ,MI    "Y   MM    `7    MM   `MM.
                    //       MM       M `MMb.       MM   d      MM   ,M9
                    //       MM       M   `YMMNq.   MMmmMM      MMmmdM9
                    //       MM       M .     `MM   MM   Y  ,   MM  YM.
                    //       YM.     ,M Mb     dM   MM     ,M   MM   `Mb.
                    //        `bmmmmd"' P"Ybmmd"  .JMMmmmmMMM .JMML. .JMM.
                    //
                    //

                    // Регистрация пользователя
                    case 'user_reg':
                        atomAJAX.blockWaiter('show');

                        $.ajax({
                            url: window.pathRoot + '/user/add',
                            data: {
                                'ajax' : true,
                                'name' : $('#forma_reg_name').val(),
                                'email': $('#forma_reg_email').val()
                            },
                            success: function(data, textStatus, jqXHR) {
                                atomCore.windowClose('user_reg');
                                atomCore.showMessage(data.code, data.text);
                            },
                            complete: function() {
                                atomAJAX.blockWaiter('hide');
                            },
                            error: function(jqXHR, textStatus, errorThrown) {
                                atomAJAX.showError(textStatus, errorThrown);
                            }
                        });
                    break;

                    // Напоминание пароля
                    case 'user_lost':
                        atomAJAX.blockWaiter('show');

                        $.ajax({
                            url: window.pathRoot + '/user/lost_password',
                            data: {
                                'ajax' : true,
                                'email': $('#forma_lost_email').val()
                            },
                            success: function(data, textStatus, jqXHR) {
                                atomCore.windowClose('user_lost');
                                atomCore.showMessage(data.code, data.text);
                            },
                            complete: function() {
                                atomAJAX.blockWaiter('hide');
                            },
                            error: function(jqXHR, textStatus, errorThrown) {
                                atomAJAX.showError(textStatus, errorThrown);
                            }
                        });
                    break;

                    // Сохранение личных данных
                    case 'user_save':
                        atomAJAX.blockWaiter('show');

                        $.ajax({
                            url: window.pathRoot + '/user/save',
                            data: {
                                'ajax'       : true,
                                'name'       : $('#forma_users_save_name').val(),
                                'password'   : $('#forma_users_save_password').val(),
                                'newpassword': $('#forma_users_save_newpassword').prop('checked')
                            },
                            success: function(data, textStatus, jqXHR) {
                                $('.userinfo_name').text($('#forma_users_save_name').val());
                                atomCore.showMessage(data.code, data.text);
                            },
                            complete: function() {
                                atomAJAX.blockWaiter('hide');
                            },
                            error: function(jqXHR, textStatus, errorThrown) {
                                atomAJAX.showError(textStatus, errorThrown);
                            }
                        });
                    break;
                }
            }
        });
    };

    return {
        init: init,
    };
})();

atomForms.init();
/**
 * Общие функции JavaScript
 *
 * @version 0.8.0 28.01.2016
 * @author Дмитрий Щербаков <atomcms@ya.ru>
 */

var atomCore = (function() {
    // Позиция скролла основного окна, для работы всплывающих окон
    var iScrolled = 0;

    // Активное всплывающее окно
    var windowActive = 'NaN';

    /**
     * Инициализация объекта
     *
     * @return null
     *
     * @version 0.8.0 28.01.2016
     * @author Дмитрий Щербаков <atomcms@ya.ru>
     */
    var init = function() {
        $('body').on('click', '.wnd_open', function() {
            atomCore.windowOpen($(this).attr('data-id'));
        });

        $('body').on('click', '.wnd_close', function() {
            atomCore.windowClose($(this).attr('data-id'));
        });

        $('body').on('click', '#window_overlay', function() {
            if (atomCore.windowActive != 'NaN') {
                atomCore.windowClose(atomCore.windowActive);
            }
        });

        $(document).mouseup(function (e) {
            if (atomCore.windowActive != 'NaN' && $('.window_layer_box').is(':visible')) {
                var container = $('.window_layer_box');
                if (container.has(e.target).length === 0){
                    atomCore.windowClose(atomCore.windowActive);
                }
            }
        });

        $('#alerts-container .close').click(function() {
            $(this).parent().hide();
        });

        // Показать \ Скрыть пароль
        $('#show_hide_password').click(function() {
            if ($(this).hasClass('fa-unlock-alt')) {
                // Скрываем
                $('input[name="password"]').attr('type', 'password');
                $(this).removeClass('fa-unlock-alt').addClass('fa-lock');
            } else {
                // Показываем
                $('input[name="password"]').attr('type', 'text');
                $(this).removeClass('fa-lock').addClass('fa-unlock-alt');
            }
        });

        // Только не мобильным браузерам
        if (!window.mobileBrowser) {
            // Находим все селекты у которых есть класс select2_wtags
            $('select.select2_wtags').select2({
                tags : true,
                width: '100%'
            });
        }

        // Показываем сообщение, если необходимо
        if (window.messageText != '') atomCore.showMessage(window.messageCode, window.messageText);

        // Подключем плагин iCheck
        $('.icheck').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass   : 'iradio_square-blue',
            increaseArea : '20%' // optional
        });

        // Подключаем плагин Bootstrap Toggle
        $('.bootstrap-toggle').bootstrapToggle({
            onstyle : 'success',
            offstyle: 'danger',
        });

        // Отслеживаем галочку "Новый пароль: Да" в профиле пользователя
        $('#forma_users_save_newpassword').change(function() {
            if ($(this).prop('checked')) {
                $('#user_profile_newpwd_block').removeClass('hide');
            } else {
                $('#user_profile_newpwd_block').addClass('hide');
            }
        });
    };

    //
    //
    //  `7MMF'     A     `7MF'`7MMF'`7MN.   `7MF'`7MM"""Yb.     .g8""8q.`7MMF'     A     `7MF'.M"""bgd
    //    `MA     ,MA     ,V    MM    MMN.    M    MM    `Yb. .dP'    `YM.`MA     ,MA     ,V ,MI    "Y
    //     VM:   ,VVM:   ,V     MM    M YMb   M    MM     `Mb dM'      `MM VM:   ,VVM:   ,V  `MMb.
    //      MM.  M' MM.  M'     MM    M  `MN. M    MM      MM MM        MM  MM.  M' MM.  M'    `YMMNq.
    //      `MM A'  `MM A'      MM    M   `MM.M    MM     ,MP MM.      ,MP  `MM A'  `MM A'   .     `MM
    //       :MM;    :MM;       MM    M     YMM    MM    ,dP' `Mb.    ,dP'   :MM;    :MM;    Mb     dM
    //        VF      VF      .JMML..JML.    YM  .JMMmmmdP'     `"bmmd"'      VF      VF     P"Ybmmd"
    //
    //

    /**
     * Открыть окно по идентификатору
     *
     * @param string wndID Идентификатор окна
     *
     * @return null
     *
     * @version 0.6.5 07.11.2015
     * @author Дмитрий Щербаков <atomcms@ya.ru>
     */
    var windowOpen = function(wndID) {
        if ($('#' + wndID).hasClass('window_layer')) {
            // Всплывающее окно как Вконтакте
            atomCore.iScrolled = $(window).scrollTop();
            $(window).scrollTop(0);
            $('#container_all').css('position', 'fixed');
            $('#container_all').css('margin-top', '-' + atomCore.iScrolled + 'px');
            $('#' + wndID).css('display', 'block').show();

            if (wndID == 'changelog') {
                atomCore.windowActive = wndID;

                // Получаем список последних изменений
                atomAJAX.blockWaiter('show');

                $.ajax({
                    url: window.pathRoot + '/changelog',
                    data: {
                        'ajax': true,
                    },
                    success: function(data, textStatus, jqXHR) {
                        if (data.code == 'success') {
                            $('#changelog .modal-body').html(data.content);
                        } else {
                            atomCore.showMessage(data.code, data.text, true);
                        }
                    },
                    complete: function() {
                        atomAJAX.blockWaiter('hide');
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        atomAJAX.showError(textStatus, errorThrown);
                    }
                });
            }
        } else {
            // Обычное всплывающее окно
            $('#' + wndID).css({
                'top' : Math.max(0, (($(window).height() - $('#' + wndID).outerHeight()) / 2)) + 'px',
                'left': Math.max(0, (($(window).width() - $('#' + wndID).outerWidth()) / 2)) + 'px'
            }).show();
        }

        $('#window_overlay').show();
        atomCore.windowActive = wndID;
    }

    /**
     * Закрыть окно по идентификатору
     *
     * @param string wndID Идентификатор окна
     *
     * @return null
     *
     * @version 0.6.5 07.11.2015
     * @author Дмитрий Щербаков <atomcms@ya.ru>
     */
    var windowClose = function(wndID) {
        switch (wndID) {
            case 'changelog':
                // Отмечаем пользователю просмотр последних изменений
                $.ajax({
                    url: window.pathRoot + '/changelog/check'
                });
            break;
        }

        if ($('#' + wndID).hasClass('window_layer')) {
            // Всплывающее окно как Вконтакте
            $('#container_all').css('margin-top', '0px');
            $('#container_all').css('position', 'absolute');
            $(window).scrollTop(atomCore.iScrolled);
            $('#' + wndID).css('display', 'none');
        } else {
            // Обычное всплывающее окно
            $('#' + wndID).hide();
        }

        $('#window_overlay').hide();
        atomCore.windowActive = 'NaN';
    }

    //
    //
    //    .g8"""bgd   .g8""8q. `7MN.   `7MF'`7MM"""YMM `7MMF'`7MM"""Mq.  `7MMM.     ,MMF'
    //  .dP'     `M .dP'    `YM. MMN.    M    MM    `7   MM    MM   `MM.   MMMb    dPMM
    //  dM'       ` dM'      `MM M YMb   M    MM   d     MM    MM   ,M9    M YM   ,M MM
    //  MM          MM        MM M  `MN. M    MM""MM     MM    MMmmdM9     M  Mb  M' MM
    //  MM.         MM.      ,MP M   `MM.M    MM   Y     MM    MM  YM.     M  YM.P'  MM
    //  `Mb.     ,' `Mb.    ,dP' M     YMM    MM         MM    MM   `Mb.   M  `YM'   MM
    //    `"bmmmd'    `"bmmd"' .JML.    YM  .JMML.     .JMML..JMML. .JMM..JML. `'  .JMML.
    //
    //

    /**
     * Показать подтверждение (confirm)
     *
     * @param object settings Настройки
     *
     * @return null
     *
     * @version 0.6.5 07.11.2015
     * @author Дмитрий Щербаков <atomcms@ya.ru>
     */
    var showConfirm = function(settings) {
        atomCore.windowActive = 'atom_confirm';

        // Новая ширина окна, особенно актуально при трех кнопках и изменении текста на кнопках
        if ('newWidth' in settings) {
            $('#atom_confirm').css({
                'width': settings.newWidth,
            });
        } else {
            $('#atom_confirm').css({
                'width': '400px',
            });
        }

        // Заголовок окна
        if ('htmlTitle' in settings) {
            $('#atom_confirm .modal-title').html(settings.htmlTitle);
        } else {
            $('#atom_confirm .modal-title').html('Подтверждение действия');
        }

        // Сообщение
        if ('htmlBody' in settings) {
            $('#atom_confirm .modal-body').html(settings.htmlBody);
        } else {
            $('#atom_confirm .modal-body').html('Вы действительно хотите выполнить это действие?');
        }

        // Кнопка OK
        if ('butOK' in settings) {
            if ('title' in settings.butOK) {
                $('#atom_confirm_ok').text(settings.butOK.title);
            } else {
                $('#atom_confirm_ok').text('OK');
            }

            if ('class' in settings.butOK) {
                $('#atom_confirm_ok').removeClass().addClass('btn ' + settings.butOK.class);
            } else {
                $('#atom_confirm_ok').removeClass().addClass('btn btn-primary');
            }

            if ('func' in settings.butOK) {
                $('#atom_confirm_ok').unbind('click').bind('click', function() {
                    settings.butOK.func();
                    atomCore.windowClose('atom_confirm');
                });
            } else {
                $('#atom_confirm_ok').unbind('click').bind('click', function() {
                    atomCore.windowClose('atom_confirm');
                });
            }
        } else {
            $('#atom_confirm_ok').text('OK').removeClass().addClass('btn btn-primary').unbind('click').bind('click', function() {
                atomCore.windowClose('atom_confirm');
            });
        }

        // Кнопка Cancel
        if ('butCancel' in settings) {
            if ('title' in settings.butCancel) {
                $('#atom_confirm_cancel').text(settings.butCancel.title);
            } else {
                $('#atom_confirm_cancel').text('Отмена');
            }

            if ('class' in settings.butCancel) {
                $('#atom_confirm_cancel').removeClass().addClass('btn pull-left ' + settings.butCancel.class);
            } else {
                $('#atom_confirm_cancel').removeClass().addClass('btn pull-left btn-default');
            }

            if ('func' in settings.butCancel) {
                $('#atom_confirm_cancel').unbind('click').bind('click', function() {
                    settings.butCancel.func();
                    atomCore.windowClose('atom_confirm');
                });
            } else {
                $('#atom_confirm_cancel').unbind('click').bind('click', function() {
                    atomCore.windowClose('atom_confirm');
                });
            }
        } else {
            $('#atom_confirm_cancel').text('Отмена').removeClass().addClass('btn pull-left btn-default').unbind('click').bind('click', function() {
                atomCore.windowClose('atom_confirm');
            });
        }

        // Кнопка Третья
        if ('butThird' in settings) {
            if ('title' in settings.butThird) {
                $('#atom_confirm_third').text(settings.butThird.title);
            } else {
                $('#atom_confirm_third').text('Третья кнопка');
            }

            if ('class' in settings.butThird) {
                $('#atom_confirm_third').removeClass().addClass('btn ' + settings.butThird.class);
            } else {
                $('#atom_confirm_third').removeClass().addClass('btn btn-primary');
            }

            if ('func' in settings.butThird) {
                $('#atom_confirm_third').unbind('click').bind('click', function() {
                    settings.butThird.func();
                    atomCore.windowClose('atom_confirm');
                });
            } else {
                $('#atom_confirm_third').unbind('click').bind('click', function() {
                    atomCore.windowClose('atom_confirm');
                });
            }

            $('#atom_confirm_third').show();
        } else {
            $('#atom_confirm_third').hide();
        }

        $('#atom_confirm').css({
            'top' : Math.max(0, (($(window).height() - $('#atom_confirm').outerHeight()) / 2)) + 'px',
            'left': Math.max(0, (($(window).width() - $('#atom_confirm').outerWidth()) / 2)) + 'px'
        }).show();

        $('#window_overlay').show();
    }

    //
    //
    //  `7MMM.     ,MMF'`7MM"""YMM   .M"""bgd  .M"""bgd      db       .g8"""bgd `7MM"""YMM
    //    MMMb    dPMM    MM    `7  ,MI    "Y ,MI    "Y     ;MM:    .dP'     `M   MM    `7
    //    M YM   ,M MM    MM   d    `MMb.     `MMb.        ,V^MM.   dM'       `   MM   d
    //    M  Mb  M' MM    MMmmMM      `YMMNq.   `YMMNq.   ,M  `MM   MM            MMmmMM
    //    M  YM.P'  MM    MM   Y  , .     `MM .     `MM   AbmmmqMA  MM.    `7MMF' MM   Y  ,
    //    M  `YM'   MM    MM     ,M Mb     dM Mb     dM  A'     VML `Mb.     MM   MM     ,M
    //  .JML. `'  .JMML..JMMmmmmMMM P"Ybmmd"  P"Ybmmd" .AMA.   .AMMA. `"bmmmdPY .JMMmmmmMMM
    //
    //

    /**
     * Показать ошибку (alert)
     *
     * @param string  code    Код ошибки
     * @param string  message Текст сообщения
     * @param boolean modal   Показать сообщения для модального окна
     *
     * @return null
     *
     * @version 0.6.5 07.11.2015
     * @author Дмитрий Щербаков <atomcms@ya.ru>
     */
    var showMessage = function(code, message, modal) {
        // Если параметр не был передан, ему присваивается значение по умолчанию
        modal = modal || false;

        if (modal) {
            // Показать сообщение внутри модального окна
            $('#alerts-container .alert-' + code + ' span').html(message);
            $('#' + atomCore.windowActive + ' .modal-alert').remove();
            $('#' + atomCore.windowActive + ' .modal-body').prepend('<div class="modal-alert alert alert-' + code + '">' + $('#alerts-container .alert-' + code).html() + '</div>');
            $('#' + atomCore.windowActive + ' .modal-alert .close').hide();
        } else {
            // Показать обычное сообщение в интерфейсе
            $('#alerts-container .alert').hide();
            $('#alerts-container .alert-' + code + ' span').html(message);
            $('#alerts-container .alert-' + code).show();
        }

        $('html,body').animate({ scrollTop: 0 }, 'slow');
    }

    return {
        init       : init,
        windowOpen : windowOpen,
        windowClose: windowClose,
        showConfirm: showConfirm,
        showMessage: showMessage,
    };
})();

atomCore.init();
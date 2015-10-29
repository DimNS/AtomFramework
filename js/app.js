/**
 * Мое приложение
 *
 * @version 0.6 27.10.2015
 * @author Дмитрий Щербаков <atomcms@ya.ru>
 */

// Выполняем следующий код только после авторизации пользователя
if (pageLogin) {
    // Если это первый вход
    if (firstLogin) {
        // Создаем и настраиваем тур по системе
        var tour = new Tour({
            backdrop: true,
            template:
                '<div class="popover tour">' +
                    '<div class="arrow"></div>' +
                    '<h3 class="popover-title"></h3>' +
                    '<div class="popover-content"></div>' +
                    '<div class="popover-navigation">' +
                        '<button class="btn btn-primary" data-role="prev"><i class="fa fa-arrow-left"></i></button>' +
                        '<span data-role="separator">&nbsp;</span>' +
                        '<button class="btn btn-primary" data-role="next"><i class="fa fa-arrow-right"></i></button>' +
                        '<button class="btn btn-default" data-role="end">Завершить</button>' +
                    '</div>' +
                '</div>'
            ,
            steps: [{
                element: '#userinfo_drop_menu',
                title: 'Меню пользователя',
                content: 'Нажав сюда, можно попасть на страницу вашего профиля, а также выйти из системы',
                placement: 'bottom'
            }, {
                element: '.logo',
                title: 'Переход на главную',
                content: 'Для перехода с любой страницы обратно на главную, нажмите здесь',
                placement: 'bottom'
            }, {
                element: '.sidebar',
                title: 'Основное меню',
                content: 'Здесь располагается основное меню системы, для перехода в нужный раздел нажмите на него',
                placement: 'right'
            }, {
                element: '.sidebar-toggle',
                title: 'Скрыть основное меню',
                content: 'Не хотите видеть основное меню, нажмите эту кнопку и меню исчезнет, чтобы показать меню ещё раз нажмите эту кнопку',
                placement: 'bottom'
            }, {
                element: '#changelog_link',
                title: 'История изменений',
                content: 'Нажав сюда вы сможете посмотреть последние изменения в системе и узнать о новых возможностях',
                placement: 'top'
            }, {
                element: '#atom_confirm',
                title: 'И в завершении',
                content: 'А сейчас вы можете сменить пароль на более удобный для вас',
                placement: 'top',
                onShow: function() {
                    // Меняем номер версии на актуальный, чтобы больше не выдавать окно с содержимым CHANGELOG.md
                    $.ajax({
                        url: pathRoot + '/changelog/check'
                    });

                    // Показываем запрос на изменение пароля
                    showConfirm({
                        htmlTitle: 'Изменение пароля',
                        htmlBody : '<p>Прямо сейчас вы можете изменить пароль на более удобный для вас. Перейти к смене пароля?</p>',
                        butOK: {
                            func: function() {
                                location.replace(pathRoot + '/user/profile');
                            },
                        },
                    });
                }
            }]
        });

        // Инициализируем тур по системе
        tour.init();

        // Запускаем тур по системе
        tour.start();
    } else {
        // Если новая версия
        if (changelog) {
            windowOpen('changelog');
        }
    }
}
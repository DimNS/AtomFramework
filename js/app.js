/**
 * Мое приложение
 *
 * @version 0.7.0 14.11.2015
 * @author Дмитрий Щербаков <atomcms@ya.ru>
 */

var app = (function() {
    /**
     * Инициализация объекта
     *
     * @return null
     *
     * @version 0.7.0 14.11.2015
     * @author Дмитрий Щербаков <atomcms@ya.ru>
     */
    var init = function() {
        // Выполняем следующий код только после авторизации пользователя
        if (window.pageLogin) {
            // Для дебага
            window.firstLogin = true;

            // Если это первый вход
            if (window.firstLogin) {
                // Инициируем тур по системе
                var tourEnjoyHint = new EnjoyHint({});

                // Настраиваем тур по системе
                tourEnjoyHint.set([
                    {
                        'next .sidebar-toggle' : '<h2>Скрыть основное меню</h2><p>Не хотите видеть основное меню, нажмите эту кнопку и меню исчезнет.</p><p>Нажмите ещё раз эту кнопку и меню появится обратно.</p>',
                        'nextButton': {text: 'Далее'},
                        'margin': 0,
                    }, {
                        'next .sidebar' : '<h2>Основное меню</h2><p>Здесь располагается основное меню системы.</p>',
                        'nextButton': {text: 'Далее'},
                        'margin': 0,
                    }, {
                        'next #changelog_link' : '<h2>История изменений</h2><p>Нажав сюда, вы сможете посмотреть последние изменения в системе и узнать о новых возможностях.</p>',
                        'nextButton': {text: 'Далее'},
                    }, {
                        'click #atom_confirm' : '<h2>И в завершении</h2><p>А сейчас вы можете сменить пароль на более удобный для вас.</p>',
                        onBeforeStart: function() {
                            // Меняем номер версии на актуальный, чтобы больше не выдавать окно с содержимым CHANGELOG.md
                            $.ajax({
                                url: window.pathRoot + '/changelog/check'
                            });

                            // Показываем запрос на изменение пароля
                            atomCore.showConfirm({
                                htmlTitle: 'Изменение пароля',
                                htmlBody : '<p>Прямо сейчас вы можете изменить пароль на более удобный для вас. Перейти к смене пароля?</p>',
                                butOK: {
                                    func: function() {
                                        location.replace(window.pathRoot + '/user/profile');
                                    },
                                },
                            });
                        },
                    },
                ]);

                // Запускаем тур по системе
                tourEnjoyHint.run();
            } else {
                // Если новая версия
                if (window.changelog) {
                    atomCore.windowOpen('changelog');
                }
            }
        } else {
            if (window.location.hash == '#register') {
                atomCore.windowOpen('user_reg');
            }
        }
    };

    return {
        init: init,
    };
})();

app.init();
## Проект устарел.

# Atom Framework

Очень маленький php-фреймворк с минимальным функционалом для разработки небольших web-приложений.

## Первичная установка

1. Устанавливаем настройки подключения к БД в файле `/App/Configs/Database.php`
2. Настраиваем необходимые параметры под свой сервер в файле `/App/Configs/Config.php`
3. Просто запускаем маршрут http://domain.tld/migration/install/?token=YOUR_TOKEN_HERE, где **YOUR_TOKEN_HERE** можно поменять в файле `/App/Configs/Config.php` в соответствующей переменной, в качестве хранилища sql-запросов используется файл `/install.sql` *(он удаляется после использования, если в настройках включен production-режим: `'production' => true`)*
4. Новые маршруты добавляются в файл `/App/Configs/Routes.php` в виде элементов массива соответствующих контроллерам

## Применение изменений в БД

Если необходимо внести какие-либо изменения в БД, для этого создается файл `/change.sql`, а затем запускается маршрут http://domain.tld/migration/change/?token=YOUR_TOKEN_HERE, после использования файл также удаляется как и при первичной установке

## Если нужно выкинуть ошибку

1. Подключаем namespace `use AtomFramework\Utility\AtomException;`
2. В нужном вам месте кода пишем `throw new AtomException('текст ошибки для разработчика', 20);`
3. Данный код выкинет страницу с ошибкой. Где будет указан номер ошибки `20-1431074362`, а в тексте будет написано `Произошла ошибка в коде`. Вы же сможете по номеру ошибки найти ее полное описание в файле `error.log`
4. Код ошибки `10` используется для ошибок в sql-запросах. Если код ошибки не указать или указать отличный от `10` или `20`, тогда пользователю покажется ошибка `0-1431074362` с текстом `Неизвестная ошибка`

## Используемые библиотеки

#### PHP (устанавливаются через Composer)
- **Mobile_Detect** https://github.com/serbanghita/Mobile-Detect
- **Parsedown** https://github.com/erusev/parsedown
- **PHPMailer** https://github.com/PHPMailer/PHPMailer

#### JS
- **AJAX Upload** http://valums.com/ajax-upload *версия устарела, но она стабильная и бесплатная*
- **Bootstrap Tour v0.10.2** http://bootstraptour.com
- **Inline Form Validation Engine v2.6.2** https://github.com/posabsolute/jQuery-Validation-Engine
- **jQuery v2.1.3** http://docs.jquery.com/Downloading_jQuery
- **JSDeferred v0.4.0** http://github.com/cho45/jsdeferred
- **Select2 v4.0.0 beta3** https://select2.github.io

#### Фреймворк дизайна
- **AdminLTE** v2.0.4 https://almsaeedstudio.com/preview *основан на Bootstrap v3.3.2*

#### Шрифт иконок
- **Font Awesome v4.2.0** http://fortawesome.github.io/Font-Awesome

## Для тех, кто будет обновлять библиотеки

#### Обновление библиотеки Font Awesome
1. Скачайте новую версию отсюда http://fortawesome.github.io/Font-Awesome
2. Поместите с заменой новые файлы сюда `/css/font-awesome`
3. Откройте файл `/css/font-awesome/css/font-awesome.css`
4. Необходимо вынести `svg шрифт` в начало, чтобы появилось сглаживание в браузере `Chrome`

**ДО**
```
@font-face {
  font-family: 'FontAwesome';
  src: url('../fonts/fontawesome-webfont.eot?v=4.2.0');
  src: url('../fonts/fontawesome-webfont.eot?#iefix&v=4.2.0') format('embedded-opentype'), url('../fonts/fontawesome-webfont.woff?v=4.2.0') format('woff'), url('../fonts/fontawesome-webfont.ttf?v=4.2.0') format('truetype'), url('../fonts/fontawesome-webfont.svg?v=4.2.0#fontawesomeregular') format('svg');
  font-weight: normal;
  font-style: normal;
}
```

**ПОСЛЕ**
```
@font-face {
  font-family: 'FontAwesome';
  src: url('../fonts/fontawesome-webfont.eot?v=4.2.0');
  src: url('../fonts/fontawesome-webfont.svg?v=4.2.0#fontawesomeregular') format('svg'), url('../fonts/fontawesome-webfont.eot?#iefix&v=4.2.0') format('embedded-opentype'), url('../fonts/fontawesome-webfont.woff?v=4.2.0') format('woff'), url('../fonts/fontawesome-webfont.ttf?v=4.2.0') format('truetype');
  font-weight: normal;
  font-style: normal;
}
```

#### Отключаем файлы .map в js библиотеках
1. Для этого открываем новый файл с js-библиотекой, например `lightbox.min.js`.
2. И комментируем строку с указанием `map-файла` или удаляем ее.
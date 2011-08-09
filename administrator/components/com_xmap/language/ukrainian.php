<?php 
/** @package Xmap
 * $LastChangedDate: 2010-05-21 15:07
 * @author Guillermo Vargas, http://joomla.vargas.co.cr/
 * Project License: GNU/GPL http://www.gnu.org/copyleft/gpl.html
 * @translator Lopatynskiy Vyacheslav, darkfisk@gmail.com
*/

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

if( !defined( 'JOOMAP_LANG' )) {
	define('JOOMAP_LANG', 1 );

	// -- General ------------------------------------------------------------------
	define('_XMAP_CFG_OPTIONS',			'Налаштування');
	define('_XMAP_CFG_CSS_CLASSNAME',		'Ім‘я класу CSS');
	define('_XMAP_CFG_EXPAND_CATEGORIES',		'Розгортати категорії');
	define('_XMAP_CFG_EXPAND_SECTIONS',		'Розгортати розділи');
	define('_XMAP_CFG_SHOW_MENU_TITLES',		'Відображати заголовки меню');
	define('_XMAP_CFG_NUMBER_COLUMNS',		'Кількість колонок');
	define('_XMAP_EX_LINK',				'Помітити зовнішні посилання');
	define('_XMAP_CFG_CLICK_HERE', 			'Натисніть тут');
	define('_XMAP_CFG_GOOGLE_MAP',			'Карта сайту Google');
	define('_XMAP_EXCLUDE_MENU',			'Виключити пункти меню (по ID)');
	define('_XMAP_TAB_DISPLAY',			'Показати');
	define('_XMAP_TAB_MENUS',			'Меню');
	define('_XMAP_CFG_WRITEABLE',			'Доступний на запис');
	define('_XMAP_CFG_UNWRITEABLE',			'Недоступний для запису');
	define('_XMAP_MSG_MAKE_UNWRITEABLE',		'Зробити недоступним для запису після збереження');
	define('_XMAP_MSG_OVERRIDE_WRITE_PROTECTION',	'Подолати заборону на запис при збереженні');
	define('_XMAP_GOOGLE_LINK',			'Посилання для Google');
	define('_XMAP_CFG_INCLUDE_LINK',		'Відображати посилання на сайт розробника');

	// -- Tips ---------------------------------------------------------------------
	define('_XMAP_EXCLUDE_MENU_TIP',	'Вкажіть ідентифікатори (ID) меню, котрі Ви хотіли б виключити з карти сайту.<br /><strong>Примітка:</strong><br />Розділяйте ідентифікатори (ID) комами!');

	// -- Menus --------------------------------------------------------------------
	define('_XMAP_CFG_SET_ORDER',		'Встановити порядок відображення меню');
	define('_XMAP_CFG_MENU_SHOW',		'Відобразити');
	define('_XMAP_CFG_MENU_REORDER',	'Впорядкувати');
	define('_XMAP_CFG_MENU_ORDER',		'Порядок');
	define('_XMAP_CFG_MENU_NAME',		'Ім‘я меню');
	define('_XMAP_CFG_DISABLE',		'Натисніть, щоб вимкнути');
	define('_XMAP_CFG_ENABLE',		'Натисніть, щоб ввімкнути');
	define('_XMAP_SHOW',			'Відобразити');
	define('_XMAP_NO_SHOW',			'Не відображати');

	// -- Toolbar ------------------------------------------------------------------
	define('_XMAP_TOOLBAR_SAVE', 			'Зберегти');
	define('_XMAP_TOOLBAR_CANCEL', 		'Відхилити');

	// -- Errors -------------------------------------------------------------------
	define('_XMAP_ERR_NO_LANG',		'[ %s ] мовний файл не знайдено, по замовчуванні завантажено англійський<br />');
	define('_XMAP_ERR_CONF_SAVE',		'Помилка: Неможливо зберегти налаштування.');
	define('_XMAP_ERR_NO_CREATE',		'Помилка: Неможливо створити таблицю налаштувань');
	define('_XMAP_ERR_NO_DEFAULT_SET',	'Помилка: Неможливо вставити (в таблицю БД) параметри за замовчуванням');
	define('_XMAP_ERR_NO_PREV_BU',		'Попередження: Неможливо очистити попередню резервну копію');
	define('_XMAP_ERR_NO_BACKUP',		'Помилка: Неможливо створити резервну копію');
	define('_XMAP_ERR_NO_DROP_DB',		'Помилка: Неможливо видалити таблицю налаштувань');
	define('_XMAP_ERR_NO_SETTINGS',		'Помилка: Неможливо завантажити настроювання з БД: <a href="%s">Створити таблицю налаштувань</a>');

	// -- Config -------------------------------------------------------------------
	define('_XMAP_MSG_SET_RESTORED',	'Налаштування відновлені');
	define('_XMAP_MSG_SET_BACKEDUP',	'Налаштування збережено');
	define('_XMAP_MSG_SET_DB_CREATED',	'Створена таблиця налаштувань');
	define('_XMAP_MSG_SET_DEF_INSERT',	'Встановлені налаштування за замовчуванням');
	define('_XMAP_MSG_SET_DB_DROPPED',	'Таблиці карти сайту збережено!');
	
	// -- CSS ----------------------------------------------------------------------
	define('_XMAP_CSS',					'Стилі CSS карти сайту');
	define('_XMAP_CSS_EDIT',				'Редагувати шаблон'); // Edit template
	
	// -- Sitemap (Frontend) -------------------------------------------------------
	define('_XMAP_SHOW_AS_EXTERN_ALT',	'Відкривати посилання в новому вікні');
	
	// -- Added for Xmap 
	define('_XMAP_CFG_MENU_SHOW_HTML',		'Відображені на сайті');
	define('_XMAP_CFG_MENU_SHOW_XML',		'Відображати у карті сайту на XML');
	define('_XMAP_CFG_MENU_PRIORITY',		'Пріоритет');
	define('_XMAP_CFG_MENU_CHANGEFREQ',		'Змінити частоту');
	define('_XMAP_CFG_CHANGEFREQ_ALWAYS',		'Завжди');
	define('_XMAP_CFG_CHANGEFREQ_HOURLY',		'Щогодини');
	define('_XMAP_CFG_CHANGEFREQ_DAILY',		'Щоденно');
	define('_XMAP_CFG_CHANGEFREQ_WEEKLY',		'Щонеділі');
	define('_XMAP_CFG_CHANGEFREQ_MONTHLY',		'Щомісяця');
	define('_XMAP_CFG_CHANGEFREQ_YEARLY',		'Щороку');
	define('_XMAP_CFG_CHANGEFREQ_NEVER',		'Ніколи');

	define('_XMAP_TIT_SETTINGS_OF',			'Налаштування для %s');
	define('_XMAP_TAB_SITEMAPS',			'Карти сайту');
	define('_XMAP_MSG_NO_SITEMAPS',			'Карта сайту ще не створена');
	define('_XMAP_MSG_NO_SITEMAP',			'Ця карта сайту недоступна');
	define('_XMAP_MSG_LOADING_SETTINGS',		'Загрузка налаштувань...');
	define('_XMAP_MSG_ERROR_LOADING_SITEMAP',	'Помилка. Неможливо завантажити карту сайту');
	define('_XMAP_MSG_ERROR_SAVE_PROPERTY',		'Помилка. Неможливо зберегти властивість карти сайту.');
	define('_XMAP_MSG_ERROR_CLEAN_CACHE',		'Помилка. Неможливо скинути кеш карти сайту');
	define('_XMAP_ERROR_DELETE_DEFAULT',		'Неможливо видалити мапу сайту за замовчуванням!');
	define('_XMAP_MSG_CACHE_CLEANED',		'Кеш скинутий!');
    define('_XMAP_CHARSET',				'UTF-8');
	define('_XMAP_SITEMAP_ID',			'Ідентифікатор (ID) карти сайту');
	define('_XMAP_ADD_SITEMAP',			'Додати карту сайту');
	define('_XMAP_NAME_NEW_SITEMAP',		'Нова карта сайту');
	define('_XMAP_DELETE_SITEMAP',			'Видалити');
	define('_XMAP_SETTINGS_SITEMAP',		'Налаштування');
	define('_XMAP_COPY_SITEMAP',			'Копіювати');
	define('_XMAP_SITEMAP_SET_DEFAULT',		'Встановити значення за замовчуванням');
	define('_XMAP_EDIT_MENU',			'Змінити');
	define('_XMAP_DELETE_MENU',			'Видалити');
	define('_XMAP_CLEAR_CACHE',			'Скинути кеш');
	define('_XMAP_MOVEUP_MENU',		'Вверх');
	define('_XMAP_MOVEDOWN_MENU',		'Вниз');
	define('_XMAP_ADD_MENU',		'Додати меню');
	define('_XMAP_COPY_OF',			'Копія %s');
	define('_XMAP_INFO_LAST_VISIT',		'Останнє відвідування');
	define('_XMAP_INFO_COUNT_VIEWS',	'Кількість відвідувань');
	define('_XMAP_INFO_TOTAL_LINKS',	'Кількість посилань');
	define('_XMAP_CFG_URLS',		'Посилання (URL) на карту сайту');
	define('_XMAP_XML_LINK_TIP',		'Скопіюйте це посилання і повідомте Google і Yahoo');
	define('_XMAP_HTML_LINK_TIP',		'Це посилання на карту сайту. Ви можете використовувати його для створення пунктів меню.');
	define('_XMAP_CFG_XML_MAP',		'Карта сайта на XML ');
	define('_XMAP_CFG_HTML_MAP',		'Карта сайту в HTML');
	define('_XMAP_XML_LINK',		'Посилання для Google');
	define('_XMAP_CFG_XML_MAP_TIP',		'XML-файл для пошукових машин створений');
	define('_XMAP_ADD',			'Зберегти');
	define('_XMAP_CANCEL',			'Відміна');
	define('_XMAP_LOADING',			'Завантаження...');
	define('_XMAP_CACHE',			'Кешування');
	define('_XMAP_USE_CACHE',		'Використовувати кешування');
	define('_XMAP_CACHE_LIFE_TIME',		'Час життя кешу');
	define('_XMAP_NEVER_VISITED',		'Ніколи');
	
	// New on Xmap 1.1 beta 1
	define('_XMAP_PLUGINS',			'Розширення (Plugins)');	
	define( '_XMAP_INSTALL_3PD_WARN',	'Попередження: Встановлення сторонніх розширень може вплинути на безпеку вашого сервера.' );
	define('_XMAP_INSTALL_NEW_PLUGIN',	'Встановити нові розширення');
	define('_XMAP_UNKNOWN_AUTHOR',		'Невідомий автор');
	define('_XMAP_PLUGIN_VERSION',		'Версія %s');
	define('_XMAP_TAB_INSTALL_PLUGIN',	'Встановити');
	define('_XMAP_TAB_EXTENSIONS',		'Розширення (Extensions)');
	define('_XMAP_TAB_INSTALLED_EXTENSIONS','Встановлення розширення (Extensions)');
	define('_XMAP_NO_PLUGINS_INSTALLED',	'Додаткові розширення (plugins) не встановлені');
	define('_XMAP_AUTHOR',			'Автор');
	define('_XMAP_CONFIRM_DELETE_SITEMAP',	'Ви дійсно бажаєте видалити цю карту сайту?');
	define('_XMAP_CONFIRM_UNINSTALL_PLUGIN','Ви дійсно бажаєте видалити це розширення (plugin)?');
	define('_XMAP_UNINSTALL',		'Видалити');
	define('_XMAP_EXT_PUBLISHED',		'Опубліковано');
	define('_XMAP_EXT_UNPUBLISHED',		'Приховано');
	define('_XMAP_PLUGIN_OPTIONS',		'Налаштування');
	define('_XMAP_EXT_INSTALLED_MSG',	'Розширення (extension) успішно встановлено, перевірте його налаштування, потім опублікуйте це розширення.');
	define('_XMAP_CONTINUE','Продовжити');
	define('_XMAP_MSG_EXCLUDE_CSS_SITEMAP',	'Не підєднувати CSS в карті сайту');
	define('_XMAP_MSG_EXCLUDE_XSL_SITEMAP',	'Використовувати класичне відображення карти сайту на XML');

	// New on Xmap 1.1
	define('_XMAP_MSG_SELECT_FOLDER',	'Оберіть теку');
	define('_XMAP_UPLOAD_PKG_FILE',		'Завантажити файл пакету');
	define('_XMAP_UPLOAD_AND_INSTALL',	'Завантажити файл &amp; Встановити');
	define('_XMAP_INSTALL_F_DIRECTORY',	'Встановити з теки');
	define('_XMAP_INSTALL_DIRECTORY',	'Тека встановлення');
	define('_XMAP_INSTALL',			'Встановити');
	define('_XMAP_WRITEABLE',		'Не доступний для запису');
	define('_XMAP_UNWRITEABLE',		'Недоступний на запис');

	// New on Xmap 1.2
	define('_XMAP_COMPRESSION',	'Стиснення');
	define('_XMAP_USE_COMPRESSION',	'Стиснути XML карту сайту, для збільшення пропускної здатності.');

	// New on Xmap 1.2.1
	define('_XMAP_CFG_NEWS_MAP',	'Новини Карти сайту');
	define('_XMAP_NEWS_LINK_TIP',	'Це новини Карти сайту.');

	// New on Xmap 1.2.2
	define('_XMAP_CFG_MENU_MODULE',		'Модуль');
	define('_XMAP_CFG_MENU_MODULE_TIP',	'Вкажіть модуль котрий Ви використовуєте, для відображення меню на своєму сайті (За замовчуванням: mod_mainmenu).');

	// New on Xmap 1.2.3
	define('_XMAP_TEXT',		'Текст посилання');
	define('_XMAP_TITLE',		'Заголовок посилання');
	define('_XMAP_LINK',		'URL посилання');
	define('_XMAP_CSS_STYLE',	'CSS стиль');
	define('_XMAP_CSS_CLASS',	'CSS клас');
	define('_XMAP_INVALID_SITEMAP',	'Невірна Карта сайту');
	define('_XMAP_OK', 		'Так');

        // New on Xmap 1.2.10
        define('_XMAP_CFG_IMAGES_MAP','Images Sitemap');


        // New on Xmap 1.2.13
        define('_XMAP_CACHE_TIP','The maximun number of time in minutes for a cache file to be stored before it is refreshed');
        define('_XMAP_MINUTES','Minutes');
}

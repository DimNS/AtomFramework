<?php
/**
 * Main
 *
 * Модель для работы главной страницы
 *
 * @version 0.1 27.04.2015
 * @author Дмитрий Щербаков <atomcms@ya.ru>
 */

namespace AtomFramework\Models;

class Main {
	//
	// 	  ,,                    ,,
	// 	  db                  `7MM
	// 	                        MM
	// 	`7MM  `7MMpMMMb.   ,M""bMM  .gP"Ya `7M'   `MF'
	// 	  MM    MM    MM ,AP    MM ,M'   Yb  `VA ,V'
	// 	  MM    MM    MM 8MI    MM 8M""""""    XMX
	// 	  MM    MM    MM `Mb    MM YM.    ,  ,V' VA.
	// 	.JMML..JMML  JMML.`Wbmd"MML.`Mbmmd'.AM.   .MA.
	//
	//

	/**
	 * Получение данных для главной страницы
	 *
	 * @return array
	 *
	 * @version 0.1 27.04.2015
	 * @author Дмитрий Щербаков <atomcms@ya.ru>
	 */
	static function index() {
		return [
			'code' => 'success',
			'text' => 'Данные успешно получены',
		];
	}
}
?>
/**
 * @package		Gantry Template Framework - RocketTheme
 * @version		1.5.4 November 16, 2010
 * @author		RocketTheme http://www.rockettheme.com
 * @copyright 	Copyright (C) 2007 - 2010 RocketTheme, LLC
 * @license		http://www.rockettheme.com/legal/license.php RocketTheme Proprietary Use License
 */

window.addEvent('domready', function() {
	var browser = (typeof Browser != 'undefined') ? (Browser.Engine.trident4 || Browser.Engine.trident5) : (window.ie6 || window.ie7);
	if (browser) {
		var body = $$('#rt-main .rt-main-inner')[0].getChildren().slice(0, -1);
		if (body.length > 1) {
			body.each(function(b, i) {
				if (!i) b.addClass('rt-ie-zindex-body');
				else b.addClass('rt-ie-zindex');
			});
		}
	}
});
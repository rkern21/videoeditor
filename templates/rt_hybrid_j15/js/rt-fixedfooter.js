/**
 * @package		Gantry Template Framework - RocketTheme
 * @version		1.5.4 November 16, 2010
 * @author		RocketTheme http://www.rockettheme.com
 * @copyright 	Copyright (C) 2007 - 2010 RocketTheme, LLC
 * @license		http://www.rockettheme.com/legal/license.php RocketTheme Proprietary Use License
 */

window.addEvent('domready', function() {
	var moo1 = (MooTools.version == '1.12' || MooTools.version == '1.11');
	var footer = (moo1) ? $('rt-footerbar') : document.id('rt-footerbar');

	if (footer && !window.ie6) {
		var height = footer.getCoordinates().height;
		var lastdiv = new Element('div', {
			'styles': 'height:' + height + 'px'
		});

		if (moo1) lastdiv.setHTML('&nbsp;');
		else lastdiv.set('html', '&nbsp;');

		lastdiv.inject(footer, 'before')
	}
});
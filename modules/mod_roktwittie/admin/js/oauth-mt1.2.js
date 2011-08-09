/**
 * RokTwittie Module
 *
 * @package RocketTheme
 * @subpackage roktwittie
 * @version   2.0 October 1, 2010
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2010 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

var OAuthToggle = {
	init: function() {
		var no = $('paramsuse_oauth0'), yes = $('paramsuse_oauth1');
		
		OAuthToggle.rows = no.getParent('tbody').getElements('#paramsconsumer_key, #paramsconsumer_secret, #signin-key').getParent('tr');
		[yes, no].each(function(radio, i) {
			radio.addEvent('click', function() {
				if (!i && radio.checked) OAuthToggle.show();
				if (i && radio.checked) OAuthToggle.hide();	
			});
		});
		
		if (no.checked) no.fireEvent('click');
		if (yes.checked) yes.fireEvent('click');
	},
	
	show: function() {
		OAuthToggle.rows.setStyle('display', 'table-row');
	},
	
	hide: function() {
		OAuthToggle.rows.setStyle('display', 'none');		
	}
};

window.addEvent('domready', OAuthToggle.init);
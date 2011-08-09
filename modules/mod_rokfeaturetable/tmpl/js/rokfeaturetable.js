/**
 * @version		1.1 September 13, 2010
 * @author		RocketTheme http://www.rockettheme.com
 * @copyright 	Copyright (C) 2007 - 2010 RocketTheme, LLC
 * @license		http://www.rockettheme.com/legal/license.php RocketTheme Proprietary Use License
 */

window.addEvent('domready', function() {
	var tables = $$('.featuretable, .rokfeaturetable'), current = {}, cls;
	var cols = tables.getElements('.featuretable-col');
	
	tables.each(function(table, j) {
		var columns = cols[j];
		
		columns.each(function(col, i) {
			if (col.hasClass('highlight')) {current[j] = i; cls = 'highlight'; }
			else if (col.hasClass('ft-highlight')) {current[j] = i; cls = 'ft-highlight'; }
			col.addEvents({
				'mouseenter': function() { columns.removeClass(cls);this.addClass(cls); },
				'mouseleave': function() { this.removeClass(cls); }
			});
		});
		if ($chk(current[j])) {
			table.addEvent('mouseleave', function() {
				table.getElements('.featuretable-col').removeClass(cls);
				table.getElements('.featuretable-col')[current[j]].addClass(cls);
			});
		}
	});
});
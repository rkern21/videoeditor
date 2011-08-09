/**
 * switcher.js - RokNavMenu params util
 * 
 * @version		1.0
 * 
 * @author		Djamil Legato <djamil@rockettheme.com>
 * @copyright	Andy Miller @ Rockettheme, LLC
 *
 */

var NavMenuSwitcher = new Class({
	version: 1.0,
	initialize: function(list){
		this.list = document.id(list);
		if (!this.list) return this;
		
		this.panes = new Hash({});
		this.options = this.list.getChildren().map(function(opt) {
			
			var value = opt.get('value').cleaned();
			this.panes.set(value, $$('.' + value));
			
			return value;
			
		}.bind(this));
		
		this.hide().show(this.list.get('value').cleaned());
		this.list.addEvent('change', this.change.bind(this));

	},
	
	change: function(e){
		var value = this.list.get('value').cleaned();
		this.hide().show(value);
	},
	
	hide: function(what){
		if (!what) this.panes.each(function(pane) {pane.setStyle('display', 'none');});
		else this.panes.get(what).setStyle('display', 'none');
		
		return this;
	},
	
	show: function(what){
		if (!what) this.panes.each(function(pane) {pane.setStyle('display', 'block');});
		else this.panes.get(what).setStyle('display', 'block');
		
		return this;
	}
});

String.implement({
	cleaned: function() {
		return this.split("/").pop();
	}
});
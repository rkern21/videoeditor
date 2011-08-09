/**
 * @package		Gantry Template Framework - RocketTheme
 * @version		3.1.10 March 5, 2011
 * @author		RocketTheme http://www.rockettheme.com
 * @copyright 	Copyright (C) 2007 - 2011 RocketTheme, LLC
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

(function(){
	
var Toggle = this.Toggle = new Class({
	
	Implements: [Options, Events],
	
	options: {
		radius: 3,
		duration: 250,
		transition: 'sine:in:out',
		'classname': '.toggle-input'
	},
	
	initialize: function(options){
		this.setOptions(options);
		
		this.elements = document.getElements(this.options.classname);
		
		var bounds = {
			attach: this.attach.bind(this),
			detach: this.detach.bind(this),
			set: this.set.bind(this)
		};
		
		this.width = 50;
		this.height = 23;
		this.min = this.options.radius;
		this.max = 47;
		this.half = 25;
		
		this.elements.each(function(toggle){
			var container = toggle.getParent('.toggle-container'),
				details = {
					container: {
						toggle: toggle,
						bound: this.mouseover.bind(this, container)
					},
					toggle: {
						input: toggle,
						container: container,
						checked: toggle.checked
					}
				};
			
			toggle.store('details', details.toggle);
			container.store('details', details.container);
			
			toggle.addEvents(bounds);
			container.addEvent('mouseenter', details.container.bound);
		}, this);
		
	},
	
	attach: function(details){
		this.check(details.input);

		details.container.removeClass('disabled');
		details.dragButton.attach();
		details.dragSwitch.attach();
	},
	
	detach: function(details){
		this.check(details.input);

		details.container.addClass('disabled');
		details.dragButton.detach();
		details.dragSwitch.detach();
	},
	
	mouseover: function(container){
		var details = container.retrieve('details'),
			toggle = details.toggle.retrieve('details');

		container.removeEvent('mouseenter', details.bound);
		
		toggle.button = container.getElement('.toggle-button');
		toggle.sides = container.getElement('.toggle-sides');
		toggle.switcher = container.getElement('.toggle-switch');
		
		details.toggle.store('details',	toggle);
		
		this.attachEvents(details.toggle);
	},
	
	attachEvents: function(toggle){
		var details = toggle.retrieve('details'),
			steps = this.options.duration / this.width,
			fx = new Fx({duration: this.options.duration, transition: this.options.transition, 'link': 'cancel'}),
			self = this;
		
		var button = details.button,
			switcher = details.switcher,
			sides = details.sides;
		
		details.steps = steps;
		toggle.store('animating', false);
		
		fx.set = function(now){
			this.update(button, switcher, sides, now);
		}.bind(this);
		
		var dragButton = new Touch(button),
			dragSwitch = new Touch(switcher),
			cancel = function(){ if (!toggle.retrieve('animating')) this.toggle(details); }.bind(this);
		
		dragButton.addEvents({
			start: function(x){
				toggle.focus();
				details.position = button.offsetLeft;
			},
			
			move: function(x){
				self.update(button, switcher, sides, details.position + x);
			},
			
			end: function(x){
				var left = button.offsetLeft;
							
				var status = (left > self.half) ? true : false;
				self.change(details, status);
			},
			
			cancel: cancel
		});
		
		dragSwitch.addEvents({
			cancel: cancel,
			start: function(){ toggle.focus(); }
		});		
				
		details.fx = fx;
		details.dragButton = dragButton;
		details.dragSwitch = dragSwitch;
		toggle.store('details', details);
	},
	
	check: function(toggle){
		if (!toggle) return;
		
		var details = toggle.retrieve('details');
		
		if (!details.dragButton) this.mouseover(details.container);
	},
	
	toggle: function(details) {
		this.check(details.input);
		
		this.change(details, (details.button.offsetLeft > this.half) ? false : true);
	},
	
	change: function(details, state, noAnim) {
		this.check(details.input);
		
		if (typeof state == 'string') state = state.toInt();
		if (details.input.retrieve('animating')) return this;
		
		if (noAnim) this.set(details, state);
		else this.animate(details, state);
		
		details.input.checked = state;
		details.input.value = (!state) ? 0 : 1;
		details.checked = state;
		details.input.store('details', details);
		
		this.onChange(details, state);
		
		details.input.fireEvent('onChange', state);
		this.fireEvent('onChange', state);
		
		return this;
	},
	
	onChange: function(details, state){
		var value = (state) ? 1 : 0;
		
		if (Gantry.MenuItemHead) {
			var cache = Gantry.MenuItemHead.Cache[Gantry.Selection];
			if (!cache) cache = new Hash({});

			var id = details.input.id.replace(GantryPrefix, '');
			cache.set(id, details.input.value.toString()); 
		}
		
		details.input.getPrevious().set('value', value);
		
		if (details.container.getParent().getParent() != details.container.getParent('.wrapper').getFirst()) return;

		var nexts = details.container.getParent('.wrapper').getChildren();

		if (nexts.length) {
			nexts.each(function(chain) {
				var cls = chain.className.split(' '), type = '';
				cls.each(function(val) {
					if (val.contains('chain-')) type = val.replace('chain-', '');
				});

				if (['position', 'groupedselection', 'showmax', 'animation', 'dateformats', 'menuids', 'selectbox', 'category', 'section'].contains(type)) {
					var select = chain.getElement('select');
					if (document.id(select)) {
						if (document.id(select).fireEvent('detach')) {
							if (value) select.fireEvent('attach');
							else select.fireEvent('detach');
						}
					}
				}
				if (['text'].contains(type)) {
					var text = chain.getElement('input[type=text]');
					if (document.id(text).fireEvent('detach')) {
						if (value) text.fireEvent('attach');
						else text.fireEvent('detach');
					}
				}
				if (['toggle'].contains(type) && chain != details.container.getParent('.wrapper').getFirst()) {
					var checkbox = chain.getElement('input[type=checkbox]');
					if (checkbox) {
						(function() {
							var details = checkbox.retrieve('details');
							if (value) checkbox.fireEvent('attach', details);
							else checkbox.fireEvent('detach', details);
						}).delay(10);
					}
				}
			}, this);
		}
	},
	
	set: function(details, state) {
		this.check(details.input);

		if (typeof state == 'string') state = state.toInt();
		this.update(details.button, details.switcher, details.sides, state ? this.width : 0);
	},
	
	animate: function(details, state) {
		this.check(details.input);
		
		details.input.store('animating', true);
		var from = details.button.offsetLeft, 
			to = (state) ? this.width : 0,
			button = details.button,
			fx = details.fx,
			dragButton = details.dragButton;
		
		fx.options.duration = Math.abs(from - to) * details.steps;
		
		dragButton.detach();
		
		fx.cancel().start(from, to).chain(function() {
			dragButton.attach();
			details.input.store('animating', false);
		}.bind(this));
	},
	
	update: function(button, switcher, sides, x) {
		if (x < 3) x = 0;
		else if (x > 47) x = 50;

		switcher.style.left = x - 50 + 'px';
		button.style.left = x + 'px';
		this.updateSides(sides, x);
	},
	
	updateSides: function(sides, x) {
		var pos = '0 0',
			height = -this.height;
		
		var coords = {
			'off': '0 ' + (height * 3),
			'on': '0 ' + (height * 2)
		};

		if (x == 0) pos = coords.off + 'px';
		else if (x == this.width) pos = coords.on + 'px';
		else pos = '0 ' + (height * 4) + 'px';

		sides.style.backgroundPosition = pos;
	}
	
});

})();
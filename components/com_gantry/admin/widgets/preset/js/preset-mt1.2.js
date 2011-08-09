/**
 * @package		Gantry Template Framework - RocketTheme
 * @version		3.1.10 March 5, 2011
 * @author		RocketTheme http://www.rockettheme.com
 * @copyright 	Copyright (C) 2007 - 2011 RocketTheme, LLC
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

var PresetDropdown = {
	list: {},
	init: function(cls) {
		PresetDropdown.list[cls] = document.id('params' + cls);

		var objs = selectboxes.getObjects(PresetDropdown.list[cls].getPrevious());
		objs.real.addEvent('change', PresetDropdown.select.bind(PresetDropdown, cls));
		
		PresetsBadges.init(cls);
	},
	
	newItem: function(cls, key, value) {
		if (!PresetDropdown.list[cls] && $$('.' + cls).length) return Scroller.addBlock(cls, key, value);
		
		var li = new Element('li').set('text', value);
		var option = new Element('option', {value: key}).set('text', value);
		var objs = selectboxes.getObjects(PresetDropdown.list[cls].getPrevious());
		
		var dup = null;
		
		objs.real.getChildren().each(function(child, i) {
			if (child.value == key) dup = i;
		});

		if (dup == null) {
			option.inject(PresetDropdown.list[cls]);
			li.inject(PresetDropdown.list[cls].getPrevious().getLast().getElement('ul'));
			PresetDropdown.attach(cls);
		} else {
			var real_option = objs.real.getChildren()[dup], real_list = PresetDropdown.list[cls].getPrevious().getLast().getElement('ul').getChildren()[dup];

			real_option.replaceWith(option);
			real_list.replaceWith(li);
			
			PresetDropdown.attach(cls, dup);
		}
		
		return true;
	},
	
	attach: function(cls, index) {
		var objs = selectboxes.getObjects(PresetDropdown.list[cls].getPrevious()), self = this;

		if (index == null) index = objs.list.length - 1;
		var el = objs.list[index];

		el.addEvents({
			'mouseenter': function() {
				objs.list.removeClass('hover');
				this.addClass('hover');
			},
			'mouseleave': function() {
				this.removeClass('hover');
			},
			'click': function() {
				objs.list.removeClass('active');
				this.addClass('active');
				this.fireEvent('select', [objs, index]);
			},
			select: selectboxes.select.bind(selectboxes, [objs, index])
		});
		selectboxes.updateSizes(PresetDropdown.list[cls].getPrevious());
		el.fireEvent('select');
	},
	
	select: function(cls) {
		var preset = Presets[cls].get(PresetDropdown.list[cls].getPrevious().getElement('.selected span').get('text'));
		
		var master = document.id('master-items');
		if (master) master = master.hasClass('active');
		
		$H(preset).each(function(value, key) {
			var el = document.id('params' + key);

			var type = el.get('tag');
			
			if (master && Gantry.MenuItemHead) Gantry.MenuItemHead.getCheckbox(key).fireEvent('switchon');
			
			switch(type) {
				case 'select': 
					var values = el.getElements('option').getProperty('value');
					var objs = selectboxes.getObjects(el.getParent());
					selectboxes.select(objs, values.indexOf(value));
					
					break;
					
				case 'input':
					var cls = el.getProperty('class');
					el.setProperty('value', value);
					
					if (cls.contains('picker-input')) {
						el.fireEvent('keyup');
					} else if (cls.contains('slider')) {
						var slider = window['slider' + key];
						slider.set(slider.list.indexOf(value));
					} else if (cls.contains('toggle')) {
						var n = key.replace("-", '');
						window['toggle' + n].set(value.toInt());
						window['toggle' + n].fireEvent('onChange', value.toInt());
					}
					
					break;
					
			}
			
		});
	}
};

var Scroller = {
	init: function(cls) {
		Scroller.wrapper = $$('.' + cls + ' .scroller .wrapper')[0];
		Scroller.bar = $$('.' + cls + ' .bar')[0];
		
		if (!Scroller.wrapper || !Scroller.bar) return;
		
		Scroller.childrens = Scroller.wrapper.getChildren();
		
		var size = Scroller.wrapper.getParent().getSize();
		var wrapSize = Scroller.wrapper.getSize();
		
		Scroller.barWrapper = new Element('div', {
			'styles': {
				'position': 'absolute', 
				'left': 20, 
				'bottom': 23,
				'width': Scroller.bar.getSize().x,
				'height': Scroller.bar.getSize().y
			}
		}).inject(Scroller.bar, 'before');
		
		Scroller.getBarSize();
		Scroller.bar.inject(Scroller.barWrapper).setStyles({'bottom': 0, 'left': 0});

		Scroller.children(cls);
		
		var deleters = $$('.delete-preset');
		
		deleters.each(function(deleter) {
			deleter.addEvent('click', function(e) {
				new Event(e).stop();
				Scroller.deleter(this, cls);
			});
		});
		
		PresetsBadges.init(cls);
		
		if (Scroller.size > size.x) return;
		
		Scroller.bar.setStyle('width', Scroller.size);
		
		Scroller.drag(Scroller.wrapper, Scroller.bar);
	},

	deleter: function(item, cls) {
		var key = item.id.replace('keydelete-', '');
		new Request.HTML({
			url: AdminURI + '?option=com_admin&tmpl=gantry-ajax-admin',
			onSuccess: function(r) {Scroller.deleteAction(r, item, cls, key);}
		}).post({
			'model': 'presets-saver',
			'action': 'delete',
			'template': Gantry.PresetsSaver.Template,
			'preset-title': cls,
			'preset-key': key
		});
	},
	
	deleteAction: function(r, item, cls, key) {
		if (PresetsKeys[cls].contains(key)) {
			item.dispose();
		} else {
			var block = item.getParent();
			Scroller.childrens.erase(block);
			var blockSize = block.getSize().x;
			block.empty().dispose();

			var last = Scroller.childrens.getLast().addClass('last');
			var first = Scroller.childrens[0].addClass('first');

			var wrapperSize = Scroller.wrapper.getStyle('width').toInt();
			Scroller.wrapper.setStyle('width', wrapperSize - blockSize);
			Scroller.bar.setStyle('width', Scroller.getBarSize());
			
			Scroller.dragger.fireEvent('onDrag');
		}
		
		if (typeof CustomPresets != 'undefined' && CustomPresets[key]) delete CustomPresets[key];
	},
	
	getBarSize: function() {
		var size = Scroller.wrapper.getParent().getSize();
		var wrapSize = Scroller.wrapper.getSize();
		Scroller.size = size.x * Scroller.barWrapper.getStyle('width').toInt() / wrapSize.x;

		return Scroller.size;
	},
	
	addBlock: function(cls, key, value) {
		var preset = Presets[cls].get(value);
		if (!preset) {
			var last = Scroller.childrens[Scroller.childrens.length - 1], length = Scroller.childrens.length;
			var newBlock = last.clone();
			newBlock.inject(last, 'after').addClass('last').className = "";
			newBlock.className = 'preset' + (length + 1) + ' block last';
			newBlock.getElement('span').set('html', value);
			last.removeClass('last');
			
			var bg = newBlock.getFirst().getStyle('background-image');
			var tmp = bg.split("/");

			var img = tmp[tmp.length - 1];
			var end = key + '.png")';
			var fin = tmp.join("/").replace(img, end);

			newBlock.getFirst().setStyle('background-image', '');
			newBlock.getFirst().setStyle('background-image', fin);
			
			var wrapperSize = Scroller.wrapper.getStyle('width').toInt();
			var blockSize = newBlock.getSize().x;
			Scroller.wrapper.setStyle('width', wrapperSize + 119);
			
			Scroller.bar.setStyle('width', Scroller.getBarSize());
			Scroller.childrens.push(newBlock);
			
			Scroller.child(cls, newBlock);
			
			var x = new Element('div', {id: 'keydelete-' + key, 'class': 'delete-preset'}).set('html', '<span>X</span>').inject(newBlock);
			x.addEvent('click', function(e) {
				new Event(e).stop();
				Scroller.deleter(this, cls);
			});
			
		} 
	},
	
	drag: function(wrapper, bar) {
		Scroller.dragger = new Drag.Move(bar, {
			container: Scroller.barWrapper, 
			onDrag: function() {
				var parent = Scroller.wrapper.getParent();
				var size = parent.getSize();
				var x = this.value.now.x * parent.getScrollSize().x / size.x;
				parent.scrollTo(x);
			}
		});
	},
	
	child: function(cls, child) {
		child.getFirst().setStyle('border', '1px solid #000');
		var fx = new Fx.Tween(child.getFirst(), {duration: 300}).set('border-color', '#000');
		child.addEvent('click', function(e) {
			new Event(e).stop();
			
		fx.start('border-color', '#fff')
			.chain(function() {this.start('border-color', '#000');})
			.chain(function() {this.start('border-color', '#fff');})
			.chain(function() {this.start('border-color', '#000');});
			
			Scroller.updateParams(cls, child);
		});
	},
	
	children: function(cls) {
		Scroller.childrens.each(function(child, i) {
			child.getFirst().setStyle('border', '1px solid #000');
			var fx = new Fx.Tween(child.getFirst(), {duration: 300}).set('border-color', '#000');
			child.addEvent('click', function(e) {
				new Event(e).stop();
				
			fx.start('border-color', '#fff')
				.chain(function() {this.start('border-color', '#000');})
				.chain(function() {this.start('border-color', '#fff');})
				.chain(function() {this.start('border-color', '#000');});
				
				Scroller.updateParams(cls, child, i);
			});
		});
	},
	
	updateParams: function(cls, child, index) {
		var key = child.getElement('span').get('text');
		var preset = Presets[cls].get(key);
		
		var del = child.getElement('.delete-preset');
		if (del) {
			var customKey = del.id.replace("keydelete-", "");
			preset = CustomPresets[customKey];
		}
	

		var master = document.id('master-items');
		if (master) master = master.hasClass('active');
		
		$H(preset).each(function(value, key) {
			if (key == 'name') return;
			var el = document.id('params' + key);

			if (el) {
				var type = el.get('tag');
			
				if (master && Gantry.MenuItemHead) Gantry.MenuItemHead.getCheckbox(key).fireEvent('switchon');
			
				switch(type) {
					case 'select': 
						var values = el.getElements('option').getProperty('value');
						var objs = selectboxes.getObjects(el.getParent());
						selectboxes.select(objs, values.indexOf(value));
					
						break;
					
					case 'input':
						var cls = el.getProperty('class');
						el.setProperty('value', value);
					
						if (cls.contains('picker-input')) {
							el.fireEvent('keyup');
						} else if (cls.contains('slider')) {
							var slider = window['slider' + key.replace(/-/g, "_")];
							slider.set(slider.list.indexOf(value));
							slider.hiddenEl.fireEvent('set', value);
						} else if (cls.contains('toggle')) {
							var n = document.id('params' + key),
								details = n.retrieve('details');

							window.gantryToggles.set(details, value);
							Gantry.MenuItemHead.Cache['defaults'][key] = value;
						} else {
							el.fireEvent('set', value);
							if (Gantry.Selection == 'defaults'){
								Gantry.MenuItemHead.Cache['defaults'][key] = value;
							}
						}
					
						break;
				}
			}
		});
	}
};


var PresetsBadges = {
	init: function(cls) {
		if (!PresetsBadges.list) PresetsBadges.list = new Hash();
		
		var label = PresetsBadges.getLabel(cls);
		var params = [];
		
		PresetsBadges.list.set(cls, []);
		
		Presets[cls].each(function(value, key) {
			if (!params.length) {
				for (var p in value) {
					params.push(p);
					var labelChild = PresetsBadges.getLabel(p);
					if (labelChild) {
						var badge = PresetsBadges.build(p, labelChild, label, false);
						PresetsBadges.list.get(cls).push(badge);
					}
				}
			}
		});
		
		if (!PresetsBadges.buttons) PresetsBadges.buttons = [];
		
		var button = PresetsBadges.build(cls, label, false, params.length);
		PresetsBadges.buttons.push(button);
		
		button.addEvents({
			'click': function(e) {
				new Event(e).stop();
			
				this.fireEvent('toggle');
			},
			
			'show': function() {
				this.getElement('.number').setStyle('visibility', 'visible');
				$$(PresetsBadges.list.get(cls)).setStyle('display', 'block');

				this.showing = true;
			},
			
			'hide': function() {
				this.getElement('.number').setStyle('visibility', 'hidden');
				$$(PresetsBadges.list.get(cls)).setStyle('display', 'none');
		
				this.showing = false;
			},
			
			'toggle': function() {				
				PresetsBadges.buttons.each(function(b) {
					if (b != button) b.fireEvent('hide');
				});
				
				if (this.showing) this.fireEvent('hide');
				else this.fireEvent('show');
			}
		});
	},
	
	build: function(cls, label, parent, count) {
		var children = label.getChildren(), height = label.getSize().y, badge;
		
		var wrapper = label.getElement('.presets-wrapper');
		if (!wrapper) {
			wrapper = new Element('div', {'class': 'presets-wrapper', 'styles': {'position': 'relative'}}).inject(label, 'top');
			children.each(wrapper.adopt.bind(wrapper));
			wrapper.setStyle('height', height + 15);
			label.getElement('.hasTip').setStyle('line-height', height + 15);
		}
		
		var text = (parent) ? parent.getElement('.hasTip').innerHTML : GantryLang['show_parameters'];
		
		badge = new Element('div', {'class': 'presets-badge'}).inject(wrapper, 'top');
		
		var left = new Element('span', {'class': 'left'}).inject(badge);
		var right = new Element('span', {'class': 'right'}).inject(left).set('text', text);
	
		if ($chk(count)) {
			var number = new Element('span', {'class': 'number'}).inject(right);
			number.set('text', count).setStyle('visibility', 'hidden');
			badge.setStyle('cursor', 'pointer').addClass('parent');
		} else {
			badge.setStyle('display', 'none');
			var layer = label.getNext().getFirst().getLast();
			if (layer) {
				var top = layer.getStyle('top').toInt();
				layer.setStyle('top', top - 10);
			}
		}
		
		return badge;
		
	},
	
	getLabel: function(cls) {
		var search = document.id('params' + cls);
		if (search) {
			var parent = search.getParent(), match = null;
			while (parent && parent.get('tag') != 'table') {
				if (parent.get('tag') == 'tr') match = parent;
				parent = parent.getParent();
			}

			return match.getFirst();
		} else {
			return null;
		}
	}
};
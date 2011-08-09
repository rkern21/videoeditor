/**
 * @package		Gantry Template Framework - RocketTheme
 * @version		3.1.10 March 5, 2011
 * @author		RocketTheme http://www.rockettheme.com
 * @copyright 	Copyright (C) 2007 - 2011 RocketTheme, LLC
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

Gantry.MenuItemHead = {
	init: function() {
		var y = document.id('master-bar'),
			indexing;
		Gantry.Selection = 'defaults';
		if (!y) return;
		Gantry.MenuItemHead.mih = y;
		Gantry.MenuItemHead.SystemMessage = document.id('system-message');
		if (Gantry.MenuItemHead.SystemMessage) {
			Gantry.MenuItemHead.SystemMessage.setStyle('opacity', 0);
			var z = Gantry.MenuItemHead.SystemMessage.getElement('.close');
			if (z) z.addEvent('click', function(e) {
				e.stop();
				Gantry.MenuItemHead.SystemMessage.fade('out');
				(function() {
					Gantry.MenuItemHead.SystemMessage.setStyle('display', 'none')
				}).delay(500)
			})
		}
		Gantry.MenuItemHead.Table = document.id('params-table');
		Gantry.MenuItemHead.Keys = Gantry.MenuItemHead.Table.getElements('.paramlist_key');
		Gantry.MenuItemHead.Params = Gantry.MenuItemHead.Table.getElements('.paramlist_value');
		Gantry.MenuItemHead.Toggles = [];
		Gantry.MenuItemHead.Bgs = [];
		Gantry.MenuItemHead.Res = [];
		Gantry.MenuItemHead.Cache = {};
		Gantry.MenuItemHead.Template = $$('input[name=id]');
		if (Gantry.MenuItemHead.Template.length) Gantry.MenuItemHead.Template = Gantry.MenuItemHead.Template[0].value;
		else Gantry.MenuItemHead.Template = false;
		Gantry.MenuItemHead.removeable = [];
		Gantry.MenuItemHead.imapreset = [];
		var A = $$('.im-a-preset');
		Gantry.MenuItemHead.Params.clone().each(function(b, i) {
			if (b.id) {
				Gantry.MenuItemHead.Params.erase(b)
			} else {
				var c = [];
				UnallowedParams.each(function(p) {
					p = 'params' + p;
					if (b.hasChild(document.id(p)) && !c.contains(i)) {
						Gantry.MenuItemHead.removeable.push(i);
						c.push(i)
					}
				});
				A.each(function(a) {
					if (b.hasChild(a)) Gantry.MenuItemHead.imapreset.push(i)
				})
			}
		});
		var B = $$('.master-items')[0];
		document.id('master-items').addEvent('click', function(e) {
			if (e) new Event(e).stop();
			document.id('master-defaults').removeClass('active');
			B.setStyles({
				'visibility': 'visible',
				'display': 'block'
			});
			$$('.notice_defaults').setStyle('display', 'none');
			$$('.notice_menuitems').setStyle('display', 'block');
			this.addClass('active');
			var a = y.getElement('select').value;
			var b = y.getElement('select').getChildren().getProperty('value');
			Gantry.Selection = a;
			b = b.indexOf(a);
			Gantry.MenuItemHead.adjustSizes();
			Gantry.MenuItemHead.show(a);
			if (y.getElement('.master-items .selected .custom-params')) document.id('erase-custom').getParent().setStyle('display', 'block');
			else document.id('erase-custom').getParent().setStyle('display', 'none');
			Gantry.MenuItemHead.select.fireEvent('change', b)
		});
		document.id('master-defaults').addEvent('click', function(e) {
			new Event(e).stop();
			Gantry.Selection = 'defaults';
			Gantry.MenuItemHead.ajax.cancel();
			document.id('master-items').removeClass('active');
			$$('.master-items')[0].setStyle('display', 'none');
			$$('.notice_menuitems').setStyle('display', 'none');
			$$('.notice_defaults').setStyle('display', 'block');
			this.addClass('active');
			var a = y.getElement('select').value;
			Gantry.MenuItemHead.hide(a);
			Gantry.MenuItemHead.loadID('defaults')
		});
		Gantry.MenuItemHead.Keys.each(function(e, i) {
			var f = e.getElement('.mih-checkbox');
			f.addEvents({
				'click': function() {
					var b = y.getElement('select').value;
					var c = Gantry.MenuItemHead.getElements(this);
					if (Gantry.Selection == 'defaults') c = [];
					var d = Gantry.MenuItemHead.Cache[b];
					Gantry.MenuItemHead.adjustSizes(i);
					if (this.checked) {
						Gantry.MenuItemHead.adjustSizes(i).setStyle('display', 'none');
						c.each(function(a) {
							if (!d.get(a)) d.set(a, document.id('params' + a).get('value').toString())
						})
					} else {
						Gantry.MenuItemHead.Bgs[i].setStyle('display', 'block');
						c.each(function(a) {
							d.erase(a)
						})
					}
				},
				'switchon': function() {
					f.checked = true;
					Gantry.MenuItemHead.adjustSizes(i).setStyle('display', 'none')
				},
				'switchoff': function() {
					f.checked = false;
					Gantry.MenuItemHead.Bgs[i].setStyle('display', 'block')
				}
			});
			Gantry.MenuItemHead.Toggles.push(f);
			var g = Gantry.MenuItemHead.Params[i].getFirst().getLast();
			if (g) Gantry.MenuItemHead.Bgs.push(g)
		});
		Gantry.MenuItemHead.Toggles = $$(Gantry.MenuItemHead.Toggles);
		Gantry.MenuItemHead.Bgs = $$(Gantry.MenuItemHead.Bgs);
		Gantry.MenuItemHead.select = y.getElement('select');
		Gantry.MenuItemHead.ParentBgs = new Hash({});
		Gantry.MenuItemHead.ParentSettings = new Hash({});
		var C = document.id('erase-custom');
		if (C) {
			C.addEvent('click', function(e) {
				e.stop();
				var f = Gantry.MenuItemHead.select.get('value');
				Gantry.MenuItemHead.ajax = new Request.HTML({
					url: AdminURI + '?option=com_admin&tmpl=gantry-ajax-admin',
					onSuccess: function(a, b, c) {
						if (!c.length) {
							document.id('erase-custom').getParent().setStyle('display', 'none');
							var d = $$('.master-items .selectbox .selected, .master-items .selectbox-dropdown ul li.active');
							if (d.length) {
								var e = d.getElement('.custom-params');
								if (e.length) {
									e.set('html', '&nbsp;&nbsp;&nbsp; ').removeClass('custom-params').addClass('no-custom-params')
								}
							}
							Gantry.MenuItemHead.Cache[f].each(function(v, k) {
								Gantry.MenuItemHead.getCheckbox(k).click();
								Gantry.MenuItemHead.Cache[f].erase(k)
							});
							Gantry.MenuItemHead.loadID(f)
						}
					}
				}).post({
					'model': 'menu-items',
					'template': Gantry.MenuItemHead.Template,
					'action': 'erase',
					'menuitem': f
				})
			})
		}
		Gantry.MenuItemHead.select.addEvent('change', function(w) {
			Gantry.MenuItemHead.switchOff();
			var x = this.value;
			Gantry.Selection = this.value;
			Gantry.MenuItemHead.Bgs.setStyle('background-color', '#f6f6f6');
			if (y.getElement('.master-items .selected .custom-params')) document.id('erase-custom').getParent().setStyle('display', 'block');
			else document.id('erase-custom').getParent().setStyle('display', 'none');
			if (!Gantry.MenuItemHead.Cache[x]) {
				Gantry.MenuItemHead.ajax = new Request.HTML({
					url: AdminURI + '?option=com_admin&tmpl=gantry-ajax-admin',
					onSuccess: function(q, r, s) {
						if (s.length) {
							var t = JSON.decode(s);
							var u = t['params'];
							var v = new Hash(t['module_counts']);
							u = new Hash(u);
							q = new Hash(t['tree']);
							u.each(function(n, o) {
								if (typeof n == 'string') Gantry.MenuItemHead.Cache[o] = new Hash({});
								else Gantry.MenuItemHead.Cache[o] = new Hash(n);
								var p = {};
								Gantry.MenuItemHead.ParentBgs.set(o, []);
								q.each(function(l, m) {
									if (l != "") {
										new Hash(l).each(function(a, b) {
											p[b] = a;
											var c = true;
											if (b != '$family' && document.id('params' + b)) {
												var d = document.id('params' + b).getParents('td');
												d = d[0];
												var e = d.getPrevious().getElement('input[type=checkbox]');
												var f = Gantry.MenuItemHead.ParentBgs.get(o);
												if (Gantry.MenuItemHead.Cache[o].get(b)) {
													c = false
												}
												var g = d.getPrevious().getFirst();
												var i = new Element('span', {
													'class': 'inherited-span'
												}).set('html', 'inherited');
												if (g.get('tag') == 'div') i.inject(g);
												else {
													var j = d.getPrevious().getChildren();
													var h = d.getSize().y;
													var k = new Element('div', {
														'styles': {
															'position': 'relative',
															'height': h
														}
													});
													k.inject(d.getPrevious()).adopt(j);
													k.getElement('label').setStyle('line-height', h);
													k.getElement('input[type=checkbox]').setStyles({
														'position': 'absolute',
														'top': '40%'
													});
													i.inject(k)
												}
												i.setStyles({
													'position': 'absolute',
													'right': 0,
													'bottom': 0
												});
												f.push(i);
												if (c) {
													e.fireEvent('click')
												}
											}
										})
									}
								});
								Gantry.MenuItemHead.ParentSettings.set(x, p);
								Gantry.MenuItemHead.countPositions[o] = new Hash();
								v.each(function(a, b) {
									if (b == 'sidebar') b = 'mainbody';
									Gantry.MenuItemHead.countPositions[o].set(b + 'Position-currentPosition', a);
									var c = window['slider' + b.replace("-", "_") + 'Position'].RT.list[a];
									if (c) {
										var d = window['slider' + b.replace("-", "_") + 'Position'].RT.navigation[a - 1];
										d.fireEvent('click')
									}
								})
							});
							Gantry.MenuItemHead.loadID(x);
							return
						}
					}
				}).post({
					'model': 'menu-items',
					'template': Gantry.MenuItemHead.Template,
					'action': 'pull',
					'menuitem': x
				})
			} else {
				Gantry.MenuItemHead.loadID(x)
			}
		});
		Gantry.MenuItemHead.removeable.each(function(a, i) {
			Gantry.MenuItemHead.Toggles[a].dispose()
		});
		Gantry.MenuItemHead.imapreset.each(function(a, i) {
			Gantry.MenuItemHead.Bgs[a].dispose()
		});
		Gantry.MenuItemHead.Cache['defaults'] = new Hash(JSON.decode(Gantry.MenuItemHead.getParams())['off']);
		Gantry.MenuItemHead.countPositions = {};
		Gantry.MenuItemHead.countPositions['defaults'] = new Hash();
		var D = $$('.countPositions');
		D.each(function(a) {
			var b = a.className.split(" ");
			Gantry.MenuItemHead.countPositions['defaults'].set(b[0], a.innerHTML)
		});
		Gantry.MenuItemHead.addAjax()
	},
	loadID: function(h) {
		var i = Gantry.MenuItemHead.Cache[h];
		var j = i;
		var l = Gantry.MenuItemHead.ParentBgs.get(h);
		Gantry.MenuItemHead.ParentBgs.each(function(a, b) {
			$$(a).setStyle('display', 'none')
		});
		if (l && l.length) $$(l).setStyle('display', 'block');
		var m = Gantry.MenuItemHead.Cache['defaults'];
		Gantry.MenuItemHead.countPositions[h].each(function(a, b) {
			var k = $$('.' + b)[0];
			if (k) k.set('html', a)
		});
		var n = Gantry.MenuItemHead.ParentSettings.get(h);
		m.each(function(a, b) {
			if (!i.get(b)) {
				var c = a;
				c = a.replace(/mb\;/g, '"mb";').replace(/sc\;/g, '"sc";').replace(/sb\;/g, '"sb";').replace(/sa\;/g, '"sa";');
				var d = document.id('params' + b);
				if (d) {
					if (d.hasClass('toggle-input')) d.fireEvent('set', [d.retrieve('details'), c]);
					else d.fireEvent('set', c)
				} else document.id('params' + b + c).fireEvent('click');
				Gantry.MenuItemHead.Cache[h].erase(b)
			}
		});
		$H(n).each(function(a, b) {
			if (!i.get(b)) {
				var c = a;
				c = a.replace(/mb\;/g, '"mb";').replace(/sc\;/g, '"sc";').replace(/sb\;/g, '"sb";').replace(/sa\;/g, '"sa";');
				var d = document.id('params' + b);
				if (d) {
					if (d.hasClass('toggle-input')) d.fireEvent('set', [d.retrieve('details'), c]);
					else d.fireEvent('set', c)
				}
				Gantry.MenuItemHead.Cache[h].erase(b)
			}
		});
		i.each(function(a, b) {
			var c = Gantry.MenuItemHead.getCheckbox(b);
			if (c && !c.checked) c.click();
			var d = a;
			d = a.replace(/mb\;/g, '"mb";').replace(/sc\;/g, '"sc";').replace(/sb\;/g, '"sb";').replace(/sa\;/g, '"sa";');
			if (b.contains('params')) b = b.replace("params", "");
			var e = document.id('params' + b);
			if (e) {
				if (e.hasClass('toggle-input')) e.fireEvent('set', [e.retrieve('details'), d]);
				else e.fireEvent('set', d)
			} else document.id('params' + b + d).fireEvent('click');
			var f = window['slider' + b.replace("-", "_")];
			if (f && b.contains('Position') && f.RT.navigation.length) {
				var g = Gantry.MenuItemHead.countPositions[h].get(b + '-currentPosition');
				if (f.RT.navigation[g - 1]) f.RT.navigation[g - 1].fireEvent('click')
			}
		})
	},
	getCheckbox: function(a) {
		var b = document.id('params' + a);
		if (b) {
			var c = b.getParent(),
				match = null;
			while (c && c.get('tag') != 'table') {
				if (c.get('tag') == 'tr') match = c;
				c = c.getParent()
			}
			return match.getFirst().getElement('input[type=checkbox]')
		} else {
			return null
		}
	},
	getElements: function(f) {
		var g = f.getParent();
		if (g.hasClass('presets-wrapper') || g.get('tag') == 'div') g = g.getParent();
		g = g.getNext();
		var h = [];
		var i = g.getElements('input');
		var j = g.getElements('select');
		if (j.length) h = i.combine(j);
		else h = i;
		if (!h.length) return [];
		if (h.length > 10 && g.getElements('.groupedsel').length) {
			var k = g.getElements('.groupedsel');
			var l = h;
			k.each(function(b) {
				var c = [];
				var d = b.getElements('input');
				var e = b.getElements('select');
				if (e.length) c = d.combine(e);
				else c = d;
				c.each(function(a) {
					h.erase(a)
				})
			})
		};
		var m = h.getProperty('id').filter(function(a) {
			return a != null && !a.contains("function(")
		}).map(function(a) {
			return a.replace("params", '')
		});
		return m
	},
	switchOn: function() {
		Gantry.MenuItemHead.Toggles.each(function(a) {
			a.fireEvent('switchon')
		})
	},
	switchOff: function() {
		Gantry.MenuItemHead.Toggles.each(function(a) {
			a.fireEvent('switchoff')
		})
	},
	adjustSizes: function(a) {
		var b;
		if (!Gantry.MenuItemHead.Bgs.length) return false;
		if (a != null) {
			b = Gantry.MenuItemHead.Params[a].getSize();
			Gantry.MenuItemHead.Bgs[a].setStyles({
				'width': b.x,
				'height': b.y
			})
		} else {
			for (var i = 0, l = Gantry.MenuItemHead.Bgs.length; i < l; i++) {
				b = Gantry.MenuItemHead.Params[i].getSize();
				Gantry.MenuItemHead.Bgs[i].setStyles({
					'width': b.x,
					'height': b.y
				})
			}
		}
		return Gantry.MenuItemHead.Bgs[a]
	},
	addAjax: function() {
		var g = document.id('toolbar-apply').getElement('a'),
			save = document.id('toolbar-save').getElement('a');
		var h = {
			'apply': g,
			'save': save
		};
		var i = document.id(document.adminForm);
		i.set('send', {
			onSuccess: Gantry.MenuItemHead.afterApply
		});
		var j = new Request.HTML({
			url: AdminURI + '?option=com_admin&tmpl=gantry-ajax-admin',
			onSuccess: function(r) {
				document.id('master-items').removeClass('active');
				$$('.master-items')[0].setStyle('display', 'none');
				$$('.notice_menuitems').setStyle('display', 'none');
				$$('.notice_defaults').setStyle('display', 'block');
				document.id('master-defaults').addClass('active');
				var a = Gantry.MenuItemHead.mih.getElement('select').value;
				Gantry.MenuItemHead.hide(a);
				Gantry.MenuItemHead.loadID('defaults');
				if (j.joomlaType == 'apply') {
					var b = document.id(document.adminForm);
					b.task.value = 'apply';
					b.send()
				} else {
					(function() {
						submitform(j.joomlaType)
					}).delay(10);
					h['apply'].removeEvents('click');
					h['save'].removeEvents('click')
				}
			}
		});
		g.onclick = null;
		save.onclick = null;
		$$(g, save).addEvent('click', function(e) {
			e.stop();
			j.joomlaType = this.getParent().id.contains('apply') ? 'apply' : 'save';
			if (j.joomlaType == 'apply') {
				$$('form[name=adminForm] input[name=option]').set('value', 'com_admin');
				$$('form[name=adminForm] input[name=id]').set('name', 'template');
				$$('form[name=adminForm] input[name=tmpl_old]').set('name', 'tmpl')
			} else {
				$$('form[name=adminForm] input[name=option]').set('value', 'com_templates');
				$$('form[name=adminForm] input[name=template]').set('name', 'id');
				$$('form[name=adminForm] input[name=tmpl]').set('name', 'tmpl_old')
			}
			if (j.isRunning() || i.get('send').isRunning()) return false;
			var a = ($$('.master-items')[0].getStyle('display') == 'block' && document.id('master-items').hasClass('active'));
			Gantry.MenuItemHead.disable(j.joomlaType, h);
			var b = Gantry.MenuItemHead.Cache;
			var c = b['defaults'];
			delete b['defaults'];
			var d = {};
			for (var f in b) {
				d[f] = b[f].getClean()
			};
			Gantry.MenuItemHead.Cache['defaults'] = c;
			
			
			j.post({
				'model': 'menu-items',
				'template': Gantry.MenuItemHead.Template,
				'action': 'push',
				'menuitems-data': JSON.encode(d)
			})
		})
	},
	afterApply: function(r) {
		var a = document.id('toolbar-apply').getElement('a'),
			save = document.id('toolbar-save').getElement('a');
		var b = {
			'apply': a,
			'save': save
		};
		Gantry.MenuItemHead.enable('apply', b);
		$$('form[name=adminForm] input[name=option]').set('value', 'com_templates');
		$$('form[name=adminForm] input[name=template]').set('name', 'id');
		$$('form[name=adminForm] input[name=tmpl]').set('name', 'tmpl_old');
		for (var c in Gantry.MenuItemHead.Cache) {
			if (c != 'defaults') delete Gantry.MenuItemHead.Cache[c]
		}
		if (Gantry.MenuItemHead.SystemMessage) {
			Gantry.MenuItemHead.SystemMessage.getElement('li').set('text', r);
			Gantry.MenuItemHead.SystemMessage.setStyles({
				'opacity': 0,
				'display': 'block'
			}).fade('in')
		}
	},
	disable: function(a, b) {
		for (button in b) {
			b[button].getParent().addClass('disabled')
		}
		b[a].getParent().addClass('spinner')
	},
	enable: function(a, b) {
		for (button in b) {
			b[button].getParent().removeClass('disabled')
		}
		b[a].getParent().removeClass('spinner')
	},
	show: function(a) {
		Gantry.MenuItemHead.Toggles.setStyle('display', 'block');
		Gantry.MenuItemHead.Bgs.setStyle('display', 'block');
		$$('.preset-saver').setStyle('display', 'none')
	},
	hide: function(a) {
		Gantry.MenuItemHead.Toggles.setStyle('display', 'none');
		Gantry.MenuItemHead.Bgs.setStyle('display', 'none');
		$$('.preset-saver').setStyle('display', 'block')
	},
	getParams: function() {
		var n = Gantry.MenuItemHead.mih.getElement('select').get('value');
		var o = {
			'menuitem': n,
			'on': {},
			'off': {}
		};
		Gantry.MenuItemHead.Toggles.each(function(f, g) {
			var h = Gantry.MenuItemHead.Params[g];
			var i = [],
				hash = {};
			var j = h.getElements('input');
			var k = h.getElements('select');
			if (k.length) i = j.combine(k);
			else i = j;
			if (!i.length) return;
			if (i.length > 10 && h.getElements('.groupedsel').length) {
				var l = h.getElements('.groupedsel');
				var m = i;
				l.each(function(b) {
					var c = [];
					var d = b.getElements('input');
					var e = b.getElements('select');
					if (e.length) c = d.combine(e);
					else c = d;
					c.each(function(a) {
						i.erase(a)
					})
				})
			};
			i.each(function(a) {
				var b = a.getProperty('name');
				if (!b) return;
				b = b.replace("params[", "").replace("]", "");
				var c = a.getProperty('value');
				if (f.checked) o['on'][b] = c;
				else o['off'][b] = c
			})
		});
		var p = JSON.encode(o);
		return p
	}
};
Element.extend({
	getParents: function(a, b) {
		var c = [];
		var d = this.getParent();
		while (d && d !== (document.id(b) || document)) {
			if (d.get('tag').test(a)) c.push(d);
			d = d.getParent()
		}
		return $$(c)
	}
});
window.addEvent('domready', Gantry.MenuItemHead.init);


var RokAjaxSearch = new Class({
	version: "2.0 (mt 1.2)",
	Implements: [Options, Events],
	options: {
		'results': null,
		'close': null,
		'websearch': false,
		'blogsearch': false,
		'imagesearch': false,
		'videosearch': false,
		'imagesize': 'MEDIUM',
		'safesearch': 'MODERATE',
		'search': null,
		'readmore': null,
		'noresults': null,
		'advsearch': null,
		'searchlink': null,
		'advsearchlink': null,
		'page': null,
		'page_of': null,
		'uribase': null,
		'limit': null,
		'perpage': null,
		'ordering': null,
		'phrase': null,
		'keyevents': true,
		'hidedivs': null,
		'includelink': null,
		'viewall': null,
		'estimated': null,
		'showestimated': true,
		'showpagination': true,
		'showcategory': true,
		'showreadmore': true,
		'showdescription': true,
		'wordpress': false
	},
	
	initialize: function(options) {
		this.setOptions(options);

		this.timer = null;
		this.rows = ['roksearch_odd', 'roksearch_even'];
		this.searchphrase = this.options.phrase;
				
		this.inputBox = document.id('roksearch_search_str').setProperty('autocomplete', 'off');
		var pos = this.inputBox.getCoordinates();
		
		
		this.results = document.id('roksearch_results').setStyles({
			'position': 'absolute',
			'top': pos.top + pos.height,
			'left': this.getLeft()
		}).inject(document.body);
		this.fx = new Fx.Tween(this.results).set('opacity', 0);
		this.current = 0;
		
		var self = this;
		window.addEvent('resize', function() {
			self.results.setStyle('left', self.getLeft());
		});
		
		this.type = 'local';
		var perpage = this.options.perpage;
		
		if (this.options.websearch || this.options.blogsearch || this.options.imagesearch) {
			$$('#rokajaxsearch .search_options input[type=radio]').each(function(input) {
				input.addEvent('click', function() { 
					this.type = input.value;
					if (this.type == 'web' || this.type == 'blog' || this.type == 'images' || this.type == 'videos') {
						this.options.perpage = 4;
						if (this.type == 'web') this.google = new google.search.WebSearch();
						else if (this.type == 'blog') this.google = new google.search.BlogSearch();
						else if (this.type == 'images') {
							this.options.perpage = 3;
							this.google = new google.search.ImageSearch();
							this.google.setRestriction(google.search.ImageSearch.RESTRICT_IMAGESIZE, google.search.ImageSearch['IMAGESIZE_' + this.options.imagesize]);	
						} else if (this.type == 'videos') {
							this.options.perpage = 3;
							this.google = new google.search.VideoSearch();
						}
						if (this.type != 'blog' && this.type != 'videos') this.google.setRestriction(google.search.Search.RESTRICT_SAFESEARCH, google.search.Search['SAFESEARCH_' + this.options.safesearch]);
						this.google.setResultSetSize(google.search.Search.SMALL_RESULTSET);
						this.google.setNoHtmlGeneration();
						this.google.setSearchCompleteCallback(this, this.googleComplete);
					}
					else this.options.perpage = perpage;
				}.bind(this));
			}, this);
			//.setSearchStartingCallback(this, this.googleStart);
		};
		
		this.addEvents();
		this.keyEvents();
	},
	
	getLeft: function() {
		var pos = this.inputBox.getCoordinates(), x = document.id('roksearch_results').getSize().x;
		var win = document.id(window).getSize(), left;
		
		if (win.x / 2 < pos.left + pos.width) {
			left = pos.left + pos.width - x;
		} else {
			left = pos.left;
		}
		
		if (left < 0) left = pos.left;
				
		return left;
	},
	
	googleStart: function() {
		if (!this.inputBox.hasClass('loading')) this.inputBox.addClass('loading');
		this.google.execute(this.inputBox.value);
	},
	
	googleComplete: function() {
		var results = this.google.results;
		var tmp = document.id('rokajaxsearch_tmp');
//		console.log(results);
		var ol = new Element('ol', {'class': 'list'}).inject(tmp);
		
		if (this.type == 'web') {
			results.each(function(res) {
				var li = new Element('li');
				var title_link = new Element('a', {'href': res.unescapedUrl}).setProperty('target', '_blank').set('html', res.title);
				var title = new Element('h4').inject(li).adopt(title_link);
			
				var category = new Element('p').set('html', '<small><a href="'+res.url+'" target="_blank">'+res.visibleUrl+'</a></small>').inject(li);
			
				var content = res.content;
				content = content.replace('<b>', '<span class="highlight">').replace('</b>', '</span>');
				var desc = new Element('div', {'class': 'description'}).set('html', content).inject(li);
			
				li.inject(ol);
			});
		} else if (this.type == 'blog') {
			results.each(function(res) {
				var li = new Element('li');
				var title_link = new Element('a', {'href': res.postUrl}).setProperty('target', '_blank').set('html', res.title);
				var title = new Element('h4').inject(li).adopt(title_link);
			
				var category = new Element('p').set('html', '<small>by '+res.author+' - <a href="'+res.blogUrl+'" target="_blank">'+res.blogUrl+'</a></small>').inject(li);
			
				var content = res.content;
				content = content.replace('<b>', '<span class="highlight">').replace('</b>', '</span>');
				var desc = new Element('div', {'class': 'description'}).set('html', content).inject(li);
			
				li.inject(ol);
			});
		} else if (this.type == 'images') {
			results.each(function(res) {
				var li = new Element('li');
				var title_link = new Element('a', {'href': res.url}).setProperty('target', '_blank').set('html', res.title);
				var title = new Element('h4').inject(li).adopt(title_link);
			
				var category = new Element('p').set('html', '<small><a href="'+res.originalContextUrl+'" target="_blank">'+res.visibleUrl+'</a></small>').inject(li);
			
				var content = res.content;
				content = content.replace('<b>', '<span class="highlight">').replace('</b>', '</span>');
				var desc = new Element('div', {'class': 'description'}).set('html', content).inject(li);
				
				var thumb_container = new Element('div', {'class': 'google-thumb-image loading'}).inject(desc);
				thumb_container.setStyles({
					'width': res.tbWidth.toInt(), 
					'height': res.tbHeight.toInt()
				});
				
				var a = new Element('a', {'href': res.url, 'target': '_blank'}).inject(thumb_container);
				var img = new Element('image', {
					width: res.tbWidth.toInt(),
					height: res.tbHeight.toInt(),
					src: res.tbUrl
				}).inject(a);
			
				li.inject(ol);
			});
		} else if (this.type == 'videos') {
			results.each(function(res) {
				var li = new Element('li');
				var title_link = new Element('a', {'href': res.playUrl}).setProperty('target', '_blank').set('html', res.title);
				var title = new Element('h4').inject(li).adopt(title_link);
				
				var seconds = res.duration.toInt();
				var duration = '00:' + ((seconds < 10) ? '0' + seconds : seconds);
				
				if (seconds >= 60) {
					var m = seconds / 60;
					var s = seconds - (m * 60);
					m = m.toInt(); s = s.toInt();
					if (m < 10) m = '0' + m;
					if (s < 10) s = '0' + s;
					duration = m + ':' + s;
					
					if (m >= 60) {
						var h = m / 60;
						h = h.toInt();
						if (h < 10) h = '0' + h;
						duration = h + duration;
					}
				}
				
				var category = new Element('p').set('html', '<span class="'+res.videoType.toLowerCase()+'">Rating: '+(parseFloat(res.rating)).toFixed(2)+' | Duration: '+duration+' <small>'+res.videoType+'</small></span>').inject(li);

				var desc = new Element('div', {'class': 'description'}).set('html', '').inject(li);

				var thumb_container = new Element('div', {'class': 'google-thumb-image loading'}).inject(desc);
				thumb_container.setStyles({
					'width': res.tbWidth.toInt(),
					'height': res.tbHeight.toInt(),
					'text-align': 'center'
				});

				var a = new Element('a', {'href': res.url, 'target': '_blank'}).inject(thumb_container);
				var img = new Element('image', {
					src: res.tbUrl,
					width: res.tbWidth.toInt(),
					height: res.tbHeight.toInt()
				}).inject(a);

				li.inject(ol);
			});
		}
		
		this.results.empty().removeClass('roksearch_results').setStyle('visibility', 'visible');

		this.arrowleft = null;
		this.arrowright = null;
		this.selectedEl = -1;
		this.els = [];

		this.outputTableless();

		tmp.empty().setStyle('visibility','visible');
		this.inputBox.removeClass('loading');
		

		var pos = this.inputBox.getCoordinates(), self = this;
		this.results.setStyles({
			'top': pos.top + pos.height,
			'left': self.getLeft()
		});		
		this.fx.start('opacity', 1);
		this.fireEvent('loaded');
		//console.log(this.google.cursor);*/
	},
	
	addEvents: function() {
		this.inputBox.addEvents({
			'click': function() {
				if (this.inputBox.get('value') == this.options.search) this.inputBox.value = '';
			}.bind(this),
			
			'blur': function() {
				if (this.inputBox.get('value') == '') this.inputBox.value = this.options.search;
			}.bind(this),
			
			'keydown': function(e) {
				e = new Event(e);
				$clear(this.timer);
				
				if (e.key == 'enter') e.stop();
			},
			
			'keyup': function(e) {
				e = new Event(e);
								
				if (e.code == 17 || e.code == 18 || e.code == 224 || e.alt || e.control || e.meta) return false;
				if (e.alt || e.control  || e.meta || e.key == 'esc' || e.key == 'up' || e.key == 'down' || e.key == 'left' || e.key == 'right') return true;
				if (e.key == 'enter') e.stop();
				if (e.key == 'enter' && this.selectedEl != -1) {
					if (this.selectedEl || this.selectedEl == 0) location.href = this.els[this.selectedEl].getFirst('a');
					return false;
				};
				
				$clear(this.timer);
				
				var lnk = this.options.searchlink.split("?")[0];
				lnk = lnk.replace(this.options.uribase, '');
				lnk = (lnk) ? lnk : "index.php";
								
				var uri = this.options.uribase + lnk;
				if (this.options.wordpress) uri = this.options.uribase + this.options.searchlink;

				if (this.inputBox.value == ''){
					var splitDivs = this.options.hidedivs.split(" ");
					this.results.empty().removeClass('roksearch_results').setStyle('visibility', 'hidden');
					
					if (splitDivs.length > 0 && splitDivs != '') splitDivs.each(function(div){
						document.id(div).setStyle('visibility', 'visible');
					});
				} else {
					if (this.type == 'local') {
						var exact = this.inputBox.value.split('"');
						if (exact.length >= 3) {
							this.options.phrase = 'exact';
						} else {
							this.options.phrase = this.searchphrase;
						}
						var request = new Request({
							url: uri,
							method: 'get',
							delay : 200,
							onRequest: function() {
								this.inputBox.addClass('loading');
							}.bind(this),
							onSuccess: function(returns, b, c) {
								
								var results = new Element('div', {'styles': {'display': 'none'}}).set('html', returns);
								var tmp = document.id('rokajaxsearch_tmp');
								
								var wrapper = results.getElement('.contentpaneopen');
								if (wrapper) {
									results.getChildren().each(function(div) {
										if (div.getProperty('class') == 'contentpaneopen' && div.id != 'page') {
											tmp.set('html', div.innerHTML);
										}
									});
								} else {
									results.inject(document.body);
									results.setStyles({
										'display': 'block',
										'position': 'absolute',
										'top': -10000
									});
									wrapper = results.getElement('div[id=page]');
									results.dispose();
									if (wrapper) {
										var rs = wrapper.getElement('.results');
										tmp.set('html', (rs) ? rs.innerHTML : '');
									}
								}
								
							
								this.results.empty().removeClass('roksearch_results').setStyle('visibility', 'visible');
							
								this.arrowleft = null;
								this.arrowright = null;
								this.selectedEl = -1;
								this.els = [];
							
								if (results.getElement('.contentpaneopen'))	this.outputTable();
								else this.outputTableless();

								tmp.empty().setStyle('visibility','visible');
								this.inputBox.removeClass('loading');
								
								var pos = this.inputBox.getCoordinates(), self = this;
								this.results.setStyles({
									'top': pos.top + pos.height,
									'left': self.getLeft()
								});		
								
								this.fx.start('opacity', 1);
								this.fireEvent('loaded');
							
							}.bind(this)
						});
					
						if (this.options.wordpress) {
							this.timer = request.get.delay(500, request, [{
								's' : this.inputBox.value.replace(/\"/g, ''),
								'task': 'search',
								'action': 'rokajaxsearch',
								'r' : $time()
							}]);
						} else {
							this.timer = request.get.delay(500, request, [{
								'type': 'raw',
								'option' : 'com_search',
								'view' : 'search',
								'searchphrase' : this.options.phrase,
								'ordering' : this.options.ordering,
								'limit' : this.options.limit,
								'searchword' : this.inputBox.value.replace(/\"/g, ''),
								'tmpl': 'component',
								'r' : $time()
							}]);
						}
					} else if (this.type != 'local') {
						this.timer = this.googleStart.delay(500, this);
					}
				}
				
				return true;
			}.bind(this)
		});
		
		return this;
	},
	
	keyEvents: function() {
		var bounds = {
			'keyup': function(e) {
				e = new Event(e);
				
				if (e.key == 'left' || e.key == 'right' || e.key == 'up' || e.key == 'down' || e.key == 'enter' || e.key == 'esc') {
					e.stop();
					
					var store = false;
					if (e.key == 'left' && this.arrowleft) this.arrowleft.fireEvent('click');
					else if (e.key == 'right' && this.arrowright) this.arrowright.fireEvent('click');
					else if (e.key == 'esc' && this.close) this.close.fireEvent('click', e);
					else if (e.key == 'down') {
						store = this.selectedEl;
						
						if (this.selectedEl == -1) this.selectedEl = (this.options.perpage) * this.current;
						else if (this.selectedEl + 1 < this.els.length) this.selectedEl++;
						else return;
						
						if (store != -1) this.els[store].fireEvent('mouseleave');

						if ((this.selectedEl/this.options.perpage).toInt() > this.current) this.arrowright.fireEvent('click', true);
						if (this.selectedEl || this.selectedEl == 0) this.els[this.selectedEl].fireEvent('mouseenter');
					} else if (e.key == 'up') {
						store = this.selectedEl;
						
						if (this.selectedEl == -1) this.selectedEl = (this.options.perpage) * this.current;
						else if (this.selectedEl - 1 >= 0) this.selectedEl--;
						else return;
						
						if (store != -1) this.els[store].fireEvent('mouseleave');
						
						if ((this.selectedEl/this.options.perpage).toInt() < this.current) this.arrowleft.fireEvent('click', true);
						if (this.selectedEl || this.selectedEl == 0) this.els[this.selectedEl].fireEvent('mouseenter');
					} else if (e.key == 'enter') {
						if (this.selectedEl || this.selectedEl == 0) window.location = this.els[this.selectedEl].getElement('a');
					}
				}
			}.bind(this)
		};
		
		if (this.options.keyevents) {
			this.addEvent('loaded', function() {
				document.addEvent('keyup', bounds.keyup);
			});
		
			this.addEvent('unloaded', function() {
				document.removeEvent('keyup', bounds.keyup);
			});
		};
	},
	
	outputTable: function() {
			var self = this;
			var wrapper1 = new Element('div', {'class': 'roksearch_wrapper1'}).inject(this.results);
			var wrapper2 = new Element('div', {'class': 'roksearch_wrapper2'}).inject(wrapper1);
			var wrapper3 = new Element('div', {'class': 'roksearch_wrapper3'}).inject(wrapper2);
			var wrapper4 = new Element('div', {'class': 'roksearch_wrapper4'}).inject(wrapper3);
			
			var header = new Element('div', {'class': 'roksearch_header png'}).set('html', this.options.results).injectInside(wrapper4);
			this.close = new Element('a', {'id': 'roksearch_link', 'class': 'png'}).setProperty('href', '#').set('html', this.options.close).injectBefore(header);
			var splitDivs= this.options.hidedivs.split(" ");
			
			this.close.addEvent('click', function(e) {
				this.fireEvent('unloaded');
				
				new Event(e).stop();

				this.inputBox.value = this.options.search;
				var self = this;
				this.fx.start('opacity', 0).chain(function() {
					self.results.empty().removeClass('roksearch_results');
				});
				//this.results.empty().removeClass('roksearch_results').setStyle('visibility', 'hidden');

				if(splitDivs.length > 0 && splitDivs != '') splitDivs.each(function(div){
					document.id(div).setStyle('visibility', 'visible');
				});
				
			}.bind(this));
			
			if(splitDivs.length > 0 && splitDivs != '') splitDivs.each(function(div){
				document.id(div).setStyle('visibility', 'hidden');
			});
			
			this.results.addClass('roksearch_results');
			var searchedRestuls = $$('#rokajaxsearch_tmp fieldset');

			if (searchedRestuls.length > 0) {
					var container = new Element('div', {'class': 'container-wrapper'}).inject(wrapper4);
					var scroller = new Element('div', {'class': 'container-scroller'}).inject(container);
				

					searchedRestuls.each(function(res, i) {
					var data = '';
					data = res.getChildren();
					
					if (data.length > 0){
						data.each(function(div, j) {
							if (div.get('tag') == "div"){
								if (div.getChildren().length > 2 && !div.getPrevious()){
									var suri = div.getFirst().getNext().getProperty('href'); 

									var el = new Element('div', {'class': this.rows[i % 2] + ' png'});
									var lnk = new Element('a').setProperty('href', suri).injectInside(el);
									var name = new Element('h3').set('html', div.getFirst().getNext().get('text')).injectInside(lnk);
									
									this.els.push(el);
									el.addEvents({
										'mouseenter': function() {
											this.addClass(self.rows[i % 2] + '-hover');
											self.selectedEl = i;
										},
										'mouseleave': function() {
											this.removeClass(self.rows[i % 2] + '-hover');
											if (self.selectedEl == i) self.selectedEl = -1;
										}
									});
									
									var description = '';
									if (this.options.showdescription) description = div.getNext().innerHTML;
									var desc = new Element('span').set('html', description).injectAfter(lnk);
								
									if(this.options.showcategory){
										var cat = new Element('span', {'class': 'small'}).set('html', div.getChildren().getLast().get('text')).injectAfter(lnk);
										var br = new Element('br').injectAfter(cat);
									}
								
									if(this.options.showreadmore){
										lnk = new Element('a', {'class': 'clr'}).setProperty('href', suri).set('html', this.options.readmore).injectAfter(desc);
										if(this.options.showdescription) br = new Element('br').injectAfter(desc);
									}
								
									var innerWrapper1 = new Element('div', {'class': 'roksearch_result_wrapper1 png'}).inject(scroller);
									var innerWrapper2 = new Element('div', {'class': 'roksearch_result_wrapper2 png'}).inject(innerWrapper1);
									var innerWrapper3 = new Element('div', {'class': 'roksearch_result_wrapper3 png'}).inject(innerWrapper2);
									var innerWrapper4 = new Element('div', {'class': 'roksearch_result_wrapper4 png'}).inject(innerWrapper3);
																
									el.inject(innerWrapper4);
								}
							}
						}, this);
					}
				}, this);
				
				var splitting = scroller.getChildren();
				var max = Math.max(this.options.perpage, splitting.length);
				var min = Math.min(this.options.perpage, splitting.length);
				var perpage = this.options.perpage;
				this.page = [];
				(Math.abs(max/min)).times(function(i) {
					if (splitting[i]) this.page.push(new Element('div', {'class': 'page page-' + i}).inject(scroller).setStyle('width', scroller.getStyle('width')));
					for (j = 0, l = perpage;j<l;j++) {
						if (splitting[i * perpage + j]) splitting[i * perpage + j].inject(this.page[i]);
					}
				}.bind(this));
				
				scroller.setStyle('width', container.getStyle('width').toInt() * Math.round(max/min) + 1000);
			}
			
			if (!searchedRestuls.length) {
				var el = new Element('div', {'class': this.rows[0]});
				var name = new Element('h3').set('html', this.options.noresults).injectInside(el);
				var lnk = new Element('a').setProperty('href', this.options.advsearchlink).injectAfter(name);
				name = new Element('span', {'class': 'advanced-search'}).set('html', this.options.advsearch).injectInside(lnk);
				el.inject(wrapper4);
			} else {
				if(this.options.includelink){
					var limit = $$('#rokajaxsearch input[name=limit]')[0];
					
					this.bottombar = new Element('div', {'class': "roksearch_row_btm png"});
					var lnk2 = new Element('a').setProperty('href',"#").injectInside(this.bottombar);
					name = new Element('span').set('html', this.options.viewall).injectInside(lnk2);
					
					lnk2.addEvent('click', function(e) {
						new Event(e).stop();
						//limit.value = '';
						document.id('rokajaxsearch').submit();
					});
					
					this.bottombar.inject(wrapper4);
					if (splitting.length > this.options.perpage) {
						this.arrowDiv = new Element('div', {'class': 'container-arrows'}).inject(this.bottombar, 'top');
						this.arrowleft = new Element('div', {'class': 'arrow-left-disabled'}).inject(this.arrowDiv);
						this.arrowright = new Element('div', {'class': 'arrow-right'}).inject(this.arrowDiv);
					
						this.arrowsInit(container);
					}
				}
			}
	},
	
	outputTableless: function() {
			var self = this;
			var wrapper1 = new Element('div', {'class': 'roksearch_wrapper1'}).inject(this.results);
			var wrapper2 = new Element('div', {'class': 'roksearch_wrapper2'}).inject(wrapper1);
			var wrapper3 = new Element('div', {'class': 'roksearch_wrapper3'}).inject(wrapper2);
			var wrapper4 = new Element('div', {'class': 'roksearch_wrapper4'}).inject(wrapper3);
			var header = new Element('div', {'class': 'roksearch_header png'}).set('html', this.options.results).injectInside(wrapper4);
			
			if (this.type != 'local') {
				wrapper4.addClass('google-search').addClass('google-search-' + this.type);
				var poweredbygoogle = '<span class="powered-by-google">(powered by <a href="http://google.com" target="_blank">Google</a>)</span>';
				header.set('html', this.options.results + poweredbygoogle);
			};

			this.close = new Element('a', {'id': 'roksearch_link', 'class': 'png'}).setProperty('href', '#').set('html', this.options.close).injectBefore(header);
			var splitDivs= this.options.hidedivs.split(" ");
			
			this.close.addEvent('click', function(e) {
				this.fireEvent('unloaded');
				
				new Event(e).stop();

				this.inputBox.value = this.options.search;
				var self = this;
				this.fx.start('opacity', 0).chain(function() {
					self.results.empty().removeClass('roksearch_results');
				});
				//this.results.empty().removeClass('roksearch_results').setStyle('visibility', 'hidden');

				if(splitDivs.length > 0 && splitDivs != '') splitDivs.each(function(div){
					document.id(div).setStyle('visibility', 'visible');
				});
				
			}.bind(this));
			
			if(splitDivs.length > 0 && splitDivs != '') splitDivs.each(function(div){
				document.id(div).setStyle('visibility', 'hidden');
			});
			
			this.results.addClass('roksearch_results');
			var searchedRestuls = $$('#rokajaxsearch_tmp ol.list li');
			
			if (searchedRestuls.length > 0) { 
				var container = new Element('div', {'class': 'container-wrapper'}).inject(wrapper4);
				var scroller = new Element('div', {'class': 'container-scroller'}).inject(container);
				
				searchedRestuls.each(function(res, i) {
					var data = '';
					data = res.getChildren();

					if (data.length > 0) {
						var suri = res.getElement('a').getProperty('href');
						var el = new Element('div', {'class': this.rows[i % 2] + ' png'});
						var lnk = new Element('a').setProperty('href', suri).injectInside(el);
						if (this.type != 'local') lnk.setProperty('target', '_blank');
						var name = new Element('h3').set('html', data[0].get('text')).injectInside(lnk);

						this.els.push(el);
						el.addEvents({
							'mouseenter': function() {
								this.addClass(self.rows[i % 2] + '-hover');
								self.selectedEl = i;
							},
							'mouseleave': function() {
								this.removeClass(self.rows[i % 2] + '-hover');
								if (self.selectedEl == i) self.selectedEl = -1;
							}
						});

						var description = '';
						if (this.options.showdescription) description = data[2].innerHTML;

						var desc = new Element('span').set('html', description).injectAfter(lnk);
						
						if(this.options.showcategory){
							var cat = new Element('span', {'class': 'small'}).set('html', data[1].innerHTML).injectAfter(lnk);
							var br = new Element('br').injectAfter(cat);
						}
						
						if(this.options.showreadmore){
							lnk = new Element('a', {'class': 'clr'}).setProperty('href', suri).set('html', this.options.readmore).injectAfter(desc);
							if (this.type != 'local') lnk.setProperty('target', '_blank');
							if(this.options.showdescription) br = new Element('br').injectAfter(desc);
						}
						
						var innerWrapper1 = new Element('div', {'class': 'roksearch_result_wrapper1 png'}).inject(scroller);
						var innerWrapper2 = new Element('div', {'class': 'roksearch_result_wrapper2 png'}).inject(innerWrapper1);
						var innerWrapper3 = new Element('div', {'class': 'roksearch_result_wrapper3 png'}).inject(innerWrapper2);
						var innerWrapper4 = new Element('div', {'class': 'roksearch_result_wrapper4 png'}).inject(innerWrapper3);
														
						el.inject(innerWrapper4);
						
					}
				}, this);
							
				var splitting = scroller.getChildren();
				var max = Math.max(this.options.perpage, splitting.length);
				var min = Math.min(this.options.perpage, splitting.length);
				var perpage = this.options.perpage;
				this.page = [];
				(Math.abs(max/min)).times(function(i) {
					if (splitting[i]) this.page.push(new Element('div', {'class': 'page page-' + i}).inject(scroller).setStyle('width', scroller.getStyle('width')));
					for (j = 0, l = perpage;j<l;j++) {
						if (splitting[i * perpage + j]) splitting[i * perpage + j].inject(this.page[i]);
					}
				}.bind(this));
				
				if (this.type != 'local') {
					var size = this.page[0].getSize();
					this.page[0].setStyle('position', 'relative');
					this.layer = new Element('div', {
						'class': 'rokajaxsearch-overlay',
						'styles': {
							'width': size.x,
							'height': size.y,
							'position': 'absolute',
							'left': 0,
							'top': 0,
							'display': 'block',
							'z-index': 5
						}
					}).inject(this.page[0], 'top');
					var overlayfx = new Fx.Tween(this.layer, {duration: 300}).set('opacity', 0.9);
				}
				
				scroller.setStyle('width', container.getStyle('width').toInt() * Math.round(max/min) + 1000);
			} 
			
			if (!searchedRestuls.length) {
				var el = new Element('div', {'class': this.rows[0]});
				var name = new Element('h3').set('html', this.options.noresults).injectInside(el);
				var lnk = new Element('a').setProperty('href', this.options.advsearchlink).injectAfter(name);
				name = new Element('span', {'class': 'advanced-search'}).set('html', this.options.advsearch).injectInside(lnk);
				el.inject(wrapper4);
			} else {
				if(this.options.includelink){
					var limit = $$('#rokajaxsearch input[name=limit]')[0];
					
					this.bottombar = new Element('div', {'class': "roksearch_row_btm png"});
					var lnk2 = new Element('a', {'class': 'viewall'}).setProperty('href',"#").injectInside(this.bottombar);
					name = new Element('span').set('html', this.options.viewall).injectInside(lnk2);
					
					if (this.type != 'local') {
						lnk2.setProperties({
							'href': this.google.cursor.moreResultsUrl,
							'target': '_blank'
						});
						
						if (this.options.showestimated) 
							var estimated = new Element('span', {'class': 'estimated_res'}).set('text', '(' + this.google.cursor.estimatedResultCount + ' ' + this.options.estimated + ')').inject(lnk2, 'after');
							
						if (this.options.showpagination) {
							this.pagination = new Element('div', {'class': 'pagination_res'}).inject(estimated || lnk2, 'after');
							this.pagination.set('html', 
								this.options.page + ' ' + 
								'<span class="highlight">'+(this.google.cursor.currentPageIndex + 1)+'</span>' + ' ' + 
								this.options.page_of + ' ' + 
								'<span class="highlight">'+this.google.cursor.pages.length+'</span>');
						}
							
					} else {
						lnk2.addEvent('click', function(e) {
							new Event(e).stop();
							//limit.value = '';
							document.id('rokajaxsearch').submit();
						});
					}
					
					this.bottombar.inject(wrapper4);
					if (splitting.length > this.options.perpage || ((this.type != 'local') && this.google.cursor.pages.length > 1)) {
						this.arrowDiv = new Element('div', {'class': 'container-arrows'}).inject(this.bottombar, 'top');
						this.arrowleft = new Element('div', {'class': 'arrow-left-disabled'}).inject(this.arrowDiv);
						this.arrowright = new Element('div', {'class': 'arrow-right'}).inject(this.arrowDiv);

						if (this.type != 'local') {
							if (this.google.cursor) {
								var current = this.google.cursor.currentPageIndex;
								if (current > 0) this.arrowleft.removeClass('arrow-left-disabled').addClass('arrow-left');
								if (current == 7) this.arrowright.removeClass('arrow-right').addClass('arrow-right-disabled');
							}
							this.arrowsGoogleInit(container);
							overlayfx.start('opacity', 0);
//							if (this.type == 'web') this.results.effect('opacity', {wait: true, duration: 200}).start(1);
						} else this.arrowsInit(container);
					}
				}
			}
	},
	arrowsGoogleInit: function(wrapper) {
		this.arrowleft.addEvent('click', function(downkey) {
			if (!downkey && this.selectedEl >= 0) this.els[this.selectedEl].fireEvent('mouseleave');
			if (!downkey) this.selectedEl = -1;

			var current = (this.google.cursor) ? this.google.cursor.currentPageIndex : null;
			
			if (current - 1 <= 0) {
				this.arrowleft.removeClass('arrow-left').addClass('arrow-left-disabled');
				this.arrowright.removeClass('arrow-right-disabled').addClass('arrow-right');
			}
			else {
				this.arrowleft.removeClass('arrow-left-disabled').addClass('arrow-left');
				this.arrowright.removeClass('arrow-right-disabled').addClass('arrow-right');
			}
			if (!current) return; 
			else {
				if (!this.inputBox.hasClass('loading')) this.inputBox.addClass('loading');
				this.layer.setStyle('opacity', 0.9);
				this.google.gotoPage(current - 1);
			}
		}.bind(this));
		
		this.arrowright.addEvent('click', function(downkey) {
			if (!downkey && this.selectedEl >= 0) this.els[this.selectedEl].fireEvent('mouseleave');
			if (!downkey) this.selectedEl = -1;

			var current = (this.google.cursor) ? this.google.cursor.currentPageIndex : null;

			if (current + 1 >= this.google.cursor.pages.length) {
				this.arrowleft.removeClass('arrow-left-disabled').addClass('arrow-left');
				this.arrowright.removeClass('arrow-right').addClass('arrow-right-disabled');
			}
			else {
				this.arrowleft.removeClass('arrow-left').addClass('arrow-left-disabled');
				this.arrowright.removeClass('arrow-right-disabled').addClass('arrow-right');
			}

			if (current >= this.google.cursor.pages.length -1) return;
			else {
				if (this.arrowleft.hasClass('arrow-left-disabled')) this.arrowleft.removeClass('arrow-left-disabled').addClass('arrow-left');
				if (!this.inputBox.hasClass('loading')) this.inputBox.addClass('loading');
				this.layer.setStyle('opacity', 0.9);
				this.google.gotoPage(current + 1);
			}
		}.bind(this));
	},
	arrowsInit: function(wrapper) {
		this.scroller = new Fx.Scroll(wrapper, {wait: false});
		this.arrowleft.addEvent('click', function(downkey) {
			if (!downkey && this.selectedEl >= 0) this.els[this.selectedEl].fireEvent('mouseleave');
			if (!downkey) this.selectedEl = -1;
			
			if (this.current - 1 <= 0) {
				this.arrowleft.removeClass('arrow-left').addClass('arrow-left-disabled');
				this.arrowright.removeClass('arrow-right-disabled').addClass('arrow-right');
			}
			else {
				this.arrowleft.removeClass('arrow-left-disabled').addClass('arrow-left');
				this.arrowright.removeClass('arrow-right-disabled').addClass('arrow-right');
			}

			if (!this.current) return; 
			else {
				if (this.current < 0) this.current = 0;
				else this.current -= 1;
				this.scroller.toElement(this.page[this.current]);
			}
		}.bind(this));
		
		this.arrowright.addEvent('click', function(downkey) {
			if (!downkey && this.selectedEl >= 0) this.els[this.selectedEl].fireEvent('mouseleave');
			if (!downkey) this.selectedEl = -1;
			
			if (this.current + 1 >= this.page.length - 1) {
				this.arrowleft.removeClass('arrow-left-disabled').addClass('arrow-left');
				this.arrowright.removeClass('arrow-right').addClass('arrow-right-disabled');
			}
			else {
				this.arrowleft.removeClass('arrow-left').addClass('arrow-left-disabled');
				this.arrowright.removeClass('arrow-right-disabled').addClass('arrow-right');
			}
			
			if (this.current >= this.page.length) return;
			else {
				if (this.arrowleft.hasClass('arrow-left-disabled')) 
					this.arrowleft.removeClass('arrow-left-disabled').addClass('arrow-left');
				if (this.current >= this.page.length - 1) this.current = this.page.length - 1;
				else this.current += 1;
				
				this.scroller.toElement(this.page[this.current]);
			}
		}.bind(this));
	}
});

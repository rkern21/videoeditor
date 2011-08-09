var RokTabsIcons = new Class({
	initialize: function() {
		this.Path = SitePath + "/";
		
		this.selects = $$('.icons select');
		this.pathEl = $('paramstabs_iconpath');
		if (this.pathEl) {
			this.pathEl.addEvent('keyup', function() {
				this.Path = SitePath + "/" + this.value;
				if (this.Path.substr(-1,1) != "/") this.Path += "/";
				this.Path = this.Path.replace("__template__", TemplatePath);
				this.Path = this.Path.replace("__module__", ModulePath);
			});
			this.Path = SitePath + "/" + this.pathEl.value;
			if (this.Path.substr(-1,1) != "/") this.Path += "/";
			
			this.Path = this.Path.replace("__template__", TemplatePath);
			this.Path = this.Path.replace("__module__", ModulePath);
		}

		this.selects.each(function(select) {
			this.selectEvent(select, this);
		}, this);
		
		this.adds = $$('.icons .controls .add');
		this.removes = $$('.icons .controls .remove');
		
		var self = this;
		this.adds.each(function(add) {
			add.addEvent('click', function() {self.add(this);});
		});
		
		this.removes.each(function(remove) {
			remove.addEvent('click', function() {self.remove(this);});
		});
		
	},
	
	selectEvent: function(select, self) {
		var value = select.getValue();
		var preview = select.getPrevious();
		var s = this;
		if (preview.getElement('img')) {
			preview.getElement('img').src = self.Path + value;
		} else {
			new Asset.image(self.Path + value).inject(preview);
		}
		
		if (value == '__none__') preview.getElement('img').setStyle('display', 'none');
		else preview.getElement('img').setStyle('display', 'block');
		
		select.getElements('option').addEvents({
			'mouseenter': function() {
				if (this.value == '__none__') preview.getElement('img').setStyle('display', 'none');
				else preview.getElement('img').setStyle('display', 'block');
				
				preview.getElement('img').src = self.Path + this.value;
			},
			'mouseleave': function() {
				if (this.value == '__none__') preview.getElement('img').setStyle('display', 'none');
				else preview.getElement('img').setStyle('display', 'block');
				
				preview.getElement('img').src = self.Path + value;
			}
		});
		select.addEvent('change', function() {
			value = this.value;

			if (value == '__none__') preview.getElement('img').setStyle('display', 'none');
			else preview.getElement('img').setStyle('display', 'block');

			preview.getElement('img').src = self.Path + value;
			
			var list = [];
			s.selects.each(function(sel) {
				list.push(sel.value);
			});
			$('paramstabs_icon').value = list.join(',');
		});
	},
	
	add: function(self) {
		var trCurrent = self.getParent().getParent();
		
		var clone = trCurrent.clone(true).inject(trCurrent, 'after');
		var select = clone.getElement('select');
		var add = clone.getElement('.add');
		var remove = clone.getElement('.remove').setStyle('display', 'block');
		
		add.addEvent('click', function() {this.add(add);}.bind(this));
		remove.addEvent('click', function() {this.remove(remove);}.bind(this));
		
		this.selectEvent(select, this);
		
		this.rearrange();
	},
	
	remove: function(self) {
		var trCurrent = self.getParent().getParent();
		
		trCurrent.empty().remove();
		
		this.rearrange();
	},
	
	rearrange: function() {
		this.removes = $$('.icons .controls .add');
		this.adds = $$('.icons .controls .remove');
		this.selects = $$('.icons select');
		
		var list = [];
		this.selects.each(function(select, i) {
			var preview = select.getPrevious();
			var label = preview.getPrevious();
			preview.className = 'preview_tabs_icon'+(i+1)+' icons_previews';
			label.setHTML("Tab "+(i+1)+": ");
			
			list.push(select.value);
		});
		
		if (this.selects.length == 1) {
			this.selects[0].getNext().getElement('.remove').setStyle('display', 'none');
		} else {
			this.selects[0].getNext().getElement('.remove').setStyle('display', 'block');
		}
		
		$('paramstabs_icon').value = list.join(',');
	}
});

RokTabsIcons.implement(new Options, new Events);

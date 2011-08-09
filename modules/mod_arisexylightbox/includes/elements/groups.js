var ARIElementGroups = new Class({
	options: {
		selectId: null,
		groupClass: 'el-group'
	},

	initialize: function(id, options) {
		this.id = id;
		this.setOptions(options);
		
		var self = this;
		$(this.options.selectId).addEvent('change', function(event) {
			var event = new Event(event);
			var ctrl = event.target;

			var groupEl = $E('.' + self.options.groupClass, '' + self.id);
			groupEl.setStyle('display', 'none');
			while ((groupEl = groupEl.getNext()))
			{
				if (groupEl.hasClass(self.options.groupClass))
					groupEl.setStyle('display', 'none');
			}
			$('group_' + self.options.selectId + '_' + ctrl.value).setStyle('display', 'block');
			self.fixParentSize();
		});
	},

	fixParentSize: function() {
		var parentSlide = $(this.options.selectId).getParent();
		while (parentSlide && !parentSlide.hasClass('jpane-slider')) {
			parentSlide = parentSlide.getParent();
		}

		if (parentSlide) {
			var size = $(parentSlide.firstChild).getSize();
			parentSlide.setStyle('height', size.size.y + 'px');
		}
	}
});
ARIElementGroups.implement(new Options);
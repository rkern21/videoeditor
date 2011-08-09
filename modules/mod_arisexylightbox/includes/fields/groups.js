var ARIElementGroups = new Class({
	options: {
		selectId: null,
		groupClass: 'el-group'
	},

	initialize: function(id, options) {
		this.id = id;
		this.setOptions(options);
		
		$(this.options.selectId).addEvent('change', (function(event) {
			var event = new Event(event),
				ctrl = event.target;

			var groupEl = $(this.id).getElement('.' + this.options.groupClass);
			groupEl.setStyle('display', 'none');
			while ((groupEl = groupEl.getNext()))
			{
				if (groupEl.hasClass(this.options.groupClass))
					groupEl.setStyle('display', 'none');
			}
			$('group_' + this.options.selectId + '_' + ctrl.value).setStyle('display', 'block');
			this.fixParentSize();
		}).bind(this));
	},

	fixParentSize: function() {
		var parentSlide = $(this.options.selectId).getParent();
		while (parentSlide && !parentSlide.hasClass('jpane-slider')) {
			parentSlide = parentSlide.getParent();
			if (parentSlide && typeof(parentSlide.hasClass) == "undefined")
				return ;

		}

		if (parentSlide) {
			var size = $(parentSlide).getFirst().getSize();
			if (typeof(size.size) != "undefined") size = size.size;
			parentSlide.setStyle('height', size.y + 'px');
		}
	}
});
ARIElementGroups.implement(new Options);
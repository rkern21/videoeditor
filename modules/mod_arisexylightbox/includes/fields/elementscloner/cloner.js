var ARIElementsCloner = new Class({
	options: {
		hiddenId: null,
		keyField: null
	},

	initialize: function(id, options, clonerOptions, data) {
		this.id = id;
		this.setOptions(options);
		this.cloner = jQuery("#" + id).elementsCloner(clonerOptions, data);
		
		var self = this;
		jQuery("#" + id).bind("reInitItem", function() {
			self.fixParentSize();
		}).bind("removeAll", function() {
			self.fixParentSize();
		});
		
		var oldSubmitForm = submitform;
		submitform = function() {
			self.saveData();
			oldSubmitForm.apply(this, arguments);
		}
		
		setTimeout(function() {
			self.fixParentSize();
		}, 10);
	},
	
	saveData: function() {
		var data = this.cloner.getData(),
			fixedData = [];

		if (this.options.keyField) {
			var keyField = this.options.keyField;
			for (var i = 0; i < data.length; i++) {
				var dataItem = data[i];
				if (dataItem[keyField])
					fixedData.push(dataItem);
			}
		}

		$(this.options.hiddenId).value = Json.toString(fixedData.length > 0 ? fixedData : null);
	},
	
	fixParentSize: function() {
		var parentSlide = $(this.id).getParent();
		while (parentSlide && !parentSlide.hasClass('jpane-slider')) {
			parentSlide = parentSlide.getParent();
			if (parentSlide && typeof(parentSlide.hasClass) == "undefined")
				return ;
		}

		if (parentSlide) {
			var size = parentSlide.getFirst().getSize();
			if (typeof(size.size) != "undefined") size = size.size;
			parentSlide.setStyle('height', size.y + 'px');
		}
	}
});
ARIElementsCloner.implement(new Options);
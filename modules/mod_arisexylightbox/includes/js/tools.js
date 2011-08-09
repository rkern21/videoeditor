(function($) {
	ARISexyLightboxTools = {
		buHandled: false,

		unloaded: false,
			
		initBeforeUnload: function(el, message) {
			$(window).unbind("beforeunload.asl");
			$(window).bind("beforeunload.asl", function(e) {
				if (ARISexyLightboxTools.buHandled)
					return ;

				setTimeout(function() {
					setTimeout(function() {
						if (!ARISexyLightboxTools.unloaded)
							$(el).click();
					}, 600);
				}, 5);

				ARISexyLightboxTools.buHandled = true;

				return message;
			});

			$(window).unload(function() {
				ARISexyLightboxTools.unloaded = true;

				SexyLightbox.close();
			});
		}
	};
})(window["jQueryASL"] || jQuery);
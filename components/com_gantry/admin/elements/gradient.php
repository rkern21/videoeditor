<?php
/**
 * @package     gantry
 * @subpackage  admin.elements
 * @version		3.1.10 March 5, 2011
 * @author		RocketTheme http://www.rockettheme.com
 * @copyright 	Copyright (C) 2007 - 2011 RocketTheme, LLC
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 *
 * Gantry uses the Joomla Framework (http://www.joomla.org), a GNU/GPLv2 content management system
 *
 */
defined('JPATH_BASE') or die();

/**
 * Renders a spacer element
 *
 * @package     gantry
 * @subpackage  admin.elements
 */

class JElementGradient extends JElement
{
	var	$_name = 'Gradient';
	
	function fetchElement($name, $value, &$node, $control_name)
	{
		global $gantry;
		
		if (!defined('GANTRY_GRADIENT')) {
			
			gantry_addScript($gantry->gantryUrl.'/admin/widgets/gradient/js/gradient.js');
				
			define('GANTRY_GRADIENT',1);
		}
		$functions = "_jsInit_" . $gantry->platform->getJSInit();
		gantry_addInlineScript($this->$functions($name, $value, $node, $control_name));
		$output = "<div id='".$name."' class='gradient-preview'></div>\n";
		
		return $output;
	}
	
	function _jsInit_mt_1_1($name, $value, &$node, $control_name) {
		$name2 = str_replace("-preview", "", $name);
		$name2 = str_replace("-", "_", $name2);
		
		$js = "
			window.addEvent('domready', function() {
				var previewBox = $('".$name."');
				var name = 'params'+'".$name."'.replace('-preview', '');
				var preview = new Element('div').setText('Sorry. Gradient previews can be seen only on WebKit and Gecko based browsers.').inject(previewBox.addClass('error'));
						
				if (window.webkit || window.gecko) {
					if (typeof r_".$name2."_from != 'undefined') r_".$name2."_from.addEvent('onChange', function() { updateGradient(); });
					if ($(name+'-fromopacity')) window.slider".$name2."_fromopacity.addEvent('onDrag', function() { updateGradient(); });
					if ($(name+'-toopacity')) window.slider".$name2."_toopacity.addEvent('onDrag', function() { updateGradient(); });
					if (typeof r_".$name2."_to != 'undefined') r_".$name2."_to.addEvent('onChange', function() { updateGradient(); });
					if ($(name+'-gradient')) $(name+'-gradient').addEvent('change', function() { updateGradient(); });
					if ($(name+'-direction_start')) $(name+'-direction_start').addEvent('change', function() { updateGradient(); });
					if ($(name+'-direction_end')) $(name+'-direction_end').addEvent('change', function() { updateGradient(); });
				};
				
				var updateGradient = function() {
					var settings = {
						'from': $(name+'-from'),
						'to': $(name+'-to'),
						'fromOp': $(name+'-fromopacity'),
						'toOp': $(name+'-toopacity'),
						'type': $(name+'-gradient'),
						'direction_start': $(name+'-direction_start'),
						'direction_end': $(name+'-direction_end')
					};
					
					var fromColor = settings.from.value.hexToRgb(true);
					var toColor = settings.to.value.hexToRgb(true);
					
					fromColor = fromColor.join(', ') + ', ' +(settings['fromOp'] ? settings['fromOp'].value : 1);
					toColor = toColor.join(', ') + ', ' +(settings['toOp'] ? settings['toOp'].value : 1);
					
					if (window.webkit) {
						
						var gradient = '-webkit-gradient(' + (settings.type ? settings.type.value : 'linear') + ', ' + settings.direction_start.value.replace('-', ' ') + ', ' + settings.direction_end.value.replace('-', ' ') + ', from(rgba(' + fromColor + ')), to(rgba(' + toColor + ')))';
						previewBox.removeClass('error').empty().style.background = gradient;
					} else if (window.gecko) {
						var start = settings.direction_start.value.split('-');
						var end = settings.direction_end.value.split('-');
					
						var pointA, pointB;
						
						pointA = start[0];
						pointB = start[1];
						if (start[0] == end[0]) pointA = 'center';
						if (start[1] == end[1]) pointB = 'center';
						
					
						var gradient = '-moz-'+(settings.type ? settings.type.value : 'linear')+'-gradient('+ pointA + ' ' + pointB + ', rgba(' + fromColor + '), rgba(' + toColor + '))';
						previewBox.removeClass('error').empty().style.background = gradient;
					}
				}
				updateGradient();
			});
		
		";
		
		return $js;
	}
	
	function _jsInit_mt_1_2($name, $value, &$node, $control_name) {
		$name2 = str_replace("-preview", "", $name);
		$name2 = str_replace("-", "_", $name2);
		
		$js = "
			window.addEvent('domready', function() {
				var previewBox = document.id('".$name."');
				var name = 'params'+'".$name."'.replace('-preview', '');
				var preview = new Element('div').set('text', 'Sorry. Gradient previews can be seen only on WebKit and Gecko based browsers.').inject(previewBox.addClass('error'));
						
				if (Browser.Engine.webkit || Browser.Engine.gecko) {
					if (typeof r_".$name2."_from != 'undefined') r_".$name2."_from.addEvent('onChange', function() { updateGradient(); });
					if (document.id(name+'-fromopacity')) window.slider".$name2."_fromopacity.addEvent('onDrag', function() { updateGradient(); });
					if (document.id(name+'-toopacity')) window.slider".$name2."_toopacity.addEvent('onDrag', function() { updateGradient(); });
					if (typeof r_".$name2."_to != 'undefined') r_".$name2."_to.addEvent('onChange', function() { updateGradient(); });
					if (document.id(name+'-gradient')) document.id(name+'-gradient').addEvent('change', function() { updateGradient(); });
					if (document.id(name+'-direction_start')) document.id(name+'-direction_start').addEvent('change', function() { updateGradient(); });
					if (document.id(name+'-direction_end')) document.id(name+'-direction_end').addEvent('change', function() { updateGradient(); });
				};
				
				var updateGradient = function() {
					var settings = {
						'from': $(name+'-from'),
						'to': $(name+'-to'),
						'fromOp': $(name+'-fromopacity'),
						'toOp': $(name+'-toopacity'),
						'type': $(name+'-gradient'),
						'direction_start': $(name+'-direction_start'),
						'direction_end': $(name+'-direction_end')
					};
					
					var fromColor = settings.from.value.hexToRgb(true);
					var toColor = settings.to.value.hexToRgb(true);
					
					fromColor = fromColor.join(', ') + ', ' +(settings['fromOp'] ? settings['fromOp'].value : 1);
					toColor = toColor.join(', ') + ', ' +(settings['toOp'] ? settings['toOp'].value : 1);
					
					if (Browser.Engine.webkit) {
						
						var gradient = '-webkit-gradient(' + (settings.type ? settings.type.value : 'linear') + ', ' + settings.direction_start.value.replace('-', ' ') + ', ' + settings.direction_end.value.replace('-', ' ') + ', from(rgba(' + fromColor + ')), to(rgba(' + toColor + ')))';
						previewBox.removeClass('error').empty().style.background = gradient;
					} else if (Browser.Engine.gecko) {
						var start = settings.direction_start.value.split('-');
						var end = settings.direction_end.value.split('-');
					
						var pointA, pointB;
						
						pointA = start[0];
						pointB = start[1];
						if (start[0] == end[0]) pointA = 'center';
						if (start[1] == end[1]) pointB = 'center';
						
					
						var gradient = '-moz-'+(settings.type ? settings.type.value : 'linear')+'-gradient('+ pointA + ' ' + pointB + ', rgba(' + fromColor + '), rgba(' + toColor + '))';
						previewBox.removeClass('error').empty().style.background = gradient;
					}
				}
				updateGradient();
			});
		
		";
		
		return $js;
	}
}

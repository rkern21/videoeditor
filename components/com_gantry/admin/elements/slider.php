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
 * @package     gantry
 * @subpackage  admin.elements
 */
class JElementSlider extends JElement {
	

	function fetchElement($name, $value, &$node, $control_name)
	{
		global $gantry;
		$output = '';

		$this->template = end(explode(DS, $gantry->templatePath));

        $class = $node->attributes('class') ? $node->attributes('class') : '';

		if (!defined('GANTRY_CSS')) {
			gantry_addStyle($gantry->gantryUrl.'/admin/widgets/gantry.css');
			define('GANTRY_CSS', 1);
		}
        if (!defined('GANTRY_POSITIONS')) {
            gantry_addScript($gantry->gantryUrl.'/admin/widgets/slider/js/slider.js');
			if (!defined('GANTRY_SLIDER')) define('GANTRY_SLIDER', 1);
        }
		
		$this->value = $value;
		$this->children = array();
		
		foreach($node->children() as $children) {
			$this->children[] = $children->data();
		}
		
		$scriptinit = $this->sliderInit($name);		
		gantry_addInlineScript($scriptinit);
		
		$output = '
		<div class="wrapper">
		<div id="'.$name.'" class="'.$class.'">
			<!--<div class="note">
				Internet Explorer 6 supports only the <strong>Low Quality</strong> setting.
			</div>-->
			<div class="slider">
			    <div class="slider2"></div>
				<div class="knob"></div>
			</div>
			<input type="hidden" id="params'.$name.'" class="slider" name="'.$control_name.'['.$name.']'.'" value="'.$this->value.'" />
		</div>
		</div>
		';
		
		return $output;
	}
	
	function sliderInit($name) {
		global $gantry;
		
		$name2 = str_replace("-", "_", $name);
		$steps = count($this->children);
		$current = array_search($this->value, $this->children);
		if ($current === false) $current = 0;

		$dollar = ($gantry->platform->jslib == 'mootools' && $gantry->platform->jslib_version == '1.1') ? "$" : "document.id";
		
		$slider = $dollar."('$name').getElement('.slider')";
		$knob = $dollar."('$name').getElement('.knob')";
		$hidden = $dollar."('params$name')";
		$children = '[\'' . implode("', '", $this->children) . '\']';

		$js = "
		window.addEvent('domready', function() {
			$hidden.addEvents({
				'set': function(value) {
					var slider = window.slider$name2;
					var index = slider.list.indexOf(value);
					slider.set(index);
				}
			});
			window.slider$name2 = new RokSlider($slider, $knob, {
				steps: ".(count($this->children) - 1).",
				snap: true,
				initialize: function() {
					this.hiddenEl = $hidden;	
				},
				onComplete: function() {
					this.knob.removeClass('down');
					
					if (Gantry.MenuItemHead) {
						var cache = Gantry.MenuItemHead.Cache[Gantry.Selection];
						if (!cache) cache = new Hash({});
						cache.set('".$name."', this.list[this.step]);
					}
				},
				onDrag: function(now) {
					this.element.getFirst().setStyle('width', now + 10);
				},
				onChange: function(step) {
					$hidden.setProperty('value', this.list[step]);
				},
				onTick: function(position) {
					if(this.options.snap) position = this.toPosition(this.step);
					this.knob.setStyle(this.property, position);
					this.fireEvent('onDrag', position);
				}
			});
			window.slider$name2.list = $children;
			window.slider$name2.set($current);
			
			$knob.addEvents({
				'mousedown': function() {this.addClass('down');},
				'mouseup': function() {this.removeClass('down');}
			});
		});
		";
		
		return $js;
	}
}

?>
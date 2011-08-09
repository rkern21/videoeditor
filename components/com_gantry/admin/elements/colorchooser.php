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
class JElementColorChooser extends JElement {
	

	function fetchElement($name, $value, &$node, $control_name)
	{
		//global $stylesList;
        /**
         * @global Gantry $gantry
         */
		global $gantry;
		$output = '';
		
		$this->template = end(explode(DS, $gantry->templatePath));
		
		//$styles = '../templates/'.$this->template.'/styles.php';
		//if (file_exists($styles)) include_once($styles);
		//else return "No styles file found";
		$transparent = 1;

		if ($node->attributes('transparent') == 'false') $transparent = 0;
		
        if (!defined('GANTRY_CSS')) {
			gantry_addStyle($gantry->gantryUrl.'/admin/widgets/gantry.css');
			define('GANTRY_CSS', 1);
		}
		
		if (!defined('GANTRY_MOORAINBOW')) {
			
			gantry_addStyle($gantry->gantryUrl.'/admin/widgets/colorchooser/css/mooRainbow.css');
			gantry_addScript($gantry->gantryUrl.'/admin/widgets/colorchooser/js/mooRainbow.js');
			
			//$scriptconfig  = $this->populateStyles($stylesList);
			$scriptconfig = $this->rainbowInit();
			
			gantry_addInlineScript($scriptconfig);
			
			define('GANTRY_MOORAINBOW',1);
		}
	
		$scriptconfig = $this->newRainbow($name, $transparent);
		
		gantry_addInlineScript($scriptconfig);

		$output .= "<div class='wrapper'>";
		$output .= "<input class=\"picker-input text-color\" id=\"".$control_name.$name."\" name=\"".$control_name."[".$name."]\" type=\"text\" size=\"7\" maxlength=\"11\" value=\"".$value."\" />";
		$output .= "<div class=\"picker\" id=\"myRainbow_".$name."_input\"><div class=\"overlay".(($value == 'transparent') ? ' overlay-transparent' : '')."\" style=\"background-color: ".$value."\"><div></div></div></div>\n";
		$output .= "</div>";
		//$output = false;
		
		return $output;
	}
	
	function newRainbow($name, $transparent)
	{
        global $gantry;

        $name2 = str_replace("-", "_", $name);

		$mt = ($gantry->platform->jslib == 'mootools' && $gantry->platform->jslib_version == '1.1');
		$dollar = $mt ? "$" : "document.id";
		$getValue = $mt ? "getValue()" : "get('value')";

		return "
		var r_".$name2.";
		window.addEvent('domready', function() {
			$('params".$name."').getParent().addEvents({
				'mouseenter': f_".$name2.",
				'mouseleave': function(){
					this.removeEvent('mouseenter', f_".$name2.");
				}
			});
		});
		
		var f_".$name2." = function(){
			var input = ".$dollar."('params".$name."');
			r_".$name2." = new MooRainbow('myRainbow_".$name."_input', {
				id: 'myRainbow_".$name."',
				startColor: $('params".$name."').".$getValue.".hexToRgb(true) || [255, 255, 255],
				imgPath: '".$gantry->gantryUrl."/admin/widgets/colorchooser/images/',
				transparent: ".$transparent.",
				onChange: function(color) {
					if (color == 'transparent') {
						input.getNext().getFirst().addClass('overlay-transparent').setStyle('background-color', 'transparent');
						input.value = 'transparent';
					}
					else {
						input.getNext().getFirst().removeClass('overlay-transparent').setStyle('background-color', color.hex);
						input.value = color.hex;
					}
					
					if (this.visible) this.okButton.focus();
					
					if (Gantry.MenuItemHead) {
						var cache = Gantry.MenuItemHead.Cache[Gantry.Selection];
						if (!cache) cache = new Hash({});
						cache.set('".$name."', input.value.toString());
					}
				}
			});	
			
			r_".$name2.".okButton.setStyle('outline', 'none');
			".$dollar."('myRainbow_".$name."_input').addEvent('click', function() {
				(function() {r_".$name2.".okButton.focus()}).delay(10);
			});
			input.addEvent('keyup', function(e) {
				if (e) e = new Event(e);
				if ((this.value.length == 4 || this.value.length == 7) && this.value[0] == '#') {
					var rgb = new Color(this.value);
					var hex = this.value;
					var hsb = rgb.rgbToHsb();
					var color = {
						'hex': hex,
						'rgb': rgb,
						'hsb': hsb
					}
					r_".$name2.".fireEvent('onChange', color);
					r_".$name2.".manualSet(color.rgb);
				};
			}).addEvent('set', function(value) {
				this.value = value;
				this.fireEvent('keyup');
			});
			input.getNext().getFirst().setStyle('background-color', r_".$name2.".sets.hex);
			rainbowLoad('myRainbow_".$name."');
		};\n";
	}
	
	function populateStyles($list)
	{
		global $gantry;
		$mt = ($gantry->platform->jslib == 'mootools' && $gantry->platform->jslib_version == '1.1');
		$dollar = $mt ? "$" : "document.id";
		$getValue = $mt ? "getValue()" : "get('value')";
		
		$script = "
		var stylesList = new Hash({});
		var styleSelected = null;
		window.addEvent('domready', function() {
			styleSelected = ".$dollar."('paramspresetStyle').".$getValue.";
			".$dollar."('paramspresetStyle').empty();\n";
		
		reset($list);
		while ( list($name) = each($list) ) {
  			$style =& $list[$name];
			$js = "			stylesList.set('$name', ['{$style->pstyle}'";
			$js .= ", '{$style->bgstyle}'";
			$js .= ", '{$style->fontfamily}'";
			$js .= ", '{$style->linkcolor}'";
			$js .= "]);\n";
			$script .= $js;
		}
			
		$script .= "		});";
		
		return $script;
	}
	
	function rainbowInit()
	{
		global $gantry;
		$mt = ($gantry->platform->jslib == 'mootools' && $gantry->platform->jslib_version == '1.1');
		$dollar = $mt ? "$" : "document.id";
		$getValue = $mt ? "getValue()" : "get('value')";
		
		return "var rainbowLoad = function(name, hex) {
				if (hex) {
					var n = name.replace('params', '');
					".$dollar."(n+'_input').getPrevious().value = hex;
					".$dollar."(n+'_input').getFirst().setStyle('background-color', hex);
				}
			};
		";
	}
}

?>
/**
 * @version   3.1.10 March 5, 2011
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2011 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

var GantryBuildSpans=function(g,j,k){(g.length).times(function(i){var e="."+g[i];var f=function(a){a.setStyle('visibility','visible');var b=a.getText();var c=b.split(" ");first=c[0];rest=c.slice(1).join(" ");html=a.innerHTML;if(rest.length>0){var d=a.clone().setText(' '+rest),span=new Element('span').setText(first);span.inject(d,'top');a.replaceWith(d)}};$$(e).each(function(c){j.each(function(h){c.getElements(h).each(function(b){var a=b.getFirst();if(a&&a.getTag()=='a')f(a);else f(b)})})})})};
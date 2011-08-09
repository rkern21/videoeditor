<?php
/**
 * @version   3.1.10 March 5, 2011
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2011 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

class GantryAdminPageFormatter extends JEvent {
    public function onAfterRender(){
    
       if (!class_exists('phpQuery')) {
       		require_once(dirname(__FILE__)."/phpQuery.php");
       }
       $document = & JFactory::getDocument();
       $doctype = $document->getType();
		if ($doctype == 'html') {
			$body =& JResponse::getBody();
			$pq = phpQuery::newDocument($body);
			
			// adding id to reference table
			$table = pq('.adminform #master-bar');
			$table->parents('fieldset')->attr('id', 'params-table');
			
            // cleaning tables
			$empties = pq('table.paramlist tr');
			
			foreach($empties as $empty){
				$empty = pq($empty);
				$children = $empty->children();				
				if (count($children->elements) < 2 || (!strlen(pq($children->elements[1])->html()))) $empty->remove();
			}
			
			// adjusting diagnostic and versioncheck
			$exceptions = pq('#diagnostic, #versioncheck, #master-bar');
			
			foreach($exceptions as $exception){
				$exception = pq($exception);
				$id = $exception->attr('id');
				$exception->parent()->attr('colspan', 2)->attr('id', $id . '-wrapper')->prev()->remove();
			}
			
			// removing paramlist_value class from exceptions
			$exceptions = pq('td[colspan=2]');
			foreach($exceptions as $exception){
				$exception = pq($exception);
				$exception->removeClass('paramlist_value');
			}
			
			// Injecting notice box
			pq('div#toolbar-box')->after('<div class="clr"></div><dl id="system-message"><dt class="message"></dt><dd class="message message fade"><ul><li></li></ul></dd><span class="close"><span>x</span></span></dl>');
			pq('#mc-title')->after('<div class="clr"></div><dl id="system-message"><dt class="message"></dt><dd class="message message fade"><ul><li></li></ul></dd><span class="close"><span>x</span></span></dl>');
			
			// menuitemhead checkboxes
			$params = pq('#params-table td.paramlist_key');
			$i=0;
			foreach($params as $param){
				$i++;
				$param = pq($param);
				$next = $param->parent();
				$value = pq('td.paramlist_value > div.wrapper', $next);

				$param->prepend('<input class="mih-checkbox" type="checkbox" style="float:left; margin: -2px 5px 0; display: none;" value="0" />');
				$value->append('<div class="clr"></div><div class="menuitems-patch" style="background: #f6f6f6;opacity: 0.6;position: absolute;top: -7px;left: -10px;display: none;visibility: visible;z-index:180;"></div>');
			}

            pq('form[name=adminForm]')->append('<input type="hidden" name="model" value="template-save">');
            pq('form[name=adminForm]')->append('<input type="hidden" name="tmpl_old" value="gantry-ajax-admin">');
            pq('form[name=adminForm]')->append('<input type="hidden" name="action" value="save">');

			// removes RokBox from admin
			pq('script[src$=rokbox.js]')->remove();
			pq('script[src$=rokbox-mt1.2.js]')->remove();
			pq('link[href$=rokbox-style.css]')->remove();

			$body = $pq->getDocument()->htmlOuter();
	    	JResponse::setBody($body);
		}
    }

    public function onAfterDispatch(){
        global $gantry;
        foreach ($gantry->adminElements as $adminElement){
            call_user_func_array(array($adminElement,"runFinalize"),array($adminElement));
        }
    }
}

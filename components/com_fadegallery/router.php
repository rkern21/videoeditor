<?php
/**
 * FadeGallery Joomla! 1.5 Native Component
 * @version 1.2.5
 * @author DesignCompass corp< <admin@designcompasscorp.com>
 * @link http://www.designcompasscorp.com
 * @license GNU/GPL
 **/

// no direct access
defined('_JEXEC') or die('Restricted access');
function FadeGalleryBuildRoute(&$query) {

       $segments = array();
       if(isset($query['view']))
       {
                $segments[] = $query['view'];
                unset( $query['view'] );
       }

       return $segments;
	
}
function FadeGalleryParseRoute($segments) {

  $vars = array();
       switch($segments[0])
       {
               case 'gallery':
                       $vars['view'] = 'gallery';
                       break;
              
       }
       return $vars;


}
?>
<?php
/**
 *    @version [ Wainuiomata ]
 *    @package hwdVideoShare
 *    @copyright (C) 2007 - 2009 Highwood Design
 *    @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 ***
 *    This program is free software: you can redistribute it and/or modify
 *    it under the terms of the GNU General Public License as published by
 *    the Free Software Foundation, either version 3 of the License, or
 *    (at your option) any later version.
 *
 *    This program is distributed in the hope that it will be useful,
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *    GNU General Public License for more details.
 *
 *    You should have received a copy of the GNU General Public License
 *    along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

/**
 * ACL functions: original code from com_comprofiler
 *
 * @package    hwdVideoShare
 * @author     Dave Horsfall <info@highwooddesign.co.uk>
 * @copyright  2008 Highwood Design
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @version    1.1.4 Alpha RC2.13
 */
class hwd_vs_search
{
    /**
     * Query SQL for all accessible category data
     *
     * @return       Nothing
     */
	function search_split_terms($terms){
		$terms = preg_replace("/\"(.*?)\"/e", "hwd_vs_search::search_transform_term('\$1')", $terms);
		$terms = preg_split("/\s+|,/", $terms);
		$out = array();
		foreach($terms as $term){
		$term = preg_replace("/\{WHITESPACE-([0-9]+)\}/e", "chr(\$1)", $term);
		$term = preg_replace("/\{COMMA\}/", ",", $term);
		$out[] = $term;
		}
		return $out;
	}
	function search_transform_term($term){
		$term = preg_replace("/(\s)/e", "'{WHITESPACE-'.ord('\$1').'}'", $term);
		$term = preg_replace("/,/", "{COMMA}", $term);
		return $term;
	}
	function search_escape_rlike($string){
		$string = preg_replace("/([.\[\]*^\$])/", '\\\$1', $string);
		// 20110102 dhorsfall
		// remove parentheses
		$string = str_replace("(", "", $string);
		$string = str_replace(")", "", $string);
		return $string;
	}
	function search_db_escape_terms($terms){
		$out = array();
		foreach($terms as $term){
		$out[] = '[[:<:]]'.AddSlashes(hwd_vs_search::search_escape_rlike($term)).'[[:>:]]';
		// 20110119 dhorsfall
		// optional partial word search
		// $out[] = AddSlashes(hwd_vs_search::search_escape_rlike($term));
		}
		return $out;
	}
	function search_rx_escape_terms($terms){
		$out = array();
		foreach($terms as $term){
		$out[] = '\b'.preg_quote($term, '/').'\b';
		}
		return $out;
	}
	function search_sort_results($a, $b){
		$ax = $a[score];
		$bx = $b[score];
		if ($ax == $bx){ return 0; }
		return ($ax > $bx) ? -1 : 1;
	}
	function search_html_escape_terms($terms){
		$out = array();
		foreach($terms as $term){
		if (preg_match("/\s|,/", $term)){
		$out[] = '"'.HtmlSpecialChars($term).'"';
		}else{
		$out[] = HtmlSpecialChars($term);
		}
		}
		return $out;
	}
	function search_pretty_terms($terms_html){
		if (count($terms_html) == 1){
		return array_pop($terms_html);
		}
		$last = array_pop($terms_html);
		return implode(', ', $terms_html)." and $last";
	}
	function search_perform_videos($type="core",$pattern=null,$category_id=null)
	{
        global $mainframe, $limitstart, $hwdvs_joinv, $hwdvs_joing, $hwdvs_selectv, $hwdvs_selectg;
		$c = hwd_vs_Config::get_instance();
		$db = & JFactory::getDBO();
		$my = & JFactory::getUser();

        if (empty($pattern))
        {
        	$pattern = JRequest::getVar( 'pattern', '' );
        }
        if (empty($category_id))
        {
        	$category_id = JRequest::getInt( 'category_id', '0' );
        }

        $ep          = JRequest::getVar( 'ep', '' );
        $ex          = JRequest::getVar( 'ex', '' );

        $limitv     = intval($c->vpp);

		if ($c->search_method == 1)
		{
        	$pattern     = addslashes($pattern);
			$search_vcols = "";
			if ($c->search_title == "on")
			{
				$search_vcols.= "title";
			}
			if ($c->search_title == "on" && ($c->search_descr == "on" || $c->search_keywo == "on"))
			{
				$search_vcols.= ",";
			}
			if ($c->search_descr == "on")
			{
				$search_vcols.= "description";
			}
			if ($c->search_descr == "on" && $c->search_keywo == "on")
			{
				$search_vcols.= ",";
			}
			if ($c->search_keywo == "on")
			{
				$search_vcols.= "tags";
			}

			$terms = hwd_vs_search::search_split_terms($pattern);
			$ft_pattern = implode(" ", $terms);

			if (empty($ep) && empty($ex))
			{
				$wherevids = " WHERE MATCH ($search_vcols) AGAINST ('$ft_pattern')";
			}
			else
			{
				$bm = "";
				if (!empty($pattern))
				{
					foreach($terms as $term){
						$bm.= " +$term";
					};
				}
				if (!empty($ep))
				{
					$ep_terms = hwd_vs_search::search_split_terms($ep);
					$ft_ep = implode(" ", $ep_terms);
					$bm.= " \"$ft_ep\"";
				}
				if (!empty($ex))
				{
					$ex_terms = hwd_vs_search::search_split_terms($ex);
					foreach($ex_terms as $ex_term){
						$bm.= " -$ex_term";
					}
				}
				$wherevids = " WHERE MATCH ($search_vcols) AGAINST ('$bm' IN BOOLEAN MODE)";
			}
		}
		else
		{
			$terms = hwd_vs_search::search_split_terms($pattern);
			$terms_db = hwd_vs_search::search_db_escape_terms($terms);
			$parts_v = array();
			foreach($terms_db as $term_db)
			{
				$search_vcols = "";
				if ($c->search_title == "on")
				{
					$parts_v[] = "video.title RLIKE '$term_db'";
				}
				if ($c->search_descr == "on")
				{
					$parts_v[] = "video.description RLIKE '$term_db'";
				}
				if ($c->search_keywo == "on")
				{
					$parts_v[] = "video.tags RLIKE '$term_db'";
				}
			}
			$parts_v = implode(' OR ', $parts_v);

			$wherevids = " WHERE ($parts_v)";
		}

		if ($category_id > 0)
		{
			$wherevids.= " AND video.category_id = $category_id";
		}

		$wherevids.= " AND video.published = 1 AND video.approved = 'yes'";

		//echo $wherevids."<br>";
		return $wherevids;
	}
	function search_perform_groups($type="core",$pattern=null)
	{
        global $mainframe, $limitstart, $hwdvs_joinv, $hwdvs_joing, $hwdvs_selectv, $hwdvs_selectg;
		$c = hwd_vs_Config::get_instance();
		$db = & JFactory::getDBO();
		$my = & JFactory::getUser();

        if (empty($pattern))
        {
        	$pattern = JRequest::getVar( 'pattern', '' );
        }

        $pattern     = addslashes($pattern);
        $ep          = JRequest::getVar( 'ep', '' );
        $ex          = JRequest::getVar( 'ex', '' );

        $limitg     = intval($c->gpp);

		if ($c->search_method == 1)
		{
			$search_gcols = "";
			if ($c->search_title == "on")
			{
				$search_gcols.= "group_name";
			}
			if ($c->search_title == "on" && $c->search_descr == "on")
			{
				$search_gcols.= ",";
			}
			if ($c->search_descr == "on")
			{
				$search_gcols.= "group_description";
			}

			$terms = hwd_vs_search::search_split_terms($pattern);
			$ft_pattern = implode(" ", $terms);

			if (empty($ep) && empty($ex))
			{
				$wheregroups = " WHERE MATCH ($search_gcols) AGAINST ('$ft_pattern')";
			}
			else
			{
				$bm = "";
				if (!empty($pattern))
				{
					foreach($terms as $term){
						$bm.= " +$term";
					};
				}
				if (!empty($ep))
				{
					$ep_terms = hwd_vs_search::search_split_terms($ep);
					$ft_ep = implode(" ", $ep_terms);
					$bm.= " \"$ft_ep\"";
				}
				if (!empty($ex))
				{
					$ex_terms = hwd_vs_search::search_split_terms($ex);
					foreach($ex_terms as $ex_term){
						$bm.= " -$ex_term";
					}
				}
				$wheregroups = " WHERE MATCH ($search_gcols) AGAINST ('$bm' IN BOOLEAN MODE)";
			}
		}
		else
		{
			$terms = hwd_vs_search::search_split_terms($pattern);
			$terms_db = hwd_vs_search::search_db_escape_terms($terms);
			$parts_g = array();
			foreach($terms_db as $term_db)
			{
				$search_gcols = "";
				if ($c->search_title == "on")
				{
					$parts_g[] = "group_name RLIKE '$term_db'";
				}
				if ($c->search_descr == "on")
				{
					$parts_g[] = "group_description RLIKE '$term_db'";
				}
			}
			$parts_g = implode(' OR ', $parts_g);

			$wheregroups = " WHERE $parts_g";
		}

		$wheregroups.= " AND g.published = 1";

		//echo $wheregroups."<br>";
		return $wheregroups;
	}
}
?>
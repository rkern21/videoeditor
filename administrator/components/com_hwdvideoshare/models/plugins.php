<?php
/**
 *    @version [ Wainuiomata ]
 *    @package hwdVideoShare
 *    @copyright (C) 2007 - 2009 Highwood Design
 *    @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 ***
 *    Originally Joomla/Mambo Community Builder : Plugin Handler
 *    @package Community Builder
 *    @copyright (C) Beat and JoomlaJoe, www.joomlapolis.com and various
 *    @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU/GPL version 2
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

class hwdvids_BE_plugins
{
   /**
	* view the plugin page
	*/
	function plugins()
	{
		hwdvids_HTML::plugins();
		return true;
	}
   /**
	* view the plugin page
	*/
	function insertVideo()
	{
		global $limitstart;
		$eName	= JRequest::getVar('e_name');
		$eName	= preg_replace( '#[^A-Z0-9\-\_\[\]]#i', '', $eName );

		$db =& JFactory::getDBO();
		$my = & JFactory::getUser();
		$doc = & JFactory::getDocument();
		$doc->addStyleSheet( JURI::root().'plugins/editors-xtd/plug_hwd_vs_insertvideo.css', 'text/css', null, array() );

		$query = 'SELECT count(*) FROM #__hwdvidsvideos';
		$db->SetQuery($query);
		$total = $db->loadResult();

		$limit = 10;
		jimport('joomla.html.pagination');
		$pageNav = new JPagination( $total, $limitstart, $limit );

		// get matching video data
		$query = 'SELECT * FROM #__hwdvidsvideos ORDER BY date_uploaded DESC';
        $db->SetQuery($query, $pageNav->limitstart, $pageNav->limit);
		$rows = $db->loadObjectList();

		?>
		<script type="text/javascript">
			function insertVideo()
			{
				var video_style = document.getElementById("style").value;
				var video_id = document.getElementById("video_id").value;
				var video_width = document.getElementById("video_width").value;
				var video_height = document.getElementById("video_height").value;

				if (video_style == "videoPlayerWithDetails")
				  {
				  var tag = "\{hwdvs-player\}id="+video_id+"|width="+video_width+"|height="+video_height+"\{/hwdvs-player\}";
				  }
				else if (video_style == "videoPlayerOnly")
				  {
				  var tag = "\{hwdvs-player\}id="+video_id+"|width="+video_width+"|height="+video_height+"|tpl=playeronly\{/hwdvs-player\}";
				  }
				else if (video_style == "videoThumbnailLightbox")
				  {
				  var tag = "\{hwdvs-player\}id="+video_id+"|width="+video_width+"|height="+video_height+"|tpl=lightbox\{/hwdvs-player\}";
				  }
				window.parent.jInsertEditorText(tag, '<?php echo $eName; ?>');
				window.parent.document.getElementById('sbox-window').close();
				return false;
			}
			function insertVideoID(id)
			{
				document.getElementById('videoId').innerHTML = '<input type="text" id="video_id" value="'+id+'" name="video_id" />';
				return false;
			}
		</script>

		<table width="100%" cellpadding="2" cellspacing="2" border="0">
			<tr>
				<td width="150" style="vertical-align:top;width:150px;">
					<form>
					<table cellpadding="2" cellspacing="2" border="0">
						<tr>
							<td class="key">
								<label for="title">
									<?php echo JText::_( 'Style' ); ?>
								</label>
								<br />
								<select name="style" id="style">
									<option value="videoPlayerWithDetails">Video player (with details)</option>
									<option value="videoPlayerOnly">Video player (player only)</option>
									<option value="videoThumbnailLightbox">Video thumbnail (lightbox)</option>
								</select>
							</td>
						</tr>
						<tr>
							<td class="key">
								<label for="title">
									<?php echo JText::_( 'Video ID' ); ?>
								</label>
								<br />
								<div id="videoId"><input type="text" id="video_id" name="video_id" /></div>
							</td>
						</tr>
						<tr>
							<td class="key">
								<label for="alias">
									<?php echo JText::_( 'Video Width' ); ?>
								</label>
								<br />
								<input type="text" id="video_width" name="video_width" value="560" />
							</td>
						</tr>
						<tr>
							<td class="key">
								<label for="alias">
									<?php echo JText::_( 'Video Height' ); ?>
								</label>
								<br />
								<input type="text" id="video_height" name="video_height" value="340" />
							</td>
						</tr>
						<tr>
							<td>
								<button onclick="insertVideo();return false;"><?php echo JText::_( 'Insert Video' ); ?></button>
							</td>
						</tr>
					</table>
					</form>
				</td>
				<td>
				<div style="height:270px;overflow-y:scroll;">
				<?php
					for ($i=0, $n=count($rows); $i < $n; $i++)
					{
						$row = $rows[$i];

						$title     = hwd_vs_tools::generateVideoLink( $row->id, $row->title, null, "insertVideoID", 1000);
						$thumbnail = hwd_vs_tools::generateVideoThumbnailLink($row->id, $row->video_id, $row->video_type, $row->thumbnail, 0, "70", null, null, null, null, "insertVideoID", null, null, $row->video_length);

						echo "<div style=\"float:left;padding:0 5px 5px 0;\">$thumbnail</div>";
						echo "<div>$title</div>";
						echo "<div style=\"clear:both;\"></div>";
					}
				?>
				</div>
				</td>
			</tr>
		</table>
		<?php
	}
}
?>
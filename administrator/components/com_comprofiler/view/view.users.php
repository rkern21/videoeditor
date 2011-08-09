<?php
/**
* Joomla/Mambo Community Builder
* @version $Id: view.users.php 1385 2011-01-30 00:04:56Z beat $
* @package Community Builder
* @subpackage admin.comprofiler.php : users view
* @author Beat
* @copyright (C) Beat, www.joomlapolis.com
* @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU/GPL version 2
*/

// ensure this file is being included by a parent file
if ( ! ( defined( '_VALID_CB' ) || defined( '_JEXEC' ) || defined( '_VALID_MOS' ) ) ) { die( 'Direct Access to this location is not allowed.' ); }

class CBView_users {

	function _userslistFilters( $search, &$lists, $inputTextExtras, $searchTabContent, $hideAdvancedLink = false ) {
		if ( count( $searchTabContent ) > 0 ) {
			cbUsersList::outputAdvancedSearchJs( 'onlyactive' );
		}
?>
  <table cellpadding="4" cellspacing="0" border="0" width="100%">
    <tr>
      <td style="width:80%;"><?php echo CBTxt::T('Search'); ?>: <input type="text" name="search" value="<?php echo htmlspecialchars( $search );?>" class="inputbox" onChange="document.adminForm.submit();"<?php echo $inputTextExtras; ?> />
<?php
		if ( count( $searchTabContent ) > 0 && ! $hideAdvancedLink ) {
?>
			<span id="cbUserListsSearchTrigger"><a href="#"><?php echo CBTxt::Th('Advanced Search'); ?></a></span>
<?php
		}
?>
      </td>
<?php
		foreach ( $lists as $li ) {
?>
	  <td width="right">
		<?php echo $li;?>
	  </td>

<?php
		}
?>

    </tr>
  </table>
<?php
		if ( $searchTabContent ) {
			if ( strpos( $inputTextExtras, 'disabled="disabled"' ) === false ) {
?>
<div class="cbUsersList"><div id="cbUsersListInner">
	<div class="cbUserListHeadTitle">
		<div class="contentdescription cbUserListSearch" id="cbUserListsSearcher" style="display:none;">
			<button type="submit" class="cbAdvancedSearch"><?php echo CBTxt::T('Search'); ?></button>
			<div class="cbUserListSearchFields">
<?php
			echo $searchTabContent;
?>
				<div class="cbClr"></div>
			</div>
			<button type="submit" class="cbAdvancedSearch"><?php echo CBTxt::T('Search'); ?></button>
		</div>
	</div>
</div><div class="cbClr"> </div></div><div class="cbClr"> </div>

<?php
			} else {
				echo '<div style="display:none;">' . $searchTabContent . '</div>';
			}
		}
	}
	function _pluginRows( $pluginRows ) {
		foreach ( $pluginRows as $pluginOutput ) {
			if ( is_array( $pluginOutput ) ) {
				foreach ( $pluginOutput as $title => $content ) {
?>
    <tr>
      <td class="captionCell"><?php echo $title; ?>:</td>
      <td class="fieldCell">
         <?php echo $content;?>
      </td>
    </tr>

<?php
				}
			}
		}
	}

	function emailUsers( &$rows, &$total, $search, $option, &$lists, $cid, $inputTextExtras, $searchTabContent, $emailSubject, $emailBody, $emailsPerBatch, $emailPause, $simulationMode, $pluginRows ) {
		global $_CB_framework;

		_CBsecureAboveForm('showUsers');

		outputCbTemplate( 2 );
		outputCbJs( 2 );

		global $_CB_Backend_Title;
		$_CB_Backend_Title	=	array( 0 => array( 'cbicon-48-massmail', CBTxt::T('CB Email Users') ) );

		ob_start();

		cbimport( 'cb.validator' );
		cbValidator::renderGenericJs();
?>
$('div.cbtoolbarbar a.cbtoolbar').click( function() {
		var taskVal = $(this).attr('href').substring(1);

		$('#cbcheckedadminForm input[name=task]').val( taskVal );
		if (taskVal != 'startemailusers') {
			$('#cbcheckedadminForm')[0].submit();
		} else {
			$('#cbcheckedadminForm').submit();
		}
		return false;
	} );

<?php
			$cbjavascript	=	ob_get_contents();
			ob_end_clean();
			$_CB_framework->outputCbJQuery( $cbjavascript, array( 'metadata', 'validate' ) );

			// Save code for HTML editor:
			$jsSaveCode			=	$_CB_framework->saveCmsEditorJS( 'emailbody' );
			if ( $jsSaveCode ) {
	 			$js				=	"$('#" . 'emailbody' . "').parent('form').submit( function() { "
								.	$jsSaveCode
								.	" } );"
								;
				$_CB_framework->outputCbJQuery( $js );
			}

?>
<form action="<?php echo $_CB_framework->backendUrl( 'index.php' ); ?>" method="post" name="adminForm" id="cbcheckedadminForm" class="cb_form">
	<?php $this->_userslistFilters( $search, $lists, $inputTextExtras, $searchTabContent, true ); ?>
  <table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminlist">
   <thead>
    <tr>
      <th colspan="2" width="100%" class="title"><?php echo CBTxt::Th('CB Email Users'); ?></th>
    </tr>
   </thead>
   <tbody>
    <tr>
      <td width="15%" class="captionCell"><?php echo sprintf( CBTxt::Th('Send Email to %s users'), (int) $total ); ?>:</td>
      <td width="85%" class="fieldCell">
         <?php
         	$displayMax					=	100;
         	$i							=	$displayMax;
         	$usermails					=	array();
         	foreach ( $rows as $row ) {
         		$usermails[]			=	htmlspecialchars( $row->name ) . ' &lt;' . htmlspecialchars( $row->email ) . '&gt;';
         		if ( --$i == 0 ) {
         			if ( count( $rows ) > $displayMax ) {
         				$usermails[]	=	'<strong>' . sprintf( CBTxt::Th('and %s more users.'), (int) ( $total - $displayMax ) ) . '</strong>';
         			}
         			break;
         		}
         	}
         	echo implode( ', ', $usermails);
         	unset( $usermails );
         ?>
         <br /><br />
      </td>
    </tr>
    <tr>
      <td class="captionCell"><?php echo CBTxt::Th('Simulation mode'); ?>:</td>
      <td class="fieldCell">
         <input type="checkbox" name="simulationmode" id="simulationmode"<?php if ( $simulationMode ) echo ' checked="checked"'; ?>" /> <label for="simulationmode"><?php echo CBTxt::Th('Do not send emails, just show me how it works'); ?></label>
      </td>
    </tr>
    <tr>
      <td class="captionCell"><?php echo CBTxt::Th('Email Subject'); ?>:</td>
      <td class="fieldCell">
         <input type="text" name="emailsubject" value="<?php echo htmlspecialchars( $emailSubject );?>" class="inputbox required" size="60" />
      </td>
    </tr>
    <tr>
      <td class="captionCell"><?php echo CBTxt::Th('Email Message'); ?>:</td>
      <td class="fieldCell">
         <?php echo $_CB_framework->displayCmsEditor( 'emailbody', $emailBody, 600, 200, 50, 7 ); ?>
      </td>
    </tr>
    <tr>
      <td class="captionCell"><?php echo CBTxt::Th('CB substitutions for subject and message'); ?>:</td>
      <td class="fieldCell">
         <?php echo CBTxt::Th('You can use all CB substitutions as in most parts: e.g.: [cb:if team="winners"] Congratulations [cb:userfield field="name" /], you are in the winning team! [/cb:if]');?>
      </td>
    </tr>
         <?php $this->_pluginRows( $pluginRows ); ?>
    <tr>
      <td class="captionCell"><?php echo CBTxt::Th('Emails per batch'); ?>:</td>
      <td class="fieldCell">
         <input type="text" name="emailsperbatch" value="<?php echo htmlspecialchars( $emailsPerBatch );?>" class="inputbox required digits" size="12" />
      </td>
    </tr>
    <tr>
      <td class="captionCell"><?php echo CBTxt::Th('Seconds of pause between batches'); ?>:</td>
      <td class="fieldCell">
         <input type="text" name="emailpause" value="<?php echo htmlspecialchars( $emailPause );?>" class="inputbox required digits" size="12" />
      </td>
    </tr>
   </tbody>
   <tfoot>
    <tr>
      <th align="center" colspan="2"></th>
    </tr>
   </tfoot>
  </table>

  <input type="hidden" name="option" value="<?php echo $option;?>" />
  <input type="hidden" name="task" value="emailusers" />
  <input type="hidden" name="boxchecked" value="0" />
  <?php
  	if ( is_array( $cid ) && count( $cid ) ) {
  		foreach  ($cid as $uid ) {
  			echo '<input type="hidden" name="cid[]" value="' . (int) $uid . '">';
  		}
  	}
	echo cbGetSpoofInputTag( 'user' );
  ?>
</form>

<?php }

	function startEmailUsers( &$rows, $search, $option, &$lists, $cid, $inputTextExtras, $searchTabContent, $emailSubject, $emailBody, $emailsPerBatch, $emailPause, $total, $simulationMode, $pluginRows ) {
		global $_CB_framework;

		_CBsecureAboveForm('showUsers');

		outputCbTemplate( 2 );
		outputCbJs( 2 );

		global $_CB_Backend_Title;
		$_CB_Backend_Title			=	array( 0 => array( 'cbicon-48-massmail', CBTxt::T('CB Sending emails to users...please wait and do not interrupt!') ) );

		if ( $total > $emailsPerBatch ) {
			$textDuringExecution	=	sprintf( CBTxt::T('Sending a batch of maximum %s emails...'), $emailsPerBatch );
		} else {
			$textDuringExecution	=	sprintf( CBTxt::T('Sending now %s emails...'), $total );
		}
		if ( $total == 1 ) {
			$textWhenDone			=	CBTxt::T('Sent your email.');
		} else {
			$textWhenDone			=	sprintf( CBTxt::T('Sent all %s emails.'), $total );
		}
		$vars	=	array( 'emailsubject' => $emailSubject, 'emailbody' => $emailBody, 'limit' => $emailsPerBatch, 'emailpause' => $emailPause, 'simulationmode' => $simulationMode );
	  	if ( is_array( $cid ) && count( $cid ) ) {
	  		$vars['cid']		=	$cid;
	  	}
		//$this->_cbadmin_ajaxContent( $_CB_framework->backendUrl( 'index.php?option=' . $option . '&task=ajaxemailusers', false, 'raw' ), '#cbProgressIndicator', $vars );
		// $this->_cbadmin_ajaxBatch( $_CB_framework->backendUrl( 'index.php?option=' . $option . '&task=ajaxemailusers', false, 'raw' ), '#cbProgressIndicator', $vars, $emailPause, 0, $emailsPerBatch, $textDuringExecution, $textWhenDone, '.cbicon-48-massmail', $textWhenDone );
		$this->_cbadmin_ajaxBatch( $_CB_framework->backendUrl( 'index.php?option=' . $option, false, 'raw' ), '#cbProgressIndicator', '#cbmailbatchform', $vars, $emailPause, 0, $emailsPerBatch, $textDuringExecution, $textWhenDone, '.cbicon-48-massmail', $textWhenDone );
?>
<form action="<?php echo $_CB_framework->backendUrl( 'index.php' ); ?>" method="post" name="adminForm" class="cb_form" id="cbmailbatchform">
	<?php $this->_userslistFilters( $search, $lists, $inputTextExtras, $searchTabContent ); ?>
  <table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminlist">
   <thead>
    <tr>
      <th colspan="2" width="100%" class="title"><?php echo CBTxt::T('CB Sending emails to users'); ?></th>
    </tr>
   </thead>
   <tbody>
   <?php if ( $simulationMode ) { ?>
    <tr>
      <td class="captionCell"><?php echo CBTxt::Th('Simulation mode'); ?>:</td>
      <td class="fieldCell">
         <input disabled="disabled" type="checkbox" name="simulationmode"<?php if ( $simulationMode ) echo ' checked="checked"'?>" /> <label for="simulationmode"><?php echo CBTxt::Th('Do not send emails, just show me how it works'); ?></label>
      </td>
    </tr>
    <?php } ?>
<?php $this->_pluginRows( $pluginRows ); ?>
    <tr>
      <td width="15%" class="captionCell"><?php echo sprintf( CBTxt::T('Sending Email to %s users'), (int) $total ); ?>:</td>
      <td width="85%" class="fieldCell">
      	<div id="cbProgressIndicatorBar" style="width:600px; height:16px;border:1px black solid;"><span style="z-index:10;position:absolute;"><?php echo CBTxt::T('Initiating...'); ?></span><div style="background-color:#8F8;width:0px;height:100%;overflow:hidden;"></div></div>
      	<div id="cbProgressIndicator" style="width:600px; min-height:300px;"></div>
      </td>
    </tr>
    <?php $this->_pluginRows( $pluginRows ); ?>
   </tbody>
   <tfoot>
    <tr>
      <th align="center" colspan="2"></th>
    </tr>
   </tfoot>
  </table>

   <?php if ( ! $simulationMode ) { ?>
  <input type="hidden" name="simulationmode" value="<?php echo htmlspecialchars( $simulationMode ); ?>" />
  <?php } ?>
  <input type="hidden" name="option" value="<?php echo $option;?>" />
  <input type="hidden" name="task" value="ajaxemailusers" />
  <input type="hidden" name="boxchecked" value="0" />
  <?php
  	if ( is_array( $cid ) && count( $cid ) ) {
  		foreach  ($cid as $uid ) {
  			echo '<input type="hidden" name="cid[]" value="' . (int) $uid . '">';
  		}
  	}
	echo cbGetSpoofInputTag( 'user' );
  ?>
</form>

<?php }

	function ajaxResults( $usernames, $emailSubject, $emailBody, $limitstart, $limit, $total ) {
		global $_CB_framework;

		?>
<h3><?php echo sprintf( CBTxt::T('Sent email to %s of %s users'), min( $total, $limitstart + $limit ), $total ); ?></h3>
<h4><?php echo sprintf( CBTxt::T('Just sent %s emails to following users:'), min( $limit, $total - $limitstart ) ); ?></h4>
<div><?php echo $usernames; ?></div>
<h3><?php
		if ( $total - ( $limitstart + $limit ) > 0 ) {
			echo sprintf( CBTxt::T('Still %s emails remaining to send.'), $total - ( $limitstart + $limit ) );
		} else {
			if ( $total == 1 ) {
				echo CBTxt::T('Your email has been sent.');
			} else {
				echo sprintf( CBTxt::T('All %s emails have been sent.'), $total );
			}
		} ?></h3>
<?php

		if ( ! ( $total - ( $limitstart + $limit ) > 0 ) ) {
?>
  <table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminlist">
   <thead>
    <tr>
      <th colspan="2" width="100%" class="title"><?php echo CBTxt::T('Email Sent'); ?></th>
    </tr>
   </thead>
   <tbody>
    <tr>
      <td width="15%" class="captionCell"><?php echo CBTxt::T('Email Subject'); ?>:</td>
      <td width="85%" class="fieldCell">
         <?php echo htmlspecialchars( $emailSubject );?>
      </td>
    </tr>
    <tr>
      <td class="captionCell"><?php echo CBTxt::T('Email Message'); ?>:</td>
      <td class="fieldCell">
         <?php echo $emailBody; ?>
      </td>
    </tr>
   </tbody>
   <tfoot>
    <tr>
      <th align="center" colspan="2"></th>
    </tr>
   </tfoot>
  </table>

<h3><a href="<?php echo $_CB_framework->backendUrl( 'index.php?option=com_comprofiler&task=showusers' ); ?>"><?php echo CBTxt::Th('Click here to go back to users management'); ?></a></h3>

<?php			
		}
	}

	function showUsers( &$rows, &$pageNav, $search, $option, &$lists, &$pluginColumns, $inputTextExtras, $searchTabContent ) {
		global $_CB_framework;

		_CBsecureAboveForm('showUsers');

		outputCbTemplate( 2 );
		outputCbJs( 2 );

		global $_CB_Backend_Title;
		$_CB_Backend_Title	=	array( 0 => array( 'cbicon-48-user', CBTxt::T('CB User Manager') ) );


/*
 * 		Auto-submission was a pain: added 2 buttons in advanced search.
		ob_start();
$('#cbUserListsSearcher select,#cbUserListsSearcher input,#cbUserListsSearcher textarea').live('change', function() {
	if ( $(this).parent('div').hasClass('cbSearchKind') ) {
		if ( $(this).val() == '' ) {
			$(this).parents('form')[0].submit();
		}
	} else {
		$(this).parents('form')[0].submit();
	}
});

			$cbjavascript	=	ob_get_contents();
			ob_end_clean();
			$_CB_framework->outputCbJQuery( $cbjavascript );
*/
			$_CB_framework->outputCbJQuery( '' );

			$colspans			=	13 + count( $pluginColumns );
?>
<form action="<?php echo $_CB_framework->backendUrl( 'index.php' ); ?>" method="post" name="adminForm" class="cb_form" id="cbshowusersform">
<?php
		$this->_userslistFilters( $search, $lists, $inputTextExtras, $searchTabContent );
?>
  <table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminlist">
   <thead>
    <tr>
      <th align="center" colspan="<?php echo $colspans; ?>"> <?php echo $pageNav->writePagesLinks(); ?></th>
    </tr>
    <tr>
      <th width="1%" class="title"><?php echo CBTxt::T('#'); ?></th>
      <th width="3%" class="title"> <input type="checkbox" name="toggle" value="" <?php echo 'onClick="checkAll(' . count($rows) . ');"'; ?> />
      </th>
      <th width="15%" class="title"><?php echo CBTxt::T('Name'); ?></th>
      <th width="10%" class="title"><?php echo CBTxt::T('UserName'); ?></th>
      <th width="5%" class="title" nowrap="nowrap"><?php echo CBTxt::T('Logged In'); ?></th>
<?php
		foreach ( $pluginColumns as $name => $content ) {
?>
	  <th width="15%" class="title"><?php echo $name; ?></th>

<?php
		}
?>
      <th width="15%" class="title"><?php echo CBTxt::T('Group'); ?></th>
      <th width="15%" class="title"><?php echo CBTxt::T('E-Mail'); ?></th>
      <th width="10%" class="title"><?php echo CBTxt::T('Registered'); ?></th>
      <th width="10%" class="title" nowrap="nowrap"><?php echo CBTxt::T('Last Visit'); ?></th>
      <th width="5%" class="title"><?php echo CBTxt::T('Enabled'); ?></th>
      <th width="5%" class="title"><?php echo CBTxt::T('Confirmed'); ?></th>
      <th width="5%" class="title"><?php echo CBTxt::T('Approved'); ?></th>
      <th width="1%" class="title"><?php echo CBTxt::T('ID'); ?></th>
    </tr>
   </thead>
   <tbody>
<?php
		$k = 0;
		$imgpath='../components/com_comprofiler/images/';
		for ($i=0, $n=count( $rows ); $i < $n; $i++) {
			$row =& $rows[$i];
			$img = $row->block ? 'publish_x.png' : 'tick.png';
			$task = $row->block ? 'unblock' : 'block';
			$hover1 = $row->block ? CBTxt::T('Blocked') : CBTxt::T('Enabled');

			switch ($row->approved) {
				case 0:
	        		$img2 = 'pending.png';
	        		$task2 = 'approve';
					$hover = CBTxt::T('Pending Approval');
				break;
				case 1:
	        		$img2 = 'tick.png';
	        		$task2 = 'reject';
					$hover = CBTxt::T('Approved');
				break;
				case 2:
	        		$img2 = 'publish_x.png';
	        		$task2 = 'approve';
					$hover = CBTxt::T('Rejected');
				break;

			}

		        $img3 = $row->confirmed ?  'tick.png' : 'publish_x.png';
		        // $task3 = $row->confirmed ?   'reject' : 'approve';
		        $hover3 = $row->confirmed ?   CBTxt::T('confirmed') : CBTxt::T('unconfirmed');

?>
    <tr class="<?php echo "row$k"; ?>">
      <td><?php echo $i+1+$pageNav->limitstart;?></td>
      <td><input type="checkbox" id="cb<?php echo $i;?>" name="cid[]" value="<?php echo $row->id; ?>" onClick="isChecked(this.checked);" /></td>
      <td> <a href="#edit" onClick="return listItemTask('cb<?php echo $i;?>','edit')">
        <?php echo $row->name; ?> </a> </td>
      <td><?php echo $row->username; ?></td>
      <td align="center"><?php echo $row->loggedin ? '<img src="' . $imgpath . 'tick.png" width="16" height="16" border="0" alt="" />': ''; ?></td>
<?php
		foreach ( $pluginColumns as $name => $content ) {
?>
	  <td><?php echo $content[$row->id]; ?></td>

<?php
		}
?>
      <td><?php echo $row->groupname; ?></td>
      <td><a href="mailto:<?php echo htmlspecialchars( $row->email ); ?>"><?php echo htmlspecialchars( $row->email ); ?></a></td>
      <td><?php echo cbFormatDate( $row->registerDate ); ?></td>
      <td><?php echo cbFormatDate( $row->lastvisitDate ); ?></td>
      <td width="10%"><a href="javascript: void(0);" onClick="return listItemTask('cb<?php echo $i;?>','<?php echo $task;?>')"><img src="<?php echo $imgpath.$img;?>" width="16" height="16" border="0" title="<?php echo $hover1; ?>" alt="<?php echo $hover1; ?>" /></a></td>
      <td width="10%"><img src="<?php echo $imgpath.$img3;?>" width="16" height="16" border="0" title="<?php echo $hover3; ?>" alt="<?php echo $hover3; ?>" /></td>
      <td width="10%"><a href="javascript: void(0);" onClick="return listItemTask('cb<?php echo $i;?>','<?php echo $task2;?>')"><img src="<?php echo $imgpath.$img2;?>" width="16" height="16" border="0" title="<?php echo $hover; ?>" alt="<?php echo $hover; ?>" /></a></td>
      <td><?php echo $row->id; ?></td>

    </tr>
    <?php $k = 1 - $k;
		}
		?>
   </tbody>
   <tfoot>
    <tr>
      <th align="center" colspan="<?php echo $colspans; ?>"> <?php echo $pageNav->getListFooter(); ?></th>
    </tr>
   </tfoot>
  </table>
  <input type="hidden" name="option" value="<?php echo $option;?>" />
  <input type="hidden" name="task" value="showusers" />
  <input type="hidden" name="boxchecked" value="0" />
  <?php
	echo cbGetSpoofInputTag( 'user' );
  ?>
</form>
<?php }
/*
	function _cbadmin_ajaxContent( $ajaxUrl, $cssSelectorReply, $postArray ) {
			global $_CB_framework;
	
			$ajaxUrl				=	addslashes( $ajaxUrl );
			$cbSpoofField			=	cbSpoofField();
			$cbSpoofString			=	cbSpoofString( null, 'cbadmingui' );
			$regAntiSpamFieldName	=	cbGetRegAntiSpamFieldName();
			$regAntiSpamValues		=	cbGetRegAntiSpams();
			cbGetRegAntiSpamInputTag( $regAntiSpamValues );		// sets the cookie
			$regAntiSpZ				=	$regAntiSpamValues[0];
	
			$postString				=	'';
			foreach ($postArray as $k => $v ) {
				$postString			.=	'&' . urlencode( $k ) . '=' . urlencode( $v );
			}
			$postString				=	addslashes( $postString );
			//$errorText				=	addslashes( $errorText );
	
			$_CB_framework->outputCbJQuery( <<<EOT
		$.ajax( {	type: 'POST',
					url:  '$ajaxUrl',
					data: '$cbSpoofField=' + encodeURIComponent('$cbSpoofString') + '&$regAntiSpamFieldName=' + encodeURIComponent('$regAntiSpZ') + '$postString',
					success: function(response) {
						$('$cssSelectorReply').hide().html(response).fadeIn('fast');
					},
					error: function (XMLHttpRequest, textStatus, errorThrown) {
						$('$cssSelectorReply').hide().html(errorThrown ? errorThrown : textStatus).fadeIn('fast');
					},
					dataType: 'html'
		});
EOT
			);
	}
*/
	function _cbadmin_ajaxBatch( $ajaxUrl, $cssSelectorReply, $formSelector, $postArray, $delay, $limitstart = 0, $limit = 30, $textDuringExecution = null, $textWhenDone = null, $cssSelectorTitle, $titleTextWhenDone ) {
			global $_CB_framework;
	
			$ajaxUrl				=	addslashes( $ajaxUrl );
			$cbSpoofField			=	cbSpoofField();
			$cbSpoofString			=	cbSpoofString( null, 'cbadmingui' );
			$regAntiSpamFieldName	=	cbGetRegAntiSpamFieldName();
			$regAntiSpamValues		=	cbGetRegAntiSpams();
			cbGetRegAntiSpamInputTag( $regAntiSpamValues );		// sets the cookie
			$regAntiSpZ				=	$regAntiSpamValues[0];
	
			$postString				=	'';
			foreach ($postArray as $k => $v ) {
				if ( is_array( $v ) ) {
					foreach ($v as $vv ) {
						$postString	.=	'&' . urlencode( $k ) . '[]=' . urlencode( $vv );
					}
				} else {
					$postString		.=	'&' . urlencode( $k ) . '=' . urlencode( $v );
				}
			}
			$postString				=	addslashes( $postString );
			//$errorText				=	addslashes( $errorText );
	
			$textWaiting			=	addslashes( CBTxt::T('Waiting delay for next batch...') );
			$textExecuting			=	addslashes( $textDuringExecution ? $textDuringExecution : CBTxt::T('Executing') );
			$textFinished			=	addslashes( $textWhenDone ? $textWhenDone : CBTxt::T('Done') );
			$textError				=	addslashes( CBTxt::T('ERROR!') );
	
			$titleTextWhenDone		=	addslashes( $titleTextWhenDone );
	
			$_CB_framework->outputCbJQuery( <<<EOT
	{
		var cbanimate = function() {
			$(this).animate({width:'100%'},20000,function(){
				$(this).animate({width:'0%'},1000,cbanimate);
			});
		};
		var cbajaxjsonbatch = function(limitstart,limit,successFnct){
			$.ajax( {	type: 'POST',
						url:  '$ajaxUrl',
						data: $('$formSelector').serialize() + '&$cbSpoofField=' + encodeURIComponent('$cbSpoofString') + '&$regAntiSpamFieldName=' + encodeURIComponent('$regAntiSpZ') + '$postString' + '&limitstart=' + limitstart,
						success: function(response) {
							$('$cssSelectorReply'+'Bar div').stop().animate( {width:'100%'},500).animate( {width:'0%'},200, function() { $(this).css({"background-color":"#8f8"}) });
							$('$cssSelectorReply').fadeOut(400, function() {
								$(this).html(response.htmlcontent).fadeIn(400, function() {
									if ( response.result == 1 ) {
									$(this).each( function() {
										$('$cssSelectorReply'+'Bar span').html('$textWaiting')
										.siblings('div').animate( {width:'100%'},$delay*1000,'linear', function() {
											$(this).animate( {width:'0%'},200, function() {
												cbajaxjsonbatch(limitstart+limit,limit,successFnct);
											});
										});
									});
									} else if ( response.result == 2 ) {
										$('$cssSelectorReply'+'Bar span').html('$textFinished');
										if (successFnct) {
											successFnct.call(response);
										}
									} else {
										$('$cssSelectorReply'+'Bar span').html('$textError')
										.siblings('div').css({"background-color":"#fcc"});
									}
								});
							})
						},
						error: function (XMLHttpRequest, textStatus, errorThrown) {
							$('$cssSelectorReply'+'Bar div').stop().animate( {width:'100%'},500).css({"background-color":"#f87"});
							$('$cssSelectorReply'+'Bar span').html('$textError');
							$('$cssSelectorReply').hide().html( ( errorThrown ? errorThrown : textStatus ? textStatus : 'No additional message' ).replace(/&/g,"&amp;").replace(/</g,"&lt;").replace(/>/g,"&gt;") ).fadeIn('fast');
						},
						dataType: 'json'
			});
			$('$cssSelectorReply'+'Bar span').html('$textExecuting')
			.siblings('div').css({"background-color":"#ee8"}).each(cbanimate);
		};
		
		var cbTitleSetDone = function() {
			$('$cssSelectorTitle').html('$titleTextWhenDone');
		};
			cbajaxjsonbatch($limitstart,$limit,cbTitleSetDone);
	}
EOT
			);
	}

}	// class CBView_users

?>
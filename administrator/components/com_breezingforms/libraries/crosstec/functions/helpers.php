<?php
defined('_JEXEC') or die('Direct Access to this location is not allowed.');

/**
 * Mail creator as expected by former FacileForms code
 * This is a not really Legacy, so it stays like that
 *
 * @param string $from
 * @param string $fromname
 * @param string $subject
 * @param string $body
 * @return JMail
 */

function bf_getFieldSelectorList($form_id, $element_target_id){
    $db = JFactory::getDBO();
    $db->setQuery("Select `name` From #__facileforms_elements Where form = " . intval($form_id) . " And `name` Not In ('bfFakeName','bfFakeName2','bfFakeName3','bfFakeName4','bfFakeName5','bfFakeName6') Order by `ordering`");
    $rows = $db->loadResultArray();
    $out = '<script type="text/javascript">
    function insertAtCursor_'.$element_target_id.'(myValue) {
var myField = document.getElementById("'.$element_target_id.'");
//IE support
if (document.selection) {
myField.focus();
sel = document.selection.createRange();
sel.text = myValue;
}
//MOZILLA/NETSCAPE support
else if (myField.selectionStart || myField.selectionStart == \'0\') {
var startPos = myField.selectionStart;
var endPos = myField.selectionEnd;
myField.value = myField.value.substring(0, startPos)
+ myValue
+ myField.value.substring(endPos, myField.value.length);
} else {
myField.value += myValue;
}
}

    </script>';
    foreach($rows As $row){
        $out .= '<a href="javascript: insertAtCursor_'.$element_target_id.'(\'{'.$row.':label}\');void(0);">{'.$row.':label}</a><br/>';
        $out .= '<a href="javascript: insertAtCursor_'.$element_target_id.'(\'{'.$row.':value}\');void(0);">{'.$row.':value}</a><br/><br/>';
    }
    return $out;
}

function bf_ToolTip( $tooltip, $title='', $width='', $image='tooltip.png', $text='', $href='', $link=1 )
{
	// Initialize the toolips if required
	static $init;
	if ( ! $init )
	{
		JHTML::_('behavior.tooltip');
		$init = true;
	}

	return JHTML::_('tooltip', $tooltip, $title, $image, $text, $href, $link);
}

// used if copy is disabled
function bf_copy($file1,$file2){
	$contentx =@file_get_contents($file1);
	$openedfile = @fopen($file2, "w");
	@fwrite($openedfile, $contentx);
	@fclose($openedfile);
	if ($contentx === FALSE) {
		$status=false;
	}else $status=true;
	 
	return $status;
}
    
function bf_createMail( $from='', $fromname='', $subject, $body ) {

	$mail =& JFactory::getMailer();

	$mail->From 	= $from ? $from : $mail->From;
	$mail->FromName = $fromname ? $fromname : $mail->FromName;
	$mail->Subject 	= $subject;
	$mail->Body 	= $body;

	return $mail;
}

function bf_sendNotificationBySession($session){
	
	$contents = JFactory::getSession()->get($session, array());

	if(count($contents) != 0){
		
		$from = $contents['from'];
		$fromname = $contents['fromname'];
		$recipient = $contents['recipients'];
		$subject = $contents['subject'];
		$body = $contents['body'];
		$attachment = $contents['attachment'];
		$html = $contents['isHtml'];

                if((is_array($recipient) && count($recipient) != 0) || ( !is_array($recipient) && $recipient != '' )){

                    $mail = bf_createMail($from, $fromname, $subject, $body);
                    if (is_array($recipient))
                    foreach ($recipient as $to) $mail->AddAddress($to);
                    else
                    $mail->AddAddress($recipient);

                    if ($attachment) {
                            if ( is_array($attachment) )
                            foreach ($attachment as $fname) $mail->AddAttachment($fname);
                            else
                            $mail->AddAttachment($attachment);
                    } // if

                    if (isset($html)) $mail->IsHTML($html);

                    $mail->Send();
                }
	}
	
	JFactory::getSession()->set($session, array());
}

function bf_sendNotificationByPaymentCache($formId, $recordId, $type = 'admin'){

        $contents = array();
        $sourcePath = JPATH_SITE . '/administrator/components/com_breezingforms/payment_cache/';
        if (@file_exists($sourcePath) && @is_readable($sourcePath) && @is_dir($sourcePath) && $handle = @opendir($sourcePath)) {
            while (false !== ($file = @readdir($handle))) {
                if($file!="." && $file!="..") {
                    $parts = explode('_', $file);
                    if(count($parts)==4) {
                        if($parts[0] == intval($formId) && $parts[1] == intval($recordId) && $parts[2] == $type) {
                            $contents = unserialize(JFile::read($sourcePath.$file));
                            JFile::delete($sourcePath.$file);
                            break;
                        }
                    }
                }
            }
            @closedir($handle);
        }

	if(count($contents) != 0){

		$from = $contents['from'];
		$fromname = $contents['fromname'];
		$recipient = $contents['recipients'];
		$subject = $contents['subject'];
		$body = $contents['body'];
		$attachment = $contents['attachment'];
		$html = $contents['isHtml'];

                if((is_array($recipient) && count($recipient) != 0) || ( !is_array($recipient) && $recipient != '' )){

                    $mail = bf_createMail($from, $fromname, $subject, $body);
                    if (is_array($recipient))
                    foreach ($recipient as $to) $mail->AddAddress($to);
                    else
                    $mail->AddAddress($recipient);

                    if ($attachment) {
                            if ( is_array($attachment) )
                            foreach ($attachment as $fname) $mail->AddAttachment($fname);
                            else
                            $mail->AddAttachment($attachment);
                    } // if

                    if (isset($html)) $mail->IsHTML($html);

                    $mail->Send();
                }
	}
}

/**
 * The name says it all
 *
 * @param string $string
 * @return boolean
 */
function bf_isUTF8($string) {
	if (is_array($string))
	{
		$enc = implode('', $string);
		return @!((ord($enc[0]) != 239) && (ord($enc[1]) != 187) && (ord($enc[2]) != 191));
	}
	else
	{
		return (utf8_encode(utf8_decode($string)) == $string);
	}
}

/**
 * The classic recursive slash remover
 *
 * @param string $value raw
 * @return string cleaned
 */
function bf_stripslashes_deep($value)
{
	if(get_magic_quotes_gpc()) {
		$value = is_array($value) ?
		array_map('bf_stripslashes_deep', $value) :
		stripslashes($value);
	}

	return $value;
}

function bf_is_email ($email, $checkDNS = false) {
	//      Check that $email is a valid address
	//              (http://tools.ietf.org/html/rfc3696)
	//              (http://tools.ietf.org/html/rfc2822)
	//              (http://tools.ietf.org/html/rfc5322#section-3.4.1)
	//              (http://tools.ietf.org/html/rfc5321#section-4.1.3)
	//              (http://tools.ietf.org/html/rfc4291#section-2.2)
	//              (http://tools.ietf.org/html/rfc1123#section-2.1)

	//      the upper limit on address lengths should normally be considered to be 256
	//              (http://www.rfc-editor.org/errata_search.php?rfc=3696)
	if (strlen($email) > 256)       return false;   //      Too long

	//      Contemporary email addresses consist of a "local part" separated from
	//      a "domain part" (a fully-qualified domain name) by an at-sign ("@").
	//              (http://tools.ietf.org/html/rfc3696#section-3)
	$index = strrpos($email,'@');

	if ($index === false)           return false;   //      No at-sign
	if ($index === 0)                       return false;   //      No local part
	if ($index > 64)                        return false;   //      Local part too long

	$localPart              = substr($email, 0, $index);
	$domain                 = substr($email, $index + 1);
	$domainLength   = strlen($domain);

	if ($domainLength === 0)        return false;   //      No domain part
	if ($domainLength > 255)        return false;   //      Domain part too long

	//      Let's check the local part for RFC compliance...
	//
	//      local-part      =       dot-atom / quoted-string / obs-local-part
	//      obs-local-part  =       word *("." word)
	//              (http://tools.ietf.org/html/rfc2822#section-3.4.1)
	if (preg_match('/^"(?:.)*"$/', $localPart) > 0) {
		$dotArray[]     = $localPart;
	} else {
		$dotArray       = explode('.', $localPart);
	}

	foreach ($dotArray as $localElement) {
		//      Period (".") may...appear, but may not be used to start or end the
		//      local part, nor may two or more consecutive periods appear.
		//              (http://tools.ietf.org/html/rfc3696#section-3)
		//
		//      A zero-length element implies a period at the beginning or end of the
		//      local part, or two periods together. Either way it's not allowed.
		if ($localElement === '')                                                                               return false;   //      Dots in wrong place

		//      Each dot-delimited component can be an atom or a quoted string
		//      (because of the obs-local-part provision)
		if (preg_match('/^"(?:.)*"$/', $localElement) > 0) {
			//      Quoted-string tests:
			//
			//      Note that since quoted-pair
			//      is allowed in a quoted-string, the quote and backslash characters may
			//      appear in a quoted-string so long as they appear as a quoted-pair.
			//              (http://tools.ietf.org/html/rfc2822#section-3.2.5)
			$groupCount     = preg_match_all('/(?:^"|"$|\\\\\\\\|\\\\")|(\\\\|")/', $localElement, $matches);
			array_multisort($matches[1], SORT_DESC);
			if ($matches[1][0] !== '')                                                                      return false;   //      Unescaped quote or backslash character inside quoted string
			if (preg_match('/^"\\\\*"$/', $localElement) > 0)                       return false;   //      "" and "\" are slipping through - note: must tidy this up
		} else {
			//      Unquoted string tests:
			//
			//      Any ASCII graphic (printing) character other than the
			//      at-sign ("@"), backslash, double quote, comma, or square brackets may
			//      appear without quoting.  If any of that list of excluded characters
			//      are to appear, they must be quoted
			//              (http://tools.ietf.org/html/rfc3696#section-3)
			//
			$stripped = '';
			//      Any excluded characters? i.e. <space>, @, [, ], \, ", <comma>
			if (preg_match('/[ @\\[\\]\\\\",]/', $localElement) > 0)
			//      Check all excluded characters are escaped
			$stripped = preg_replace('/\\\\[ @\\[\\]\\\\",]/', '', $localElement);
			if (preg_match('/[ @\\[\\]\\\\",]/', $stripped) > 0)    return false;   //      Unquoted excluded characters
		}
	}

	//      Now let's check the domain part...

	//      The domain name can also be replaced by an IP address in square brackets
	//              (http://tools.ietf.org/html/rfc3696#section-3)
	//              (http://tools.ietf.org/html/rfc5321#section-4.1.3)
	//              (http://tools.ietf.org/html/rfc4291#section-2.2)
	if (preg_match('/^\\[(.)+]$/', $domain) === 1) {
		//      It's an address-literal
		$addressLiteral = substr($domain, 1, $domainLength - 2);
		$matchesIP              = array();

		//      Extract IPv4 part from the end of the address-literal (if there is one)
		if (preg_match('/\\b(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/', $addressLiteral, $matchesIP) > 0) {
			$index = strrpos($addressLiteral, $matchesIP[0]);

			if ($index === 0) {
				//      Nothing there except a valid IPv4 address, so...
				return true;
			} else {
				//      Assume it's an attempt at a mixed address (IPv6 + IPv4)
				if ($addressLiteral[$index - 1] !== ':')                        return false;   //      Character preceding IPv4 address must be ':'
				if (substr($addressLiteral, 0, 5) !== 'IPv6:')          return false;   //      RFC5321 section 4.1.3

				$IPv6 = substr($addressLiteral, 5, ($index ===7) ? 2 : $index - 6);
				$groupMax = 6;
			}
		} else {
			//      It must be an attempt at pure IPv6
			if (substr($addressLiteral, 0, 5) !== 'IPv6:')                  return false;   //      RFC5321 section 4.1.3
			$IPv6 = substr($addressLiteral, 5);
			$groupMax = 8;
		}

		$groupCount     = preg_match_all('/^[0-9a-fA-F]{0,4}|\\:[0-9a-fA-F]{0,4}|(.)/', $IPv6, $matchesIP);
		$index          = strpos($IPv6,'::');

		if ($index === false) {
			//      We need exactly the right number of groups
			if ($groupCount !== $groupMax)                                                  return false;   //      RFC5321 section 4.1.3
		} else {
			if ($index !== strrpos($IPv6,'::'))                                             return false;   //      More than one '::'
			$groupMax = ($index === 0 || $index === (strlen($IPv6) - 2)) ? $groupMax : $groupMax - 1;
			if ($groupCount > $groupMax)                                                    return false;   //      Too many IPv6 groups in address
		}

		//      Check for unmatched characters
		array_multisort($matchesIP
		[1], SORT_DESC);
		if ($matchesIP[1][0] !== '')                                                            return false;   //      Illegal characters in address

		//      It's a valid IPv6 address, so...
		return true;
	} else {
		//      It's a domain name...

		//      The syntax of a legal Internet host name was specified in RFC-952
		//      One aspect of host name syntax is hereby changed: the
		//      restriction on the first character is relaxed to allow either a
		//      letter or a digit.
		//              (http://tools.ietf.org/html/rfc1123#section-2.1)
		//
		//      NB RFC 1123 updates RFC 1035, but this is not currently apparent from reading RFC 1035.
		//
		//      Most common applications, including email and the Web, will generally not permit...escaped strings
		//              (http://tools.ietf.org/html/rfc3696#section-2)
		//
		//      Characters outside the set of alphabetic characters, digits, and hyphen MUST NOT appear in domain name
		//      labels for SMTP clients or servers
		//              (http://tools.ietf.org/html/rfc5321#section-4.1.2)
		//
		//      RFC5321 precludes the use of a trailing dot in a domain name for SMTP purposes
		//              (http://tools.ietf.org/html/rfc5321#section-4.1.2)
		$matches        = array();
		$groupCount     = preg_match_all('/(?:[0-9a-zA-Z][0-9a-zA-Z-]{0,61}[0-9a-zA-Z]|[a-zA-Z])(?:\\.|$)|(.)/', $domain, $matches);
		$level          = count($matches[0]);

		if ($level == 1)                                                                                        return false;   //      Mail host can't be a TLD

		$TLD = $matches[0][$level - 1];
		if (substr($TLD, strlen($TLD) - 1, 1) === '.')                          return false;   //      TLD can't end in a dot
		if (preg_match('/^[0-9]+$/', $TLD) > 0)                                         return false;   //      TLD can't be all-numeric

		//      Check for unmatched characters
		array_multisort($matches[1], SORT_DESC);
		if ($matches[1][0] !== '')                                                                      return false;   //      Illegal characters in domain, or label longer than 63 characters

		//      Check DNS?
		if ($checkDNS && function_exists('checkdnsrr')) {
			if (!(checkdnsrr($domain, 'A') || checkdnsrr($domain, 'MX'))) {
				return false;   //      Domain doesn't actually exist
			}
		}

		//      Eliminate all other factors, and the one which remains must be the truth.
		//              (Sherlock Holmes, The Sign of Four)
		return true;
	}
}

function BFRedirect($link, $msg = null) {
 	global $mainframe;
	$mainframe->redirect($link, $msg);
}
{* 
//////
//    @version [ Wainuiomata ]
//    @package hwdVideoShare
//    @copyright (C) 2007 - 2009 Highwood Design
//    @license http://creativecommons.org/licenses/by-nc-nd/3.0/
//////
*}

{include file='admin_header.tpl'}
		
{literal}
<script type="text/javascript">

checked=false;

function checkAllPageBoxes ()
{
	var aa= document.getElementById('adminForm');
	if (checked == false)
	{
		checked = true
	}
	else
	{
		checked = false
	}
	for (var i =0; i < aa.elements.length; i++) 
	{
		aa.elements[i].checked = checked;
	}
}
</script>
{/literal}

{$startpane}
    {$starttab1}
        {include file='admin_reported_videos.tpl'}
    {$endtab}
    {$starttab2}
        {include file='admin_reported_groups.tpl'}
    {$endtab}
{$endpane}

{include file='admin_footer.tpl'}

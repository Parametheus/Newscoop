<html>
<head>
    <META http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<META HTTP-EQUIV="Expires" CONTENT="now">
	<LINK rel="stylesheet" type="text/css" href="<?php echo $Campsite['WEBSITE_URL']; ?>/css/admin_stylesheet.css">
	<title><?php putGS("Edit Interview"); ?></title>
	<?php include_once($_SERVER['DOCUMENT_ROOT']."/$ADMIN_DIR/javascript_common.php"); ?>
	<style type="text/css">@import url(<?php echo $Campsite["WEBSITE_URL"]; ?>/javascript/jscalendar/calendar-system.css);</style>
    <script type="text/javascript" src="<?php echo $Campsite["WEBSITE_URL"]; ?>/javascript/jscalendar/calendar.js"></script>
    <script type="text/javascript" src="<?php echo $Campsite["WEBSITE_URL"]; ?>/javascript/jscalendar/lang/calendar-<?php echo camp_session_get('TOL_Language', 'en'); ?>.js"></script>
    <script type="text/javascript" src="<?php echo $Campsite["WEBSITE_URL"]; ?>/javascript/jscalendar/calendar-setup.js"></script>

    <script type="text/javascript">
    function activate_fields(type)
    {
        var value;
        
        if (type == 'guest') {
            if (document.getElementsByName('f_guest_user_id')[0].value == '__new__') {
                value = false;
            } else {
                value = true;
            }
            document.getElementsByName('f_guest_new_user_login')[0].disabled = value;
            document.getElementsByName('f_guest_new_user_email')[0].disabled = value;
        }
        
        if (type == 'moderator') {
            if (document.getElementsByName('f_moderator_user_id')[0].value == '__new__') {
                value = false;
            } else {
                value = true;
            }
            document.getElementsByName('f_moderator_new_user_login')[0].disabled = value;
            document.getElementsByName('f_moderator_new_user_email')[0].disabled = value;
        } 
    }    
    </script>  
</head>
<body>
<?php
camp_load_translation_strings("plugin_interview");

// Check permissions
if (!$g_user->hasPermission('plugin_interview_admin')) {
    camp_html_display_error(getGS('You do not have the right to manage interviews.'));
    exit;
}

$f_interview_id = Input::Get('f_interview_id', 'int');
$Interview = new Interview($f_interview_id);


// new usernames may exist
foreach(array('guest') as $type) {
    if ($_REQUEST['f_'.$type.'_user_id'] == '__new__') {
        require_once($_SERVER['DOCUMENT_ROOT']. "/$ADMIN_DIR/users/users_common.php");
    
        if (User::UserNameExists($_REQUEST['f_'.$type.'_new_user_login']) || Phorum_user::UserNameExists($_REQUEST['f_'.$type.'_new_user_login'])) {
            $errorMsg = getGS('User name $1 already exists, please choose a different login name.', $_REQUEST['f_'.$type.'_new_user_login']);
            camp_html_add_msg($errorMsg);
            $error = true;
        }
    }    
};

if (!$error && $Interview->store()) {
    ?>
    <script language="javascript">
        window.opener.location.reload();
        window.close();
    </script>
    <?php
    exit();
}

?>
<?php camp_html_display_msgs(); ?>
<table style="margin-top: 10px; margin-left: 15px; margin-right: 15px;" cellpadding="0" cellspacing="0" width="95%" class="table_input">
    <TR>
    	<TD style="padding: 3px";>
    		<B><?php $Interview->exists() ? putGS('Edit Interview') : putGS('Add new Interview'); ?></B>
    		<hr style="color: #8baed1";>
    	</TD>
    </TR>
    <tr>
        <td>
            <?php p($Interview->getForm('edit.php', array(), true)); ?>
        </td>
    </tr>
</table>
</body>
</html>

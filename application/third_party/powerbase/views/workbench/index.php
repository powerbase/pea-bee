<?php
$userName = $user_name;
$userId = $user_id;
echo doctype();
?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>pea-bee - Workbench</title>
<link media="all" type="text/css" rel="stylesheet" href="<?php echo base_url("powerbase/css/normalize.css"); ?>">
<link media="all" type="text/css" rel="stylesheet" href="<?php echo base_url("powerbase/css/layout.css"); ?>">
<link media="all" type="text/css" rel="stylesheet" href="<?php echo base_url("powerbase/css/powerbase.css"); ?>">
<link media="all" type="text/css" rel="stylesheet" href="<?php echo base_url("powerbase/css/bootstrap.min.css"); ?>">
<link media="all" type="text/css" rel="stylesheet" href="<?php echo base_url("powerbase/css/smoke.min.css"); ?>">
<link media="all" type="text/css" rel="stylesheet" href="<?php echo base_url("powerbase/css/datatables.min.css"); ?>">
<script src="<?php echo base_url("powerbase/js/jquery-2.2.4.min.js"); ?>"></script>
<script src="<?php echo base_url("powerbase/js/jquery-ui-1.9.2.custom.js"); ?>"></script>
<script src="<?php echo base_url("powerbase/js/jquery.layout-latest.js"); ?>"></script>
<script src="<?php echo base_url("powerbase/js/jquery.cookie.js"); ?>"></script>
<script src="<?php echo base_url("powerbase/js/smoke.min.js"); ?>"></script>
<script src="<?php echo base_url("powerbase/js/powerbase.js"); ?>"></script>
<script src="<?php echo base_url("powerbase/js/powerbase.workbench.js"); ?>"></script>
<script src="<?php echo base_url("powerbase/js/datatables.min.js"); ?>"></script>
<script src="<?php echo base_url("powerbase/js/dataTables.colResize.js"); ?>"></script>
</head>

<body id="workbench">
<div id="shadow-overlay"></div>
<div id="user-operation">
	<ul class="submenu" id="user-navi">
		<li><a href="<?php echo base_url("workbench/logout"); ?>">Sign Out</a></li>
	</ul>
</div>
<div class="ui-layout-north">

	<header id="header">
		<div id="header-left">
			<div id="header-title"><?=$system_name?> workbench</div>
		</div>
		<div id="header-center">
			<div id="progresss-animation" style="padding-top:8px; display:none;">
				<img alt="" src="<?php echo base_url("powerbase/images/progress_g.gif"); //http://www.ajaxload.info/ ?>">
			</div>
		</div>
		<div id="header-right">
			<span style="font-style:italic; margin-top:6px; margin-right:12px;">Non-programing Database Application Platform</span>
			<a id="touch-user" href="javascript:void(0);" onclick="toggleUserMenu(); return false;">
			<span class="top-navi-right" id="user-disp-area">
			<?= $userName ?>&nbsp;|&nbsp;&#9660;
			</span>
			</a>
		</div>
	</header>

</div>


<div class="ui-layout-center split-pane" id="center-container">
</div>

<div id="panel-database" style="display:none;">
	<div class="ui-layout-center">
		<div style="height:24px; width:100%; background-color: #93b881"><span style="padding:4px;font-size:120%;">System</span></div>
		<div style="padding:10px;">

		</div>
	</div>
</div>

<div id="panel-users" class="v-split-pane upper-list" style="display:none;">
	<div class="ui-layout-center">
		<div style="height:24px; width:100%; background-color: #93b881"><span style="padding:4px;font-size:120%;">Users</span></div>
		<div id="outer-user-list" style="padding:12px;"></div>
	</div>
	<div class="ui-layout-south" id="user-view">
		<div id="outer-user-panel" style="padding:12px; width:100%;"></div>
	</div>
</div>

<div id="panel-tables" style="display:none;">
	<div class="ui-layout-center">
		<div style="height:24px; width:100%; background-color: #93b881"><span style="padding:4px;font-size:120%;">Tables</span></div>
		<div style="padding:10px;">
			tables
		</div>
	</div>
	<div class="ui-layout-south" id="group-view">
		items
	</div>

</div>

<div class="ui-layout-west">
	<div id="navi">
		<div class="side-menu" id="menu-database">
			<img style="vertical-align:middle;" src="<?php echo base_url("powerbase/images/settings.png"); ?>" /><span class="side-menu-text">System</span>
		</div>
		<div class="side-menu" id="menu-groups">
			<img style="vertical-align:middle;" src="<?php echo base_url("powerbase/images/users.png"); ?>" /><span class="side-menu-text">Groups</span>
		</div>
		<div class="side-menu" id="menu-users">
			<img style="vertical-align:middle;" src="<?php echo base_url("powerbase/images/user.png"); ?>" /><span class="side-menu-text">Users</span>
		</div>
		<div class="side-menu" id="menu-tables">
			<img style="vertical-align:middle;" src="<?php echo base_url("powerbase/images/table.png"); ?>" /><span class="side-menu-text">Tables</span>
		</div>
		<div class="side-menu" id="menu-editor">
			<img style="vertical-align:middle;" src="<?php echo base_url("powerbase/images/edit.png"); ?>" /><span class="side-menu-text">Table Editor</span>
		</div>
		<div class="side-menu" id="menu-models">
			<img style="vertical-align:middle;" src="<?php echo base_url("powerbase/images/node.png"); ?>" /><span class="side-menu-text">Data Models</span>
		</div>
		<div class="side-menu" id="menu-pages">
			<img style="vertical-align:middle;" src="<?php echo base_url("powerbase/images/document-empty.png"); ?>" /><span class="side-menu-text">Pages</span>
		</div>
		<div class="side-menu" id="menu-reports">
			<img style="vertical-align:middle;" src="<?php echo base_url("powerbase/images/report.png"); ?>" /><span class="side-menu-text">Reports</span>
		</div>
		<div class="side-menu" id="menu-endpoints">
			<img style="vertical-align:middle;" src="<?php echo base_url("powerbase/images/sitemap.png"); ?>" /><span class="side-menu-text">Endpoints</span>
		</div>
		<!--		<div id="db-tree" style="display:none;">-->
		<!--  		</div>-->
	</div>
</div>
<script type="text/javascript">
	var endPoint = '<?php echo base_url(); ?>' + 'workbench/';
	var workbench;
	var table = 0;
	var toggleUserMenu = function(){
		var width = $("#user-disp-area").width();
		if (width < 100) width = 100;
		var $user_operation = $('#user-operation');
		$user_operation.css('width', width+50);
		if ($user_operation.css('visibility') === "hidden") {
			$user_operation.css('visibility', 'visible');
		} else {
			$user_operation.css('visibility', 'hidden');
		}
	};
	$(window).click(function(e){
		if ($(e.target).attr("id") === 'user-disp-area') return;
		$('#user-operation').css('visibility', 'hidden');
	});
	$(document).ready(function(){
		workbench = new Workbench();
		workbench.layout();
		workbench.paneling('menu-database');
		$("#menu-database").addClass("side-menu-on");
		$('.side-menu').on('click', function() {
			$(".side-menu").each(function() {
				$(this).removeClass("side-menu-on");
			});
			$(this).addClass("side-menu-on");
			workbench.paneling($(this).attr('id'));
		});
	});

</script>
</body>
</html>
 

<?php
/** @var PbView $view */
echo doctype() . PHP_EOL;
$lang = new PbTextPerLang();
?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title><?= $lang->welcome_to_the_peabee ?></title>
    <link media="all" type="text/css" rel="stylesheet" href="<?php echo base_url("powerbase/css/normalize.css"); ?>">
	<link media="all" type="text/css" rel="stylesheet" href="<?php echo base_url("powerbase/css/powerbase.css"); ?>">
  	<link rel="stylesheet" href="<?php echo base_url("powerbase/css/bootstrap.min.css"); ?>">
	<link rel="stylesheet" href="<?php echo base_url("powerbase/css/smoke.min.css"); ?>">
	<script src="<?php echo base_url("powerbase/js/jquery-2.2.4.min.js"); ?>"></script>
	<script src="<?php echo base_url("powerbase/js/smoke.min.js"); ?>"></script>
	<script src="<?php echo base_url("powerbase/js/powerbase.js"); ?>"></script>
	<style type="text/css">
		body {overflow-x: hidden;}
	</style>
	<script type="text/javascript">
		$(document).ready(function() {
			$('#sub').click(function() {
				if (!$('form').smkValidate()) return false;
			});
		});
		$(window).load(function () {
		    var messages = '#messages';
			<?php if ($view->exists("errors")) : ?>
			var html = '<div><h3>Error</h3><ul>';
			<?php foreach($view->value("errors") as $error) : ?>
			html += '<li><?= h($error); ?></li>';
			<?php endforeach; ?>
			html += '</ul></div>';
			$(messages).html(html);
			$(messages).show();
			<?php else : ?>
			$(messages).hide();
			<?php endif; ?>
		});		
	</script>
</head>
<body id="workbench">
<div id="shadow-overlay"></div>
<div class="ui-layout-north">
	<header id="header">
		<div id="header-left">
			<div id="header-title"><?= $lang->welcome_to_the_peabee ?></div>
		</div>
	</header>
</div>
<div id="sample-panel" class="panel panel-success" style="margin:24px auto; width:800px;">
	<div class="panel-heading" style="font-size:120%;"><?= $lang->initialization_description ?></div>
	<div class="panel-body">
		<div>
			<div id="messages" class="alert alert-danger" role="alert"></div>
			<?= form_open("workbench/initialization", array("id"=>"settings", "class" => "form-horizontal", "data-smk-icon" => "glyphicon-remove-sign")) ?>
				<div class="form-group">
					<label class="col-sm-3 control-label"><?= $lang->system_name ?></label>
					<div class="col-sm-9">
						<input type="text" name="settings[system_name]" class="form-control"
							   placeholder="<?= $lang->system_name ?>" value="<?= h($view->value("settings[system_name]"))?>" required />
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label"><?= $lang->admin_userid ?></label>
					<div class="col-sm-9">
						<input type="text" name="settings[admin_userid]" class="form-control"
							   placeholder="<?= $lang->admin_userid ?>" value="<?= h($view->value("settings[admin_userid]"))?>" required />
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label"><?= $lang->admin_password ?></label>
					<div class="col-sm-9">
						<input type="text" name="settings[admin_password]" class="form-control"
							   placeholder="<?= $lang->admin_password ?>" value="<?= h($view->value("settings[admin_password]"))?>" required />
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label"><?= $lang->pdo_dsn ?></label>
					<div class="col-sm-9">
						<input type="text" name="settings[pdo_dsn]" class="form-control" placeholder="<?= $lang->pdo_dsn ?>"
							   value="<?= h($view->value("settings[pdo_dsn]"))?>" required />
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label"><?= $lang->db_username ?></label>
					<div class="col-sm-9">
						<input type="text" name="settings[db_username]" class="form-control"
							   placeholder="<?= $lang->db_username ?>" value="<?= h($view->value("settings[db_username]"))?>" />
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label"><?= $lang->db_password ?></label>
					<div class="col-sm-9">
						<input type="text" name="settings[db_password]" class="form-control"
							   placeholder="<?= $lang->db_password ?>" value="<?= h($view->value("settings[db_password]"))?>" />
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-offset-3 col-sm-10">
						<button id="sub" type="submit" class="btn btn-success"><?= $lang->submit ?></button>
					</div>
				</div>
			<?= form_close() ?>

		</div>
	</div>
</div>
</body>
</html>

<?php

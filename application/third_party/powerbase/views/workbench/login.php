<?php
/** @var PbView $view */
echo doctype() . PHP_EOL;
$lang = new PbTextPerLang();
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title><?= $lang->text("welcome_to_the_peabee", $system_name) ?></title>
<link media="all" type="text/css" rel="stylesheet" href="<?php echo base_url("powerbase/css/normalize.css"); ?>">
<link media="all" type="text/css" rel="stylesheet" href="<?php echo base_url("powerbase/css/powerbase.css"); ?>">
<link rel="stylesheet" href="<?php echo base_url("powerbase/css/bootstrap.min.css"); ?>">
<link rel="stylesheet" href="<?php echo base_url("powerbase/css/smoke.min.css"); ?>">
<script src="<?php echo base_url("powerbase/js/jquery-2.2.4.min.js"); ?>"></script>
<script src="<?php echo base_url("powerbase/js/smoke.min.js"); ?>"></script>
<script src="<?php echo base_url("powerbase/js/powerbase.js"); ?>"></script>
<style type="text/css">
	body {overflow-x: hidden;}

	.form-signin {
		max-width: 330px;
		padding: 15px;
		margin: 0 auto;
	}
	.form-signin .form-control {
		position: relative;
		font-size: 16px;
		height: auto;
		padding: 10px;
		-webkit-box-sizing: border-box;
		-moz-box-sizing: border-box;
		box-sizing: border-box;
	}
	.form-signin .form-control:focus {
		z-index: 2;
	}
	.form-signin input[type="text"] {
		margin-bottom: -1px;
		border-bottom-left-radius: 0;
		border-bottom-right-radius: 0;
	}
	.form-signin input[type="password"] {
		margin-bottom: 10px;
		border-top-left-radius: 0;
		border-top-right-radius: 0;
	}
	.account-wall 	{
		margin-top: 20px;
		padding: 40px 0px 20px 0px;
		background-color: #f7f7f7;
		-moz-box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
		-webkit-box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
		box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
	}
	.login-title {
		color: #555;
		font-size: 18px;
		font-weight: 400;
		display: block;
	}
	.profile-img {
		width: 96px;
		height: 96px;
		margin: 0 auto 10px;
		display: block;
		-moz-border-radius: 50%;
		-webkit-border-radius: 50%;
		border-radius: 50%;
	}
</style>
</head>
<body id="login">
<div id="shadow-overlay"></div>
<div class="ui-layout-north">
	<header id="header">
		<div id="header-left">
			<div id="header-title"><?= $lang->text("welcome_to_the_peabee", $system_name) ?></div>
		</div>
	</header>
</div>

<div class="container">
	<div class="row">
		<div class="col-sm-6 col-md-4 col-md-offset-4">
			<div class="account-wall">
				<h1 class="text-center login-title">Please sign in to continue.</h1>
				<p class="text-center text-danger">&nbsp;<?=$view->message?>&nbsp;</p>
				<img class="profile-img" src="<?php echo base_url("powerbase/images/admin.png"); ?>"
					 alt="Administrator">
				<?= form_open("workbench/login", array("class" => "form-signin")) ?>
					<input name="admin" type="text" class="form-control" placeholder="User ID" required autofocus>
					<input name="passwd" type="password" class="form-control" placeholder="Password" required>
					<button class="btn btn-lg btn-success btn-block" type="submit">
						Sign in</button>
				<?= form_close() ?>
			</div>
		</div>
	</div>
</div>
</body>
</html>

<?php

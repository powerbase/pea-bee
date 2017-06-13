<?php
/** @var PbView $view */
$lang = new PbTextPerLang();
$super_admin = false;
if ($view->value("data[pb_users][group_id]") == -1) $super_admin = true;
?>
<div id="userData">
	<div>
		<button id= "user-panel-new" type="button" class="btn btn-default" title="NEW">
			<span class="glyphicon glyphicon-plus" style="margin-right:4px"></span>NEW
		</button>
		<button id= "user-panel-save" type="button" class="btn btn-success" title="SAVE">
			<span class="glyphicon glyphicon-save" style="margin-right:4px"></span>SAVE
		</button>
		<?php if ($id && !$super_admin) : ?>
		<button id= "user-panel-del" type="button" class="btn btn-danger" title="DELETE" onclick="workbench.users.delete('<?= h($id)?>')">
			<span class="glyphicon glyphicon-trash" style="margin-right:4px"></span>DELETE
		</button>
		<?php endif; ?>
	</div>
	
	<div class="container">
		<?= form_open("", array("id"=>"userDataForm", "class" => "form-horizontal",)) ?>
			<div class="form-group">
				<input type="hidden" name="data[pb_users][id]" value="<?= h($id)?>">
			</div>
			<div class="form-group">
				<label class="control-label col-xs-1">User ID</label>
				<div class="col-xs-2">
					<input type="text" name="data[pb_users][user_id]" class="form-control" 
						   value="<?= h($view->value("data[pb_users][user_id]"))?>"<?= ($id?" readonly":"")?> required>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-xs-1">Name</label>
				<div class="col-xs-4">
					<input type="text" name="data[pb_users][user_name]" 
						   value="<?= h($view->value("data[pb_users][user_name]"))?>" class="form-control" required>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-xs-1">Password</label>
				<div class="col-xs-4">
					<input type="text" name="data[pb_users][password]" 
						   value="" class="form-control"><span style="font-size:90%;">Required only when changing.</span>
				</div>
			</div>
			<div class="form-group">
				<div class="col-xs-offset-1 col-xs-10">
					<div class="checkbox">
						<label>
							<input type="hidden" name="data[pb_users][admin]" value="<?=($super_admin?"1":"0")?>" />
							<input name="data[pb_users][admin]" value="1" type="checkbox"<?=(h($view->value("data[pb_users][admin]"))=="1"?" checked":"")?>
								<?=($super_admin?" disabled":"")?>>Administrator
						</label>
					</div>
				</div>
			</div>
		<?= form_close() ?>
	</div>
</div>


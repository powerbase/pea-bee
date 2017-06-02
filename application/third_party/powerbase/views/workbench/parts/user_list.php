<?php
/** @var PbView $view */
$lang = new PbTextPerLang();
?>
<table id="userList" class="table table-bordered" cellspacing="0" width="100%">
	<thead>
	<tr>
		<th>User ID</th>
		<th>Name</th>
	</tr>
	</thead>
	<tbody>
<?php
	foreach($users as $user) {
		$view->responce('<tr id="pb-user-'.$user["id"].'" class="pb-user-row">');
		$view->responce('<td>'.h($user["user_id"]).'</td>');
		$view->responce('<td>'.h($user["user_name"]).'</td>');
		$view->responce('</tr>');
	}
?>	
	</tbody>
</table>



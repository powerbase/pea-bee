<?php

class PbWorkbenchController extends PB_Controller {

	private $setting;
	
	private $users;
	
	public function __construct() {
		parent::__construct();
		
		if (!$this->config->item("pb_workbench_enable")) {
			header(PbUtils::httpStatusCode(403));
			exit;
		}
		$this->setting = PbSettings::getInstance();
	}
	
	public function users($func=null) {
		switch($func) {
			case "get_users_as_html":
				$model = new PbUsersModel();
				$users = $model->get();
				$this->set("users", $users);
				echo $this->render("workbench/parts/user_list", true);
				break;

			case "get_user":
				$id = (int)$this->input->get("id");
				$model = new PbUsersModel();
				$user = array();
				if ($id) $user = $model->getById($id);
				$this->set("id", $id);
				$this->set("data", array("pb_users"=>$user));
				echo $this->render("workbench/parts/user", true);
				break;

			case "save":
				$data = $this->input->post("data");
				$model = new PbUsersModel();
				$model->save($data["pb_users"]);
				break;
			default:
				break;
		}
		exit;
	}

	public function index() {
		if ($this->setting->isEmptyDb()) $this->redirect("workbench/initialization");	// redirect to initialization.
		else {
			if (!$this->sessionExists("admin_id")) $this->redirect("workbench/login");	// redirect to login.
		}
		$this->users = new PbUsersModel();
		$user = $this->users->getById($this->readSession("admin_id"));

		$this->set("user_id", $user["user_id"]);
		$this->set("user_name", $user["user_name"]);
		$this->set("system_name", $this->setting->get(PbSettings::SYSTEM_NAME));
		$this->render("workbench/index");
	}
	
	public function logout() {
		$this->destroySession();
		$this->redirect("workbench");
	}
	
	public function login() {
		if ($this->setting->isEmptyDb()) $this->redirect("workbench/initialization");	// redirect to initialization.
		if ($this->input->method() == "post") {
			$this->setFlashData("message", "Sign in failed.");
			
			//Administrator authentication.
			$users = new PbUsersModel();
			$user = $users->getRow(array(), array("user_id"=>$this->input->post("admin")));
			if (is_empty($user)) $this->redirect("workbench/login");	// not exists.
			if ($user["admin"] != "1") $this->redirect("workbench/login");	// not admin.
			if (!PbPassword::verify($this->input->post("passwd"), $user["password"]))  $this->redirect("workbench/login"); // password mismatch.
			
			//Login success.
			session_regenerate_id();
			$this->unsetSession("message");
			$this->writeSession("admin_id", $user["id"]);
			$this->redirect("workbench");
		}
		if ($message = $this->getFlashData("message")) $this->set("message", $message);
		$this->set("system_name", $this->setting->get(PbSettings::SYSTEM_NAME));
		$this->render("workbench/login");
	}

	public function initialization() {
		if (!$this->setting->isEmptyDb()) {
			//Prohibit settings.db overwriting.
			header(PbUtils::httpStatusCode(403));
			exit;
		}
		if ($this->input->method() == "post") {
			$this->writeSession("_post", $this->input->post());
			$res = $this->setting->initialize($this->input->post("settings"));
			if ($res !== true) {
				if (!is_array($res)) throw new PbException("invalid responce.");
				$this->setFlashData("errors", $res);
				$this->redirect("workbench/initialization");
			}
			if ($this->sessionExists("_post")) $this->unsetSession("_post");
			$this->redirect("workbench");
		} else {
			if ($this->sessionExists("_post")) $post = $this->readSession("_post");
			if ($settings = (isset($post["settings"]) ? $post["settings"] : array())) $this->set("settings", $settings);
			if ($errors = $this->getFlashData("errors")) $this->set("errors", $errors);
			$this->render("workbench/initialization");
		}
	}

}

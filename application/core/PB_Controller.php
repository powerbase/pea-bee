<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Class PB_Controller
 *
 * @property CI_Router $router
 * @property CI_Input $input
 * @property CI_Config $config
 * @property CI_Benchmark $benchmark
 * @property CI_Hooks $hooks
 * @property CI_Log $log
 * @property CI_Utf8 $utf8
 * @property CI_URI $uri
 * @property CI_Output $output
 * @property CI_Security $security
 * @property CI_Lang $lang
 * @property CI_Loader $load
 * @property CI_Session $session
 * @property CI_Model $model
 * @property CI_DB_query_builder $db
 */

class PB_Controller extends CI_Controller {

	/**
	 * System version.
	 */
	const PB_VERSION = "0.1";

	/**
	 * Items to view.
	 * @var array
	 */
	protected $_data;

	public function __construct() {
		parent::__construct();
		
		$this->load->library('session');
		$this->load->library("Autoloader");
		$this->load->library("PbBasics");
		
		$this->_data = array();
		
	}
	
	public function loadModel($model, $name=null) {
		if ($name === null) $name = $model;
		$this->{$name} = new PB_Model($model);
	}

	/**
	 * @return array
	 */
	public function getData() {
		return $this->_data;
	}
	
	public function save(array $data) {
		$error = false;
		$this->db->trans_begin();
		foreach($data as $tblName=>$items) {
			$model = PbTable::newInstance($tblName);
			$error = $model->check($data);
			if ($error !== true) break;
			$model->save($data);
		}
		if ($error === false) {
			$this->db->trans_commit();
			return true;
		}
		$this->db->trans_rollback();
		return $error;
	}

	public function redirect($url) {
		$this->load->helper('url');
		redirect($this->config->base_url() . ltrim($url, "/"));
		exit;
	}
	
	public function set($key, $value) {
		// does not escape here.
		$this->_data[$key] = $value;
	}

	protected function render($view, $return=false) {
		$this->set("view", new PbView($this));
		return $this->load->view($view, $this->_data, $return);
	}

	protected function setFlashData($key, $data) {
		$this->session->set_flashdata($key, $data);
	}

	protected function getFlashData($key) {
		if ($this->session->has_userdata($key)) {
			return $this->session->userdata($key);
		}
		return null;
	}
	
	protected function writeSession($key, $data, $suffix=null) {
		if ($suffix !== null) $key = $key . "@" . $suffix;
		$this->session->set_userdata($key, $data);
	}

	protected function sessionExists($key, $suffix=null) {
		if ($suffix !== null) $key = $key . "@" . $suffix;
		return $this->session->has_userdata($key);
	}

	protected function readSession($key, $suffix=null) {
		if ($suffix !== null) $key = $key . "@" . $suffix;
		if ($this->sessionExists($key, $suffix)) {
			return $this->session->userdata($key);
		}
		return null;
	}

	protected function unsetSession($key, $suffix=null) {
		if ($suffix !== null) $key = $key . "@" . $suffix;
		$this->session->unset_userdata($key);
	}

	protected function destroySession($suffix=null) {
		if ($suffix === null) {
			$this->session->sess_destroy();
			return;
		}
		$user_data = array();
		$keys = $this->session->all_userdata();
		foreach($keys as $key=>$val) {
			if (PbUtils::endsWith($key, "@" . $suffix)) $user_data[] = $key;
		}
		$this->session->unset_userdata($user_data);
	}

}

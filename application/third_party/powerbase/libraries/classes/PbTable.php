<?php

/**
 * Class PbTable
 *
 * @property CI_DB_query_builder $db
 * @property CI_Config $config
 * @property CI_DB_forge $dbforge
 * @property CI_loader $load
 * 
 */
class PbTable extends PB_Model {

	const DB_FIELD_TYPE_INTEGER = "integer";
	const DB_FIELD_TYPE_REAL = "real";
	const DB_FIELD_TYPE_STRING = "string";
	const DB_FIELD_TYPE_DATETIME = "datetime";
	const DB_FIELD_TYPE_FUNCTION = "function";
	const DB_FIELD_TYPE_CURRENCY = "currency";
	const DB_FIELD_TYPE_BIN = "bin";
	const DB_FIELD_TYPE_BOOLEAN = "boolean";
	const DB_FIELD_TYPE_GEOMETRY = "geometry";
	const DB_FIELD_TYPE_NETADDR = "netaddr";
	
	private $dbName;
	
	private $tableName;
	
	private $pk = "id";
	
	private $fields = array();
	
	public static function newInstance($tableName, $dbName="default") {
		return new PbTable($tableName, $dbName);
	}
	
	public function __construct($tableName=null, $dbName="default") {
		parent::__construct();
		
		if ($tableName === null) return;
		
		$this->dbName = $dbName;
		$this->tableName = $tableName;
		$connection = new PbDbConnection();
		$info = $connection->getConnection($this->dbName);
		$this->db = $this->load->database(PbDbConnection::connectionParam($info["dsn"], $info["user"], $info["passwd"]), true);
		$this->load->dbforge($this->db);
		if (!$this->tableExists($this->tableName)) $this->createTable();
		$fields = $this->db->field_data($this->tableName);
		foreach($fields as $field) {
			$this->fields[$field->name] = array(
				"name" => $field->name,
				"native_type" => self::type($field->type),
				"type" => self::uniType($field->type),
				"max_length" => is_empty($field->max_length) ? "" : $field->max_length,
				"default" => is_empty($field->default) ? "" : $field->default,
			);
		}
	}
	
	protected function createTable() {
		$forge = new PbDbForge($this);
		$fields = array(
			'id' => array(
				'type' => 'INT',
				'unsigned' => TRUE,
				'auto_increment' => TRUE
			),
		);
		$forge->create("id", $fields);
	}
	
	public function resultAsArray(CI_DB_result $query) {
		if (is_empty($this->tableName)) throw new PbException("invalid table manipuration.");
		return $query->result("array");
	}

	public function getRow($select=array(), $where=array()) {
		if (is_empty($this->tableName)) throw new PbException("invalid table manipuration.");
		$rows = $this->get($select, $where);
		return $rows[0];
	}

	public function get($select=array(), $where=array(), $orderBy="", $limit=0, $offset=0) {
		if (is_empty($this->tableName)) throw new PbException("invalid table manipuration.");
		$rows = $this->resultAsArray($this->getResult($select, $where, $orderBy, $limit, $offset));
		if (is_empty($rows)) return array();
		return $rows;
	}
	
	public function getResult($select=array(), $where=array(), $orderBy="", $limit=0, $offset=0) {
		if (is_empty($this->tableName)) throw new PbException("invalid table manipuration.");
		$result = $this->db
			->select($select)
			->from($this->tableName)
			->where($where)
			->order_by($orderBy)
			->limit($limit, $offset)
			->get();
		return $result;
	}

	public function getById($id, $select=array(), $exclude=array(), $depth=0) {
		if (is_empty($this->tableName)) throw new PbException("invalid table manipuration.");
		if (is_empty($id)) return array();
		$this->db->select($select);
		$this->db->from($this->tableName);
		$id = $this->castValue($this->pk, $id);
		$this->db->where(array($this->pk=>$id));
		$rows = $this->resultAsArray($this->db->get());
		if (is_empty($rows)) return array();
		return $rows[0];
	}

	public function exists($id) {
		if (is_empty($this->tableName)) throw new PbException("invalid table manipuration.");
		$query = $this->db->select($this->pk)->from($this->tableName)->where(array($this->pk => $id))->get();
		if ($query->num_rows() == 0) return false;
		return true;
	}
	
	public function castValue($name, $value) {
		if (is_empty($this->tableName)) throw new PbException("invalid table manipuration.");
		if (!isset($this->fields[$name])) return $value;
		if ($this->fields[$name]["type"] == self::DB_FIELD_TYPE_INTEGER) {
			$castValue = (int)$value;
		} elseif ($this->fields[$name]["type"] == self::DB_FIELD_TYPE_REAL) {
			$castValue = (float)$value;
		} else {
			$castValue = (string)$value;
		}
		return $castValue;
	}
	
	public function cast($data) {
		if (is_empty($this->tableName)) throw new PbException("invalid table manipuration.");
		$cast = array();
		foreach($data as $key=>$val) $cast[$key] = $this->castValue($key, $val);
		return $cast;
	}

	public function insert(array $data) {
		if (is_empty($this->tableName)) throw new PbException("invalid table manipuration.");
		if (array_key_exists("created_at", $this->fields)) $data["created_at"] = PbUtils::now();
		if (array_key_exists("updated_at", $this->fields)) $data["updated_at"] = PbUtils::now();
		if (isset($data[$this->pk]) && is_empty($data[$this->pk])) unset($data[$this->pk]);
		$ret = $this->db->insert($this->tableName, $this->cast($data));
		if ($ret === false) throw new PbException("DB insert failed.");
		return $this->db->insert_id();
	}
	
	public function update(array $data, $id=null) {
		if (is_empty($this->tableName)) throw new PbException("invalid table manipuration.");
		if ($id === null) $id = $data[$this->pk];
		if (is_empty($id)) throw new PbException("primary key is empty.");
		$id = $this->castValue($this->pk, $id);
		if (!$this->exists($id)) throw new PbException("not found.");
		if (array_key_exists("updated_at", $this->fields)) $data["updated_at"] = PbUtils::now();
		$ret = $this->db->update($this->tableName, $this->cast($data), array($this->pk => $id));
		if ($ret === false) throw new PbException("DB update failed.");
		return $ret;
	}

	/**
	 * Multiple record deletion.
	 * All of the following syntaxes are valid.
	 * 	$this->delete(2) ... delete pk = 2
	 * 	$this->delete(array(3,5,7)) ... pk in (3,5,7)
	 * @param $id
	 * @return mixed
	 * @throws PbException
	 */
	public function delete($id) {
		if (is_empty($this->tableName)) throw new PbException("invalid table manipuration.");
		if (is_empty($id)) throw new PbException("primary key is empty.");
		if (is_array($id)) $delId = $id;
		else {
			$delId = array($id);
		}
		foreach($delId as $di) {
			if (!$this->exists($di)) continue;
			$ret = $this->db->delete($this->tableName, array($this->pk => $di));
			if ($ret === false) throw new PbException("DB delete failed.");
		}
		return $delId;
	}
	
	public function check(array $data) {
		$errors = array();
		foreach($data as $name=>$val) {
			$fields = $this->fields[$name];
			$type = $fields["type"];
			$max_length = $fields["max_length"];
			if ($type == self::DB_FIELD_TYPE_STRING) {
				if (!empty($max_length)) {
					//TODO: implements
				}
			} else {
				//TODO: implements
			}
		}
		
		if (count($errors) == 0) return true;
		return $errors;
	}

	public function save(array $data, $id=null) {
		if (is_empty($this->tableName)) throw new PbException("invalid table manipuration.");
		if ($id === null && isset($data[$this->pk])) $id = $data[$this->pk];
		if (is_empty($id)) {
			return $this->insert($data);
		} else {
			if (!$this->exists((int)$id)) return $this->insert($data);
		}

		return $this->update($data, $id);
	}

	public function getColumValue($q) {
		if ($q instanceof CI_DB_result) {
			$query = $q;
		} else {
			$query = $this->db->query($q);
		}
		$row = $query->row_array();
		$v = "";
		foreach($row as $val) {
			$v = $val; break;
		}
		return (string)$v;
	}

	public function getValue($col, $where) {
		if (is_empty($where)) $where = array();
		if (is_array($col)) {
			if (count($col) != 1) throw new PbException("wrong usage.");
		} else {
			if (strpos($col, ",") !== false) throw new PbException("wrong usage.");
		}
		$res = $this->getColumValue(
			$this->db->
			select($col)->
			from($this->tableName)->
			where($where)->
			get()
		);
		return $res;
	}
	
	public function getNumOfRows($where=array()) {
		return (int)$this->getValue("count(*)", $where);
	}
	
	public function count() {
		return $this->getNumOfRows();
	}

	public function tableExists($table) {
		return $this->db->table_exists($table);
	}

	/**
	 * @return mixed
	 */
	public function getTableName() {
		return $this->tableName;
	}


	/**
	 * Solves the synonym of the same type.
	 * e.g. varchar vs. character varying
	 *
	 * @param $type
	 * @return string
	 */
	private static function type($type) {
		if ($type == "character varying") return "varchar";
		if ($type == "double precision") return "double";
		if ($type == "timestamp without time zone") return "timestamp";
		if ($type == "timestamp with time zone") return "timestamptz";	//depends on pgsql.
		return $type;
	}

	/**
	 * Get a unified type symbol.
	 *
	 * @see https://www.postgresql.org/docs/9.4/static/datatype.html
	 * @see https://dev.mysql.com/doc/refman/5.6/en/data-types.html
	 * @param $type
	 * @return string
	 * @throws Exception
	 */
	public static function uniType($type) {
		$type = strtolower($type);
		//Numeric Types (Integer)
		if ($type == "int") return self::DB_FIELD_TYPE_INTEGER;
		if ($type == "smallint") return self::DB_FIELD_TYPE_INTEGER;
		if ($type == "integer") return self::DB_FIELD_TYPE_INTEGER;
		if ($type == "bigint") return self::DB_FIELD_TYPE_INTEGER;
		if ($type == "smallserial") return self::DB_FIELD_TYPE_INTEGER;
		if ($type == "serial") return self::DB_FIELD_TYPE_INTEGER;
		if ($type == "bigserial") return self::DB_FIELD_TYPE_INTEGER;
		if ($type == "tinyint") return self::DB_FIELD_TYPE_INTEGER;
		if ($type == "mediumint") return self::DB_FIELD_TYPE_INTEGER;

		//Numeric Types (Arbitrary Precision Numbers)
		if ($type == "decimal") return self::DB_FIELD_TYPE_REAL;
		if ($type == "numeric") return self::DB_FIELD_TYPE_REAL;
		if ($type == "real") return self::DB_FIELD_TYPE_REAL;
		if ($type == "double precision") return self::DB_FIELD_TYPE_REAL;
		if ($type == "float") return self::DB_FIELD_TYPE_REAL;
		if ($type == "double") return self::DB_FIELD_TYPE_REAL;

		//Monetary Types
		if ($type == "money") return self::DB_FIELD_TYPE_CURRENCY;

		//Character Types
		if (strtok($type, " ") == "character") return self::DB_FIELD_TYPE_STRING;
		if ($type == "varchar") return self::DB_FIELD_TYPE_STRING;
		if ($type == "char") return self::DB_FIELD_TYPE_STRING;
		if ($type == "text") return self::DB_FIELD_TYPE_STRING;
		if ($type == "binary") return self::DB_FIELD_TYPE_STRING;
		if ($type == "varbinary") return self::DB_FIELD_TYPE_STRING;

		//TODO: ENUM, SET
		//TODO: BLOB, CLOB

		//Binary Data Types
		if ($type == "bytea") return self::DB_FIELD_TYPE_BIN;

		//Date/Time Types
		if (strtok($type, " ") == "timestamp") return self::DB_FIELD_TYPE_DATETIME;
		if (strtok($type, " ") == "timestamptz") return self::DB_FIELD_TYPE_DATETIME;
		if ($type == "date") return self::DB_FIELD_TYPE_DATETIME;
		if ($type == "datetime") return self::DB_FIELD_TYPE_DATETIME;
		if ($type == "year") return self::DB_FIELD_TYPE_DATETIME;
		if (strtok($type, " ") == "time") return self::DB_FIELD_TYPE_DATETIME;
		if (strtok($type, " ") == "interval") return self::DB_FIELD_TYPE_DATETIME;

		//Boolean Type
		if ($type == "boolean") return self::DB_FIELD_TYPE_BOOLEAN;

		//Geometric Types
		if ($type == "point") return self::DB_FIELD_TYPE_GEOMETRY;
		if ($type == "line") return self::DB_FIELD_TYPE_GEOMETRY;
		if ($type == "lseg") return self::DB_FIELD_TYPE_GEOMETRY;
		if ($type == "box") return self::DB_FIELD_TYPE_GEOMETRY;
		if ($type == "path") return self::DB_FIELD_TYPE_GEOMETRY;
		if ($type == "path") return self::DB_FIELD_TYPE_GEOMETRY;
		if ($type == "polygon") return self::DB_FIELD_TYPE_GEOMETRY;
		if ($type == "circle") return self::DB_FIELD_TYPE_GEOMETRY;

		//Network Address Types
		if ($type == "cidr") return self::DB_FIELD_TYPE_NETADDR;
		if ($type == "inet") return self::DB_FIELD_TYPE_NETADDR;
		if ($type == "macaddr") return self::DB_FIELD_TYPE_NETADDR;

		throw new PbException(" Unknown type \"" . $type . "\" specified.");
	}
}

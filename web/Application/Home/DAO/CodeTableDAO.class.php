<?php

namespace Home\DAO;

/**
 * 码表DAO
 *
 * @author 李静波
 */
class CodeTableDAO extends PSIBaseExDAO {

	/**
	 * 码表分类列表
	 */
	public function categoryList($params) {
		$db = $this->db;
		
		$sql = "select id, code, name
				from t_code_table_category
				order by code";
		$data = $db->query($sql);
		
		$result = [];
		foreach ( $data as $v ) {
			$result[] = [
					"id" => $v["id"],
					"code" => $v["code"],
					"name" => $v["name"]
			];
		}
		
		return $result;
	}

	/**
	 * 新增码表分类
	 *
	 * @param array $params        	
	 * @return null|array
	 */
	public function addCodeTableCategory(& $params) {
		$db = $this->db;
		
		$code = $params["code"] ?? "";
		$code = strtoupper($code);
		$name = $params["name"];
		
		// 检查编码是否存在
		if ($code) {
			$sql = "select count(*) as cnt from t_code_table_category where code = '%s' ";
			$data = $db->query($sql, $code);
			$cnt = $data[0]["cnt"];
			if ($cnt) {
				return $this->bad("码表分类编码[{$code}]已经存在");
			}
		} else {
			$code = "";
		}
		
		// 检查分类名称是否存在
		$sql = "select count(*) as cnt from t_code_table_category where name = '%s' ";
		$data = $db->query($sql, $name);
		$cnt = $data[0]["cnt"];
		if ($cnt) {
			return $this->bad("码表分类[{$name}]已经存在");
		}
		
		$id = $this->newId();
		$sql = "insert into t_code_table_category (id, code, name, parent_id)
				values ('%s', '%s', '%s', null)";
		
		$rc = $db->execute($sql, $id, $code, $name);
		if ($rc === false) {
			return $this->sqlError(__METHOD__, __LINE__);
		}
		
		// 操作成功
		$params["id"] = $id;
		return null;
	}

	/**
	 * 编辑码表分类
	 *
	 * @param array $params        	
	 */
	public function updateCodeTableCategory($params) {
		$db = $this->db;
		
		$id = $params["id"];
		$code = $params["code"] ?? "";
		$code = strtoupper($code);
		$name = $params["name"];
		
		$category = $this->getCodeTableCategoryById($id);
		if (! $category) {
			return $this->bad("要编辑的码表分类不存在");
		}
		
		// 检查编码是否存在
		if ($code) {
			$sql = "select count(*) as cnt from t_code_table_category 
					where code = '%s' and id <> '%s' ";
			$data = $db->query($sql, $code, $id);
			$cnt = $data[0]["cnt"];
			if ($cnt) {
				return $this->bad("码表分类编码[{$code}]已经存在");
			}
		} else {
			$code = "";
		}
		
		// 检查分类名称是否存在
		$sql = "select count(*) as cnt from t_code_table_category 
				where name = '%s' and id <> '%s' ";
		$data = $db->query($sql, $name, $id);
		$cnt = $data[0]["cnt"];
		if ($cnt) {
			return $this->bad("码表分类[{$name}]已经存在");
		}
		
		$sql = "update t_code_table_category
				set code = '%s', name = '%s'
				where id = '%s' ";
		
		$rc = $db->execute($sql, $code, $name, $id);
		if ($rc === false) {
			return $this->sqlError(__METHOD__, __LINE__);
		}
		
		// 操作成功
		return null;
	}

	public function getCodeTableCategoryById($id) {
		$db = $this->db;
		
		$sql = "select code, name from t_code_table_category where id = '%s' ";
		$data = $db->query($sql, $id);
		if ($data) {
			return [
					"code" => $data[0]["code"],
					"name" => $data[0]["name"]
			];
		} else {
			return null;
		}
	}

	/**
	 * 删除码表分类
	 */
	public function deleteCodeTableCategory(& $params) {
		$db = $this->db;
		
		$id = $params["id"];
		
		$category = $this->getCodeTableCategoryById($id);
		if (! $category) {
			return $this->bad("要删除的码表分类不存在");
		}
		$name = $category["name"];
		
		// 查询该分类是否被使用了
		$sql = "select count(*) as cnt from t_code_table_md
				where category_id = '%s' ";
		$data = $db->query($sql, $id);
		$cnt = $data[0]["cnt"];
		if ($cnt > 0) {
			return $this->bad("码表分类[$name]下还有码表，不能删除");
		}
		
		$sql = "delete from t_code_table_category where id = '%s' ";
		$rc = $db->execute($sql, $id);
		if ($rc === false) {
			return $this->sqlError(__METHOD__, __LINE__);
		}
		
		// 操作成功
		$params["name"] = $name;
		return null;
	}

	/**
	 * 码表列表
	 */
	public function codeTableList($params) {
		$db = $this->db;
		
		$categoryId = $params["categoryId"];
		
		$sql = "select id, code, name, table_name, memo
				from t_code_table_md
				where category_id = '%s' 
				order by code, table_name";
		$data = $db->query($sql, $categoryId);
		
		$result = [];
		foreach ( $data as $v ) {
			$result[] = [
					"id" => $v["id"],
					"code" => $v["code"],
					"name" => $v["name"],
					"tableName" => $v["table_name"],
					"memo" => $v["memo"]
			];
		}
		return $result;
	}

	/**
	 * 码表分类自定义字段 - 查询数据
	 */
	public function queryDataForCategory($params) {
		$db = $this->db;
		
		$queryKey = $params["queryKey"] ?? "";
		
		$sql = "select id, code, name
				from t_code_table_category
				where code like '%s' or name like '%s' ";
		$queryParams = [];
		$queryParams[] = "%{$queryKey}%";
		$queryParams[] = "%{$queryKey}%";
		
		$data = $db->query($sql, $queryParams);
		
		$result = [];
		foreach ( $data as $v ) {
			$result[] = [
					"id" => $v["id"],
					"code" => $v["code"],
					"name" => $v["name"]
			];
		}
		
		return $result;
	}

	private function checkTableName($tableName) {
		$tableName = strtolower($tableName);
		
		$len = strlen($tableName);
		if ($len == 0) {
			return $this->bad("表名不能为空");
		}
		
		$c = ord($tableName{0});
		$isABC = ord('a') <= $c && ord('z') >= $c;
		if (! $isABC) {
			return $this->bad("表名需要以字符开头");
		}
		
		for($i = 1; $i < $len; $i ++) {
			$c = ord($tableName{$i});
			$isABC = ord('a') <= $c && ord('z') >= $c;
			$isNumber = ord('0') <= $c && ord('9') >= $c;
			$isOK = $isABC || $isNumber || ord('_') == $c;
			if (! $isOK) {
				$index = $i + 1;
				return $this->bad("表名的第{$index}个字符非法");
			}
		}
		
		// 表名正确
		return null;
	}

	/**
	 * 返回码表的系统固有列
	 *
	 * @return array
	 */
	private function getCodeTableSysCols() {
		$result = [];
		
		// id
		$result[] = [
				"caption" => "id",
				"fieldName" => "id",
				"fieldType" => "varchar",
				"fieldLength" => 255,
				"fieldDecimal" => 0,
				"valueFrom" => 1,
				"valueFromTableName" => "",
				"valueFromColName" => "",
				"mustInput" => 1,
				"showOrder" => - 1000,
				"sysCol" => 1,
				"isVisible" => 2
		];
		
		// code
		$result[] = [
				"caption" => "编码",
				"fieldName" => "code",
				"fieldType" => "varchar",
				"fieldLength" => 255,
				"fieldDecimal" => 0,
				"valueFrom" => 1,
				"valueFromTableName" => "",
				"valueFromColName" => "",
				"mustInput" => 1,
				"showOrder" => 0,
				"sysCol" => 1,
				"isVisible" => 1
		];
		
		// name
		$result[] = [
				"caption" => "名称",
				"fieldName" => "name",
				"fieldType" => "varchar",
				"fieldLength" => 255,
				"fieldDecimal" => 0,
				"valueFrom" => 1,
				"valueFromTableName" => "",
				"valueFromColName" => "",
				"mustInput" => 1,
				"showOrder" => 1,
				"sysCol" => 1,
				"isVisible" => 1
		];
		
		// 拼音字头
		$result[] = [
				"caption" => "拼音字头",
				"fieldName" => "py",
				"fieldType" => "varchar",
				"fieldLength" => 255,
				"fieldDecimal" => 0,
				"valueFrom" => 1,
				"valueFromTableName" => "",
				"valueFromColName" => "",
				"mustInput" => 0,
				"showOrder" => - 900,
				"sysCol" => 1,
				"isVisible" => 2
		];
		
		// 数据域data_org
		$result[] = [
				"caption" => "数据域",
				"fieldName" => "data_org",
				"fieldType" => "varchar",
				"fieldLength" => 255,
				"fieldDecimal" => 0,
				"valueFrom" => 1,
				"valueFromTableName" => "",
				"valueFromColName" => "",
				"mustInput" => 0,
				"showOrder" => - 800,
				"sysCol" => 1,
				"isVisible" => 2
		];
		
		return $result;
	}

	/**
	 * 新增码表
	 *
	 * @param array $params        	
	 * @return array|null
	 */
	public function addCodeTable(&$params) {
		$db = $this->db;
		
		$categoryId = $params["categoryId"];
		if (! $this->getCodeTableCategoryById($categoryId)) {
			return $this->bad("码表分类不存在");
		}
		
		$code = strtoupper($params["code"] ?? "");
		$name = $params["name"];
		$memo = $params["memo"] ?? "";
		$py = $params["py"];
		$tableName = strtolower($params["tableName"]);
		
		// 检查编码是否已经存在
		if ($code) {
			$sql = "select count(*) as cnt from t_code_table_md
					where code = '%s' ";
			$data = $db->query($sql, $code);
			$cnt = $data[0]["cnt"];
			if ($cnt > 0) {
				return $this->bad("编码为[{$code}]的码表已经存在");
			}
		}
		
		// 检查名称是否已经存在
		$sql = "select count(*) as cnt from t_code_table_md
					where name = '%s' ";
		$data = $db->query($sql, $name);
		$cnt = $data[0]["cnt"];
		if ($cnt > 0) {
			return $this->bad("名称为[{$name}]的码表已经存在");
		}
		
		// 检查表名是否正确
		$rc = $this->checkTableName($tableName);
		if ($rc) {
			return $rc;
		}
		// 检查名表是否已经存在
		$sql = "select count(*) as cnt from t_code_table_md
					where table_name = '%s' ";
		$data = $db->query($sql, $tableName);
		$cnt = $data[0]["cnt"];
		if ($cnt > 0) {
			return $this->bad("表名为[{$tableName}]的码表已经存在");
		}
		// 检查数据库中是否已经存在该表了
		$dbUtilDAO = new DBUtilDAO($db);
		if ($dbUtilDAO->tableExists($tableName)) {
			return $this->bad("表[{$tableName}]已经在数据库中存在了");
		}
		
		$id = $this->newId();
		
		$sql = "insert into t_code_table_md (id, category_id, code, name, table_name, py, memo)
				values ('%s', '%s', '%s', '%s', '%s', '%s', '%s')";
		$rc = $db->execute($sql, $id, $categoryId, $code, $name, $tableName, $py, $memo);
		if ($rc === false) {
			return $this->sqlError(__METHOD__, __LINE__);
		}
		
		// 码表标准列
		$cols = $this->getCodeTableSysCols();
		foreach ( $cols as $v ) {
			$sql = "insert into t_code_table_cols_md (id, table_id,
						caption, db_field_name, db_field_type, db_field_length,
						db_field_decimal, show_order, value_from, value_from_table_name,
						value_from_col_name, must_input, sys_col, is_visible)
					values ('%s', '%s',
						'%s', '%s', '%s', %d,
						%d, %d, %d, '%s',
						'%s', %d, %d, %d)";
			$rc = $db->execute($sql, $this->newId(), $id, $v["caption"], $v["fieldName"], 
					$v["fieldType"], $v["fieldLength"], $v["fieldDecimal"], $v["showOrder"], 
					$v["valueFrom"], $v["valueFromTableName"], $v["valueFromColName"], 
					$v["mustInput"], $v["sysCol"], $v["isVisible"]);
			if ($rc === false) {
				return $this->sqlError(__METHOD__, __LINE__);
			}
		}
		
		// 创建数据库表
		$sql = "CREATE TABLE IF NOT EXISTS `{$tableName}` (";
		foreach ( $cols as $v ) {
			$fieldName = $v["fieldName"];
			$fieldType = $v["fieldType"];
			$fieldLength = $v["fieldLength"];
			$fieldDecimal = $v["fieldDecimal"];
			$mustInput = $v["mustInput"];
			
			$type = $fieldType;
			
			if ($fieldType == "varchar") {
				$type .= "({$fieldLength})";
			} else if ($fieldType == "decimal") {
				$type .= "(19, {$fieldDecimal})";
			} else if ($fieldType == "int") {
				$type .= "(11)";
			}
			
			$sql .= "`{$fieldName}` {$type} ";
			if ($mustInput == 1) {
				$sql .= " NOT NULL";
			} else {
				$sql .= " DEFAULT NULL";
			}
			$sql .= ",";
		}
		$sql .= "PRIMARY KEY (`id`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
		$rc = $db->execute($sql);
		if ($rc === false) {
			return $this->sqlError(__METHOD__, __LINE__);
		}
		
		// 操作成功
		$params["id"] = $id;
		return null;
	}

	private function valueFromCodeToName($valueFrom) {
		switch ($valueFrom) {
			case 1 :
				return "直接录入";
			case 2 :
				return "引用系统数据字典";
			case 3 :
				return "引用其他码表";
			default :
				return "";
		}
	}

	/**
	 * 某个码表的列
	 */
	public function codeTableColsList($params) {
		$db = $this->db;
		
		// 码表id
		$id = $params["id"];
		
		$sql = "select id, caption, db_field_name, db_field_type, db_field_length,
						db_field_decimal, show_order, value_from, value_from_table_name,
						value_from_col_name, must_input, sys_col, is_visible
				from t_code_table_cols_md
				where table_id = '%s' 
				order by show_order";
		$data = $db->query($sql, $id);
		
		$result = [];
		foreach ( $data as $v ) {
			$result[] = [
					"id" => $v["id"],
					"caption" => $v["caption"],
					"fieldName" => $v["db_field_name"],
					"fieldType" => $v["db_field_type"],
					"fieldLength" => $v["db_field_length"],
					"fieldDecimal" => $v["db_field_decimal"],
					"showOrder" => $v["show_order"],
					"valueFrom" => $this->valueFromCodeToName($v["value_from"]),
					"valueFromTableName" => $v["value_from_table_name"],
					"valueFromColName" => $v["value_from_col_name"],
					"mustInput" => $v["must_input"] == 1 ? "必录项" : "",
					"sysCol" => $v["sys_col"] == 1 ? "系统列" : "",
					"isVisible" => $v["is_visible"] == 1 ? "可见" : "不可见"
			];
		}
		
		return $result;
	}

	public function getCodeTableById($id) {
		$db = $this->db;
		
		$sql = "select code, name from t_code_table_md where id = '%s' ";
		$data = $db->query($sql, $id);
		if ($data) {
			return [
					"code" => $data[0]["code"],
					"name" => $data[0]["name"]
			];
		} else {
			return null;
		}
	}

	/**
	 * 删除码表
	 */
	public function deleteCodeTable(& $params) {
		$db = $this->db;
		
		// 码表id
		$id = $params["id"];
		
		$codeTable = $this->getCodeTableById($id);
		if (! $codeTable) {
			return $this->bad("要删除的码表不存在");
		}
		$name = $codeTable["name"];
		
		// 列
		$sql = "delete from t_code_table_cols_md where table_id = '%s' ";
		$rc = $db->execute($sql, $id);
		if ($rc === false) {
			return $this->sqlError(__METHOD__, __LINE__);
		}
		
		// 主表
		$sql = "delete from t_code_table_md where id = '%s' ";
		$rc = $db->execute($sql, $id);
		if ($rc === false) {
			return $this->sqlError(__METHOD__, __LINE__);
		}
		
		// 操作成功
		$params["name"] = $name;
		return null;
	}
}
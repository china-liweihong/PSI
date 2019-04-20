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
				"fieldType" => "varchar(",
				"fieldLength" => 255,
				"fieldDecimal" => 0,
				"valueFrom" => "",
				"valueFromTableName" => "",
				"valueFromColName" => "",
				"mustInput" => 1,
				"showOrder" => - 1000
		];
		
		// code
		$result[] = [
				"caption" => "编码",
				"fieldName" => "code",
				"fieldType" => "varchar",
				"fieldLength" => 255,
				"fieldDecimal" => 0,
				"valueFrom" => "",
				"valueFromTableName" => "",
				"valueFromColName" => "",
				"mustInput" => 1,
				"showOrder" => 0
		];
		
		// name
		$result[] = [
				"caption" => "名称",
				"fieldName" => "name",
				"fieldType" => "varchar",
				"fieldLength" => 255,
				"fieldDecimal" => 0,
				"valueFrom" => "",
				"valueFromTableName" => "",
				"valueFromColName" => "",
				"mustInput" => 1,
				"showOrder" => 1
		];
		
		// 拼音字头
		$result[] = [
				"caption" => "拼音字头",
				"fieldName" => "py",
				"fieldType" => "varchar",
				"fieldLength" => 255,
				"fieldDecimal" => 0,
				"valueFrom" => "",
				"valueFromTableName" => "",
				"valueFromColName" => "",
				"mustInput" => 0,
				"showOrder" => -900
		];
		
		
		// 数据域data_org
		$result[] = [
				"caption" => "数据域",
				"fieldName" => "data_org",
				"fieldType" => "varchar",
				"fieldLength" => 255,
				"fieldDecimal" => 0,
				"valueFrom" => "",
				"valueFromTableName" => "",
				"valueFromColName" => "",
				"mustInput" => 0,
				"showOrder" => -800
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
		
		$code = strtoupper($params["code"] ?? "");
		$name = $params["name"];
		$memo = $params["memo"] ?? "";
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
		
		$id = $this->newId();
		
		$sql = "insert into t_code_table_md (id, code, name, table_name, memo)
				values ('%s', '%s', '%s', '%s', '%s')";
		$rc = $db->execute($sql, $id, $code, $name, $tableName, $memo);
		if ($rc === false) {
			return $this->sqlError(__METHOD__, __LINE__);
		}
		
		return $this->todo();
	}
}
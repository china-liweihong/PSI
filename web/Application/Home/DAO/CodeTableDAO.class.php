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
		
		$code = $params["code"];
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
		$code = $params["code"];
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
}
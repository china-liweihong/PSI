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
		return $this->todo();
	}
}
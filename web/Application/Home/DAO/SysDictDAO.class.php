<?php

namespace Home\DAO;

/**
 * 系统数据字典DAO
 *
 * @author 李静波
 */
class SysDictDAO extends PSIBaseExDAO {

	/**
	 * 系统数据字典分类列表
	 */
	public function categoryList($params) {
		$db = $this->db;
		
		$sql = "select id, code, name
				from t_dict_table_category
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
}
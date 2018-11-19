<?php

namespace Home\DAO;

/**
 * 拆分单 DAO
 *
 * @author 李静波
 */
class WSPBillDAO extends PSIBaseExDAO {

	/**
	 * 获得某个拆分单的商品构成
	 *
	 * @param array $params        	
	 */
	public function goodsBOM($params) {
		return [];
	}

	/**
	 * 拆分单详情
	 */
	public function wspBillInfo($params) {
		$db = $this->db;
		
		$id = $params["id"];
		
		if ($id) {
			// 编辑
		} else {
			// 新建
		}
		
		return $this->emptyResult();
	}
}
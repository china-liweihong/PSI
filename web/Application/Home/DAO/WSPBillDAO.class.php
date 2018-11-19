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
		
		$companyId = $params["companyId"];
		if ($this->companyIdNotExists($companyId)) {
			return $this->emptyResult();
		}
		
		$bcDAO = new BizConfigDAO($db);
		$dataScale = $bcDAO->getGoodsCountDecNumber($companyId);
		$fmt = "decimal(19, " . $dataScale . ")";
		
		$result = [];
		
		$id = $params["id"];
		
		if ($id) {
			// 编辑
		} else {
			// 新建
			$result["bizUserId"] = $params["loginUserId"];
			$result["bizUserName"] = $params["loginUserName"];
		}
		
		return $result;
	}

	/**
	 * 新建拆分单
	 * 
	 * @param array $bill        	
	 */
	public function addWSPBill(& $bill) {
		return $this->todo();
	}

	/**
	 * 编辑拆分单
	 * 
	 * @param array $bill        	
	 */
	public function updateWSPBill(& $bill) {
		return $this->todo();
	}
}
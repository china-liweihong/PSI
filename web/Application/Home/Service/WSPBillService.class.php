<?php

namespace Home\Service;

use Home\DAO\WSPBillDAO;

/**
 * 存货拆分Service
 *
 * @author 李静波
 */
class WSPBillService extends PSIBaseExService {
	private $LOG_CATEGORY = "存货拆分";

	/**
	 * 获得某个拆分单的商品构成
	 * 
	 * @param array $params        	
	 */
	public function goodsBOM($params) {
		$dao = new WSPBillDAO($this->db());
		return $dao->goodsBOM($params);
	}
}
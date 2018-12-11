<?php

namespace Home\Service;

use Home\DAO\DMOBillDAO;

/**
 * 成品委托生产订单Service
 *
 * @author 李静波
 */
class DMOBillService extends PSIBaseExService {
	private $LOG_CATEGORY = "成品委托生产订单";

	/**
	 * 获得成品委托生产订单的信息
	 */
	public function dmoBillInfo($params) {
		if ($this->isNotOnline()) {
			return $this->emptyResult();
		}
		
		$params["companyId"] = $this->getCompanyId();
		$params["loginUserId"] = $this->getLoginUserId();
		$params["loginUserName"] = $this->getLoginUserName();
		
		$dao = new DMOBillDAO($this->db());
		return $dao->dmoBillInfo($params);
	}
}
<?php

namespace Home\Service;

use Home\DAO\DMWBillDAO;

/**
 * 成品委托生产入库单Service
 *
 * @author 李静波
 */
class DMWBillService extends PSIBaseExService {
	private $LOG_CATEGORY = "成品委托生产入库";

	/**
	 * 成品委托生产入库单 - 单据详情
	 */
	public function dmwBillInfo($params) {
		if ($this->isNotOnline()) {
			return $this->emptyResult();
		}
		
		$params["companyId"] = $this->getCompanyId();
		$params["loginUserId"] = $this->getLoginUserId();
		$params["loginUserName"] = $this->getLoginUserName();
		
		$dao = new DMWBillDAO($this->db());
		return $dao->dmwBillInfo($params);
	}
}
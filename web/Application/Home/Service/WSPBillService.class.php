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
		if ($this->isNotOnline()) {
			return $this->emptyResult();
		}
		
		$params["companyId"] = $this->getCompanyId();
		
		$dao = new WSPBillDAO($this->db());
		return $dao->goodsBOM($params);
	}

	/**
	 * 拆分单详情
	 */
	public function wspBillInfo($params) {
		if ($this->isNotOnline()) {
			return $this->emptyResult();
		}
		
		$params["loginUserId"] = $this->getLoginUserId();
		$params["loginUserName"] = $this->getLoginUserName();
		$params["companyId"] = $this->getCompanyId();
		
		$dao = new WSPBillDAO($this->db());
		return $dao->wspBillInfo($params);
	}

	/**
	 * 新增或编辑拆分单
	 */
	public function editWSPBill($json) {
		if ($this->isNotOnline()) {
			return $this->notOnlineError();
		}
		
		$bill = json_decode(html_entity_decode($json), true);
		if ($bill == null) {
			return $this->bad("传入的参数错误，不是正确的JSON格式");
		}
		
		$db = $this->db();
		
		$db->startTrans();
		
		$dao = new WSPBillDAO($db);
		
		$id = $bill["id"];
		
		$log = null;
		
		$bill["companyId"] = $this->getCompanyId();
		
		if ($id) {
			// 编辑
			
			$bill["loginUserId"] = $this->getLoginUserId();
			
			$rc = $dao->updateWSPBill($bill);
			if ($rc) {
				$db->rollback();
				return $rc;
			}
			
			$ref = $bill["ref"];
			
			$log = "编辑拆分单，单号：{$ref}";
		} else {
			// 新建
			
			$bill["loginUserId"] = $this->getLoginUserId();
			$bill["dataOrg"] = $this->getLoginUserDataOrg();
			
			$rc = $dao->addWSPBill($bill);
			if ($rc) {
				$db->rollback();
				return $rc;
			}
			
			$id = $bill["id"];
			$ref = $bill["ref"];
			
			$log = "新建拆分单，单号：{$ref}";
		}
		
		// 记录业务日志
		$bs = new BizlogService($db);
		$bs->insertBizlog($log, $this->LOG_CATEGORY);
		
		$db->commit();
		
		return $this->ok($id);
	}

	/**
	 * 拆分单主表列表
	 */
	public function wspbillList($params) {
		if ($this->isNotOnline()) {
			return $this->emptyResult();
		}
		
		$params["loginUserId"] = $this->getLoginUserId();
		
		$dao = new WSPBillDAO($this->db());
		return $dao->wspbillList($params);
	}

	/**
	 * 拆分单明细
	 */
	public function wspBillDetailList($params) {
		if ($this->isNotOnline()) {
			return $this->emptyResult();
		}
		
		$params["companyId"] = $this->getCompanyId();
		
		$dao = new WSPBillDAO($this->db());
		return $dao->wspBillDetailList($params);
	}

	/**
	 * 拆分单明细 - 拆分后明细
	 */
	public function wspBillDetailExList($params) {
		if ($this->isNotOnline()) {
			return $this->emptyResult();
		}
		
		$params["companyId"] = $this->getCompanyId();
		
		$dao = new WSPBillDAO($this->db());
		return $dao->wspBillDetailExList($params);
	}

	/**
	 * 删除拆分单
	 */
	public function deleteWSPBill($params) {
		if ($this->isNotOnline()) {
			return $this->notOnlineError();
		}
		
		$db = $this->db();
		$db->startTrans();
		
		$dao = new WSPBillDAO($db);
		
		$rc = $dao->deleteWSPBill($params);
		if ($rc) {
			$db->rollback();
			return $rc;
		}
		
		$ref = $params["ref"];
		
		$bs = new BizlogService($db);
		$log = "删除拆分单，单号：$ref";
		$bs->insertBizlog($log, $this->LOG_CATEGORY);
		
		$db->commit();
		
		return $this->ok();
	}
}
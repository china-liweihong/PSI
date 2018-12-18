<?php

namespace Home\Controller;

use Home\Common\FIdConst;
use Home\Service\UserService;
use Home\Service\DMOBillService;

/**
 * 成品委托生产Controller
 *
 * @author 李静波
 *        
 */
class DMController extends PSIBaseController {

	/**
	 * 成品委托生产订单 - 主页面
	 */
	public function dmobillIndex() {
		$us = new UserService();
		
		if ($us->hasPermission(FIdConst::DMO)) {
			$this->initVar();
			
			$this->assign("pAdd", $us->hasPermission(FIdConst::DMO_ADD) ? "1" : "0");
			$this->assign("pEdit", $us->hasPermission(FIdConst::DMO_EDIT) ? "1" : "0");
			$this->assign("pDelete", $us->hasPermission(FIdConst::DMO_DELETE) ? "1" : "0");
			$this->assign("pCommit", $us->hasPermission(FIdConst::DMO_COMMIT) ? "1" : "0");
			$this->assign("pGenDMWBill", $us->hasPermission(FIdConst::DMO_GEN_DMW_BILL) ? "1" : "0");
			$this->assign("showAddGoodsButton", $us->hasPermission(FIdConst::GOODS_ADD) ? "1" : "0");
			$this->assign("showAddFactoryButton", 
					$us->hasPermission(FIdConst::FACTORY_ADD) ? "1" : "0");
			$this->assign("pCloseBill", $us->hasPermission(FIdConst::DMO_CLOSE_BILL) ? "1" : "0");
			$this->assign("pGenPDF", $us->hasPermission(FIdConst::DMO_PDF) ? "1" : "0");
			$this->assign("pPrint", $us->hasPermission(FIdConst::DMO_PRINT) ? "1" : "0");
			
			$this->assign("title", "成品委托生产订单");
			
			$this->display();
		} else {
			$this->gotoLoginPage("/Home/DM/dmobillIndex");
		}
	}

	/**
	 * 获得成品委托生产订单的信息
	 */
	public function dmoBillInfo() {
		if (IS_POST) {
			$params = [
					"id" => I("post.id")
			];
			
			$service = new DMOBillService();
			$this->ajaxReturn($service->dmoBillInfo($params));
		}
	}

	/**
	 * 新增或编辑成品委托生产订单
	 */
	public function editDMOBill() {
		if (IS_POST) {
			$json = I("post.jsonStr");
			$service = new DMOBillService();
			$this->ajaxReturn($service->editDMOBill($json));
		}
	}

	/**
	 * 获得成品委托生产订单主表信息列表
	 */
	public function dmobillList() {
		if (IS_POST) {
			$params = [
					"billStatus" => I("post.billStatus"),
					"ref" => I("post.ref"),
					"fromDT" => I("post.fromDT"),
					"toDT" => I("post.toDT"),
					"factoryId" => I("post.factoryId"),
					"start" => I("post.start"),
					"limit" => I("post.limit")
			];
			
			$service = new DMOBillService();
			$this->ajaxReturn($service->dmobillList($params));
		}
	}

	/**
	 * 获得成品委托生产订单的明细信息
	 */
	public function dmoBillDetailList() {
		if (IS_POST) {
			$params = [
					"id" => I("post.id")
			];
			
			$service = new DMOBillService();
			$this->ajaxReturn($service->dmoBillDetailList($params));
		}
	}
}
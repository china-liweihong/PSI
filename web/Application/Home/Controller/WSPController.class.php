<?php

namespace Home\Controller;

use Home\Common\FIdConst;
use Home\Service\UserService;
use Home\Service\WSPBillService;

/**
 * 存货拆分Controller
 *
 * @author 李静波
 *        
 */
class WSPController extends PSIBaseController {

	/**
	 * 存货拆分 - 主页面
	 */
	public function index() {
		$us = new UserService();
		
		if ($us->hasPermission(FIdConst::WSP)) {
			$this->initVar();
			
			$this->assign("title", "存货拆分");
			
			$this->display();
		} else {
			$this->gotoLoginPage("/Home/WSP/index");
		}
	}

	/**
	 * 获得某个拆分单的商品构成
	 */
	public function goodsBOM() {
		if (IS_POST) {
			$params = [
					"id" => I("post.id")
			];
			
			$service = new WSPBillService();
			$this->ajaxReturn($service->goodsBOM($params));
		}
	}

	/**
	 * 拆分单详情
	 */
	public function wspBillInfo() {
		if (IS_POST) {
			$params = [
					"id" => I("post.id")
			];
			
			$service = new WSPBillService();
			$this->ajaxReturn($service->wspBillInfo($params));
		}
	}

	/**
	 * 新增或编辑拆分单
	 */
	public function editWSPBill() {
		if (IS_POST) {
			$json = I("post.jsonStr");
			$ps = new WSPBillService();
			$this->ajaxReturn($ps->editWSPBill($json));
		}
	}

	/**
	 * 拆分单主表列表
	 */
	public function wspbillList() {
		if (IS_POST) {
			$params = [
					"billStatus" => I("post.billStatus"),
					"ref" => I("post.ref"),
					"fromDT" => I("post.fromDT"),
					"toDT" => I("post.toDT"),
					"fromWarehouseId" => I("post.fromWarehouseId"),
					"toWarehouseId" => I("post.toWarehouseId"),
					"start" => I("post.start"),
					"limit" => I("post.limit")
			];
			
			$service = new WSPBillService();
			$this->ajaxReturn($service->wspbillList($params));
		}
	}

	/**
	 * 拆分单明细
	 */
	public function wspBillDetailList() {
		if (IS_POST) {
			$params = [
					"id" => I("post.id")
			];
			
			$service = new WSPBillService();
			$this->ajaxReturn($service->wspBillDetailList($params));
		}
	}

	/**
	 * 拆分单明细 - 拆分后明细
	 */
	public function wspBillDetailExList() {
		if (IS_POST) {
			$params = [
					"id" => I("post.id")
			];
			
			$service = new WSPBillService();
			$this->ajaxReturn($service->wspBillDetailExList($params));
		}
	}

	/**
	 * 删除拆分单
	 */
	public function deleteWSPBill() {
		if (IS_POST) {
			$params = [
					"id" => I("post.id")
			];
			
			$service = new WSPBillService();
			$this->ajaxReturn($service->deleteWSPBill($params));
		}
	}

	/**
	 * 提交拆分单
	 */
	public function commitWSPBill() {
		if (IS_POST) {
			$params = [
					"id" => I("post.id")
			];
			
			$service = new WSPBillService();
			$this->ajaxReturn($service->commitWSPBill($params));
		}
	}

	/**
	 * 拆分单生成pdf文件
	 */
	public function wspBillPdf() {
		$params = [
				"ref" => I("get.ref")
		];
		
		$ws = new WSPBillService();
		$ws->pdf($params);
	}
}
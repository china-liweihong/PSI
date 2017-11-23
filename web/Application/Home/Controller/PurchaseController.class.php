<?php

namespace Home\Controller;

use Home\Common\FIdConst;
use Home\Service\POBillService;
use Home\Service\PWBillService;
use Home\Service\UserService;

/**
 * 采购Controller
 *
 * @author 李静波
 *        
 */
class PurchaseController extends PSIBaseController {

	/**
	 * 采购入库单主页面
	 */
	public function pwbillIndex() {
		$us = new UserService();
		
		if ($us->hasPermission(FIdConst::PURCHASE_WAREHOUSE)) {
			$this->initVar();
			
			$this->assign("title", "采购入库");
			
			$this->assign("showAddGoodsButton", $us->hasPermission(FIdConst::GOODS_ADD) ? "1" : "0");
			
			$this->display();
		} else {
			$this->gotoLoginPage("/Home/Purchase/pwbillIndex");
		}
	}

	/**
	 * 获得采购入库单主表列表
	 */
	public function pwbillList() {
		if (IS_POST) {
			$ps = new PWBillService();
			$params = array(
					"billStatus" => I("post.billStatus"),
					"ref" => I("post.ref"),
					"fromDT" => I("post.fromDT"),
					"toDT" => I("post.toDT"),
					"warehouseId" => I("post.warehouseId"),
					"supplierId" => I("post.supplierId"),
					"paymentType" => I("post.paymentType"),
					"start" => I("post.start"),
					"limit" => I("post.limit")
			);
			$this->ajaxReturn($ps->pwbillList($params));
		}
	}

	/**
	 * 获得采购入库单的商品明细记录
	 */
	public function pwBillDetailList() {
		if (IS_POST) {
			$pwbillId = I("post.pwBillId");
			$ps = new PWBillService();
			$this->ajaxReturn($ps->pwBillDetailList($pwbillId));
		}
	}

	/**
	 * 新增或编辑采购入库单
	 */
	public function editPWBill() {
		if (IS_POST) {
			$json = I("post.jsonStr");
			$ps = new PWBillService();
			$this->ajaxReturn($ps->editPWBill($json));
		}
	}

	/**
	 * 获得采购入库单的信息
	 */
	public function pwBillInfo() {
		if (IS_POST) {
			$params = array(
					"id" => I("post.id"),
					"pobillRef" => I("post.pobillRef")
			);
			
			$ps = new PWBillService();
			$this->ajaxReturn($ps->pwBillInfo($params));
		}
	}

	/**
	 * 删除采购入库单
	 */
	public function deletePWBill() {
		if (IS_POST) {
			$id = I("post.id");
			$ps = new PWBillService();
			$this->ajaxReturn($ps->deletePWBill($id));
		}
	}

	/**
	 * 提交采购入库单
	 */
	public function commitPWBill() {
		if (IS_POST) {
			$id = I("post.id");
			$ps = new PWBillService();
			$this->ajaxReturn($ps->commitPWBill($id));
		}
	}

	/**
	 * 采购订单 - 主页面
	 */
	public function pobillIndex() {
		$us = new UserService();
		
		if ($us->hasPermission(FIdConst::PURCHASE_ORDER)) {
			$this->initVar();
			
			$this->assign("title", "采购订单");
			
			$this->assign("pConfirm", 
					$us->hasPermission(FIdConst::PURCHASE_ORDER_CONFIRM) ? "1" : "0");
			$this->assign("pGenPWBill", 
					$us->hasPermission(FIdConst::PURCHASE_ORDER_GEN_PWBILL) ? "1" : "0");
			$this->assign("showAddGoodsButton", $us->hasPermission(FIdConst::GOODS_ADD) ? "1" : "0");
			
			$this->display();
		} else {
			$this->gotoLoginPage("/Home/Purchase/pobillIndex");
		}
	}

	/**
	 * 获得采购订单主表信息列表
	 */
	public function pobillList() {
		if (IS_POST) {
			$ps = new POBillService();
			$params = array(
					"billStatus" => I("post.billStatus"),
					"ref" => I("post.ref"),
					"fromDT" => I("post.fromDT"),
					"toDT" => I("post.toDT"),
					"supplierId" => I("post.supplierId"),
					"paymentType" => I("post.paymentType"),
					"start" => I("post.start"),
					"limit" => I("post.limit")
			);
			$this->ajaxReturn($ps->pobillList($params));
		}
	}

	/**
	 * 新增或编辑采购订单
	 */
	public function editPOBill() {
		if (IS_POST) {
			$json = I("post.jsonStr");
			$ps = new POBillService();
			$this->ajaxReturn($ps->editPOBill($json));
		}
	}

	/**
	 * 获得采购订单的信息
	 */
	public function poBillInfo() {
		if (IS_POST) {
			$params = array(
					"id" => I("post.id")
			);
			
			$ps = new POBillService();
			$this->ajaxReturn($ps->poBillInfo($params));
		}
	}

	/**
	 * 获得采购订单的明细信息
	 */
	public function poBillDetailList() {
		if (IS_POST) {
			$params = array(
					"id" => I("post.id")
			);
			
			$ps = new POBillService();
			$this->ajaxReturn($ps->poBillDetailList($params));
		}
	}

	/**
	 * 删除采购订单
	 */
	public function deletePOBill() {
		if (IS_POST) {
			$params = array(
					"id" => I("post.id")
			);
			
			$ps = new POBillService();
			$this->ajaxReturn($ps->deletePOBill($params));
		}
	}

	/**
	 * 审核采购订单
	 */
	public function commitPOBill() {
		if (IS_POST) {
			$params = array(
					"id" => I("post.id")
			);
			
			$ps = new POBillService();
			$this->ajaxReturn($ps->commitPOBill($params));
		}
	}

	/**
	 * 取消审核采购订单
	 */
	public function cancelConfirmPOBill() {
		if (IS_POST) {
			$params = array(
					"id" => I("post.id")
			);
			
			$ps = new POBillService();
			$this->ajaxReturn($ps->cancelConfirmPOBill($params));
		}
	}

	/**
	 * 采购订单生成PDF文件
	 */
	public function poBillPdf() {
		$params = array(
				"ref" => I("get.ref")
		);
		
		$ps = new POBillService();
		$ps->pdf($params);
	}

	/**
	 * 采购入库单生成PDF文件
	 */
	public function pwBillPdf() {
		$params = array(
				"ref" => I("get.ref")
		);
		
		$ps = new PWBillService();
		$ps->pdf($params);
	}

	/**
	 * 采购订单执行的采购入库单信息
	 */
	public function poBillPWBillList() {
		if (IS_POST) {
			$params = array(
					"id" => I("post.id")
			);
			
			$ps = new PWBillService();
			$this->ajaxReturn($ps->poBillPWBillList($params));
		}
	}

	/**
	 * 关闭采购订单
	 */
	public function closePOBill() {
		if (IS_POST) {
			$params = array(
					"id" => I("post.id")
			);
			
			$ps = new POBillService();
			$this->ajaxReturn($ps->closePOBill($params));
		}
	}

	/**
	 * 取消关闭采购订单
	 */
	public function cancelClosedPOBill() {
		if (IS_POST) {
			$params = array(
					"id" => I("post.id")
			);
			
			$ps = new POBillService();
			$this->ajaxReturn($ps->cancelClosedPOBill($params));
		}
	}
}
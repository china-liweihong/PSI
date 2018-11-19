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
			$params = [];
			
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
}
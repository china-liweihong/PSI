<?php

namespace Home\Controller;

use Home\Common\FIdConst;
use Home\Service\UserService;
use Home\Service\FactoryService;

/**
 * 工厂Controller
 *
 * @author 李静波
 *        
 */
class FactoryController extends PSIBaseController {

	/**
	 * 工厂 - 主页面
	 */
	public function index() {
		$us = new UserService();
		
		if ($us->hasPermission(FIdConst::FACTORY)) {
			$this->initVar();
			
			$this->assign("title", "工厂");
			
			$this->display();
		} else {
			$this->gotoLoginPage("/Home/Factory/index");
		}
	}

	/**
	 * 新建或编辑工厂分类
	 */
	public function editCategory() {
		if (IS_POST) {
			$params = [
					"id" => I("post.id"),
					"code" => strtoupper(I("post.code")),
					"name" => I("post.name")
			];
			
			$service = new FactoryService();
			$this->ajaxReturn($service->editCategory($params));
		}
	}
}

<?php

namespace Home\Controller;

use Home\Common\FIdConst;
use Home\Service\UserService;
use Home\Service\CodeTableService;

/**
 * 码表Controller
 *
 * @author 李静波
 *        
 */
class CodeTableController extends PSIBaseController {

	/**
	 * 码表设置 - 主页面
	 */
	public function index() {
		$us = new UserService();
		
		if ($us->hasPermission(FIdConst::CODE_TABLE)) {
			$this->initVar();
			
			$this->assign("title", "码表设置");
			
			$this->display();
		} else {
			$this->gotoLoginPage("/Home/CodeTable/index");
		}
	}

	/**
	 * 码表分类列表
	 */
	public function categoryList() {
		if (IS_POST) {
			$params = [];
			
			$service = new CodeTableService();
			$this->ajaxReturn($service->categoryList($params));
		}
	}

	/**
	 * 新增或编辑码表分类
	 */
	public function editCodeTableCategory() {
		if (IS_POST) {
			$params = [
					"id" => I("post.id"),
					"code" => I("post.code"),
					"name" => I("post.name")
			];
			
			$service = new CodeTableService();
			$this->ajaxReturn($service->editCodeTableCategory($params));
		}
	}
}
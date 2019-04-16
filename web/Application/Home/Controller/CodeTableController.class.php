<?php

namespace Home\Controller;

use Home\Common\FIdConst;
use Home\Service\UserService;

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
}
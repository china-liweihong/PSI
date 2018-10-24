<?php

namespace Home\Controller;

use Home\Common\FIdConst;
use Home\Service\UserService;
use Home\Service\GLPeriodService;

/**
 * 会计期间Controller
 *
 * @author 李静波
 *        
 */
class GLPeriodController extends PSIBaseController {

	/**
	 * 会计期间 - 主页面
	 */
	public function index() {
		$us = new UserService();
		
		if ($us->hasPermission(FIdConst::GL_PERIOD)) {
			$this->initVar();
			
			$this->assign("title", "会计期间");
			
			$this->display();
		} else {
			$this->gotoLoginPage("/Home/GLPeriod/index");
		}
	}

	/**
	 * 返回所有的公司列表
	 */
	public function companyList() {
		if (IS_POST) {
			$service = new GLPeriodService();
			$this->ajaxReturn($service->companyList());
		}
	}
}
<?php

namespace Home\Controller;

use Home\Common\FIdConst;
use Home\Service\UserService;

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
}
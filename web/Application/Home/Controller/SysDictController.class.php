<?php

namespace Home\Controller;

use Home\Common\FIdConst;
use Home\Service\UserService;

/**
 * 系统数据字典Controller
 *
 * @author 李静波
 *        
 */
class SysDictController extends PSIBaseController {

	/**
	 * 系统数据字典 - 主页面
	 */
	public function index() {
		$us = new UserService();
		
		if ($us->hasPermission(FIdConst::SYS_DICT)) {
			$this->initVar();
			
			$this->assign("title", "系统数据字典");
			
			$this->display();
		} else {
			$this->gotoLoginPage("/Home/SysDict/index");
		}
	}
}
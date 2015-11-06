<?php

namespace Home\Controller;

use Think\Controller;
use Home\Service\UserService;
use Home\Service\BizConfigService;
use Home\Common\FIdConst;

/**
 * 业务设置Controller
 *
 * @author 李静波
 *        
 */
class BizConfigController extends Controller {

	/**
	 * 业务设置 - 主页面
	 */
	public function index() {
		$us = new UserService();
		
		if ($us->hasPermission(FIdConst::BIZ_CONFIG)) {
			$bcs = new BizConfigService();
			$this->assign("productionName", $bcs->getProductionName());
			
			$this->assign("title", "业务设置");
			$this->assign("uri", __ROOT__ . "/");
			
			$this->assign("loginUserName", $us->getLoignUserNameWithOrgFullName());
			
			$dtFlag = getdate();
			$this->assign("dtFlag", $dtFlag[0]);
			$this->display();
		} else {
			redirect(__ROOT__ . "/Home/User/login");
		}
	}

	/**
	 * 获得所有的配置项目
	 */
	public function allConfigs() {
		if (IS_POST) {
			$bs = new BizConfigService();
			
			$this->ajaxReturn($bs->allConfigs());
		}
	}

	/**
	 * 获得所有的配置项目以及配置项目附带的数据
	 */
	public function allConfigsWithExtData() {
		if (IS_POST) {
			$bs = new BizConfigService();
			
			$this->ajaxReturn($bs->allConfigsWithExtData());
		}
	}

	/**
	 * 编辑配置项
	 */
	public function edit() {
		if (IS_POST) {
			$bs = new BizConfigService();
			
			$params = array(
					"9000-01" => I("post.value9000-01"),
					"9000-02" => I("post.value9000-02"),
					"9000-03" => I("post.value9000-03"),
					"9000-04" => I("post.value9000-04"),
					"9000-05" => I("post.value9000-05"),
					"1003-02" => I("post.value1003-02"),
					"2001-01" => I("post.value2001-01"),
					"2002-01" => I("post.value2002-01"),
					"2002-02" => I("post.value2002-02"),
					"9001-01" => I("post.value9001-01"),
					"9002-01" => I("post.value9002-01")
			);
			
			$this->ajaxReturn($bs->edit($params));
		}
	}
}
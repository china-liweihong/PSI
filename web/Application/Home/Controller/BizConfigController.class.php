<?php

namespace Home\Controller;

use Think\Controller;
use Home\Service\UserService;
use Home\Service\BizlogService;
use Home\Common\FIdConst;

class BizConfigController extends Controller {

	public function index() {
		$us = new UserService();

		$this->assign("title", "业务设置");
		$this->assign("uri", __ROOT__ . "/");

		$this->assign("loginUserName", $us->getLoginUserName());
		
		$dtFlag = getdate();
		$this->assign("dtFlag", $dtFlag[0]);

		if ($us->hasPermission(FIdConst::BIZ_CONFIG)) {
			$this->display();
		} else {
			redirect(__ROOT__ . "/Home/User/login");
		}
	}

	public function logList() {
		if (IS_POST) {
			$bs = new BizlogService();

			$params = array(
				"page" => I("post.page"),
				"start" => I("post.start"),
				"limit" => I("post.limit")
			);

			$data = $bs->logList($params);
			$totalCount = $bs->logTotalCount();
			$result["totalCount"] = $totalCount;
			$result["logs"] = $data;
			
			$this->ajaxReturn($result);
		}
	}
}
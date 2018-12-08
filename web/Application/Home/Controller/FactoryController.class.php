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
	 * 工厂分类
	 */
	public function categoryList() {
		if (IS_POST) {
			$params = [
					"code" => I("post.code"),
					"name" => I("post.name"),
					"address" => I("post.address"),
					"contact" => I("post.contact"),
					"mobile" => I("post.mobile"),
					"tel" => I("post.tel")
			];
			$service = new FactoryService();
			$this->ajaxReturn($service->categoryList($params));
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

	/**
	 * 删除工厂分类
	 */
	public function deleteCategory() {
		if (IS_POST) {
			$params = [
					"id" => I("post.id")
			];
			
			$service = new FactoryService();
			$this->ajaxReturn($service->deleteCategory($params));
		}
	}

	/**
	 * 工厂列表
	 */
	public function factoryList() {
		if (IS_POST) {
			$params = array(
					"categoryId" => I("post.categoryId"),
					"code" => I("post.code"),
					"name" => I("post.name"),
					"address" => I("post.address"),
					"contact" => I("post.contact"),
					"mobile" => I("post.mobile"),
					"tel" => I("post.tel"),
					"page" => I("post.page"),
					"start" => I("post.start"),
					"limit" => I("post.limit")
			);
			$service = new FactoryService();
			$this->ajaxReturn($service->factoryList($params));
		}
	}

	/**
	 * 新建或编辑工厂
	 */
	public function editFactory() {
		if (IS_POST) {
			$params = [
					"id" => I("post.id"),
					"code" => strtoupper(I("post.code")),
					"name" => I("post.name"),
					"address" => I("post.address"),
					"contact01" => I("post.contact01"),
					"mobile01" => I("post.mobile01"),
					"tel01" => I("post.tel01"),
					"contact02" => I("post.contact02"),
					"mobile02" => I("post.mobile02"),
					"tel02" => I("post.tel02"),
					"bankName" => I("post.bankName"),
					"bankAccount" => I("post.bankAccount"),
					"tax" => I("post.tax"),
					"fax" => I("post.fax"),
					"note" => I("post.note"),
					"categoryId" => I("post.categoryId"),
					"initPayables" => I("post.initPayables"),
					"initPayablesDT" => I("post.initPayablesDT"),
					"recordStatus" => I("post.recordStatus")
			];
			$service = new FactoryService();
			$this->ajaxReturn($service->editFactory($params));
		}
	}
}

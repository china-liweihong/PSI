<?php

namespace Home\Service;

use Home\DAO\FactoryDAO;

/**
 * 工厂Service
 *
 * @author 李静波
 */
class FactoryService extends PSIBaseExService {
	private $LOG_CATEGORY = "基础数据-工厂";

	/**
	 * 工厂分类列表
	 */
	public function categoryList($params) {
		if ($this->isNotOnline()) {
			return $this->emptyResult();
		}
		
		$params["loginUserId"] = $this->getLoginUserId();
		
		$dao = new FactoryDAO($this->db());
		return $dao->categoryList($params);
	}

	/**
	 * 新建或编辑供应商分类
	 */
	public function editCategory($params) {
		if ($this->isNotOnline()) {
			return $this->notOnlineError();
		}
		
		$id = $params["id"];
		$code = $params["code"];
		$name = $params["name"];
		
		$db = $this->db();
		$db->startTrans();
		
		$dao = new FactoryDAO($db);
		
		$log = null;
		
		if ($id) {
			// 编辑
			$rc = $dao->updateFactoryCategory($params);
			if ($rc) {
				$db->rollback();
				return $rc;
			}
			
			$log = "编辑工厂分类: 编码 = $code, 分类名 = $name";
		} else {
			// 新增
			
			$params["dataOrg"] = $this->getLoginUserDataOrg();
			$params["companyId"] = $this->getCompanyId();
			
			$rc = $dao->addFactoryCategory($params);
			if ($rc) {
				$db->rollback();
				return $rc;
			}
			
			$id = $params["id"];
			
			$log = "新增工厂分类：编码 = $code, 分类名 = $name";
		}
		
		// 记录业务日志
		$bs = new BizlogService($db);
		$bs->insertBizlog($log, $this->LOG_CATEGORY);
		
		$db->commit();
		
		return $this->ok($id);
	}

	/**
	 * 删除工厂分类
	 */
	public function deleteCategory($params) {
		if ($this->isNotOnline()) {
			return $this->notOnlineError();
		}
		
		$id = $params["id"];
		
		$db = $this->db();
		$db->startTrans();
		$dao = new FactoryDAO($db);
		
		$rc = $dao->deleteFactoryCategory($params);
		if ($rc) {
			$db->rollback();
			return $rc;
		}
		
		$log = "删除工厂分类： 编码 = {$params['code']}, 分类名称 = {$params['name']}";
		$bs = new BizlogService($db);
		$bs->insertBizlog($log, $this->LOG_CATEGORY);
		
		$db->commit();
		
		return $this->ok();
	}

	/**
	 * 某个分类下的工厂列表
	 */
	public function factoryList($params) {
		if ($this->isNotOnline()) {
			return $this->emptyResult();
		}
		
		$params["loginUserId"] = $this->getLoginUserId();
		
		$dao = new FactoryDAO($this->db());
		return $dao->factoryList($params);
	}
}
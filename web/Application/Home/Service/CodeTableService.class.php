<?php

namespace Home\Service;

use Home\DAO\CodeTableDAO;

/**
 * 码表Service
 *
 * @author 李静波
 */
class CodeTableService extends PSIBaseExService {
	private $LOG_CATEGORY = "码表";

	/**
	 * 码表分类列表
	 */
	public function categoryList($params) {
		if ($this->isNotOnline()) {
			return $this->emptyResult();
		}
		
		$dao = new CodeTableDAO($this->db());
		return $dao->categoryList($params);
	}

	/**
	 * 新增或编辑码表分类
	 */
	public function editCodeTableCategory($params) {
		if ($this->isNotOnline()) {
			return $this->notOnlineError();
		}
		
		$id = $params["id"];
		$name = $params["name"];
		
		$db = $this->db();
		$db->startTrans();
		
		$log = null;
		$dao = new CodeTableDAO($db);
		if ($id) {
			// 编辑
			$rc = $dao->updateCodeTableCategory($params);
			if ($rc) {
				$db->rollback();
				return $rc;
			}
			
			$log = "编辑码表分类：{$name}";
		} else {
			// 新增
			$rc = $dao->addCodeTableCategory($params);
			if ($rc) {
				$db->rollback();
				return $rc;
			}
			
			$id = $params["id"];
			$log = "新增码表分类：{$name}";
		}
		
		// 记录业务日志
		$bs = new BizlogService($db);
		$bs->insertBizlog($log, $this->LOG_CATEGORY);
		
		$db->commit();
		
		return $this->ok($id);
	}

	/**
	 * 删除码表分类
	 */
	public function deleteCodeTableCategory($params) {
		if ($this->isNotOnline()) {
			return $this->notOnlineError();
		}
		
		$db = $this->db();
		$db->startTrans();
		
		$dao = new CodeTableDAO($db);
		$rc = $dao->deleteCodeTableCategory($params);
		if ($rc) {
			$db->rollback();
			return $rc;
		}
		
		$name = $params["name"];
		$log = "删除码表分类：{$name}";
		
		// 记录业务日志
		$bs = new BizlogService($db);
		$bs->insertBizlog($log, $this->LOG_CATEGORY);
		
		$db->commit();
		
		return $this->ok();
	}

	/**
	 * 码表列表
	 */
	public function codeTableList($params) {
		if ($this->isNotOnline()) {
			return $this->emptyResult();
		}
		
		$dao = new CodeTableDAO($this->db());
		return $dao->codeTableList($params);
	}

	/**
	 * 码表分类自定义字段 - 查询数据
	 */
	public function queryDataForCategory($params) {
		if ($this->isNotOnline()) {
			return $this->emptyResult();
		}
		
		$dao = new CodeTableDAO($this->db());
		return $dao->queryDataForCategory($params);
	}
}
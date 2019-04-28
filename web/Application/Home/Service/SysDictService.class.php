<?php

namespace Home\Service;

use Home\DAO\SysDictDAO;

/**
 * 系统数据字典Service
 *
 * @author 李静波
 */
class SysDictService extends PSIBaseExService {

	/**
	 * 系统数据字段分类列表
	 */
	public function categoryList($params) {
		if ($this->isNotOnline()) {
			return $this->emptyResult();
		}
		
		$dao = new SysDictDAO($this->db());
		return $dao->categoryList($params);
	}
}
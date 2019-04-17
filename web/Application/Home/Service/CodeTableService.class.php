<?php

namespace Home\Service;

/**
 * 码表Service
 *
 * @author 李静波
 */
class CodeTableService extends PSIBaseExService {
	private $LOG_CATEGORY = "码表";

	/**
	 * 新增或编辑码表分类
	 */
	public function editCodeTableCategory($params) {
		if ($this->isNotOnline()) {
			return $this->notOnlineError();
		}
		
		return $this->todo();
	}
}
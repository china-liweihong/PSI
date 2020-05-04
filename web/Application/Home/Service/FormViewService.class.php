<?php

namespace Home\Service;

use Home\DAO\FormViewDAO;

/**
 * 视图开发助手Service
 *
 * @author 李静波
 */
class FormViewService extends PSIBaseExService
{
  private $LOG_CATEGORY = "视图开发助手";

  /**
   * 视图分类列表
   */
  public function categoryList($params)
  {
    if ($this->isNotOnline()) {
      return $this->emptyResult();
    }

    $dao = new FormViewDAO($this->db());
    return $dao->categoryList($params);
  }
}

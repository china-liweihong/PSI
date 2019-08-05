<?php

namespace Home\Service;

use Home\DAO\FormDAO;

/**
 * 自定义表单Service
 *
 * @author 李静波
 */
class FormService extends PSIBaseExService
{
  private $LOG_CATEGORY = "自定义表单";

  /**
   * 自定义表单列表
   */
  public function categoryList()
  {
    if ($this->isNotOnline()) {
      return $this->emptyResult();
    }

    $params = [
      "loginUserId" => $this->getLoginUserId()
    ];

    $dao = new FormDAO($this->db());

    return $dao->categoryList($params);
  }
}

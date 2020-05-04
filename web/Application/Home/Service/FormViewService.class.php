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

  /**
   * 新增或编辑视图分类
   */
  public function editViewCategory($params)
  {
    if ($this->isNotOnline()) {
      return $this->notOnlineError();
    }

    $id = $params["id"];
    $name = $params["name"];

    $db = $this->db();
    $db->startTrans();

    $log = null;
    $dao = new FormViewDAO($db);
    if ($id) {
      // 编辑
      $rc = $dao->updateViewCategory($params);
      if ($rc) {
        $db->rollback();
        return $rc;
      }

      $log = "编辑视图分类：{$name}";
    } else {
      // 新增
      $rc = $dao->addViewCategory($params);
      if ($rc) {
        $db->rollback();
        return $rc;
      }

      $id = $params["id"];
      $log = "新增视图分类：{$name}";
    }

    // 记录业务日志
    $bs = new BizlogService($db);
    $bs->insertBizlog($log, $this->LOG_CATEGORY);

    $db->commit();

    return $this->ok($id);
  }
}

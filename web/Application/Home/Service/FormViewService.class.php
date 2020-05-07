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

  /**
   * 删除视图分类
   */
  public function deleteViewCategory($params)
  {
    if ($this->isNotOnline()) {
      return $this->notOnlineError();
    }

    $db = $this->db();
    $db->startTrans();

    $dao = new FormViewDAO($db);
    $rc = $dao->deleteViewCategory($params);
    if ($rc) {
      $db->rollback();
      return $rc;
    }

    $name = $params["name"];
    $log = "删除视图分类：{$name}";

    // 记录业务日志
    $bs = new BizlogService($db);
    $bs->insertBizlog($log, $this->LOG_CATEGORY);

    $db->commit();

    return $this->ok();
  }

  /**
   * 视图的列表
   */
  public function fvList($params)
  {
    if ($this->isNotOnline()) {
      return $this->emptyResult();
    }

    $dao = new FormViewDAO($this->db());
    return $dao->fvList($params);
  }

  /**
   * 视图分类自定义字段 - 查询数据
   */
  public function queryDataForFvCategory($params)
  {
    if ($this->isNotOnline()) {
      return $this->emptyResult();
    }

    $dao = new FormViewDAO($this->db());
    return $dao->queryDataForFvCategory($params);
  }

  /**
   * 新增或编辑视图
   */
  public function editFv($params)
  {
    if ($this->isNotOnline()) {
      return $this->notOnlineError();
    }

    $id = $params["id"];
    $name = $params["name"];

    $pyService = new PinyinService();
    $py = $pyService->toPY($name);
    $params["py"] = $py;

    $db = $this->db();
    $db->startTrans();

    $log = null;
    $dao = new FormViewDAO($db);
    if ($id) {
      // 编辑
      $rc = $dao->updateFv($params);
      if ($rc) {
        $db->rollback();
        return $rc;
      }

      $log = "编辑视图[{$name}]的元数据";
    } else {
      // 新增
      $rc = $dao->addFv($params);
      if ($rc) {
        $db->rollback();
        return $rc;
      }

      $id = $params["id"];
      $log = "新增视图：{$name}";
    }

    // 记录业务日志
    $bs = new BizlogService($db);
    $bs->insertBizlog($log, $this->LOG_CATEGORY);

    $db->commit();

    return $this->ok($id);
  }
}

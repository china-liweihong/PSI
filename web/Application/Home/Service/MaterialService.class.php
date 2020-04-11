<?php

namespace Home\Service;

use Home\DAO\MaterialUnitDAO;

/**
 * 物料Service
 *
 * @author 李静波
 */
class MaterialService extends PSIBaseExService
{
  private $LOG_CATEGORY_UNIT = "物料单位";
  private $LOG_CATEGORY = "物料";

  /**
   * 返回所有物料单位
   */
  public function allUnits()
  {
    if ($this->isNotOnline()) {
      return $this->emptyResult();
    }

    $dao = new MaterialUnitDAO($this->db());

    return $dao->allUnits();
  }

  /**
   * 新建或者编辑物料单位
   */
  public function editUnit($params)
  {
    if ($this->isNotOnline()) {
      return $this->notOnlineError();
    }

    $id = $params["id"];
    $name = $params["name"];

    $db = $this->db();
    $db->startTrans();

    $dao = new MaterialUnitDAO($db);

    $log = null;

    if ($id) {
      // 编辑

      $rc = $dao->updateUnit($params);
      if ($rc) {
        $db->rollback();
        return $rc;
      }

      $log = "编辑物料单位: $name";
    } else {
      // 新增

      $params["dataOrg"] = $this->getLoginUserDataOrg();
      $params["companyId"] = $this->getCompanyId();

      $rc = $dao->addUnit($params);
      if ($rc) {
        $db->rollback();
        return $rc;
      }

      $id = $params["id"];

      $log = "新增物料单位: $name";
    }

    // 记录业务日志
    $bs = new BizlogService($db);
    $bs->insertBizlog($log, $this->LOG_CATEGORY_UNIT);

    $db->commit();

    return $this->ok($id);
  }
}

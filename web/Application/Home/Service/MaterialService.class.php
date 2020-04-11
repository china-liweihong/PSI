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
}

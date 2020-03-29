<?php

namespace Home\Service;

use Home\Common\FIdConst;
use Home\DAO\BizConfigDAO;
use Home\DAO\PurchaseReportDAO;

/**
 * 采购报表Service
 *
 * @author 李静波
 */
class PurchaseReportService extends PSIBaseExService
{
  private $LOG_CATEGORY = "采购报表";

  /**
   * 采购入库明细表 - 数据查询
   */
  public function purchaseDetailQueryData($params)
  {
    if ($this->isNotOnline()) {
      return $this->emptyResult();
    }

    $params["companyId"] = $this->getCompanyId();

    $dao = new PurchaseReportDAO($this->db());

    return $dao->purchaseDetailQueryData($params);
  }
}

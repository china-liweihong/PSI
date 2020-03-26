<?php

namespace Home\DAO;

/**
 * 采购报表 DAO
 *
 * @author 李静波
 */
class PurchaseReportDAO extends PSIBaseExDAO
{

  /**
   * 采购入库明细表 - 查询数据
   *
   * @param array $params
   */
  public function purchaseDetailQueryData($params)
  {
    $db = $this->db;

    $supplierId = $params["supplierId"];
    $warehouseId = $params["warehouseId"];
    $fromDT = $params["fromDT"];
    $toDT = $params["toDT"];

    $result = [];
    $cnt = 0;

    return [
      "dataList" => $result,
      "totalCount" => $cnt
    ];
  }
}

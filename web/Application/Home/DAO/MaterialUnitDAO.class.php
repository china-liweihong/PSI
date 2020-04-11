<?php

namespace Home\DAO;

/**
 * 物料单位 DAO
 *
 * @author 李静波
 */
class MaterialUnitDAO extends PSIBaseExDAO
{
  /**
   * 返回所有物料单位
   *
   * @return array
   */
  public function allUnits()
  {
    $db = $this->db;

    $sql = "select id, name, code, record_status
            from t_material_unit
            order by record_status, code";

    $data = $db->query($sql);

    $result = [];

    foreach ($data as $v) {
      $result[] = [
        "id" => $v["id"],
        "code" => $v["code"],
        "name" => $v["name"],
        "recordStatus" => $v["record_status"]
      ];
    }

    return $result;
  }
}

<?php

namespace Home\DAO;

use Home\Common\FIdConst;

/**
 * 原材料 DAO
 *
 * @author 李静波
 */
class RawMaterialDAO extends PSIBaseExDAO
{
  private function toTaxRate($taxRate)
  {
    if (!$taxRate) {
      return null;
    }

    $r = intval($taxRate);
    if ($r >= 0 && $r <= 17) {
      return "{$r}%";
    } else {
      return null;
    }
  }

  /**
   * 原材料列表
   */
  public function rawMaterialList($params)
  {
    $db = $this->db;

    $categoryId = $params["categoryId"];
    $code = $params["code"];
    $name = $params["name"];
    $spec = $params["spec"];

    $start = $params["start"];
    $limit = $params["limit"];

    $loginUserId = $params["loginUserId"];
    if ($this->loginUserIdNotExists($loginUserId)) {
      return $this->emptyResult();
    }

    $result = [];
    $sql = "select g.id, g.code, g.name, g.spec,  g.unit_id, u.name as unit_name,
              g.purchase_price, g.memo, g.data_org, g.record_status,
              g.tax_rate
            from t_raw_material g, t_material_unit u
            where (g.unit_id = u.id) and (g.category_id = '%s') ";
    $queryParam = [];
    $queryParam[] = $categoryId;
    $ds = new DataOrgDAO($db);
    $rs = $ds->buildSQL(FIdConst::RAW_MATERIAL, "g", $loginUserId);
    if ($rs) {
      $sql .= " and " . $rs[0];
      $queryParam = array_merge($queryParam, $rs[1]);
    }

    if ($code) {
      $sql .= " and (g.code like '%s') ";
      $queryParam[] = "%{$code}%";
    }
    if ($name) {
      $sql .= " and (g.name like '%s' or g.py like '%s') ";
      $queryParam[] = "%{$name}%";
      $queryParam[] = "%{$name}%";
    }
    if ($spec) {
      $sql .= " and (g.spec like '%s')";
      $queryParam[] = "%{$spec}%";
    }

    $sql .= " order by g.code limit %d, %d";
    $queryParam[] = $start;
    $queryParam[] = $limit;
    $data = $db->query($sql, $queryParam);

    foreach ($data as $v) {
      $result[] = [
        "id" => $v["id"],
        "code" => $v["code"],
        "name" => $v["name"],
        "spec" => $v["spec"],
        "unitId" => $v["unit_id"],
        "unitName" => $v["unit_name"],
        "purchasePrice" => $v["purchase_price"] == 0 ? null : $v["purchase_price"],
        "memo" => $v["memo"],
        "dataOrg" => $v["data_org"],
        "recordStatus" => $v["record_status"],
        "taxRate" => $this->toTaxRate($v["tax_rate"])
      ];
    }

    $sql = "select count(*) as cnt from t_raw_material g where (g.category_id = '%s') ";
    $queryParam = [];
    $queryParam[] = $categoryId;
    $ds = new DataOrgDAO($db);
    $rs = $ds->buildSQL(FIdConst::RAW_MATERIAL, "g", $loginUserId);
    if ($rs) {
      $sql .= " and " . $rs[0];
      $queryParam = array_merge($queryParam, $rs[1]);
    }
    if ($code) {
      $sql .= " and (g.code like '%s') ";
      $queryParam[] = "%{$code}%";
    }
    if ($name) {
      $sql .= " and (g.name like '%s' or g.py like '%s') ";
      $queryParam[] = "%{$name}%";
      $queryParam[] = "%{$name}%";
    }
    if ($spec) {
      $sql .= " and (g.spec like '%s')";
      $queryParam[] = "%{$spec}%";
    }

    $data = $db->query($sql, $queryParam);
    $totalCount = $data[0]["cnt"];

    return [
      "dataList" => $result,
      "totalCount" => $totalCount
    ];
  }

  /**
   * 获得某个原材料的详情
   */
  public function getRawMaterialInfo($params)
  {
    $db = $this->db;

    $id = $params["id"];
    $categoryId = $params["categoryId"];

    $sql = "select category_id, code, name, spec, unit_id, purchase_price,
              memo, record_status, tax_rate
            from t_raw_material
            where id = '%s' ";
    $data = $db->query($sql, $id);
    if ($data) {
      $result = [];
      $categoryId = $data[0]["category_id"];
      $result["categoryId"] = $categoryId;

      $result["code"] = $data[0]["code"];
      $result["name"] = $data[0]["name"];
      $result["spec"] = $data[0]["spec"];
      $result["unitId"] = $data[0]["unit_id"];

      $v = $data[0]["purchase_price"];
      if ($v == 0) {
        $result["purchasePrice"] = null;
      } else {
        $result["purchasePrice"] = $v;
      }

      $result["memo"] = $data[0]["memo"];
      $result["recordStatus"] = $data[0]["record_status"];
      $result["taxRate"] = $data[0]["tax_rate"];

      $sql = "select full_name from t_raw_material_category where id = '%s' ";
      $data = $db->query($sql, $categoryId);
      if ($data) {
        $result["categoryName"] = $data[0]["full_name"];
      }

      return $result;
    } else {
      $result = [];

      $sql = "select full_name from t_raw_material_category where id = '%s' ";
      $data = $db->query($sql, $categoryId);
      if ($data) {
        $result["categoryId"] = $categoryId;
        $result["categoryName"] = $data[0]["full_name"];
      }
      return $result;
    }
  }
}

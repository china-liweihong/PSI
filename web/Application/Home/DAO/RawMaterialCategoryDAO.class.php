<?php

namespace Home\DAO;

use Home\Common\FIdConst;

/**
 * 原材料 DAO
 *
 * @author 李静波
 */
class RawMaterialCategoryDAO extends PSIBaseExDAO
{

  private function allCategoriesInternal($db, $parentId, $rs, $params)
  {
    $result = array();
    $sql = "select id, code, name, full_name, tax_rate
            from t_raw_material_category c
            where (parent_id = '%s')
		    		";
    $queryParam = array();
    $queryParam[] = $parentId;
    if ($rs) {
      $sql .= " and " . $rs[0];
      $queryParam = array_merge($queryParam, $rs[1]);
    }

    $sql .= " order by code";
    $data = $db->query($sql, $queryParam);
    foreach ($data as $i => $v) {
      $id = $v["id"];
      $result[$i]["id"] = $v["id"];
      $result[$i]["text"] = $v["name"];
      $result[$i]["code"] = $v["code"];
      $fullName = $v["full_name"];
      if (!$fullName) {
        $fullName = $v["name"];
      }
      $result[$i]["fullName"] = $fullName;
      $result[$i]["taxRate"] = $this->toTaxRate($v["tax_rate"]);

      $children = $this->allCategoriesInternal($db, $id, $rs, $params); // 自身递归调用

      $result[$i]["children"] = $children;
      $result[$i]["leaf"] = count($children) == 0;
      $result[$i]["expanded"] = true;
      $result[$i]["iconCls"] = "PSI-RawMaterialCategory";

      $result[$i]["cnt"] = $this->getRawMaterialCountWithAllSub($db, $id, $params, $rs);
    }

    return $result;
  }
  /**
   * 获得某个原材料分类及其所属子分类下的所有原材料的种类数
   */
  private function getRawMaterialCountWithAllSub($db, $categoryId, $params, $rs)
  {
    $code = $params["code"];
    $name = $params["name"];
    $spec = $params["spec"];

    $sql = "select count(*) as cnt 
            from t_raw_material c
            where c.category_id = '%s' ";
    $queryParam = array();
    $queryParam[] = $categoryId;
    if ($rs) {
      $sql .= " and " . $rs[0];
      $queryParam = array_merge($queryParam, $rs[1]);
    }
    if ($code) {
      $sql .= " and (c.code like '%s') ";
      $queryParam[] = "%{$code}%";
    }
    if ($name) {
      $sql .= " and (c.name like '%s' or c.py like '%s') ";
      $queryParam[] = "%{$name}%";
      $queryParam[] = "%{$name}%";
    }
    if ($spec) {
      $sql .= " and (c.spec like '%s')";
      $queryParam[] = "%{$spec}%";
    }

    $data = $db->query($sql, $queryParam);
    $result = $data[0]["cnt"];

    // 子分类
    $sql = "select id
            from t_raw_material_category c
            where (parent_id = '%s')
    				";
    $queryParam = array();
    $queryParam[] = $categoryId;
    if ($rs) {
      $sql .= " and " . $rs[0];
      $queryParam = array_merge($queryParam, $rs[1]);
    }

    $data = $db->query($sql, $queryParam);
    foreach ($data as $v) {
      // 递归调用自身
      $result += $this->getRawMaterialCountWithAllSub($db, $v["id"], $params, $rs);
    }
    return $result;
  }

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
   * 把分类中原材料数量是0的分类过滤掉
   *
   * @param array $data
   * @return array
   */
  private function filterCategory($data)
  {
    $result = [];
    foreach ($data as $v) {
      if ($v["cnt"] == 0) {
        continue;
      }

      $result[] = $v;
    }

    return $result;
  }

  /**
   * 返回所有的原材料分类
   *
   */
  public function allRawMaterialCategories($params)
  {
    $db = $this->db;

    $code = $params["code"];
    $name = $params["name"];
    $spec = $params["spec"];

    $inQuery = false;
    if ($code || $name || $spec) {
      $inQuery = true;
    }

    $loginUserId = $params["loginUserId"];
    if ($this->loginUserIdNotExists($loginUserId)) {
      return $this->emptyResult();
    }

    $sql = "select id, code, name, full_name, tax_rate
            from t_raw_material_category c
            where (parent_id is null)
            ";
    $queryParam = array();
    $ds = new DataOrgDAO($db);
    $rs = $ds->buildSQL(FIdConst::RAW_MATERIAL_CATEGORY, "c", $loginUserId);
    if ($rs) {
      $sql .= " and " . $rs[0];
      $queryParam = array_merge($queryParam, $rs[1]);
    }

    $sql .= " order by code";

    $data = $db->query($sql, $queryParam);
    $result = array();
    foreach ($data as $i => $v) {
      $id = $v["id"];
      $result[$i]["id"] = $v["id"];
      $result[$i]["text"] = $v["name"];
      $result[$i]["code"] = $v["code"];
      $fullName = $v["full_name"];
      if (!$fullName) {
        $fullName = $v["name"];
      }
      $result[$i]["fullName"] = $fullName;
      $result[$i]["taxRate"] = $this->toTaxRate($v["tax_rate"]);

      $children = $this->allCategoriesInternal($db, $id, $rs, $params);

      $result[$i]["children"] = $children;
      $result[$i]["leaf"] = count($children) == 0;
      $result[$i]["expanded"] = true;
      $result[$i]["iconCls"] = "PSI-RawMaterialCategory";

      $result[$i]["cnt"] = $this->getRawMaterialCountWithAllSub($db, $id, $params, $rs);
    }

    if ($inQuery) {
      $result = $this->filterCategory($result);
    }

    return $result;
  }
}

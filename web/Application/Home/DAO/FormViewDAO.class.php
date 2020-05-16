<?php

namespace Home\DAO;

/**
 * 表单视图 DAO
 *
 * @author 李静波
 */
class FormViewDAO extends PSIBaseExDAO
{

  /**
   * 视图分类列表
   */
  public function categoryList($params)
  {
    $db = $this->db;

    $sql = "select id, code, name, is_system
            from t_fv_category
            order by code";
    $data = $db->query($sql);

    $result = [];
    foreach ($data as $v) {
      $result[] = [
        "id" => $v["id"],
        "code" => $v["code"],
        "name" => $v["name"],
        "isSystem" => $v["is_system"],
        "isSystemCaption" => $v["is_system"] == 1 ? "▲" : "",
      ];
    }

    return $result;
  }

  /**
   * 新增视图分类
   *
   * @param array $params
   * @return null|array
   */
  public function addViewCategory(&$params)
  {
    $db = $this->db;

    $code = $params["code"] ?? "";
    $code = strtoupper($code);
    $name = $params["name"];

    // 检查编码是否存在
    if ($code) {
      $sql = "select count(*) as cnt from t_fv_category where code = '%s' ";
      $data = $db->query($sql, $code);
      $cnt = $data[0]["cnt"];
      if ($cnt) {
        return $this->bad("视图分类编码[{$code}]已经存在");
      }
    } else {
      $code = "";
    }

    // 检查分类名称是否存在
    $sql = "select count(*) as cnt from t_fv_category where name = '%s' ";
    $data = $db->query($sql, $name);
    $cnt = $data[0]["cnt"];
    if ($cnt) {
      return $this->bad("视图分类[{$name}]已经存在");
    }

    $id = $this->newId();
    $sql = "insert into t_fv_category (id, code, name, parent_id)
            values ('%s', '%s', '%s', null)";

    $rc = $db->execute($sql, $id, $code, $name);
    if ($rc === false) {
      return $this->sqlError(__METHOD__, __LINE__);
    }

    // 操作成功
    $params["id"] = $id;
    return null;
  }

  public function getViewCategoryById($id)
  {
    $db = $this->db;

    $sql = "select code, name, is_system from t_fv_category where id = '%s' ";
    $data = $db->query($sql, $id);
    if ($data) {
      return [
        "code" => $data[0]["code"],
        "name" => $data[0]["name"],
        "isSystem" => $data[0]["is_system"],
      ];
    } else {
      return null;
    }
  }

  /**
   * 编辑视图分类
   *
   * @param array $params
   */
  public function updateViewCategory($params)
  {
    $db = $this->db;

    $id = $params["id"];
    $code = $params["code"] ?? "";
    $code = strtoupper($code);
    $name = $params["name"];

    $category = $this->getViewCategoryById($id);
    if (!$category) {
      return $this->bad("要编辑的视图分类不存在");
    }
    $isSystem = $category["isSystem"];
    if ($isSystem == 1) {
      $n = $category["name"];
      return $this->bad("分类[{$n}]是系统固有分类，不能编辑");
    }

    // 检查编码是否存在
    if ($code) {
      $sql = "select count(*) as cnt from t_fv_category 
              where code = '%s' and id <> '%s' ";
      $data = $db->query($sql, $code, $id);
      $cnt = $data[0]["cnt"];
      if ($cnt) {
        return $this->bad("视图分类编码[{$code}]已经存在");
      }
    } else {
      $code = "";
    }

    // 检查分类名称是否存在
    $sql = "select count(*) as cnt from t_fv_category 
            where name = '%s' and id <> '%s' ";
    $data = $db->query($sql, $name, $id);
    $cnt = $data[0]["cnt"];
    if ($cnt) {
      return $this->bad("视图分类[{$name}]已经存在");
    }

    $sql = "update t_fv_category
            set code = '%s', name = '%s'
            where id = '%s' ";

    $rc = $db->execute($sql, $code, $name, $id);
    if ($rc === false) {
      return $this->sqlError(__METHOD__, __LINE__);
    }

    // 操作成功
    return null;
  }

  /**
   * 删除视图分类
   */
  public function deleteViewCategory(&$params)
  {
    $db = $this->db;

    $id = $params["id"];

    $category = $this->getViewCategoryById($id);
    if (!$category) {
      return $this->bad("要删除的视图分类不存在");
    }
    $name = $category["name"];
    $isSystem = $category["isSystem"];
    if ($isSystem == 1) {
      return $this->bad("分类[{$name}]是系统固有分类，不能删除");
    }

    // 查询该分类是否被使用了
    $sql = "select count(*) as cnt from t_fv
            where category_id = '%s' ";
    $data = $db->query($sql, $id);
    $cnt = $data[0]["cnt"];
    if ($cnt > 0) {
      return $this->bad("视图分类[$name]下还有视图，不能删除");
    }

    $sql = "delete from t_fv_category where id = '%s' ";
    $rc = $db->execute($sql, $id);
    if ($rc === false) {
      return $this->sqlError(__METHOD__, __LINE__);
    }

    // 操作成功
    $params["name"] = $name;
    return null;
  }

  /**
   * 视图分类自定义字段 - 查询数据
   */
  public function queryDataForFvCategory($params)
  {
    $db = $this->db;

    $queryKey = $params["queryKey"] ?? "";

    $sql = "select id, code, name
            from t_fv_category
            where code like '%s' or name like '%s' ";
    $queryParams = [];
    $queryParams[] = "%{$queryKey}%";
    $queryParams[] = "%{$queryKey}%";

    $data = $db->query($sql, $queryParams);

    $result = [];
    foreach ($data as $v) {
      $result[] = [
        "id" => $v["id"],
        "code" => $v["code"],
        "name" => $v["name"]
      ];
    }

    return $result;
  }

  /**
   * 视图的列表
   */
  public function fvList($params)
  {
    return [];
  }

  /**
   * 新增视图
   */
  public function addFv(&$params)
  {
    $db = $this->db;

    $categoryId = $params["categoryId"];
    $code = $params["code"];
    $name = $params["name"];
    $moduleName = $params["moduleName"];
    $xtype = $params["xtype"];
    $region = $params["region"];
    $widthOrHeight = $params["widthOrHeight"];
    $layout = $params["layout"];
    $memo = $params["memo"];
    $py = $params["py"];

    $category = $this->getViewCategoryById($categoryId);
    if (!$category) {
      return $this->bad("视图分类不存在");
    }

    $id = $this->newId();
    $fid = "fv" . date("YmdHis");

    $sql = "insert into t_fv (id, category_id, code, name, memo, py, fid,
              module_name, xtype, region, width_or_height, layout_type)
            values ('%s', '%s', '%s', '%s', '%s', '%s', '%s',
              '%s', '%s', '%s', '%s', %d)";
    $rc = $db->execute(
      $sql,
      $id,
      $categoryId,
      $code,
      $name,
      $memo,
      $py,
      $fid,
      $moduleName,
      $xtype,
      $region,
      $widthOrHeight,
      $layout
    );
    if ($rc === false) {
      return $this->sqlError(__METHOD__, __LINE__);
    }


    // 操作成功
    $params["id"] = $id;
    return null;
  }

  /**
   * 编辑视图
   */
  public function updateFv(&$params)
  {
    return $this->todo();
  }

  /**
   * 某个视图的详情
   */
  public function fvInfo($params)
  {
    $db = $this->db;

    // 视图id
    $id = $params["id"];

    $sql = "select code_int, name, memo from t_sysdict_fv_xtype order by show_order";
    $data = $db->query($sql);
    $allXtype = [];
    foreach ($data as $v) {
      $allXtype[] = [
        "id" => $v["code_int"],
        "text" => $v["name"] . " - " . $v["memo"]
      ];
    }
    $result = ["allXtype" => $allXtype];

    if ($id) {
      // 编辑
    }

    return $result;
  }
}

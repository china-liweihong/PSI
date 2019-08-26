<?php

namespace Home\DAO;

/**
 * 自定义表单 DAO
 *
 * @author 李静波
 */
class FormDAO extends PSIBaseExDAO
{

  /**
   * 表单分类列表
   */
  public function categoryList($params)
  {
    $db = $this->db;

    $sql = "select id, code, name
            from t_form_category
            order by code";
    $data = $db->query($sql);

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
   * 新增表单分类
   */
  public function addFormCategory(&$params)
  {
    $db = $this->db;

    $code = $params["code"] ?? "";
    $code = strtoupper($code);
    $name = $params["name"];

    // 检查编码是否存在
    if ($code) {
      $sql = "select count(*) as cnt from t_form_category where code = '%s' ";
      $data = $db->query($sql, $code);
      $cnt = $data[0]["cnt"];
      if ($cnt) {
        return $this->bad("表单分类编码[{$code}]已经存在");
      }
    } else {
      $code = "";
    }

    // 检查分类名称是否存在
    $sql = "select count(*) as cnt from t_form_category where name = '%s' ";
    $data = $db->query($sql, $name);
    $cnt = $data[0]["cnt"];
    if ($cnt) {
      return $this->bad("表单分类[{$name}]已经存在");
    }

    $id = $this->newId();
    $sql = "insert into t_form_category (id, code, name, parent_id)
            values ('%s', '%s', '%s', null)";

    $rc = $db->execute($sql, $id, $code, $name);
    if ($rc === false) {
      return $this->sqlError(__METHOD__, __LINE__);
    }

    // 操作成功
    $params["id"] = $id;
    return null;
  }

  /**
   * 编辑表单分类
   */
  public function updateFormCategory(&$params)
  {
    $db = $this->db;

    return $this->todo();
  }
}

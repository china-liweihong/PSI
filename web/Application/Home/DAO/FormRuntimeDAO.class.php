<?php

namespace Home\DAO;

/**
 * 自定义表单Runtime DAO
 *
 * @author 李静波
 */
class FormRuntimeDAO extends PSIBaseExDAO
{
  public function getFormMetadataForViewInit($params)
  {
    $db = $this->db;

    $fid = $params["fid"];

    $sql = "select name from t_form where fid = '%s'";
    $data = $db->query($sql, $fid);
    if ($data) {
      return [
        "title" => $data[0]["name"],
      ];
    } else {
      return null;
    }
  }

  public function getFormMetadataForRuntime($params)
  {
    $db = $this->db;

    $fid = $params["fid"];

    $sql = "select id, name from t_form where fid = '%s'";
    $data = $db->query($sql, $fid);
    if (!$data) {
      return null;
    }

    $v = $data[0];
    $formId = $v["id"];
    $result = [
      "name" => $v["name"],
    ];

    // 主表
    $sql = "select caption, db_field_name
            from t_form_cols
            where form_id = '%s' and is_visible = 1
            order by show_order";
    $data = $db->query($sql, $formId);
    $cols = [];
    foreach ($data as $v) {
      $cols[] = [
        "caption" => $v["caption"],
        "fieldName" => $v["db_field_name"]
      ];
    }
    // 主表列
    $result["cols"] = $cols;

    // 明细表

    return $result;
  }
}

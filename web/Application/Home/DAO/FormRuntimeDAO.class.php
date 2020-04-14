<?php

namespace Home\DAO;

/**
 * 自定义表单Runtime DAO
 *
 * @author 李静波
 */
class FormRuntimeDAO extends PSIBaseExDAO
{
  public function getFormMetadataForRuntime($params)
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
}

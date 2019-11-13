<?php

namespace Home\Service;

use Home\Common\FIdConst;
use Think\Think;

/**
 * 数据库升级Service - 用于PSI 2020，减少原有UpdateDBService的代码行数
 * 
 * 由UpdateDBService调用本class
 *
 * @author 李静波
 */
class UpdateDB2020Service extends PSIBaseService
{
  /**
   *
   * @var \Think\Model $db
   */
  protected $db;

  function __construct($db)
  {
    $this->db = $db;
  }

  public function update()
  {
    $this->update_20191113_01();
  }

  private function update_20191113_01()
  {
    // 本次更新：t_acc_fmt_cols新增字段code_table_field_name_fk
    $db = $this->db;

    $tableName = "t_acc_fmt_cols";

    $columnName = "code_table_field_name_fk";
    if (!$this->columnExists($db, $tableName, $columnName)) {
      $sql = "alter table {$tableName} add {$columnName} varchar(255) DEFAULT NULL;";
      $db->execute($sql);
    }
  }
}

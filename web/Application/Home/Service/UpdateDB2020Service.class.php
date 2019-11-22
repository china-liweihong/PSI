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

  // ============================================
  // !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
  // 注意：
  // 如果修改了数据库结构，别忘记了在InstallService中修改相应的SQL语句
  // !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
  // ============================================
  private function notForgot()
  { }


  public function update()
  {
    $this->update_20191113_01();
    $this->update_20191120_01();
    $this->update_20191122_01();
  }

  private function update_20191122_01()
  {
    // 本次更新：t_form新增字段sys_form
    $db = $this->db;

    $tableName = "t_form";
    $columnName = "sys_form";
    if (!$this->columnExists($db, $tableName, $columnName)) {
      $sql = "alter table {$tableName} add {$columnName} int(11) NOT NULL DEFAULT 0;";
      $db->execute($sql);
    }
  }

  private function update_20191120_01()
  {
    // 本次更新：新增表t_form
    $db = $this->db;
    $tableName = "t_form";
    if (!$this->tableExists($db, $tableName)) {
      $sql = "CREATE TABLE IF NOT EXISTS `t_form` (
                `id` varchar(255) NOT NULL,
                `code` varchar(255) NOT NULL,
                `name` varchar(1000) NOT NULL,
                `category_id` varchar(255) NOT NULL,
                PRIMARY KEY (`id`)
              ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
              ";
      $db->execute($sql);
    }
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

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
  {
  }

  public function update()
  {
    $this->update_20191113_01();
    $this->update_20191120_01();
    $this->update_20191122_01();
    $this->update_20191123_01();
    $this->update_20191125_01();
    $this->update_20191125_02();
    $this->update_20191125_03();
    $this->update_20191126_01();
    $this->update_20200310_01();
    $this->update_20200313_01();
    $this->update_20200402_01();
    $this->update_20200403_01();
    $this->update_20200410_01();
    $this->update_20200410_02();
    $this->update_20200410_03();
    $this->update_20200411_01();
    $this->update_20200412_01();
    $this->update_20200413_01();
    $this->update_20200415_01();
    $this->update_20200416_01();
    $this->update_20200416_02();
    $this->update_20200416_03();
    $this->update_20200418_01();
    $this->update_20200419_01();
    $this->update_20200421_01();
    $this->update_20200422_01();
    $this->update_20200423_01();
    $this->update_20200423_02();
    $this->update_20200423_03();
    $this->update_20200424_01();
    $this->update_20200424_02();
    $this->update_20200424_03();
    $this->update_20200424_04();
    $this->update_20200427_01();
    $this->update_20200427_02();
  }

  private function update_20200427_02()
  {
    // 本次更新：新建表t_sysdict_tax_rate
    $db = $this->db;
    $tableName = "t_sysdict_tax_rate";
    if (!$this->tableExists($db, $tableName)) {
      $sql = "CREATE TABLE IF NOT EXISTS `t_sysdict_tax_rate` (
                `id` varchar(255) NOT NULL,
                `code` varchar(255) NOT NULL,
                `code_int` int(11) NOT NULL,
                `name` varchar(255) NOT NULL,
                `py` varchar(255) NOT NULL,
                `memo` varchar(255) NOT NULL,
                `show_order` int(11) DEFAULT NULL,
                PRIMARY KEY (`id`)
              ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
              ";
      $db->execute($sql);
    }
  }

  private function update_20200427_01()
  {
    // 本次更新：初始化t_goods_brand的码表元数据
    $db = $this->db;

    $sql = "DELETE FROM `t_code_table_md` where `id` = 'D7D9D328-8834-11EA-8C36-E86A641ED142';
            DELETE FROM `t_code_table_cols_md` where `table_id` = 'D7D9D328-8834-11EA-8C36-E86A641ED142' and `sys_col` = 1;
            INSERT INTO `t_code_table_md` (`id`, `code`, `name`, `table_name`, `category_id`, `memo`, `py`, `fid`, `md_version`, `is_fixed`, `enable_parent_id`, `handler_class_name`) VALUES
            ('D7D9D328-8834-11EA-8C36-E86A641ED142', 'PSI-0002-03', '商品品牌', 't_goods_brand', '58BF84A3-8517-11EA-B071-E86A641ED142', '', '', '', 1, 1, 1, '');
            INSERT INTO `t_code_table_cols_md` (`id`, `table_id`, `caption`, `db_field_name`, `db_field_type`, `db_field_length`, `db_field_decimal`, `show_order`, `value_from`, `value_from_table_name`, `value_from_col_name`, `value_from_col_name_display`, `must_input`, `sys_col`, `is_visible`, `width_in_view`, `note`, `show_order_in_view`, `editor_xtype`) VALUES
            ('D7DA0F07-8834-11EA-8C36-E86A641ED142', 'D7D9D328-8834-11EA-8C36-E86A641ED142', 'id', 'id', 'varchar', 255, 0, -1000, 1, '', '', '', 1, 1, 2, 0, NULL, -1000, 'textfield'),
            ('D7DA2831-8834-11EA-8C36-E86A641ED142', 'D7D9D328-8834-11EA-8C36-E86A641ED142', '编码', 'code', 'varchar', 255, 0, 0, 1, '', '', '', 2, 1, 1, 120, NULL, 0, 'textfield'),
            ('D7DA38C1-8834-11EA-8C36-E86A641ED142', 'D7D9D328-8834-11EA-8C36-E86A641ED142', '名称', 'name', 'varchar', 255, 0, 1, 1, '', '', '', 2, 1, 1, 200, NULL, 1, 'textfield'),
            ('D7DA46DE-8834-11EA-8C36-E86A641ED142', 'D7D9D328-8834-11EA-8C36-E86A641ED142', '拼音字头', 'py', 'varchar', 255, 0, -900, 1, '', '', '', 1, 1, 2, 0, NULL, -1000, 'textfield'),
            ('D7DA5596-8834-11EA-8C36-E86A641ED142', 'D7D9D328-8834-11EA-8C36-E86A641ED142', '数据域', 'data_org', 'varchar', 255, 0, -800, 1, '', '', '', 1, 1, 2, 0, NULL, -1000, 'textfield'),
            ('D7DA6187-8834-11EA-8C36-E86A641ED142', 'D7D9D328-8834-11EA-8C36-E86A641ED142', '公司id', 'company_id', 'varchar', 255, 0, -700, 1, '', '', '', 1, 1, 2, 0, NULL, -1000, 'textfield'),
            ('D7DA6ED9-8834-11EA-8C36-E86A641ED142', 'D7D9D328-8834-11EA-8C36-E86A641ED142', '记录创建时间', 'date_created', 'datetime', 0, 0, -699, 1, '', '', '', 1, 1, 2, 0, NULL, -1000, 'textfield'),
            ('D7DA7DA5-8834-11EA-8C36-E86A641ED142', 'D7D9D328-8834-11EA-8C36-E86A641ED142', '记录创建人id', 'create_user_id', 'varchar', 255, 0, -698, 1, '', '', '', 1, 1, 2, 0, NULL, -1000, 'textfield'),
            ('D7DA8BEF-8834-11EA-8C36-E86A641ED142', 'D7D9D328-8834-11EA-8C36-E86A641ED142', '最后一次编辑时间', 'update_dt', 'datetime', 0, 0, -697, 1, '', '', '', 1, 1, 2, 0, NULL, -1000, 'textfield'),
            ('D7DA9B13-8834-11EA-8C36-E86A641ED142', 'D7D9D328-8834-11EA-8C36-E86A641ED142', '最后一次编辑人id', 'update_user_id', 'varchar', 255, 0, -696, 1, '', '', '', 1, 1, 2, 0, NULL, -1000, 'textfield'),
            ('D7DAA800-8834-11EA-8C36-E86A641ED142', 'D7D9D328-8834-11EA-8C36-E86A641ED142', '状态', 'record_status', 'int', 11, 0, 2, 2, 't_sysdict_record_status', 'code_int', 'name', 2, 1, 1, 80, NULL, 2, 'textfield'),
            ('D7DAB56C-8834-11EA-8C36-E86A641ED142', 'D7D9D328-8834-11EA-8C36-E86A641ED142', '上级', 'parent_id', 'varchar', 255, 0, 4, 4, 't_goods_brand', 'id', 'full_name', 0, 1, 1, 0, NULL, -1000, 'psi_codetable_parentidfield'),
            ('D7DAC2CD-8834-11EA-8C36-E86A641ED142', 'D7D9D328-8834-11EA-8C36-E86A641ED142', '全名', 'full_name', 'varchar', 1000, 0, -1000, 5, '', '', '', 0, 1, 2, 300, NULL, 3, 'textfield');
            ";
    $db->execute($sql);
  }

  private function update_20200424_04()
  {
    // 本次更新：初始化t_goods_unit的码表元数据
    $db = $this->db;

    $sql = "DELETE FROM `t_code_table_md` where `id` = '8BD2B19E-8623-11EA-B463-E86A641ED142';
            DELETE FROM `t_code_table_cols_md` where `table_id` = '8BD2B19E-8623-11EA-B463-E86A641ED142' and `sys_col` = 1;
            INSERT INTO `t_code_table_md` (`id`, `code`, `name`, `table_name`, `category_id`, `memo`, `py`, `fid`, `md_version`, `is_fixed`, `enable_parent_id`, `handler_class_name`) VALUES
            ('8BD2B19E-8623-11EA-B463-E86A641ED142', 'PSI-0002-02', '商品计量单位', 't_goods_unit', '58BF84A3-8517-11EA-B071-E86A641ED142', '', '', '', 1, 1, 0, '');
            INSERT INTO `t_code_table_cols_md` (`id`, `table_id`, `caption`, `db_field_name`, `db_field_type`, `db_field_length`, `db_field_decimal`, `show_order`, `value_from`, `value_from_table_name`, `value_from_col_name`, `value_from_col_name_display`, `must_input`, `sys_col`, `is_visible`, `width_in_view`, `note`, `show_order_in_view`, `editor_xtype`) VALUES
            ('8BD477BD-8623-11EA-B463-E86A641ED142', '8BD2B19E-8623-11EA-B463-E86A641ED142', 'id', 'id', 'varchar', 255, 0, -1000, 1, '', '', '', 1, 1, 2, 0, NULL, -1000, 'textfield'),
            ('8BD49349-8623-11EA-B463-E86A641ED142', '8BD2B19E-8623-11EA-B463-E86A641ED142', '编码', 'code', 'varchar', 255, 0, 0, 1, '', '', '', 2, 1, 1, 120, NULL, 0, 'textfield'),
            ('8BD4A5B2-8623-11EA-B463-E86A641ED142', '8BD2B19E-8623-11EA-B463-E86A641ED142', '名称', 'name', 'varchar', 255, 0, 1, 1, '', '', '', 2, 1, 1, 200, NULL, 1, 'textfield'),
            ('8BD4B6D7-8623-11EA-B463-E86A641ED142', '8BD2B19E-8623-11EA-B463-E86A641ED142', '拼音字头', 'py', 'varchar', 255, 0, -900, 1, '', '', '', 1, 1, 2, 0, NULL, -1000, 'textfield'),
            ('8BD4C711-8623-11EA-B463-E86A641ED142', '8BD2B19E-8623-11EA-B463-E86A641ED142', '数据域', 'data_org', 'varchar', 255, 0, -800, 1, '', '', '', 1, 1, 2, 0, NULL, -1000, 'textfield'),
            ('8BD4D4AF-8623-11EA-B463-E86A641ED142', '8BD2B19E-8623-11EA-B463-E86A641ED142', '公司id', 'company_id', 'varchar', 255, 0, -700, 1, '', '', '', 1, 1, 2, 0, NULL, -1000, 'textfield'),
            ('8BD4E2A3-8623-11EA-B463-E86A641ED142', '8BD2B19E-8623-11EA-B463-E86A641ED142', '记录创建时间', 'date_created', 'datetime', 0, 0, -699, 1, '', '', '', 1, 1, 2, 0, NULL, -1000, 'textfield'),
            ('8BD4EFF4-8623-11EA-B463-E86A641ED142', '8BD2B19E-8623-11EA-B463-E86A641ED142', '记录创建人id', 'create_user_id', 'varchar', 255, 0, -698, 1, '', '', '', 1, 1, 2, 0, NULL, -1000, 'textfield'),
            ('8BD4FEA4-8623-11EA-B463-E86A641ED142', '8BD2B19E-8623-11EA-B463-E86A641ED142', '最后一次编辑时间', 'update_dt', 'datetime', 0, 0, -697, 1, '', '', '', 1, 1, 2, 0, NULL, -1000, 'textfield'),
            ('8BD50B33-8623-11EA-B463-E86A641ED142', '8BD2B19E-8623-11EA-B463-E86A641ED142', '最后一次编辑人id', 'update_user_id', 'varchar', 255, 0, -696, 1, '', '', '', 1, 1, 2, 0, NULL, -1000, 'textfield'),
            ('8BD5173E-8623-11EA-B463-E86A641ED142', '8BD2B19E-8623-11EA-B463-E86A641ED142', '状态', 'record_status', 'int', 11, 0, 2, 2, 't_sysdict_record_status', 'code_int', 'name', 2, 1, 1, 80, NULL, 2, 'textfield');
            ";
    $db->execute($sql);
  }

  private function update_20200424_03()
  {
    // 本次更新：初始化t_goods_category的码表元数据
    $db = $this->db;

    $sql = "DELETE FROM `t_code_table_md` where `id` = 'C68DBABE-860B-11EA-A0E2-E86A641ED142';
            DELETE FROM `t_code_table_cols_md` where `table_id` = 'C68DBABE-860B-11EA-A0E2-E86A641ED142' and `sys_col` = 1;
            INSERT INTO `t_code_table_md` (`id`, `code`, `name`, `table_name`, `category_id`, `memo`, `py`, `fid`, `md_version`, `is_fixed`, `enable_parent_id`, `handler_class_name`) VALUES
            ('C68DBABE-860B-11EA-A0E2-E86A641ED142', 'PSI-0002-01', '商品分类', 't_goods_category', '58BF84A3-8517-11EA-B071-E86A641ED142', '', '', '', 1, 1, 1, '');
            INSERT INTO `t_code_table_cols_md` (`id`, `table_id`, `caption`, `db_field_name`, `db_field_type`, `db_field_length`, `db_field_decimal`, `show_order`, `value_from`, `value_from_table_name`, `value_from_col_name`, `value_from_col_name_display`, `must_input`, `sys_col`, `is_visible`, `width_in_view`, `note`, `show_order_in_view`, `editor_xtype`) VALUES
            ('C68F8441-860B-11EA-A0E2-E86A641ED142', 'C68DBABE-860B-11EA-A0E2-E86A641ED142', 'id', 'id', 'varchar', 255, 0, -1000, 1, '', '', '', 1, 1, 2, 0, NULL, -1000, 'textfield'),
            ('C68F9A48-860B-11EA-A0E2-E86A641ED142', 'C68DBABE-860B-11EA-A0E2-E86A641ED142', '编码', 'code', 'varchar', 255, 0, 0, 1, '', '', '', 2, 1, 1, 120, NULL, 0, 'textfield'),
            ('C68FAABB-860B-11EA-A0E2-E86A641ED142', 'C68DBABE-860B-11EA-A0E2-E86A641ED142', '名称', 'name', 'varchar', 255, 0, 1, 1, '', '', '', 2, 1, 1, 200, NULL, 1, 'textfield'),
            ('C68FB980-860B-11EA-A0E2-E86A641ED142', 'C68DBABE-860B-11EA-A0E2-E86A641ED142', '拼音字头', 'py', 'varchar', 255, 0, -900, 1, '', '', '', 1, 1, 2, 0, NULL, -1000, 'textfield'),
            ('C68FC83A-860B-11EA-A0E2-E86A641ED142', 'C68DBABE-860B-11EA-A0E2-E86A641ED142', '数据域', 'data_org', 'varchar', 255, 0, -800, 1, '', '', '', 1, 1, 2, 0, NULL, -1000, 'textfield'),
            ('C68FD533-860B-11EA-A0E2-E86A641ED142', 'C68DBABE-860B-11EA-A0E2-E86A641ED142', '公司id', 'company_id', 'varchar', 255, 0, -700, 1, '', '', '', 1, 1, 2, 0, NULL, -1000, 'textfield'),
            ('C68FE37E-860B-11EA-A0E2-E86A641ED142', 'C68DBABE-860B-11EA-A0E2-E86A641ED142', '记录创建时间', 'date_created', 'datetime', 0, 0, -699, 1, '', '', '', 1, 1, 2, 0, NULL, -1000, 'textfield'),
            ('C68FF17F-860B-11EA-A0E2-E86A641ED142', 'C68DBABE-860B-11EA-A0E2-E86A641ED142', '记录创建人id', 'create_user_id', 'varchar', 255, 0, -698, 1, '', '', '', 1, 1, 2, 0, NULL, -1000, 'textfield'),
            ('C68FFCCA-860B-11EA-A0E2-E86A641ED142', 'C68DBABE-860B-11EA-A0E2-E86A641ED142', '最后一次编辑时间', 'update_dt', 'datetime', 0, 0, -697, 1, '', '', '', 1, 1, 2, 0, NULL, -1000, 'textfield'),
            ('C69009F3-860B-11EA-A0E2-E86A641ED142', 'C68DBABE-860B-11EA-A0E2-E86A641ED142', '最后一次编辑人id', 'update_user_id', 'varchar', 255, 0, -696, 1, '', '', '', 1, 1, 2, 0, NULL, -1000, 'textfield'),
            ('C6901758-860B-11EA-A0E2-E86A641ED142', 'C68DBABE-860B-11EA-A0E2-E86A641ED142', '状态', 'record_status', 'int', 11, 0, 2, 2, 't_sysdict_record_status', 'code_int', 'name', 2, 1, 1, 80, NULL, 2, 'textfield'),
            ('C69022E3-860B-11EA-A0E2-E86A641ED142', 'C68DBABE-860B-11EA-A0E2-E86A641ED142', '上级', 'parent_id', 'varchar', 255, 0, 4, 4, 't_goods_category', 'id', 'full_name', 0, 1, 1, 0, NULL, -1000, 'psi_codetable_parentidfield'),
            ('C6902FD8-860B-11EA-A0E2-E86A641ED142', 'C68DBABE-860B-11EA-A0E2-E86A641ED142', '全名', 'full_name', 'varchar', 1000, 0, -1000, 5, '', '', '', 0, 1, 2, 300, NULL, 3, 'textfield'),
            ('F04C6359-860B-11EA-A0E2-E86A641ED142', 'C68DBABE-860B-11EA-A0E2-E86A641ED142', '税率', 'tax_rate', 'decimal', 19, 2, 8, 1, '', '', '', 1, 1, 1, 120, '', 8, 'numberfield');
            ";
    $db->execute($sql);
  }

  private function update_20200424_02()
  {
    // 本次更新：初始化t_warehouse的码表元数据
    $db = $this->db;

    $sql = "DELETE FROM `t_code_table_md` where `id` = '49F3F27F-8607-11EA-A0E2-E86A641ED142';
            DELETE FROM `t_code_table_cols_md` where `table_id` = '49F3F27F-8607-11EA-A0E2-E86A641ED142' and `sys_col` = 1;
            INSERT INTO `t_code_table_md` (`id`, `code`, `name`, `table_name`, `category_id`, `memo`, `py`, `fid`, `md_version`, `is_fixed`, `enable_parent_id`, `handler_class_name`) VALUES
            ('49F3F27F-8607-11EA-A0E2-E86A641ED142', 'PSI-0003-01', '仓库', 't_warehouse', '05717096-851A-11EA-B071-E86A641ED142', '', '', '', 1, 1, 0, '');
            INSERT INTO `t_code_table_cols_md` (`id`, `table_id`, `caption`, `db_field_name`, `db_field_type`, `db_field_length`, `db_field_decimal`, `show_order`, `value_from`, `value_from_table_name`, `value_from_col_name`, `value_from_col_name_display`, `must_input`, `sys_col`, `is_visible`, `width_in_view`, `note`, `show_order_in_view`, `editor_xtype`) VALUES
            ('49F42B5E-8607-11EA-A0E2-E86A641ED142', '49F3F27F-8607-11EA-A0E2-E86A641ED142', 'id', 'id', 'varchar', 255, 0, -1000, 1, '', '', '', 1, 1, 2, 0, NULL, -1000, 'textfield'),
            ('49F43F2A-8607-11EA-A0E2-E86A641ED142', '49F3F27F-8607-11EA-A0E2-E86A641ED142', '编码', 'code', 'varchar', 255, 0, 0, 1, '', '', '', 2, 1, 1, 120, NULL, 0, 'textfield'),
            ('49F44FFE-8607-11EA-A0E2-E86A641ED142', '49F3F27F-8607-11EA-A0E2-E86A641ED142', '名称', 'name', 'varchar', 255, 0, 1, 1, '', '', '', 2, 1, 1, 200, NULL, 1, 'textfield'),
            ('49F45D67-8607-11EA-A0E2-E86A641ED142', '49F3F27F-8607-11EA-A0E2-E86A641ED142', '拼音字头', 'py', 'varchar', 255, 0, -900, 1, '', '', '', 1, 1, 2, 0, NULL, -1000, 'textfield'),
            ('49F46C4D-8607-11EA-A0E2-E86A641ED142', '49F3F27F-8607-11EA-A0E2-E86A641ED142', '数据域', 'data_org', 'varchar', 255, 0, -800, 1, '', '', '', 1, 1, 2, 0, NULL, -1000, 'textfield'),
            ('49F47EF1-8607-11EA-A0E2-E86A641ED142', '49F3F27F-8607-11EA-A0E2-E86A641ED142', '公司id', 'company_id', 'varchar', 255, 0, -700, 1, '', '', '', 1, 1, 2, 0, NULL, -1000, 'textfield'),
            ('49F48D39-8607-11EA-A0E2-E86A641ED142', '49F3F27F-8607-11EA-A0E2-E86A641ED142', '记录创建时间', 'date_created', 'datetime', 0, 0, -699, 1, '', '', '', 1, 1, 2, 0, NULL, -1000, 'textfield'),
            ('49F49D88-8607-11EA-A0E2-E86A641ED142', '49F3F27F-8607-11EA-A0E2-E86A641ED142', '记录创建人id', 'create_user_id', 'varchar', 255, 0, -698, 1, '', '', '', 1, 1, 2, 0, NULL, -1000, 'textfield'),
            ('49F4AB83-8607-11EA-A0E2-E86A641ED142', '49F3F27F-8607-11EA-A0E2-E86A641ED142', '最后一次编辑时间', 'update_dt', 'datetime', 0, 0, -697, 1, '', '', '', 1, 1, 2, 0, NULL, -1000, 'textfield'),
            ('49F4B946-8607-11EA-A0E2-E86A641ED142', '49F3F27F-8607-11EA-A0E2-E86A641ED142', '最后一次编辑人id', 'update_user_id', 'varchar', 255, 0, -696, 1, '', '', '', 1, 1, 2, 0, NULL, -1000, 'textfield'),
            ('49F4C69A-8607-11EA-A0E2-E86A641ED142', '49F3F27F-8607-11EA-A0E2-E86A641ED142', '状态', 'record_status', 'int', 11, 0, 2, 2, 't_sysdict_record_status', 'code_int', 'name', 2, 1, 1, 80, NULL, 2, 'textfield');
            ";
    $db->execute($sql);
  }

  private function update_20200424_01()
  {
    // 本次更新：初始化t_user的码表元数据
    $db = $this->db;

    $sql = "DELETE FROM `t_code_table_md` where `id` = '1C7AE1C9-85CC-11EA-A819-E86A641ED142';
            DELETE FROM `t_code_table_cols_md` where `table_id` = '1C7AE1C9-85CC-11EA-A819-E86A641ED142' and `sys_col` = 1;
            INSERT INTO `t_code_table_md` (`id`, `code`, `name`, `table_name`, `category_id`, `memo`, `py`, `fid`, `md_version`, `is_fixed`, `enable_parent_id`, `handler_class_name`) VALUES
            ('1C7AE1C9-85CC-11EA-A819-E86A641ED142', 'PSI-0001-02', '用户', 't_user', 'F9D80BD6-8519-11EA-B071-E86A641ED142', '', '', '', 1, 1, 0, '');
            INSERT INTO `t_code_table_cols_md` (`id`, `table_id`, `caption`, `db_field_name`, `db_field_type`, `db_field_length`, `db_field_decimal`, `show_order`, `value_from`, `value_from_table_name`, `value_from_col_name`, `value_from_col_name_display`, `must_input`, `sys_col`, `is_visible`, `width_in_view`, `note`, `show_order_in_view`, `editor_xtype`) VALUES
            ('1C7B14AE-85CC-11EA-A819-E86A641ED142', '1C7AE1C9-85CC-11EA-A819-E86A641ED142', 'id', 'id', 'varchar', 255, 0, -1000, 1, '', '', '', 1, 1, 2, 0, NULL, -1000, 'textfield'),
            ('1C7B3E91-85CC-11EA-A819-E86A641ED142', '1C7AE1C9-85CC-11EA-A819-E86A641ED142', '编码', 'code', 'varchar', 255, 0, 0, 1, '', '', '', 2, 1, 1, 120, NULL, 0, 'textfield'),
            ('1C7B5BB4-85CC-11EA-A819-E86A641ED142', '1C7AE1C9-85CC-11EA-A819-E86A641ED142', '名称', 'name', 'varchar', 255, 0, 1, 1, '', '', '', 2, 1, 1, 200, NULL, 1, 'textfield'),
            ('1C7B791B-85CC-11EA-A819-E86A641ED142', '1C7AE1C9-85CC-11EA-A819-E86A641ED142', '拼音字头', 'py', 'varchar', 255, 0, -900, 1, '', '', '', 1, 1, 2, 0, NULL, -1000, 'textfield'),
            ('1C7BA8A5-85CC-11EA-A819-E86A641ED142', '1C7AE1C9-85CC-11EA-A819-E86A641ED142', '数据域', 'data_org', 'varchar', 255, 0, -800, 1, '', '', '', 1, 1, 2, 0, NULL, -1000, 'textfield'),
            ('1C7BC667-85CC-11EA-A819-E86A641ED142', '1C7AE1C9-85CC-11EA-A819-E86A641ED142', '公司id', 'company_id', 'varchar', 255, 0, -700, 1, '', '', '', 1, 1, 2, 0, NULL, -1000, 'textfield'),
            ('1C7BDDD5-85CC-11EA-A819-E86A641ED142', '1C7AE1C9-85CC-11EA-A819-E86A641ED142', '记录创建时间', 'date_created', 'datetime', 0, 0, -699, 1, '', '', '', 1, 1, 2, 0, NULL, -1000, 'textfield'),
            ('1C7BF3A3-85CC-11EA-A819-E86A641ED142', '1C7AE1C9-85CC-11EA-A819-E86A641ED142', '记录创建人id', 'create_user_id', 'varchar', 255, 0, -698, 1, '', '', '', 1, 1, 2, 0, NULL, -1000, 'textfield'),
            ('1C7C092C-85CC-11EA-A819-E86A641ED142', '1C7AE1C9-85CC-11EA-A819-E86A641ED142', '最后一次编辑时间', 'update_dt', 'datetime', 0, 0, -697, 1, '', '', '', 1, 1, 2, 0, NULL, -1000, 'textfield'),
            ('1C7C1E35-85CC-11EA-A819-E86A641ED142', '1C7AE1C9-85CC-11EA-A819-E86A641ED142', '最后一次编辑人id', 'update_user_id', 'varchar', 255, 0, -696, 1, '', '', '', 1, 1, 2, 0, NULL, -1000, 'textfield'),
            ('1C7C3324-85CC-11EA-A819-E86A641ED142', '1C7AE1C9-85CC-11EA-A819-E86A641ED142', '状态', 'record_status', 'int', 11, 0, 2, 2, 't_sysdict_record_status', 'code_int', 'name', 2, 1, 1, 80, NULL, 2, 'textfield');
            ";
    $db->execute($sql);
  }

  private function update_20200423_03()
  {
    // 本次更新：初始化t_org的码表元数据
    $db = $this->db;

    $sql = "DELETE FROM `t_code_table_md` where `id` = 'AFB52688-851E-11EA-B071-E86A641ED142';
            DELETE FROM `t_code_table_cols_md` where `table_id` = 'AFB52688-851E-11EA-B071-E86A641ED142' and `sys_col` = 1;
            INSERT INTO `t_code_table_md` (`id`, `code`, `name`, `table_name`, `category_id`, `memo`, `py`, `fid`, `md_version`, `is_fixed`, `enable_parent_id`, `handler_class_name`) VALUES
            ('AFB52688-851E-11EA-B071-E86A641ED142', 'PSI-0001-01', '组织机构', 't_org', 'F9D80BD6-8519-11EA-B071-E86A641ED142', '', '', '', 1, 1, 1, '');
            INSERT INTO `t_code_table_cols_md` (`id`, `table_id`, `caption`, `db_field_name`, `db_field_type`, `db_field_length`, `db_field_decimal`, `show_order`, `value_from`, `value_from_table_name`, `value_from_col_name`, `value_from_col_name_display`, `must_input`, `sys_col`, `is_visible`, `width_in_view`, `note`, `show_order_in_view`, `editor_xtype`) VALUES
            ('AFB552D4-851E-11EA-B071-E86A641ED142', 'AFB52688-851E-11EA-B071-E86A641ED142', 'id', 'id', 'varchar', 255, 0, -1000, 1, '', '', '', 1, 1, 2, 0, NULL, -1000, 'textfield'),
            ('AFB57C6D-851E-11EA-B071-E86A641ED142', 'AFB52688-851E-11EA-B071-E86A641ED142', '编码', 'code', 'varchar', 255, 0, 0, 1, '', '', '', 2, 1, 1, 120, NULL, 0, 'textfield'),
            ('AFB58EDB-851E-11EA-B071-E86A641ED142', 'AFB52688-851E-11EA-B071-E86A641ED142', '名称', 'name', 'varchar', 255, 0, 1, 1, '', '', '', 2, 1, 1, 200, NULL, 1, 'textfield'),
            ('AFB5A12F-851E-11EA-B071-E86A641ED142', 'AFB52688-851E-11EA-B071-E86A641ED142', '拼音字头', 'py', 'varchar', 255, 0, -900, 1, '', '', '', 1, 1, 2, 0, NULL, -1000, 'textfield'),
            ('AFB5B1D0-851E-11EA-B071-E86A641ED142', 'AFB52688-851E-11EA-B071-E86A641ED142', '数据域', 'data_org', 'varchar', 255, 0, -800, 1, '', '', '', 1, 1, 2, 0, NULL, -1000, 'textfield'),
            ('AFB5C1E8-851E-11EA-B071-E86A641ED142', 'AFB52688-851E-11EA-B071-E86A641ED142', '公司id', 'company_id', 'varchar', 255, 0, -700, 1, '', '', '', 1, 1, 2, 0, NULL, -1000, 'textfield'),
            ('AFB5D22F-851E-11EA-B071-E86A641ED142', 'AFB52688-851E-11EA-B071-E86A641ED142', '记录创建时间', 'date_created', 'datetime', 0, 0, -699, 1, '', '', '', 1, 1, 2, 0, NULL, -1000, 'textfield'),
            ('AFB5E0A0-851E-11EA-B071-E86A641ED142', 'AFB52688-851E-11EA-B071-E86A641ED142', '记录创建人id', 'create_user_id', 'varchar', 255, 0, -698, 1, '', '', '', 1, 1, 2, 0, NULL, -1000, 'textfield'),
            ('AFB5EEA3-851E-11EA-B071-E86A641ED142', 'AFB52688-851E-11EA-B071-E86A641ED142', '最后一次编辑时间', 'update_dt', 'datetime', 0, 0, -697, 1, '', '', '', 1, 1, 2, 0, NULL, -1000, 'textfield'),
            ('AFB5FCDA-851E-11EA-B071-E86A641ED142', 'AFB52688-851E-11EA-B071-E86A641ED142', '最后一次编辑人id', 'update_user_id', 'varchar', 255, 0, -696, 1, '', '', '', 1, 1, 2, 0, NULL, -1000, 'textfield'),
            ('AFB60A25-851E-11EA-B071-E86A641ED142', 'AFB52688-851E-11EA-B071-E86A641ED142', '状态', 'record_status', 'int', 11, 0, 2, 2, 't_sysdict_record_status', 'code_int', 'name', 2, 1, 1, 80, NULL, 2, 'textfield'),
            ('AFB617D7-851E-11EA-B071-E86A641ED142', 'AFB52688-851E-11EA-B071-E86A641ED142', '上级', 'parent_id', 'varchar', 255, 0, 4, 4, 't_org', 'id', 'full_name', 0, 1, 1, 0, NULL, -1000, 'psi_codetable_parentidfield'),
            ('AFB625A6-851E-11EA-B071-E86A641ED142', 'AFB52688-851E-11EA-B071-E86A641ED142', '全名', 'full_name', 'varchar', 1000, 0, -1000, 5, '', '', '', 0, 1, 2, 300, NULL, 3, 'textfield');
            ";
    $db->execute($sql);
  }

  private function update_20200423_02()
  {
    // 本次更新：系统固有码表分类
    $db = $this->db;

    $sql = "DELETE FROM `t_code_table_category` where `id` = 'F9D80BD6-8519-11EA-B071-E86A641ED142';
            INSERT INTO `t_code_table_category` (`id`, `code`, `name`, `parent_id`, `is_system`) VALUES
            ('F9D80BD6-8519-11EA-B071-E86A641ED142', 'PSI-0001', '用户', NULL, 1);
            DELETE FROM `t_code_table_category` where `id` = '58BF84A3-8517-11EA-B071-E86A641ED142';
            INSERT INTO `t_code_table_category` (`id`, `code`, `name`, `parent_id`, `is_system`) VALUES
            ('58BF84A3-8517-11EA-B071-E86A641ED142', 'PSI-0002', '商品', NULL, 1);
            DELETE FROM `t_code_table_category` where `id` = '05717096-851A-11EA-B071-E86A641ED142';
            INSERT INTO `t_code_table_category` (`id`, `code`, `name`, `parent_id`, `is_system`) VALUES
            ('05717096-851A-11EA-B071-E86A641ED142', 'PSI-0003', '仓库', NULL, 1);
            DELETE FROM `t_code_table_category` where `id` = '0F8C175C-851A-11EA-B071-E86A641ED142';
            INSERT INTO `t_code_table_category` (`id`, `code`, `name`, `parent_id`, `is_system`) VALUES
            ('0F8C175C-851A-11EA-B071-E86A641ED142', 'PSI-0004', '供应商', NULL, 1);
            DELETE FROM `t_code_table_category` where `id` = '19DFD9E7-851A-11EA-B071-E86A641ED142';
            INSERT INTO `t_code_table_category` (`id`, `code`, `name`, `parent_id`, `is_system`) VALUES
            ('19DFD9E7-851A-11EA-B071-E86A641ED142', 'PSI-0005', '客户', NULL, 1);
            DELETE FROM `t_code_table_category` where `id` = '2FCB8D75-851A-11EA-B071-E86A641ED142';
            INSERT INTO `t_code_table_category` (`id`, `code`, `name`, `parent_id`, `is_system`) VALUES
            ('2FCB8D75-851A-11EA-B071-E86A641ED142', 'PSI-0006', '工厂', NULL, 1);";
    $db->execute($sql);
  }

  private function update_20200423_01()
  {
    // 本次更新：t_code_table_category新增字段is_system
    $db = $this->db;

    $tableName = "t_code_table_category";
    $columnName = "is_system";
    if (!$this->columnExists($db, $tableName, $columnName)) {
      $sql = "alter table {$tableName} add {$columnName} int(11) NOT NULL DEFAULT 2;";
      $db->execute($sql);
    }
  }

  private function update_20200422_01()
  {
    // 本次更新：t_sysdict_form_editor_xtype数据更新
    $db = $this->db;

    $sql = "TRUNCATE TABLE `t_sysdict_form_editor_xtype`;
            INSERT INTO `t_sysdict_form_editor_xtype` (`id`, `code`, `code_int`, `name`, `py`, `memo`, `show_order`) VALUES
            ('133BC834-62A4-11EA-BE39-F0BF9790E21F', '1', 1, 'textfield', 'textfield', '字符字段编辑器', 1),
            ('2E01A0A4-62A4-11EA-BE39-F0BF9790E21F', '2', 2, 'numberfield', 'numberfield', '数值字段编辑器', 2),
            ('28D248CD-843D-11EA-8C00-E86A641ED142', '3', 3, 'datefield', 'datefield', '日期字段编辑器', 3),
            ('BF6F569E-843D-11EA-8C00-E86A641ED142', '4', 4, 'displayfield', 'displayfield', '不使用编辑器', 4),
            ('CD0B859B-843D-11EA-8C00-E86A641ED142', '5', 5, 'psi_userfield', 'psi_userfield', '用户字段编辑器', 5);
            ";
    $db->execute($sql);
  }

  private function update_20200421_01()
  {
    // 本次更新：新增权限-库存账查询\总账导出Excel
    $db = $this->db;

    // fid
    $sql = "TRUNCATE TABLE `t_fid`;
            INSERT INTO `t_fid` (`fid`, `name`, `py`, `memo`) VALUES
            ('-7999', '自定义表单', 'ZDYBD', ''),
            ('-7994', '系统数据字典', 'XTSJZD', ''),
            ('-7995', '主菜单维护', 'ZCDWH', ''),
            ('-7996', '码表设置', 'MBSZ', ''),
            ('-7997', '表单视图开发助手', 'BDSTKFZS', ''),
            ('-9999', '重新登录', '', ''),
            ('-9997', '首页', 'SY', ''),
            ('-9996', '修改我的密码', 'XGWDMM', ''),
            ('-9995', '帮助', 'BZ', ''),
            ('-9994', '关于', 'GY', ''),
            ('-9993', '购买商业服务', '', ''),
            ('-8999', '用户管理', 'YHGL', ''),
            ('-8999-01', '组织机构在业务单据中的使用权限', '', ''),
            ('-8999-02', '业务员在业务单据中的使用权限', '', ''),
            ('-8997', '业务日志', 'YWRZ', ''),
            ('-8996', '权限管理', 'QXGL', ''),
            ('1001', '商品', 'SP', ''),
            ('1001-01', '商品在业务单据中的使用权限', '', ''),
            ('1001-02', '商品分类', 'SPFL', ''),
            ('1002', '商品计量单位', 'SPJLDW', ''),
            ('1003', '仓库', 'CK', ''),
            ('1003-01', '仓库在业务单据中的使用权限', '', ''),
            ('1004', '供应商档案', 'GYSDA', ''),
            ('1004-01', '供应商档案在业务单据中的使用权限', '', ''),
            ('1004-02', '供应商分类', '', ''),
            ('1007', '客户资料', 'KHZL', ''),
            ('1007-01', '客户资料在业务单据中的使用权限', '', ''),
            ('1007-02', '客户分类', '', ''),
            ('2000', '库存建账', 'KCJZ', ''),
            ('2001', '采购入库', 'CGRK', ''),
            ('2001-01', '采购入库-新建采购入库单', '', ''),
            ('2001-02', '采购入库-编辑采购入库单', '', ''),
            ('2001-03', '采购入库-删除采购入库单', '', ''),
            ('2001-04', '采购入库-提交入库', '', ''),
            ('2001-05', '采购入库-单据生成PDF', '', ''),
            ('2001-06', '采购入库-采购单价和金额可见', '', ''),
            ('2001-07', '采购入库-打印', '', ''),
            ('2002', '销售出库', 'XSCK', ''),
            ('2002-01', '销售出库-销售出库单允许编辑销售单价', '', ''),
            ('2002-02', '销售出库-新建销售出库单', '', ''),
            ('2002-03', '销售出库-编辑销售出库单', '', ''),
            ('2002-04', '销售出库-删除销售出库单', '', ''),
            ('2002-05', '销售出库-提交出库', '', ''),
            ('2002-06', '销售出库-单据生成PDF', '', ''),
            ('2002-07', '销售出库-打印', '', ''),
            ('2003', '库存账查询', 'KCZCX', ''),
            ('2003-01', '库存账查询-总账导出Excel', '', ''),
            ('2004', '应收账款管理', 'YSZKGL', ''),
            ('2005', '应付账款管理', 'YFZKGL', ''),
            ('2006', '销售退货入库', 'XSTHRK', ''),
            ('2006-01', '销售退货入库-新建销售退货入库单', '', ''),
            ('2006-02', '销售退货入库-编辑销售退货入库单', '', ''),
            ('2006-03', '销售退货入库-删除销售退货入库单', '', ''),
            ('2006-04', '销售退货入库-提交入库', '', ''),
            ('2006-05', '销售退货入库-单据生成PDF', '', ''),
            ('2006-06', '销售退货入库-打印', '', ''),
            ('2007', '采购退货出库', 'CGTHCK', ''),
            ('2007-01', '采购退货出库-新建采购退货出库单', '', ''),
            ('2007-02', '采购退货出库-编辑采购退货出库单', '', ''),
            ('2007-03', '采购退货出库-删除采购退货出库单', '', ''),
            ('2007-04', '采购退货出库-提交采购退货出库单', '', ''),
            ('2007-05', '采购退货出库-单据生成PDF', '', ''),
            ('2007-06', '采购退货出库-打印', '', ''),
            ('2008', '业务设置', 'YWSZ', ''),
            ('2009', '库间调拨', 'KJDB', ''),
            ('2009-01', '库间调拨-新建调拨单', '', ''),
            ('2009-02', '库间调拨-编辑调拨单', '', ''),
            ('2009-03', '库间调拨-删除调拨单', '', ''),
            ('2009-04', '库间调拨-提交调拨单', '', ''),
            ('2009-05', '库间调拨-单据生成PDF', '', ''),
            ('2009-06', '库间调拨-打印', '', ''),
            ('2010', '库存盘点', 'KCPD', ''),
            ('2010-01', '库存盘点-新建盘点单', '', ''),
            ('2010-02', '库存盘点-编辑盘点单', '', ''),
            ('2010-03', '库存盘点-删除盘点单', '', ''),
            ('2010-04', '库存盘点-提交盘点单', '', ''),
            ('2010-05', '库存盘点-单据生成PDF', '', ''),
            ('2010-06', '库存盘点-打印', '', ''),
            ('2011-01', '首页-销售看板', '', ''),
            ('2011-02', '首页-库存看板', '', ''),
            ('2011-03', '首页-采购看板', '', ''),
            ('2011-04', '首页-资金看板', '', ''),
            ('2012', '报表-销售日报表(按商品汇总)', 'BBXSRBBASPHZ', ''),
            ('2013', '报表-销售日报表(按客户汇总)', 'BBXSRBBAKHHZ', ''),
            ('2014', '报表-销售日报表(按仓库汇总)', 'BBXSRBBACKHZ', ''),
            ('2015', '报表-销售日报表(按业务员汇总)', 'BBXSRBBAYWYHZ', ''),
            ('2016', '报表-销售月报表(按商品汇总)', 'BBXSYBBASPHZ', ''),
            ('2017', '报表-销售月报表(按客户汇总)', 'BBXSYBBAKHHZ', ''),
            ('2018', '报表-销售月报表(按仓库汇总)', 'BBXSYBBACKHZ', ''),
            ('2019', '报表-销售月报表(按业务员汇总)', 'BBXSYBBAYWYHZ', ''),
            ('2020', '报表-安全库存明细表', 'BBAQKCMXB', ''),
            ('2021', '报表-应收账款账龄分析表', 'BBYSZKZLFXB', ''),
            ('2022', '报表-应付账款账龄分析表', 'BBYFZKZLFXB', ''),
            ('2023', '报表-库存超上限明细表', 'BBKCCSXMXB', ''),
            ('2024', '现金收支查询', 'XJSZCX', ''),
            ('2025', '预收款管理', 'YSKGL', ''),
            ('2026', '预付款管理', 'YFKGL', ''),
            ('2027', '采购订单', 'CGDD', ''),
            ('2027-01', '采购订单-审核/取消审核', '', ''),
            ('2027-02', '采购订单-生成采购入库单', '', ''),
            ('2027-03', '采购订单-新建采购订单', '', ''),
            ('2027-04', '采购订单-编辑采购订单', '', ''),
            ('2027-05', '采购订单-删除采购订单', '', ''),
            ('2027-06', '采购订单-关闭订单/取消关闭订单', '', ''),
            ('2027-07', '采购订单-单据生成PDF', '', ''),
            ('2027-08', '采购订单-打印', '', ''),
            ('2028', '销售订单', 'XSDD', ''),
            ('2028-01', '销售订单-审核/取消审核', '', ''),
            ('2028-02', '销售订单-生成销售出库单', '', ''),
            ('2028-03', '销售订单-新建销售订单', '', ''),
            ('2028-04', '销售订单-编辑销售订单', '', ''),
            ('2028-05', '销售订单-删除销售订单', '', ''),
            ('2028-06', '销售订单-单据生成PDF', '', ''),
            ('2028-07', '销售订单-打印', '', ''),
            ('2028-08', '销售订单-生成采购订单', '', ''),
            ('2028-09', '销售订单-关闭订单/取消关闭订单', '', ''),
            ('2029', '商品品牌', 'SPPP', ''),
            ('2030-01', '商品构成-新增子商品', '', ''),
            ('2030-02', '商品构成-编辑子商品', '', ''),
            ('2030-03', '商品构成-删除子商品', '', ''),
            ('2031', '价格体系', 'JGTX', ''),
            ('2031-01', '商品-设置商品价格体系', '', ''),
            ('2032', '销售合同', 'XSHT', ''),
            ('2032-01', '销售合同-新建销售合同', '', ''),
            ('2032-02', '销售合同-编辑销售合同', '', ''),
            ('2032-03', '销售合同-删除销售合同', '', ''),
            ('2032-04', '销售合同-审核/取消审核', '', ''),
            ('2032-05', '销售合同-生成销售订单', '', ''),
            ('2032-06', '销售合同-单据生成PDF', '', ''),
            ('2032-07', '销售合同-打印', '', ''),
            ('2033', '存货拆分', 'CHCF', ''),
            ('2033-01', '存货拆分-新建拆分单', '', ''),
            ('2033-02', '存货拆分-编辑拆分单', '', ''),
            ('2033-03', '存货拆分-删除拆分单', '', ''),
            ('2033-04', '存货拆分-提交拆分单', '', ''),
            ('2033-05', '存货拆分-单据生成PDF', '', ''),
            ('2033-06', '存货拆分-打印', '', ''),
            ('2034', '工厂', 'GC', ''),
            ('2034-01', '工厂在业务单据中的使用权限', '', ''),
            ('2034-02', '工厂分类', '', ''),
            ('2034-03', '工厂-新增工厂分类', '', ''),
            ('2034-04', '工厂-编辑工厂分类', '', ''),
            ('2034-05', '工厂-删除工厂分类', '', ''),
            ('2034-06', '工厂-新增工厂', '', ''),
            ('2034-07', '工厂-编辑工厂', '', ''),
            ('2034-08', '工厂-删除工厂', '', ''),
            ('2035', '成品委托生产订单', 'CPWTSCDD', ''),
            ('2035-01', '成品委托生产订单-新建成品委托生产订单', '', ''),
            ('2035-02', '成品委托生产订单-编辑成品委托生产订单', '', ''),
            ('2035-03', '成品委托生产订单-删除成品委托生产订单', '', ''),
            ('2035-04', '成品委托生产订单-提交成品委托生产订单', '', ''),
            ('2035-05', '成品委托生产订单-审核/取消审核成品委托生产入库单', '', ''),
            ('2035-06', '成品委托生产订单-关闭/取消关闭成品委托生产订单', '', ''),
            ('2035-07', '成品委托生产订单-单据生成PDF', '', ''),
            ('2035-08', '成品委托生产订单-打印', '', ''),
            ('2036', '成品委托生产入库', 'CPWTSCRK', ''),
            ('2036-01', '成品委托生产入库-新建成品委托生产入库单', '', ''),
            ('2036-02', '成品委托生产入库-编辑成品委托生产入库单', '', ''),
            ('2036-03', '成品委托生产入库-删除成品委托生产入库单', '', ''),
            ('2036-04', '成品委托生产入库-提交入库', '', ''),
            ('2036-05', '成品委托生产入库-单据生成PDF', '', ''),
            ('2036-06', '成品委托生产入库-打印', '', ''),
            ('2037', '报表-采购入库明细表', '', ''),
            ('2101', '会计科目', 'KJKM', ''),
            ('2102', '银行账户', 'YHZH', ''),
            ('2103', '会计期间', 'KJQJ', ''),
            ('3101', '物料单位', 'WLDW', ''),
            ('3102', '原材料', '', ''),
            ('3102-01', '原材料-原材料在业务单据中的使用权限', '', ''),
            ('3102-02', '原材料-原材料分类数据权限', '', ''),
            ('3102-03', '原材料-新增原材料分类', '', ''),
            ('3102-04', '原材料-编辑原材料分类', '', ''),
            ('3102-05', '原材料-删除原材料分类', '', ''),
            ('3102-06', '原材料-新增原材料', '', ''),
            ('3102-07', '原材料-编辑原材料', '', ''),
            ('3102-08', '原材料-删除原材料', '', '');
            ";
    $db->execute($sql);

    // 权限项
    $sql = "TRUNCATE TABLE `t_permission`;
            INSERT INTO `t_permission` (`id`, `fid`, `name`, `note`, `category`, `py`, `show_order`) VALUES
            ('-7999', '-7999', '自定义表单', '模块权限：通过菜单进入自定义表单模块的权限', '自定义表单', 'ZDYBD', 100),
            ('-7994', '-7994', '系统数据字典', '模块权限：通过菜单进入系统数据字典模块的权限', '系统数据字典', 'XTSJZD', 100),
            ('-7995', '-7995', '主菜单维护', '模块权限：通过菜单进入主菜单维护模块的权限', '主菜单维护', 'ZCDWH', 100),
            ('-7996', '-7996', '码表设置', '模块权限：通过菜单进入码表设置模块的权限', '码表设置', 'MBSZ', 100),
            ('-8996', '-8996', '权限管理', '模块权限：通过菜单进入权限管理模块的权限', '权限管理', 'QXGL', 100),
            ('-8996-01', '-8996-01', '权限管理-新增角色', '按钮权限：权限管理模块[新增角色]按钮权限', '权限管理', 'QXGL_XZJS', 201),
            ('-8996-02', '-8996-02', '权限管理-编辑角色', '按钮权限：权限管理模块[编辑角色]按钮权限', '权限管理', 'QXGL_BJJS', 202),
            ('-8996-03', '-8996-03', '权限管理-删除角色', '按钮权限：权限管理模块[删除角色]按钮权限', '权限管理', 'QXGL_SCJS', 203),
            ('-8997', '-8997', '业务日志', '模块权限：通过菜单进入业务日志模块的权限', '系统管理', 'YWRZ', 100),
            ('-8999', '-8999', '用户管理', '模块权限：通过菜单进入用户管理模块的权限', '用户管理', 'YHGL', 100),
            ('-8999-01', '-8999-01', '组织机构在业务单据中的使用权限', '数据域权限：组织机构在业务单据中的使用权限', '用户管理', 'ZZJGZYWDJZDSYQX', 300),
            ('-8999-02', '-8999-02', '业务员在业务单据中的使用权限', '数据域权限：业务员在业务单据中的使用权限', '用户管理', 'YWYZYWDJZDSYQX', 301),
            ('-8999-03', '-8999-03', '用户管理-新增组织机构', '按钮权限：用户管理模块[新增组织机构]按钮权限', '用户管理', 'YHGL_XZZZJG', 201),
            ('-8999-04', '-8999-04', '用户管理-编辑组织机构', '按钮权限：用户管理模块[编辑组织机构]按钮权限', '用户管理', 'YHGL_BJZZJG', 202),
            ('-8999-05', '-8999-05', '用户管理-删除组织机构', '按钮权限：用户管理模块[删除组织机构]按钮权限', '用户管理', 'YHGL_SCZZJG', 203),
            ('-8999-06', '-8999-06', '用户管理-新增用户', '按钮权限：用户管理模块[新增用户]按钮权限', '用户管理', 'YHGL_XZYH', 204),
            ('-8999-07', '-8999-07', '用户管理-编辑用户', '按钮权限：用户管理模块[编辑用户]按钮权限', '用户管理', 'YHGL_BJYH', 205),
            ('-8999-08', '-8999-08', '用户管理-删除用户', '按钮权限：用户管理模块[删除用户]按钮权限', '用户管理', 'YHGL_SCYH', 206),
            ('-8999-09', '-8999-09', '用户管理-修改用户密码', '按钮权限：用户管理模块[修改用户密码]按钮权限', '用户管理', 'YHGL_XGYHMM', 207),
            ('1001', '1001', '商品', '模块权限：通过菜单进入商品模块的权限', '商品', 'SP', 100),
            ('1001-01', '1001-01', '商品在业务单据中的使用权限', '数据域权限：商品在业务单据中的使用权限', '商品', 'SPZYWDJZDSYQX', 300),
            ('1001-02', '1001-02', '商品分类', '数据域权限：商品模块中商品分类的数据权限', '商品', 'SPFL', 301),
            ('1001-03', '1001-03', '新增商品分类', '按钮权限：商品模块[新增商品分类]按钮权限', '商品', 'XZSPFL', 201),
            ('1001-04', '1001-04', '编辑商品分类', '按钮权限：商品模块[编辑商品分类]按钮权限', '商品', 'BJSPFL', 202),
            ('1001-05', '1001-05', '删除商品分类', '按钮权限：商品模块[删除商品分类]按钮权限', '商品', 'SCSPFL', 203),
            ('1001-06', '1001-06', '新增商品', '按钮权限：商品模块[新增商品]按钮权限', '商品', 'XZSP', 204),
            ('1001-07', '1001-07', '编辑商品', '按钮权限：商品模块[编辑商品]按钮权限', '商品', 'BJSP', 205),
            ('1001-08', '1001-08', '删除商品', '按钮权限：商品模块[删除商品]按钮权限', '商品', 'SCSP', 206),
            ('1001-09', '1001-09', '导入商品', '按钮权限：商品模块[导入商品]按钮权限', '商品', 'DRSP', 207),
            ('1001-10', '1001-10', '设置商品安全库存', '按钮权限：商品模块[设置安全库存]按钮权限', '商品', 'SZSPAQKC', 208),
            ('1001-11', '1001-11', '导出Excel', '按钮权限：商品模块[导出Excel]按钮权限', '商品', 'DCEXCEL', 209),
            ('1002', '1002', '商品计量单位', '模块权限：通过菜单进入商品计量单位模块的权限', '商品', 'SPJLDW', 500),
            ('1003', '1003', '仓库', '模块权限：通过菜单进入仓库的权限', '仓库', 'CK', 100),
            ('1003-01', '1003-01', '仓库在业务单据中的使用权限', '数据域权限：仓库在业务单据中的使用权限', '仓库', 'CKZYWDJZDSYQX', 300),
            ('1003-02', '1003-02', '新增仓库', '按钮权限：仓库模块[新增仓库]按钮权限', '仓库', 'XZCK', 201),
            ('1003-03', '1003-03', '编辑仓库', '按钮权限：仓库模块[编辑仓库]按钮权限', '仓库', 'BJCK', 202),
            ('1003-04', '1003-04', '删除仓库', '按钮权限：仓库模块[删除仓库]按钮权限', '仓库', 'SCCK', 203),
            ('1003-05', '1003-05', '修改仓库数据域', '按钮权限：仓库模块[修改数据域]按钮权限', '仓库', 'XGCKSJY', 204),
            ('1004', '1004', '供应商档案', '模块权限：通过菜单进入供应商档案的权限', '供应商管理', 'GYSDA', 100),
            ('1004-01', '1004-01', '供应商档案在业务单据中的使用权限', '数据域权限：供应商档案在业务单据中的使用权限', '供应商管理', 'GYSDAZYWDJZDSYQX', 301),
            ('1004-02', '1004-02', '供应商分类', '数据域权限：供应商档案模块中供应商分类的数据权限', '供应商管理', 'GYSFL', 300),
            ('1004-03', '1004-03', '新增供应商分类', '按钮权限：供应商档案模块[新增供应商分类]按钮权限', '供应商管理', 'XZGYSFL', 201),
            ('1004-04', '1004-04', '编辑供应商分类', '按钮权限：供应商档案模块[编辑供应商分类]按钮权限', '供应商管理', 'BJGYSFL', 202),
            ('1004-05', '1004-05', '删除供应商分类', '按钮权限：供应商档案模块[删除供应商分类]按钮权限', '供应商管理', 'SCGYSFL', 203),
            ('1004-06', '1004-06', '新增供应商', '按钮权限：供应商档案模块[新增供应商]按钮权限', '供应商管理', 'XZGYS', 204),
            ('1004-07', '1004-07', '编辑供应商', '按钮权限：供应商档案模块[编辑供应商]按钮权限', '供应商管理', 'BJGYS', 205),
            ('1004-08', '1004-08', '删除供应商', '按钮权限：供应商档案模块[删除供应商]按钮权限', '供应商管理', 'SCGYS', 206),
            ('1007', '1007', '客户资料', '模块权限：通过菜单进入客户资料模块的权限', '客户管理', 'KHZL', 100),
            ('1007-01', '1007-01', '客户资料在业务单据中的使用权限', '数据域权限：客户资料在业务单据中的使用权限', '客户管理', 'KHZLZYWDJZDSYQX', 300),
            ('1007-02', '1007-02', '客户分类', '数据域权限：客户档案模块中客户分类的数据权限', '客户管理', 'KHFL', 301),
            ('1007-03', '1007-03', '新增客户分类', '按钮权限：客户资料模块[新增客户分类]按钮权限', '客户管理', 'XZKHFL', 201),
            ('1007-04', '1007-04', '编辑客户分类', '按钮权限：客户资料模块[编辑客户分类]按钮权限', '客户管理', 'BJKHFL', 202),
            ('1007-05', '1007-05', '删除客户分类', '按钮权限：客户资料模块[删除客户分类]按钮权限', '客户管理', 'SCKHFL', 203),
            ('1007-06', '1007-06', '新增客户', '按钮权限：客户资料模块[新增客户]按钮权限', '客户管理', 'XZKH', 204),
            ('1007-07', '1007-07', '编辑客户', '按钮权限：客户资料模块[编辑客户]按钮权限', '客户管理', 'BJKH', 205),
            ('1007-08', '1007-08', '删除客户', '按钮权限：客户资料模块[删除客户]按钮权限', '客户管理', 'SCKH', 206),
            ('1007-09', '1007-09', '导入客户', '按钮权限：客户资料模块[导入客户]按钮权限', '客户管理', 'DRKH', 207),
            ('2000', '2000', '库存建账', '模块权限：通过菜单进入库存建账模块的权限', '库存建账', 'KCJZ', 100),
            ('2001', '2001', '采购入库', '模块权限：通过菜单进入采购入库模块的权限', '采购入库', 'CGRK', 100),
            ('2001-01', '2001-01', '采购入库-新建采购入库单', '按钮权限：采购入库模块[新建采购入库单]按钮权限', '采购入库', 'CGRK_XJCGRKD', 201),
            ('2001-02', '2001-02', '采购入库-编辑采购入库单', '按钮权限：采购入库模块[编辑采购入库单]按钮权限', '采购入库', 'CGRK_BJCGRKD', 202),
            ('2001-03', '2001-03', '采购入库-删除采购入库单', '按钮权限：采购入库模块[删除采购入库单]按钮权限', '采购入库', 'CGRK_SCCGRKD', 203),
            ('2001-04', '2001-04', '采购入库-提交入库', '按钮权限：采购入库模块[提交入库]按钮权限', '采购入库', 'CGRK_TJRK', 204),
            ('2001-05', '2001-05', '采购入库-单据生成PDF', '按钮权限：采购入库模块[单据生成PDF]按钮权限', '采购入库', 'CGRK_DJSCPDF', 205),
            ('2001-06', '2001-06', '采购入库-采购单价和金额可见', '字段权限：采购入库单的采购单价和金额可以被用户查看', '采购入库', 'CGRK_CGDJHJEKJ', 206),
            ('2001-07', '2001-07', '采购入库-打印', '按钮权限：采购入库模块[打印预览]和[直接打印]按钮权限', '采购入库', 'CGRK_DY', 207),
            ('2002', '2002', '销售出库', '模块权限：通过菜单进入销售出库模块的权限', '销售出库', 'XSCK', 100),
            ('2002-01', '2002-01', '销售出库-销售出库单允许编辑销售单价', '功能权限：销售出库单允许编辑销售单价', '销售出库', 'XSCKDYXBJXSDJ', 101),
            ('2002-02', '2002-02', '销售出库-新建销售出库单', '按钮权限：销售出库模块[新建销售出库单]按钮权限', '销售出库', 'XSCK_XJXSCKD', 201),
            ('2002-03', '2002-03', '销售出库-编辑销售出库单', '按钮权限：销售出库模块[编辑销售出库单]按钮权限', '销售出库', 'XSCK_BJXSCKD', 202),
            ('2002-04', '2002-04', '销售出库-删除销售出库单', '按钮权限：销售出库模块[删除销售出库单]按钮权限', '销售出库', 'XSCK_SCXSCKD', 203),
            ('2002-05', '2002-05', '销售出库-提交出库', '按钮权限：销售出库模块[提交出库]按钮权限', '销售出库', 'XSCK_TJCK', 204),
            ('2002-06', '2002-06', '销售出库-单据生成PDF', '按钮权限：销售出库模块[单据生成PDF]按钮权限', '销售出库', 'XSCK_DJSCPDF', 205),
            ('2002-07', '2002-07', '销售出库-打印', '按钮权限：销售出库模块[打印预览]和[直接打印]按钮权限', '销售出库', 'XSCK_DY', 207),
            ('2003', '2003', '库存账查询', '模块权限：通过菜单进入库存账查询模块的权限', '库存账查询', 'KCZCX', 100),
            ('2003-01', '2003-01', '总账导出Excel', '按钮权限：库存账查询模块[总账导出Excel]按钮权限', '库存账查询', '', 201),
            ('2004', '2004', '应收账款管理', '模块权限：通过菜单进入应收账款管理模块的权限', '应收账款管理', 'YSZKGL', 100),
            ('2005', '2005', '应付账款管理', '模块权限：通过菜单进入应付账款管理模块的权限', '应付账款管理', 'YFZKGL', 100),
            ('2006', '2006', '销售退货入库', '模块权限：通过菜单进入销售退货入库模块的权限', '销售退货入库', 'XSTHRK', 100),
            ('2006-01', '2006-01', '销售退货入库-新建销售退货入库单', '按钮权限：销售退货入库模块[新建销售退货入库单]按钮权限', '销售退货入库', 'XSTHRK_XJXSTHRKD', 201),
            ('2006-02', '2006-02', '销售退货入库-编辑销售退货入库单', '按钮权限：销售退货入库模块[编辑销售退货入库单]按钮权限', '销售退货入库', 'XSTHRK_BJXSTHRKD', 202),
            ('2006-03', '2006-03', '销售退货入库-删除销售退货入库单', '按钮权限：销售退货入库模块[删除销售退货入库单]按钮权限', '销售退货入库', 'XSTHRK_SCXSTHRKD', 203),
            ('2006-04', '2006-04', '销售退货入库-提交入库', '按钮权限：销售退货入库模块[提交入库]按钮权限', '销售退货入库', 'XSTHRK_TJRK', 204),
            ('2006-05', '2006-05', '销售退货入库-单据生成PDF', '按钮权限：销售退货入库模块[单据生成PDF]按钮权限', '销售退货入库', 'XSTHRK_DJSCPDF', 205),
            ('2006-06', '2006-06', '销售退货入库-打印', '按钮权限：销售退货入库模块[打印预览]和[直接打印]按钮权限', '销售退货入库', 'XSTHRK_DY', 206),
            ('2007', '2007', '采购退货出库', '模块权限：通过菜单进入采购退货出库模块的权限', '采购退货出库', 'CGTHCK', 100),
            ('2007-01', '2007-01', '采购退货出库-新建采购退货出库单', '按钮权限：采购退货出库模块[新建采购退货出库单]按钮权限', '采购退货出库', 'CGTHCK_XJCGTHCKD', 201),
            ('2007-02', '2007-02', '采购退货出库-编辑采购退货出库单', '按钮权限：采购退货出库模块[编辑采购退货出库单]按钮权限', '采购退货出库', 'CGTHCK_BJCGTHCKD', 202),
            ('2007-03', '2007-03', '采购退货出库-删除采购退货出库单', '按钮权限：采购退货出库模块[删除采购退货出库单]按钮权限', '采购退货出库', 'CGTHCK_SCCGTHCKD', 203),
            ('2007-04', '2007-04', '采购退货出库-提交采购退货出库单', '按钮权限：采购退货出库模块[提交采购退货出库单]按钮权限', '采购退货出库', 'CGTHCK_TJCGTHCKD', 204),
            ('2007-05', '2007-05', '采购退货出库-单据生成PDF', '按钮权限：采购退货出库模块[单据生成PDF]按钮权限', '采购退货出库', 'CGTHCK_DJSCPDF', 205),
            ('2007-06', '2007-06', '采购退货出库-打印', '按钮权限：采购退货出库模块[打印预览]和[直接打印]按钮权限', '采购退货出库', 'CGTHCK_DY', 206),
            ('2008', '2008', '业务设置', '模块权限：通过菜单进入业务设置模块的权限', '系统管理', 'YWSZ', 100),
            ('2009', '2009', '库间调拨', '模块权限：通过菜单进入库间调拨模块的权限', '库间调拨', 'KJDB', 100),
            ('2009-01', '2009-01', '库间调拨-新建调拨单', '按钮权限：库间调拨模块[新建调拨单]按钮权限', '库间调拨', 'KJDB_XJDBD', 201),
            ('2009-02', '2009-02', '库间调拨-编辑调拨单', '按钮权限：库间调拨模块[编辑调拨单]按钮权限', '库间调拨', 'KJDB_BJDBD', 202),
            ('2009-03', '2009-03', '库间调拨-删除调拨单', '按钮权限：库间调拨模块[删除调拨单]按钮权限', '库间调拨', 'KJDB_SCDBD', 203),
            ('2009-04', '2009-04', '库间调拨-提交调拨单', '按钮权限：库间调拨模块[提交调拨单]按钮权限', '库间调拨', 'KJDB_TJDBD', 204),
            ('2009-05', '2009-05', '库间调拨-单据生成PDF', '按钮权限：库间调拨模块[单据生成PDF]按钮权限', '库间调拨', 'KJDB_DJSCPDF', 205),
            ('2009-06', '2009-06', '库间调拨-打印', '按钮权限：库间调拨模块[打印预览]和[直接打印]按钮权限', '库间调拨', 'KJDB_DY', 206),
            ('2010', '2010', '库存盘点', '模块权限：通过菜单进入库存盘点模块的权限', '库存盘点', 'KCPD', 100),
            ('2010-01', '2010-01', '库存盘点-新建盘点单', '按钮权限：库存盘点模块[新建盘点单]按钮权限', '库存盘点', 'KCPD_XJPDD', 201),
            ('2010-02', '2010-02', '库存盘点-编辑盘点单', '按钮权限：库存盘点模块[编辑盘点单]按钮权限', '库存盘点', 'KCPD_BJPDD', 202),
            ('2010-03', '2010-03', '库存盘点-删除盘点单', '按钮权限：库存盘点模块[删除盘点单]按钮权限', '库存盘点', 'KCPD_SCPDD', 203),
            ('2010-04', '2010-04', '库存盘点-提交盘点单', '按钮权限：库存盘点模块[提交盘点单]按钮权限', '库存盘点', 'KCPD_TJPDD', 204),
            ('2010-05', '2010-05', '库存盘点-单据生成PDF', '按钮权限：库存盘点模块[单据生成PDF]按钮权限', '库存盘点', 'KCPD_DJSCPDF', 205),
            ('2010-06', '2010-06', '库存盘点-打印', '按钮权限：库存盘点模块[打印预览]和[直接打印]按钮权限', '库存盘点', 'KCPD_DY', 206),
            ('2011-01', '2011-01', '首页-销售看板', '功能权限：在首页显示销售看板', '首页看板', 'SY_XSKB', 100),
            ('2011-02', '2011-02', '首页-库存看板', '功能权限：在首页显示库存看板', '首页看板', 'SY_KCKB', 100),
            ('2011-03', '2011-03', '首页-采购看板', '功能权限：在首页显示采购看板', '首页看板', 'SY_CGKB', 100),
            ('2011-04', '2011-04', '首页-资金看板', '功能权限：在首页显示资金看板', '首页看板', 'SY_ZJKB', 100),
            ('2012', '2012', '报表-销售日报表(按商品汇总)', '模块权限：通过菜单进入销售日报表(按商品汇总)模块的权限', '销售日报表', 'BB_XSRBB_ASPHZ_', 100),
            ('2013', '2013', '报表-销售日报表(按客户汇总)', '模块权限：通过菜单进入销售日报表(按客户汇总)模块的权限', '销售日报表', 'BB_XSRBB_AKHHZ_', 100),
            ('2014', '2014', '报表-销售日报表(按仓库汇总)', '模块权限：通过菜单进入销售日报表(按仓库汇总)模块的权限', '销售日报表', 'BB_XSRBB_ACKHZ_', 100),
            ('2015', '2015', '报表-销售日报表(按业务员汇总)', '模块权限：通过菜单进入销售日报表(按业务员汇总)模块的权限', '销售日报表', 'BB_XSRBB_AYWYHZ_', 100),
            ('2016', '2016', '报表-销售月报表(按商品汇总)', '模块权限：通过菜单进入销售月报表(按商品汇总)模块的权限', '销售月报表', 'BB_XSYBB_ASPHZ_', 100),
            ('2017', '2017', '报表-销售月报表(按客户汇总)', '模块权限：通过菜单进入销售月报表(按客户汇总)模块的权限', '销售月报表', 'BB_XSYBB_AKHHZ_', 100),
            ('2018', '2018', '报表-销售月报表(按仓库汇总)', '模块权限：通过菜单进入销售月报表(按仓库汇总)模块的权限', '销售月报表', 'BB_XSYBB_ACKHZ_', 100),
            ('2019', '2019', '报表-销售月报表(按业务员汇总)', '模块权限：通过菜单进入销售月报表(按业务员汇总)模块的权限', '销售月报表', 'BB_XSYBB_AYWYHZ_', 100),
            ('2020', '2020', '报表-安全库存明细表', '模块权限：通过菜单进入安全库存明细表模块的权限', '库存报表', 'BB_AQKCMXB', 100),
            ('2021', '2021', '报表-应收账款账龄分析表', '模块权限：通过菜单进入应收账款账龄分析表模块的权限', '资金报表', 'BB_YSZKZLFXB', 100),
            ('2022', '2022', '报表-应付账款账龄分析表', '模块权限：通过菜单进入应付账款账龄分析表模块的权限', '资金报表', 'BB_YFZKZLFXB', 100),
            ('2023', '2023', '报表-库存超上限明细表', '模块权限：通过菜单进入库存超上限明细表模块的权限', '库存报表', 'BB_KCCSXMXB', 100),
            ('2024', '2024', '现金收支查询', '模块权限：通过菜单进入现金收支查询模块的权限', '现金管理', 'XJSZCX', 100),
            ('2025', '2025', '预收款管理', '模块权限：通过菜单进入预收款管理模块的权限', '预收款管理', 'YSKGL', 100),
            ('2026', '2026', '预付款管理', '模块权限：通过菜单进入预付款管理模块的权限', '预付款管理', 'YFKGL', 100),
            ('2027', '2027', '采购订单', '模块权限：通过菜单进入采购订单模块的权限', '采购订单', 'CGDD', 100),
            ('2027-01', '2027-01', '采购订单-审核/取消审核', '按钮权限：采购订单模块[审核]按钮和[取消审核]按钮的权限', '采购订单', 'CGDD _ SH_QXSH', 204),
            ('2027-02', '2027-02', '采购订单-生成采购入库单', '按钮权限：采购订单模块[生成采购入库单]按钮权限', '采购订单', 'CGDD _ SCCGRKD', 205),
            ('2027-03', '2027-03', '采购订单-新建采购订单', '按钮权限：采购订单模块[新建采购订单]按钮权限', '采购订单', 'CGDD _ XJCGDD', 201),
            ('2027-04', '2027-04', '采购订单-编辑采购订单', '按钮权限：采购订单模块[编辑采购订单]按钮权限', '采购订单', 'CGDD _ BJCGDD', 202),
            ('2027-05', '2027-05', '采购订单-删除采购订单', '按钮权限：采购订单模块[删除采购订单]按钮权限', '采购订单', 'CGDD _ SCCGDD', 203),
            ('2027-06', '2027-06', '采购订单-关闭订单/取消关闭订单', '按钮权限：采购订单模块[关闭采购订单]和[取消采购订单关闭状态]按钮权限', '采购订单', 'CGDD _ GBDD_QXGBDD', 206),
            ('2027-07', '2027-07', '采购订单-单据生成PDF', '按钮权限：采购订单模块[单据生成PDF]按钮权限', '采购订单', 'CGDD _ DJSCPDF', 207),
            ('2027-08', '2027-08', '采购订单-打印', '按钮权限：采购订单模块[打印预览]和[直接打印]按钮权限', '采购订单', 'CGDD_DY', 208),
            ('2028', '2028', '销售订单', '模块权限：通过菜单进入销售订单模块的权限', '销售订单', 'XSDD', 100),
            ('2028-01', '2028-01', '销售订单-审核/取消审核', '按钮权限：销售订单模块[审核]按钮和[取消审核]按钮的权限', '销售订单', 'XSDD_SH_QXSH', 204),
            ('2028-02', '2028-02', '销售订单-生成销售出库单', '按钮权限：销售订单模块[生成销售出库单]按钮的权限', '销售订单', 'XSDD_SCXSCKD', 206),
            ('2028-03', '2028-03', '销售订单-新建销售订单', '按钮权限：销售订单模块[新建销售订单]按钮的权限', '销售订单', 'XSDD_XJXSDD', 201),
            ('2028-04', '2028-04', '销售订单-编辑销售订单', '按钮权限：销售订单模块[编辑销售订单]按钮的权限', '销售订单', 'XSDD_BJXSDD', 202),
            ('2028-05', '2028-05', '销售订单-删除销售订单', '按钮权限：销售订单模块[删除销售订单]按钮的权限', '销售订单', 'XSDD_SCXSDD', 203),
            ('2028-06', '2028-06', '销售订单-单据生成PDF', '按钮权限：销售订单模块[单据生成PDF]按钮的权限', '销售订单', 'XSDD_DJSCPDF', 207),
            ('2028-07', '2028-07', '销售订单-打印', '按钮权限：销售订单模块[打印预览]和[直接打印]按钮的权限', '销售订单', 'XSDD_DY', 208),
            ('2028-08', '2028-08', '销售订单-生成采购订单', '按钮权限：销售订单模块[生成采购订单]按钮的权限', '销售订单', 'XSDD_SCCGDD', 205),
            ('2028-09', '2028-09', '销售订单-关闭订单/取消关闭订单', '按钮权限：销售订单模块[关闭销售订单]和[取消销售订单关闭状态]按钮的权限', '销售订单', 'XSDD_GBDD', 209),
            ('2029', '2029', '商品品牌', '模块权限：通过菜单进入商品品牌模块的权限', '商品', 'SPPP', 600),
            ('2030-01', '2030-01', '商品构成-新增子商品', '按钮权限：商品模块[新增子商品]按钮权限', '商品', 'SPGC_XZZSP', 209),
            ('2030-02', '2030-02', '商品构成-编辑子商品', '按钮权限：商品模块[编辑子商品]按钮权限', '商品', 'SPGC_BJZSP', 210),
            ('2030-03', '2030-03', '商品构成-删除子商品', '按钮权限：商品模块[删除子商品]按钮权限', '商品', 'SPGC_SCZSP', 211),
            ('2031', '2031', '价格体系', '模块权限：通过菜单进入价格体系模块的权限', '商品', 'JGTX', 700),
            ('2031-01', '2031-01', '商品-设置商品价格体系', '按钮权限：商品模块[设置商品价格体系]按钮权限', '商品', 'JGTX', 701),
            ('2032', '2032', '销售合同', '模块权限：通过菜单进入销售合同模块的权限', '销售合同', 'XSHT', 100),
            ('2032-01', '2032-01', '销售合同-新建销售合同', '按钮权限：销售合同模块[新建销售合同]按钮的权限', '销售合同', 'XSHT_XJXSHT', 201),
            ('2032-02', '2032-02', '销售合同-编辑销售合同', '按钮权限：销售合同模块[编辑销售合同]按钮的权限', '销售合同', 'XSHT_BJXSHT', 202),
            ('2032-03', '2032-03', '销售合同-删除销售合同', '按钮权限：销售合同模块[删除销售合同]按钮的权限', '销售合同', 'XSHT_SCXSHT', 203),
            ('2032-04', '2032-04', '销售合同-审核/取消审核', '按钮权限：销售合同模块[审核]按钮和[取消审核]按钮的权限', '销售合同', 'XSHT_SH_QXSH', 204),
            ('2032-05', '2032-05', '销售合同-生成销售订单', '按钮权限：销售合同模块[生成销售订单]按钮的权限', '销售合同', 'XSHT_SCXSDD', 205),
            ('2032-06', '2032-06', '销售合同-单据生成PDF', '按钮权限：销售合同模块[单据生成PDF]按钮的权限', '销售合同', 'XSHT_DJSCPDF', 206),
            ('2032-07', '2032-07', '销售合同-打印', '按钮权限：销售合同模块[打印预览]和[直接打印]按钮的权限', '销售合同', 'XSHT_DY', 207),
            ('2033', '2033', '存货拆分', '模块权限：通过菜单进入存货拆分模块的权限', '存货拆分', 'CHCF', 100),
            ('2033-01', '2033-01', '存货拆分-新建拆分单', '按钮权限：存货拆分模块[新建拆分单]按钮的权限', '存货拆分', 'CHCFXJCFD', 201),
            ('2033-02', '2033-02', '存货拆分-编辑拆分单', '按钮权限：存货拆分模块[编辑拆分单]按钮的权限', '存货拆分', 'CHCFBJCFD', 202),
            ('2033-03', '2033-03', '存货拆分-删除拆分单', '按钮权限：存货拆分模块[删除拆分单]按钮的权限', '存货拆分', 'CHCFSCCFD', 203),
            ('2033-04', '2033-04', '存货拆分-提交拆分单', '按钮权限：存货拆分模块[提交拆分单]按钮的权限', '存货拆分', 'CHCFTJCFD', 204),
            ('2033-05', '2033-05', '存货拆分-单据生成PDF', '按钮权限：存货拆分模块[单据生成PDF]按钮的权限', '存货拆分', 'CHCFDJSCPDF', 205),
            ('2033-06', '2033-06', '存货拆分-打印', '按钮权限：存货拆分模块[打印预览]和[直接打印]按钮的权限', '存货拆分', 'CHCFDY', 206),
            ('2034', '2034', '工厂', '模块权限：通过菜单进入工厂模块的权限', '工厂', 'GC', 100),
            ('2034-01', '2034-01', '工厂在业务单据中的使用权限', '数据域权限：工厂在业务单据中的使用权限', '工厂', 'GCCYWDJZDSYQX', 301),
            ('2034-02', '2034-02', '工厂分类', '数据域权限：工厂模块中工厂分类的数据权限', '工厂', 'GCFL', 300),
            ('2034-03', '2034-03', '新增工厂分类', '按钮权限：工厂模块[新增工厂分类]按钮权限', '工厂', 'XZGYSFL', 201),
            ('2034-04', '2034-04', '编辑工厂分类', '按钮权限：工厂模块[编辑工厂分类]按钮权限', '工厂', 'BJGYSFL', 202),
            ('2034-05', '2034-05', '删除工厂分类', '按钮权限：工厂模块[删除工厂分类]按钮权限', '工厂', 'SCGYSFL', 203),
            ('2034-06', '2034-06', '新增工厂', '按钮权限：工厂模块[新增工厂]按钮权限', '工厂', 'XZGYS', 204),
            ('2034-07', '2034-07', '编辑工厂', '按钮权限：工厂模块[编辑工厂]按钮权限', '工厂', 'BJGYS', 205),
            ('2034-08', '2034-08', '删除工厂', '按钮权限：工厂模块[删除工厂]按钮权限', '工厂', 'SCGYS', 206),
            ('2035', '2035', '成品委托生产订单', '模块权限：通过菜单进入成品委托生产订单模块的权限', '成品委托生产订单', 'CPWTSCDD', 100),
            ('2035-01', '2035-01', '成品委托生产订单-新建成品委托生产订单', '按钮权限：成品委托生产订单模块[新建成品委托生产订单]按钮的权限', '成品委托生产订单', 'CPWTSCDDXJCPWTSCDD', 201),
            ('2035-02', '2035-02', '成品委托生产订单-编辑成品委托生产订单', '按钮权限：成品委托生产订单模块[编辑成品委托生产订单]按钮的权限', '成品委托生产订单', 'CPWTSCDDBJCPWTSCDD', 202),
            ('2035-03', '2035-03', '成品委托生产订单-删除成品委托生产订单', '按钮权限：成品委托生产订单模块[删除成品委托生产订单]按钮的权限', '成品委托生产订单', 'CPWTSCDDSCCPWTSCDD', 203),
            ('2035-04', '2035-04', '成品委托生产订单-审核/取消审核', '按钮权限：成品委托生产订单模块[审核]和[取消审核]按钮的权限', '成品委托生产订单', 'CPWTSCDDSHQXSH', 204),
            ('2035-05', '2035-05', '成品委托生产订单-生成成品委托生产入库单', '按钮权限：成品委托生产订单模块[生成成品委托生产入库单]按钮的权限', '成品委托生产订单', 'CPWTSCDDSCCPWTSCRKD', 205),
            ('2035-06', '2035-06', '成品委托生产订单-关闭/取消关闭成品委托生产订单', '按钮权限：成品委托生产订单模块[关闭成品委托生产订单]和[取消关闭成品委托生产订单]按钮的权限', '成品委托生产订单', 'CPWTSCDGBJCPWTSCDD', 206),
            ('2035-07', '2035-07', '成品委托生产订单-单据生成PDF', '按钮权限：成品委托生产订单模块[单据生成PDF]按钮的权限', '成品委托生产订单', 'CPWTSCDDDJSCPDF', 207),
            ('2035-08', '2035-08', '成品委托生产订单-打印', '按钮权限：成品委托生产订单模块[打印预览]和[直接打印]按钮的权限', '成品委托生产订单', 'CPWTSCDDDY', 208),
            ('2036', '2036', '成品委托生产入库', '模块权限：通过菜单进入成品委托生产入库模块的权限', '成品委托生产入库', 'CPWTSCRK', 100),
            ('2036-01', '2036-01', '成品委托生产入库-新建成品委托生产入库单', '按钮权限：成品委托生产入库模块[新建成品委托生产入库单]按钮的权限', '成品委托生产入库', 'CPWTSCRKXJCPWTSCRKD', 201),
            ('2036-02', '2036-02', '成品委托生产入库-编辑成品委托生产入库单', '按钮权限：成品委托生产入库模块[编辑成品委托生产入库单]按钮的权限', '成品委托生产入库', 'CPWTSCRKBJCPWTSCRKD', 202),
            ('2036-03', '2036-03', '成品委托生产入库-删除成品委托生产入库单', '按钮权限：成品委托生产入库模块[删除成品委托生产入库单]按钮的权限', '成品委托生产入库', 'CPWTSCRKSCCPWTSCRKD', 203),
            ('2036-04', '2036-04', '成品委托生产入库-提交入库', '按钮权限：成品委托生产入库模块[提交入库]按钮的权限', '成品委托生产入库', 'CPWTSCRKTJRK', 204),
            ('2036-05', '2036-05', '成品委托生产入库-单据生成PDF', '按钮权限：成品委托生产入库模块[单据生成PDF]按钮的权限', '成品委托生产入库', 'CPWTSCRKDJSCPDF', 205),
            ('2036-06', '2036-06', '成品委托生产入库-打印', '按钮权限：成品委托生产入库模块[打印预览]和[直接打印]按钮的权限', '成品委托生产入库', 'CPWTSCRKDY', 206),
            ('2037', '2037', '采购入库明细表', '模块权限：通过菜单进入采购入库明细表模块的权限', '采购报表', 'CGRKMXB', 100),
            ('2101', '2101', '会计科目', '模块权限：通过菜单进入会计科目模块的权限', '会计科目', 'KJKM', 100),
            ('2102', '2102', '银行账户', '模块权限：通过菜单进入银行账户模块的权限', '银行账户', 'YHZH', 100),
            ('2103', '2103', '会计期间', '模块权限：通过菜单进入会计期间模块的权限', '会计期间', 'KJQJ', 100),
            ('3101', '3101', '物料单位', '模块权限：通过菜单进入物料单位模块的权限', '物料', 'WLDW', 500),
            ('3102', '3102', '原材料', '模块权限：通过菜单进入原材料模块的权限', '原材料', '', 100),
            ('3102-01', '3102-01', '原材料在业务单据中的使用权限', '数据域权限：原材料在业务单据中的使用权限', '原材料', '', 300),
            ('3102-02', '3102-02', '原材料分类数据权限', '数据域权限：原材料模块中原材料分类的数据权限', '原材料', '', 301),
            ('3102-03', '3102-03', '新增原材料分类', '按钮权限：原材料模块[新增原材料分类]按钮权限', '原材料', '', 201),
            ('3102-04', '3102-04', '编辑原材料分类', '按钮权限：原材料模块[编辑原材料分类]按钮权限', '原材料', '', 202),
            ('3102-05', '3102-05', '删除原材料分类', '按钮权限：原材料模块[删除原材料分类]按钮权限', '原材料', '', 203),
            ('3102-06', '3102-06', '新增原材料', '按钮权限：原材料模块[新增原材料]按钮权限', '原材料', '', 204),
            ('3102-07', '3102-07', '编辑原材料', '按钮权限：原材料模块[编辑原材料]按钮权限', '原材料', '', 205),
            ('3102-08', '3102-08', '删除原材料', '按钮权限：原材料模块[删除原材料]按钮权限', '原材料', '', 206);
            ";
    $db->execute($sql);
  }

  private function update_20200419_01()
  {
    // 本次更新：t_form新增字段module_name
    $db = $this->db;

    $tableName = "t_form";
    $columnName = "module_name";
    if (!$this->columnExists($db, $tableName, $columnName)) {
      $sql = "alter table {$tableName} add {$columnName} varchar(255) DEFAULT NULL;";
      $db->execute($sql);
    }
  }

  private function update_20200418_01()
  {
    // 本次更新：t_form_cols新增字段show_order_in_view
    $db = $this->db;

    $tableName = "t_form_cols";
    $columnName = "show_order_in_view";
    if (!$this->columnExists($db, $tableName, $columnName)) {
      $sql = "alter table {$tableName} add {$columnName} int(11) NOT NULL DEFAULT -1;";
      $db->execute($sql);
    }
  }

  private function update_20200416_03()
  {
    // 本次更新：t_permission新增字段parent_fid
    $db = $this->db;

    $tableName = "t_permission";
    $columnName = "parent_fid";
    if (!$this->columnExists($db, $tableName, $columnName)) {
      $sql = "alter table {$tableName} add {$columnName} varchar(255) DEFAULT NULL;";
      $db->execute($sql);
    }
  }

  private function update_20200416_02()
  {
    // 本次更新：t_permission_plus新增字段parent_fid
    $db = $this->db;

    $tableName = "t_permission_plus";
    $columnName = "parent_fid";
    if (!$this->columnExists($db, $tableName, $columnName)) {
      $sql = "alter table {$tableName} add {$columnName} varchar(255) DEFAULT NULL;";
      $db->execute($sql);
    }
  }

  private function update_20200416_01()
  {
    // 本次更新：t_form_cols新增字段width_in_view
    $db = $this->db;

    // t_form_cols
    $tableName = "t_form_cols";
    $columnName = "width_in_view";
    if (!$this->columnExists($db, $tableName, $columnName)) {
      $sql = "alter table {$tableName} add {$columnName} int(11) NOT NULL DEFAULT 120;";
      $db->execute($sql);
    }
  }

  private function update_20200415_01()
  {
    //本次更新：t_form_cols和t_form_detail_cols新增字段data_index
    $db = $this->db;

    // t_form_cols
    $tableName = "t_form_cols";
    $columnName = "data_index";
    if (!$this->columnExists($db, $tableName, $columnName)) {
      $sql = "alter table {$tableName} add {$columnName} varchar(255) DEFAULT NULL;";
      $db->execute($sql);
    }

    // t_form_detail_cols
    $tableName = "t_form_detail_cols";
    $columnName = "data_index";
    if (!$this->columnExists($db, $tableName, $columnName)) {
      $sql = "alter table {$tableName} add {$columnName} varchar(255) DEFAULT NULL;";
      $db->execute($sql);
    }
  }

  private function update_20200413_01()
  {
    // 本次更新：t_form新增字段fid
    $db = $this->db;

    $tableName = "t_form";
    $columnName = "fid";
    if (!$this->columnExists($db, $tableName, $columnName)) {
      $sql = "alter table {$tableName} add {$columnName} varchar(255) DEFAULT NULL;";
      $db->execute($sql);
    }
  }

  private function update_20200412_01()
  {
    // 本次更新：新增模块-原材料
    $db = $this->db;

    // fid
    $sql = "TRUNCATE TABLE `t_fid`;
            INSERT INTO `t_fid` (`fid`, `name`, `py`, `memo`) VALUES
            ('-7999', '自定义表单', 'ZDYBD', ''),
            ('-7994', '系统数据字典', 'XTSJZD', ''),
            ('-7995', '主菜单维护', 'ZCDWH', ''),
            ('-7996', '码表设置', 'MBSZ', ''),
            ('-7997', '表单视图开发助手', 'BDSTKFZS', ''),
            ('-9999', '重新登录', '', ''),
            ('-9997', '首页', 'SY', ''),
            ('-9996', '修改我的密码', 'XGWDMM', ''),
            ('-9995', '帮助', 'BZ', ''),
            ('-9994', '关于', 'GY', ''),
            ('-9993', '购买商业服务', '', ''),
            ('-8999', '用户管理', 'YHGL', ''),
            ('-8999-01', '组织机构在业务单据中的使用权限', '', ''),
            ('-8999-02', '业务员在业务单据中的使用权限', '', ''),
            ('-8997', '业务日志', 'YWRZ', ''),
            ('-8996', '权限管理', 'QXGL', ''),
            ('1001', '商品', 'SP', ''),
            ('1001-01', '商品在业务单据中的使用权限', '', ''),
            ('1001-02', '商品分类', 'SPFL', ''),
            ('1002', '商品计量单位', 'SPJLDW', ''),
            ('1003', '仓库', 'CK', ''),
            ('1003-01', '仓库在业务单据中的使用权限', '', ''),
            ('1004', '供应商档案', 'GYSDA', ''),
            ('1004-01', '供应商档案在业务单据中的使用权限', '', ''),
            ('1004-02', '供应商分类', '', ''),
            ('1007', '客户资料', 'KHZL', ''),
            ('1007-01', '客户资料在业务单据中的使用权限', '', ''),
            ('1007-02', '客户分类', '', ''),
            ('2000', '库存建账', 'KCJZ', ''),
            ('2001', '采购入库', 'CGRK', ''),
            ('2001-01', '采购入库-新建采购入库单', '', ''),
            ('2001-02', '采购入库-编辑采购入库单', '', ''),
            ('2001-03', '采购入库-删除采购入库单', '', ''),
            ('2001-04', '采购入库-提交入库', '', ''),
            ('2001-05', '采购入库-单据生成PDF', '', ''),
            ('2001-06', '采购入库-采购单价和金额可见', '', ''),
            ('2001-07', '采购入库-打印', '', ''),
            ('2002', '销售出库', 'XSCK', ''),
            ('2002-01', '销售出库-销售出库单允许编辑销售单价', '', ''),
            ('2002-02', '销售出库-新建销售出库单', '', ''),
            ('2002-03', '销售出库-编辑销售出库单', '', ''),
            ('2002-04', '销售出库-删除销售出库单', '', ''),
            ('2002-05', '销售出库-提交出库', '', ''),
            ('2002-06', '销售出库-单据生成PDF', '', ''),
            ('2002-07', '销售出库-打印', '', ''),
            ('2003', '库存账查询', 'KCZCX', ''),
            ('2004', '应收账款管理', 'YSZKGL', ''),
            ('2005', '应付账款管理', 'YFZKGL', ''),
            ('2006', '销售退货入库', 'XSTHRK', ''),
            ('2006-01', '销售退货入库-新建销售退货入库单', '', ''),
            ('2006-02', '销售退货入库-编辑销售退货入库单', '', ''),
            ('2006-03', '销售退货入库-删除销售退货入库单', '', ''),
            ('2006-04', '销售退货入库-提交入库', '', ''),
            ('2006-05', '销售退货入库-单据生成PDF', '', ''),
            ('2006-06', '销售退货入库-打印', '', ''),
            ('2007', '采购退货出库', 'CGTHCK', ''),
            ('2007-01', '采购退货出库-新建采购退货出库单', '', ''),
            ('2007-02', '采购退货出库-编辑采购退货出库单', '', ''),
            ('2007-03', '采购退货出库-删除采购退货出库单', '', ''),
            ('2007-04', '采购退货出库-提交采购退货出库单', '', ''),
            ('2007-05', '采购退货出库-单据生成PDF', '', ''),
            ('2007-06', '采购退货出库-打印', '', ''),
            ('2008', '业务设置', 'YWSZ', ''),
            ('2009', '库间调拨', 'KJDB', ''),
            ('2009-01', '库间调拨-新建调拨单', '', ''),
            ('2009-02', '库间调拨-编辑调拨单', '', ''),
            ('2009-03', '库间调拨-删除调拨单', '', ''),
            ('2009-04', '库间调拨-提交调拨单', '', ''),
            ('2009-05', '库间调拨-单据生成PDF', '', ''),
            ('2009-06', '库间调拨-打印', '', ''),
            ('2010', '库存盘点', 'KCPD', ''),
            ('2010-01', '库存盘点-新建盘点单', '', ''),
            ('2010-02', '库存盘点-编辑盘点单', '', ''),
            ('2010-03', '库存盘点-删除盘点单', '', ''),
            ('2010-04', '库存盘点-提交盘点单', '', ''),
            ('2010-05', '库存盘点-单据生成PDF', '', ''),
            ('2010-06', '库存盘点-打印', '', ''),
            ('2011-01', '首页-销售看板', '', ''),
            ('2011-02', '首页-库存看板', '', ''),
            ('2011-03', '首页-采购看板', '', ''),
            ('2011-04', '首页-资金看板', '', ''),
            ('2012', '报表-销售日报表(按商品汇总)', 'BBXSRBBASPHZ', ''),
            ('2013', '报表-销售日报表(按客户汇总)', 'BBXSRBBAKHHZ', ''),
            ('2014', '报表-销售日报表(按仓库汇总)', 'BBXSRBBACKHZ', ''),
            ('2015', '报表-销售日报表(按业务员汇总)', 'BBXSRBBAYWYHZ', ''),
            ('2016', '报表-销售月报表(按商品汇总)', 'BBXSYBBASPHZ', ''),
            ('2017', '报表-销售月报表(按客户汇总)', 'BBXSYBBAKHHZ', ''),
            ('2018', '报表-销售月报表(按仓库汇总)', 'BBXSYBBACKHZ', ''),
            ('2019', '报表-销售月报表(按业务员汇总)', 'BBXSYBBAYWYHZ', ''),
            ('2020', '报表-安全库存明细表', 'BBAQKCMXB', ''),
            ('2021', '报表-应收账款账龄分析表', 'BBYSZKZLFXB', ''),
            ('2022', '报表-应付账款账龄分析表', 'BBYFZKZLFXB', ''),
            ('2023', '报表-库存超上限明细表', 'BBKCCSXMXB', ''),
            ('2024', '现金收支查询', 'XJSZCX', ''),
            ('2025', '预收款管理', 'YSKGL', ''),
            ('2026', '预付款管理', 'YFKGL', ''),
            ('2027', '采购订单', 'CGDD', ''),
            ('2027-01', '采购订单-审核/取消审核', '', ''),
            ('2027-02', '采购订单-生成采购入库单', '', ''),
            ('2027-03', '采购订单-新建采购订单', '', ''),
            ('2027-04', '采购订单-编辑采购订单', '', ''),
            ('2027-05', '采购订单-删除采购订单', '', ''),
            ('2027-06', '采购订单-关闭订单/取消关闭订单', '', ''),
            ('2027-07', '采购订单-单据生成PDF', '', ''),
            ('2027-08', '采购订单-打印', '', ''),
            ('2028', '销售订单', 'XSDD', ''),
            ('2028-01', '销售订单-审核/取消审核', '', ''),
            ('2028-02', '销售订单-生成销售出库单', '', ''),
            ('2028-03', '销售订单-新建销售订单', '', ''),
            ('2028-04', '销售订单-编辑销售订单', '', ''),
            ('2028-05', '销售订单-删除销售订单', '', ''),
            ('2028-06', '销售订单-单据生成PDF', '', ''),
            ('2028-07', '销售订单-打印', '', ''),
            ('2028-08', '销售订单-生成采购订单', '', ''),
            ('2028-09', '销售订单-关闭订单/取消关闭订单', '', ''),
            ('2029', '商品品牌', 'SPPP', ''),
            ('2030-01', '商品构成-新增子商品', '', ''),
            ('2030-02', '商品构成-编辑子商品', '', ''),
            ('2030-03', '商品构成-删除子商品', '', ''),
            ('2031', '价格体系', 'JGTX', ''),
            ('2031-01', '商品-设置商品价格体系', '', ''),
            ('2032', '销售合同', 'XSHT', ''),
            ('2032-01', '销售合同-新建销售合同', '', ''),
            ('2032-02', '销售合同-编辑销售合同', '', ''),
            ('2032-03', '销售合同-删除销售合同', '', ''),
            ('2032-04', '销售合同-审核/取消审核', '', ''),
            ('2032-05', '销售合同-生成销售订单', '', ''),
            ('2032-06', '销售合同-单据生成PDF', '', ''),
            ('2032-07', '销售合同-打印', '', ''),
            ('2033', '存货拆分', 'CHCF', ''),
            ('2033-01', '存货拆分-新建拆分单', '', ''),
            ('2033-02', '存货拆分-编辑拆分单', '', ''),
            ('2033-03', '存货拆分-删除拆分单', '', ''),
            ('2033-04', '存货拆分-提交拆分单', '', ''),
            ('2033-05', '存货拆分-单据生成PDF', '', ''),
            ('2033-06', '存货拆分-打印', '', ''),
            ('2034', '工厂', 'GC', ''),
            ('2034-01', '工厂在业务单据中的使用权限', '', ''),
            ('2034-02', '工厂分类', '', ''),
            ('2034-03', '工厂-新增工厂分类', '', ''),
            ('2034-04', '工厂-编辑工厂分类', '', ''),
            ('2034-05', '工厂-删除工厂分类', '', ''),
            ('2034-06', '工厂-新增工厂', '', ''),
            ('2034-07', '工厂-编辑工厂', '', ''),
            ('2034-08', '工厂-删除工厂', '', ''),
            ('2035', '成品委托生产订单', 'CPWTSCDD', ''),
            ('2035-01', '成品委托生产订单-新建成品委托生产订单', '', ''),
            ('2035-02', '成品委托生产订单-编辑成品委托生产订单', '', ''),
            ('2035-03', '成品委托生产订单-删除成品委托生产订单', '', ''),
            ('2035-04', '成品委托生产订单-提交成品委托生产订单', '', ''),
            ('2035-05', '成品委托生产订单-审核/取消审核成品委托生产入库单', '', ''),
            ('2035-06', '成品委托生产订单-关闭/取消关闭成品委托生产订单', '', ''),
            ('2035-07', '成品委托生产订单-单据生成PDF', '', ''),
            ('2035-08', '成品委托生产订单-打印', '', ''),
            ('2036', '成品委托生产入库', 'CPWTSCRK', ''),
            ('2036-01', '成品委托生产入库-新建成品委托生产入库单', '', ''),
            ('2036-02', '成品委托生产入库-编辑成品委托生产入库单', '', ''),
            ('2036-03', '成品委托生产入库-删除成品委托生产入库单', '', ''),
            ('2036-04', '成品委托生产入库-提交入库', '', ''),
            ('2036-05', '成品委托生产入库-单据生成PDF', '', ''),
            ('2036-06', '成品委托生产入库-打印', '', ''),
            ('2037', '报表-采购入库明细表', '', ''),
            ('2101', '会计科目', 'KJKM', ''),
            ('2102', '银行账户', 'YHZH', ''),
            ('2103', '会计期间', 'KJQJ', ''),
            ('3101', '物料单位', 'WLDW', ''),
            ('3102', '原材料', '', ''),
            ('3102-01', '原材料-原材料在业务单据中的使用权限', '', ''),
            ('3102-02', '原材料-原材料分类数据权限', '', ''),
            ('3102-03', '原材料-新增原材料分类', '', ''),
            ('3102-04', '原材料-编辑原材料分类', '', ''),
            ('3102-05', '原材料-删除原材料分类', '', ''),
            ('3102-06', '原材料-新增原材料', '', ''),
            ('3102-07', '原材料-编辑原材料', '', ''),
            ('3102-08', '原材料-删除原材料', '', '');
            ";
    $db->execute($sql);

    // 主菜单
    $sql = "TRUNCATE TABLE `t_menu_item`;
            INSERT INTO `t_menu_item` (`id`, `caption`, `fid`, `parent_id`, `show_order`, `py`, `memo`) VALUES
            ('01', '文件', NULL, NULL, 1, '', ''),
            ('0101', '首页', '-9997', '01', 1, 'SY', ''),
            ('0102', '重新登录', '-9999', '01', 2, '', ''),
            ('0103', '修改我的密码', '-9996', '01', 3, 'XGWDMM', ''),
            ('02', '采购', NULL, NULL, 2, '', ''),
            ('0200', '采购订单', '2027', '02', 0, 'CGDD', ''),
            ('0201', '采购入库', '2001', '02', 1, 'CGRK', ''),
            ('0202', '采购退货出库', '2007', '02', 2, 'CGTHCK', ''),
            ('03', '库存', NULL, NULL, 3, '', ''),
            ('0301', '库存账查询', '2003', '03', 1, 'KCZCX', ''),
            ('0302', '库存建账', '2000', '03', 2, 'KCJZ', ''),
            ('0303', '库间调拨', '2009', '03', 3, 'KJDB', ''),
            ('0304', '库存盘点', '2010', '03', 4, 'KCPD', ''),
            ('12', '加工', NULL, NULL, 4, '', ''),
            ('1201', '存货拆分', '2033', '12', 1, 'CHCF', ''),
            ('1202', '成品委托生产', NULL, '12', 2, '', ''),
            ('120201', '成品委托生产订单', '2035', '1202', 1, 'CPWTSCDD', ''),
            ('120202', '成品委托生产入库', '2036', '1202', 2, 'CPWTSCRK', ''),
            ('04', '销售', NULL, NULL, 5, '', ''),
            ('0401', '销售合同', '2032', '04', 1, 'XSHT', ''),
            ('0402', '销售订单', '2028', '04', 2, 'XSDD', ''),
            ('0403', '销售出库', '2002', '04', 3, 'XSCK', ''),
            ('0404', '销售退货入库', '2006', '04', 4, 'XSTHRK', ''),
            ('05', '客户关系', NULL, NULL, 6, '', ''),
            ('0501', '客户资料', '1007', '05', 1, 'KHZL', ''),
            ('06', '资金', NULL, NULL, 7, '', ''),
            ('0601', '应收账款管理', '2004', '06', 1, 'YSZKGL', ''),
            ('0602', '应付账款管理', '2005', '06', 2, 'YFZKGL', ''),
            ('0603', '现金收支查询', '2024', '06', 3, 'XJSZCX', ''),
            ('0604', '预收款管理', '2025', '06', 4, 'YSKGL', ''),
            ('0605', '预付款管理', '2026', '06', 5, 'YFKGL', ''),
            ('07', '报表', NULL, NULL, 8, '', ''),
            ('0700', '采购报表', NULL, '07', 0, '', ''),
            ('070001', '采购入库明细表', '2037', '0700', 1, 'CGRKMXB', ''),
            ('0701', '销售日报表', NULL, '07', 1, '', ''),
            ('070101', '销售日报表(按商品汇总)', '2012', '0701', 1, 'XSRBBASPHZ', ''),
            ('070102', '销售日报表(按客户汇总)', '2013', '0701', 2, 'XSRBBAKHHZ', ''),
            ('070103', '销售日报表(按仓库汇总)', '2014', '0701', 3, 'XSRBBACKHZ', ''),
            ('070104', '销售日报表(按业务员汇总)', '2015', '0701', 4, 'XSRBBAYWYHZ', ''),
            ('0702', '销售月报表', NULL, '07', 2, '', ''),
            ('070201', '销售月报表(按商品汇总)', '2016', '0702', 1, 'XSYBBASPHZ', ''),
            ('070202', '销售月报表(按客户汇总)', '2017', '0702', 2, 'XSYBBAKHHZ', ''),
            ('070203', '销售月报表(按仓库汇总)', '2018', '0702', 3, 'XSYBBACKHZ', ''),
            ('070204', '销售月报表(按业务员汇总)', '2019', '0702', 4, 'XSYBBAYWYHZ', ''),
            ('0703', '库存报表', NULL, '07', 3, '', ''),
            ('070301', '安全库存明细表', '2020', '0703', 1, 'AQKCMXB', ''),
            ('070302', '库存超上限明细表', '2023', '0703', 2, 'KCCSXMXB', ''),
            ('0706', '资金报表', NULL, '07', 6, '', ''),
            ('070601', '应收账款账龄分析表', '2021', '0706', 1, 'YSZKZLFXB', ''),
            ('070602', '应付账款账龄分析表', '2022', '0706', 2, 'YFZKZLFXB', ''),
            ('11', '财务总账', NULL, NULL, 9, '', ''),
            ('1101', '基础数据', NULL, '11', 1, '', ''),
            ('110101', '会计科目', '2101', '1101', 1, 'KJKM', ''),
            ('110102', '银行账户', '2102', '1101', 2, 'YHZH', ''),
            ('110103', '会计期间', '2103', '1101', 3, 'KJQJ', ''),
            ('08', '基础数据', NULL, NULL, 10, '', ''),
            ('0801', '商品', NULL, '08', 1, '', ''),
            ('080101', '商品', '1001', '0801', 1, 'SP', ''),
            ('080102', '商品计量单位', '1002', '0801', 2, 'SPJLDW', ''),
            ('080103', '商品品牌', '2029', '0801', 3, '', 'SPPP'),
            ('080104', '价格体系', '2031', '0801', 4, '', 'JGTX'),
            ('0803', '仓库', '1003', '08', 3, 'CK', ''),
            ('0804', '供应商档案', '1004', '08', 4, 'GYSDA', ''),
            ('0805', '工厂', '2034', '08', 5, 'GC', ''),
            ('0806', '物料', NULL, '08', 6, '', ''),
            ('080601', '原材料', '3102', '0806', 1, 'YCL', ''),
            ('080604', '物料单位', '3101', '0806', 4, 'WLDW', ''),
            ('09', '系统管理', NULL, NULL, 11, '', ''),
            ('0901', '用户管理', '-8999', '09', 1, 'YHGL', ''),
            ('0902', '权限管理', '-8996', '09', 2, 'QXGL', ''),
            ('0903', '业务日志', '-8997', '09', 3, 'YWRZ', ''),
            ('0904', '业务设置', '2008', '09', 4, '', 'YWSZ'),
            ('0905', '二次开发', NULL, '09', 5, '', ''),
            ('090501', '码表设置', '-7996', '0905', 1, 'MBSZ', ''),
            ('090502', '自定义表单', '-7999', '0905', 2, 'ZDYBD', ''),
            ('090503', '表单视图开发助手', '-7997', '0905', 3, 'BDSTKFZS', ''),
            ('090504', '主菜单维护', '-7995', '0905', 4, 'ZCDWH', ''),
            ('090505', '系统数据字典', '-7994', '0905', 5, 'XTSJZD', ''),
            ('10', '帮助', NULL, NULL, 12, '', ''),
            ('1001', '使用帮助', '-9995', '10', 1, 'SYBZ', ''),
            ('1003', '关于', '-9994', '10', 3, 'GY', '');
            ";
    $db->execute($sql);

    //权限
    $sql = "TRUNCATE TABLE `t_permission`;
            INSERT INTO `t_permission` (`id`, `fid`, `name`, `note`, `category`, `py`, `show_order`) VALUES
            ('-7999', '-7999', '自定义表单', '模块权限：通过菜单进入自定义表单模块的权限', '自定义表单', 'ZDYBD', 100),
            ('-7994', '-7994', '系统数据字典', '模块权限：通过菜单进入系统数据字典模块的权限', '系统数据字典', 'XTSJZD', 100),
            ('-7995', '-7995', '主菜单维护', '模块权限：通过菜单进入主菜单维护模块的权限', '主菜单维护', 'ZCDWH', 100),
            ('-7996', '-7996', '码表设置', '模块权限：通过菜单进入码表设置模块的权限', '码表设置', 'MBSZ', 100),
            ('-8996', '-8996', '权限管理', '模块权限：通过菜单进入权限管理模块的权限', '权限管理', 'QXGL', 100),
            ('-8996-01', '-8996-01', '权限管理-新增角色', '按钮权限：权限管理模块[新增角色]按钮权限', '权限管理', 'QXGL_XZJS', 201),
            ('-8996-02', '-8996-02', '权限管理-编辑角色', '按钮权限：权限管理模块[编辑角色]按钮权限', '权限管理', 'QXGL_BJJS', 202),
            ('-8996-03', '-8996-03', '权限管理-删除角色', '按钮权限：权限管理模块[删除角色]按钮权限', '权限管理', 'QXGL_SCJS', 203),
            ('-8997', '-8997', '业务日志', '模块权限：通过菜单进入业务日志模块的权限', '系统管理', 'YWRZ', 100),
            ('-8999', '-8999', '用户管理', '模块权限：通过菜单进入用户管理模块的权限', '用户管理', 'YHGL', 100),
            ('-8999-01', '-8999-01', '组织机构在业务单据中的使用权限', '数据域权限：组织机构在业务单据中的使用权限', '用户管理', 'ZZJGZYWDJZDSYQX', 300),
            ('-8999-02', '-8999-02', '业务员在业务单据中的使用权限', '数据域权限：业务员在业务单据中的使用权限', '用户管理', 'YWYZYWDJZDSYQX', 301),
            ('-8999-03', '-8999-03', '用户管理-新增组织机构', '按钮权限：用户管理模块[新增组织机构]按钮权限', '用户管理', 'YHGL_XZZZJG', 201),
            ('-8999-04', '-8999-04', '用户管理-编辑组织机构', '按钮权限：用户管理模块[编辑组织机构]按钮权限', '用户管理', 'YHGL_BJZZJG', 202),
            ('-8999-05', '-8999-05', '用户管理-删除组织机构', '按钮权限：用户管理模块[删除组织机构]按钮权限', '用户管理', 'YHGL_SCZZJG', 203),
            ('-8999-06', '-8999-06', '用户管理-新增用户', '按钮权限：用户管理模块[新增用户]按钮权限', '用户管理', 'YHGL_XZYH', 204),
            ('-8999-07', '-8999-07', '用户管理-编辑用户', '按钮权限：用户管理模块[编辑用户]按钮权限', '用户管理', 'YHGL_BJYH', 205),
            ('-8999-08', '-8999-08', '用户管理-删除用户', '按钮权限：用户管理模块[删除用户]按钮权限', '用户管理', 'YHGL_SCYH', 206),
            ('-8999-09', '-8999-09', '用户管理-修改用户密码', '按钮权限：用户管理模块[修改用户密码]按钮权限', '用户管理', 'YHGL_XGYHMM', 207),
            ('1001', '1001', '商品', '模块权限：通过菜单进入商品模块的权限', '商品', 'SP', 100),
            ('1001-01', '1001-01', '商品在业务单据中的使用权限', '数据域权限：商品在业务单据中的使用权限', '商品', 'SPZYWDJZDSYQX', 300),
            ('1001-02', '1001-02', '商品分类', '数据域权限：商品模块中商品分类的数据权限', '商品', 'SPFL', 301),
            ('1001-03', '1001-03', '新增商品分类', '按钮权限：商品模块[新增商品分类]按钮权限', '商品', 'XZSPFL', 201),
            ('1001-04', '1001-04', '编辑商品分类', '按钮权限：商品模块[编辑商品分类]按钮权限', '商品', 'BJSPFL', 202),
            ('1001-05', '1001-05', '删除商品分类', '按钮权限：商品模块[删除商品分类]按钮权限', '商品', 'SCSPFL', 203),
            ('1001-06', '1001-06', '新增商品', '按钮权限：商品模块[新增商品]按钮权限', '商品', 'XZSP', 204),
            ('1001-07', '1001-07', '编辑商品', '按钮权限：商品模块[编辑商品]按钮权限', '商品', 'BJSP', 205),
            ('1001-08', '1001-08', '删除商品', '按钮权限：商品模块[删除商品]按钮权限', '商品', 'SCSP', 206),
            ('1001-09', '1001-09', '导入商品', '按钮权限：商品模块[导入商品]按钮权限', '商品', 'DRSP', 207),
            ('1001-10', '1001-10', '设置商品安全库存', '按钮权限：商品模块[设置安全库存]按钮权限', '商品', 'SZSPAQKC', 208),
            ('1001-11', '1001-11', '导出Excel', '按钮权限：商品模块[导出Excel]按钮权限', '商品', 'DCEXCEL', 209),
            ('1002', '1002', '商品计量单位', '模块权限：通过菜单进入商品计量单位模块的权限', '商品', 'SPJLDW', 500),
            ('1003', '1003', '仓库', '模块权限：通过菜单进入仓库的权限', '仓库', 'CK', 100),
            ('1003-01', '1003-01', '仓库在业务单据中的使用权限', '数据域权限：仓库在业务单据中的使用权限', '仓库', 'CKZYWDJZDSYQX', 300),
            ('1003-02', '1003-02', '新增仓库', '按钮权限：仓库模块[新增仓库]按钮权限', '仓库', 'XZCK', 201),
            ('1003-03', '1003-03', '编辑仓库', '按钮权限：仓库模块[编辑仓库]按钮权限', '仓库', 'BJCK', 202),
            ('1003-04', '1003-04', '删除仓库', '按钮权限：仓库模块[删除仓库]按钮权限', '仓库', 'SCCK', 203),
            ('1003-05', '1003-05', '修改仓库数据域', '按钮权限：仓库模块[修改数据域]按钮权限', '仓库', 'XGCKSJY', 204),
            ('1004', '1004', '供应商档案', '模块权限：通过菜单进入供应商档案的权限', '供应商管理', 'GYSDA', 100),
            ('1004-01', '1004-01', '供应商档案在业务单据中的使用权限', '数据域权限：供应商档案在业务单据中的使用权限', '供应商管理', 'GYSDAZYWDJZDSYQX', 301),
            ('1004-02', '1004-02', '供应商分类', '数据域权限：供应商档案模块中供应商分类的数据权限', '供应商管理', 'GYSFL', 300),
            ('1004-03', '1004-03', '新增供应商分类', '按钮权限：供应商档案模块[新增供应商分类]按钮权限', '供应商管理', 'XZGYSFL', 201),
            ('1004-04', '1004-04', '编辑供应商分类', '按钮权限：供应商档案模块[编辑供应商分类]按钮权限', '供应商管理', 'BJGYSFL', 202),
            ('1004-05', '1004-05', '删除供应商分类', '按钮权限：供应商档案模块[删除供应商分类]按钮权限', '供应商管理', 'SCGYSFL', 203),
            ('1004-06', '1004-06', '新增供应商', '按钮权限：供应商档案模块[新增供应商]按钮权限', '供应商管理', 'XZGYS', 204),
            ('1004-07', '1004-07', '编辑供应商', '按钮权限：供应商档案模块[编辑供应商]按钮权限', '供应商管理', 'BJGYS', 205),
            ('1004-08', '1004-08', '删除供应商', '按钮权限：供应商档案模块[删除供应商]按钮权限', '供应商管理', 'SCGYS', 206),
            ('1007', '1007', '客户资料', '模块权限：通过菜单进入客户资料模块的权限', '客户管理', 'KHZL', 100),
            ('1007-01', '1007-01', '客户资料在业务单据中的使用权限', '数据域权限：客户资料在业务单据中的使用权限', '客户管理', 'KHZLZYWDJZDSYQX', 300),
            ('1007-02', '1007-02', '客户分类', '数据域权限：客户档案模块中客户分类的数据权限', '客户管理', 'KHFL', 301),
            ('1007-03', '1007-03', '新增客户分类', '按钮权限：客户资料模块[新增客户分类]按钮权限', '客户管理', 'XZKHFL', 201),
            ('1007-04', '1007-04', '编辑客户分类', '按钮权限：客户资料模块[编辑客户分类]按钮权限', '客户管理', 'BJKHFL', 202),
            ('1007-05', '1007-05', '删除客户分类', '按钮权限：客户资料模块[删除客户分类]按钮权限', '客户管理', 'SCKHFL', 203),
            ('1007-06', '1007-06', '新增客户', '按钮权限：客户资料模块[新增客户]按钮权限', '客户管理', 'XZKH', 204),
            ('1007-07', '1007-07', '编辑客户', '按钮权限：客户资料模块[编辑客户]按钮权限', '客户管理', 'BJKH', 205),
            ('1007-08', '1007-08', '删除客户', '按钮权限：客户资料模块[删除客户]按钮权限', '客户管理', 'SCKH', 206),
            ('1007-09', '1007-09', '导入客户', '按钮权限：客户资料模块[导入客户]按钮权限', '客户管理', 'DRKH', 207),
            ('2000', '2000', '库存建账', '模块权限：通过菜单进入库存建账模块的权限', '库存建账', 'KCJZ', 100),
            ('2001', '2001', '采购入库', '模块权限：通过菜单进入采购入库模块的权限', '采购入库', 'CGRK', 100),
            ('2001-01', '2001-01', '采购入库-新建采购入库单', '按钮权限：采购入库模块[新建采购入库单]按钮权限', '采购入库', 'CGRK_XJCGRKD', 201),
            ('2001-02', '2001-02', '采购入库-编辑采购入库单', '按钮权限：采购入库模块[编辑采购入库单]按钮权限', '采购入库', 'CGRK_BJCGRKD', 202),
            ('2001-03', '2001-03', '采购入库-删除采购入库单', '按钮权限：采购入库模块[删除采购入库单]按钮权限', '采购入库', 'CGRK_SCCGRKD', 203),
            ('2001-04', '2001-04', '采购入库-提交入库', '按钮权限：采购入库模块[提交入库]按钮权限', '采购入库', 'CGRK_TJRK', 204),
            ('2001-05', '2001-05', '采购入库-单据生成PDF', '按钮权限：采购入库模块[单据生成PDF]按钮权限', '采购入库', 'CGRK_DJSCPDF', 205),
            ('2001-06', '2001-06', '采购入库-采购单价和金额可见', '字段权限：采购入库单的采购单价和金额可以被用户查看', '采购入库', 'CGRK_CGDJHJEKJ', 206),
            ('2001-07', '2001-07', '采购入库-打印', '按钮权限：采购入库模块[打印预览]和[直接打印]按钮权限', '采购入库', 'CGRK_DY', 207),
            ('2002', '2002', '销售出库', '模块权限：通过菜单进入销售出库模块的权限', '销售出库', 'XSCK', 100),
            ('2002-01', '2002-01', '销售出库-销售出库单允许编辑销售单价', '功能权限：销售出库单允许编辑销售单价', '销售出库', 'XSCKDYXBJXSDJ', 101),
            ('2002-02', '2002-02', '销售出库-新建销售出库单', '按钮权限：销售出库模块[新建销售出库单]按钮权限', '销售出库', 'XSCK_XJXSCKD', 201),
            ('2002-03', '2002-03', '销售出库-编辑销售出库单', '按钮权限：销售出库模块[编辑销售出库单]按钮权限', '销售出库', 'XSCK_BJXSCKD', 202),
            ('2002-04', '2002-04', '销售出库-删除销售出库单', '按钮权限：销售出库模块[删除销售出库单]按钮权限', '销售出库', 'XSCK_SCXSCKD', 203),
            ('2002-05', '2002-05', '销售出库-提交出库', '按钮权限：销售出库模块[提交出库]按钮权限', '销售出库', 'XSCK_TJCK', 204),
            ('2002-06', '2002-06', '销售出库-单据生成PDF', '按钮权限：销售出库模块[单据生成PDF]按钮权限', '销售出库', 'XSCK_DJSCPDF', 205),
            ('2002-07', '2002-07', '销售出库-打印', '按钮权限：销售出库模块[打印预览]和[直接打印]按钮权限', '销售出库', 'XSCK_DY', 207),
            ('2003', '2003', '库存账查询', '模块权限：通过菜单进入库存账查询模块的权限', '库存账查询', 'KCZCX', 100),
            ('2004', '2004', '应收账款管理', '模块权限：通过菜单进入应收账款管理模块的权限', '应收账款管理', 'YSZKGL', 100),
            ('2005', '2005', '应付账款管理', '模块权限：通过菜单进入应付账款管理模块的权限', '应付账款管理', 'YFZKGL', 100),
            ('2006', '2006', '销售退货入库', '模块权限：通过菜单进入销售退货入库模块的权限', '销售退货入库', 'XSTHRK', 100),
            ('2006-01', '2006-01', '销售退货入库-新建销售退货入库单', '按钮权限：销售退货入库模块[新建销售退货入库单]按钮权限', '销售退货入库', 'XSTHRK_XJXSTHRKD', 201),
            ('2006-02', '2006-02', '销售退货入库-编辑销售退货入库单', '按钮权限：销售退货入库模块[编辑销售退货入库单]按钮权限', '销售退货入库', 'XSTHRK_BJXSTHRKD', 202),
            ('2006-03', '2006-03', '销售退货入库-删除销售退货入库单', '按钮权限：销售退货入库模块[删除销售退货入库单]按钮权限', '销售退货入库', 'XSTHRK_SCXSTHRKD', 203),
            ('2006-04', '2006-04', '销售退货入库-提交入库', '按钮权限：销售退货入库模块[提交入库]按钮权限', '销售退货入库', 'XSTHRK_TJRK', 204),
            ('2006-05', '2006-05', '销售退货入库-单据生成PDF', '按钮权限：销售退货入库模块[单据生成PDF]按钮权限', '销售退货入库', 'XSTHRK_DJSCPDF', 205),
            ('2006-06', '2006-06', '销售退货入库-打印', '按钮权限：销售退货入库模块[打印预览]和[直接打印]按钮权限', '销售退货入库', 'XSTHRK_DY', 206),
            ('2007', '2007', '采购退货出库', '模块权限：通过菜单进入采购退货出库模块的权限', '采购退货出库', 'CGTHCK', 100),
            ('2007-01', '2007-01', '采购退货出库-新建采购退货出库单', '按钮权限：采购退货出库模块[新建采购退货出库单]按钮权限', '采购退货出库', 'CGTHCK_XJCGTHCKD', 201),
            ('2007-02', '2007-02', '采购退货出库-编辑采购退货出库单', '按钮权限：采购退货出库模块[编辑采购退货出库单]按钮权限', '采购退货出库', 'CGTHCK_BJCGTHCKD', 202),
            ('2007-03', '2007-03', '采购退货出库-删除采购退货出库单', '按钮权限：采购退货出库模块[删除采购退货出库单]按钮权限', '采购退货出库', 'CGTHCK_SCCGTHCKD', 203),
            ('2007-04', '2007-04', '采购退货出库-提交采购退货出库单', '按钮权限：采购退货出库模块[提交采购退货出库单]按钮权限', '采购退货出库', 'CGTHCK_TJCGTHCKD', 204),
            ('2007-05', '2007-05', '采购退货出库-单据生成PDF', '按钮权限：采购退货出库模块[单据生成PDF]按钮权限', '采购退货出库', 'CGTHCK_DJSCPDF', 205),
            ('2007-06', '2007-06', '采购退货出库-打印', '按钮权限：采购退货出库模块[打印预览]和[直接打印]按钮权限', '采购退货出库', 'CGTHCK_DY', 206),
            ('2008', '2008', '业务设置', '模块权限：通过菜单进入业务设置模块的权限', '系统管理', 'YWSZ', 100),
            ('2009', '2009', '库间调拨', '模块权限：通过菜单进入库间调拨模块的权限', '库间调拨', 'KJDB', 100),
            ('2009-01', '2009-01', '库间调拨-新建调拨单', '按钮权限：库间调拨模块[新建调拨单]按钮权限', '库间调拨', 'KJDB_XJDBD', 201),
            ('2009-02', '2009-02', '库间调拨-编辑调拨单', '按钮权限：库间调拨模块[编辑调拨单]按钮权限', '库间调拨', 'KJDB_BJDBD', 202),
            ('2009-03', '2009-03', '库间调拨-删除调拨单', '按钮权限：库间调拨模块[删除调拨单]按钮权限', '库间调拨', 'KJDB_SCDBD', 203),
            ('2009-04', '2009-04', '库间调拨-提交调拨单', '按钮权限：库间调拨模块[提交调拨单]按钮权限', '库间调拨', 'KJDB_TJDBD', 204),
            ('2009-05', '2009-05', '库间调拨-单据生成PDF', '按钮权限：库间调拨模块[单据生成PDF]按钮权限', '库间调拨', 'KJDB_DJSCPDF', 205),
            ('2009-06', '2009-06', '库间调拨-打印', '按钮权限：库间调拨模块[打印预览]和[直接打印]按钮权限', '库间调拨', 'KJDB_DY', 206),
            ('2010', '2010', '库存盘点', '模块权限：通过菜单进入库存盘点模块的权限', '库存盘点', 'KCPD', 100),
            ('2010-01', '2010-01', '库存盘点-新建盘点单', '按钮权限：库存盘点模块[新建盘点单]按钮权限', '库存盘点', 'KCPD_XJPDD', 201),
            ('2010-02', '2010-02', '库存盘点-编辑盘点单', '按钮权限：库存盘点模块[编辑盘点单]按钮权限', '库存盘点', 'KCPD_BJPDD', 202),
            ('2010-03', '2010-03', '库存盘点-删除盘点单', '按钮权限：库存盘点模块[删除盘点单]按钮权限', '库存盘点', 'KCPD_SCPDD', 203),
            ('2010-04', '2010-04', '库存盘点-提交盘点单', '按钮权限：库存盘点模块[提交盘点单]按钮权限', '库存盘点', 'KCPD_TJPDD', 204),
            ('2010-05', '2010-05', '库存盘点-单据生成PDF', '按钮权限：库存盘点模块[单据生成PDF]按钮权限', '库存盘点', 'KCPD_DJSCPDF', 205),
            ('2010-06', '2010-06', '库存盘点-打印', '按钮权限：库存盘点模块[打印预览]和[直接打印]按钮权限', '库存盘点', 'KCPD_DY', 206),
            ('2011-01', '2011-01', '首页-销售看板', '功能权限：在首页显示销售看板', '首页看板', 'SY_XSKB', 100),
            ('2011-02', '2011-02', '首页-库存看板', '功能权限：在首页显示库存看板', '首页看板', 'SY_KCKB', 100),
            ('2011-03', '2011-03', '首页-采购看板', '功能权限：在首页显示采购看板', '首页看板', 'SY_CGKB', 100),
            ('2011-04', '2011-04', '首页-资金看板', '功能权限：在首页显示资金看板', '首页看板', 'SY_ZJKB', 100),
            ('2012', '2012', '报表-销售日报表(按商品汇总)', '模块权限：通过菜单进入销售日报表(按商品汇总)模块的权限', '销售日报表', 'BB_XSRBB_ASPHZ_', 100),
            ('2013', '2013', '报表-销售日报表(按客户汇总)', '模块权限：通过菜单进入销售日报表(按客户汇总)模块的权限', '销售日报表', 'BB_XSRBB_AKHHZ_', 100),
            ('2014', '2014', '报表-销售日报表(按仓库汇总)', '模块权限：通过菜单进入销售日报表(按仓库汇总)模块的权限', '销售日报表', 'BB_XSRBB_ACKHZ_', 100),
            ('2015', '2015', '报表-销售日报表(按业务员汇总)', '模块权限：通过菜单进入销售日报表(按业务员汇总)模块的权限', '销售日报表', 'BB_XSRBB_AYWYHZ_', 100),
            ('2016', '2016', '报表-销售月报表(按商品汇总)', '模块权限：通过菜单进入销售月报表(按商品汇总)模块的权限', '销售月报表', 'BB_XSYBB_ASPHZ_', 100),
            ('2017', '2017', '报表-销售月报表(按客户汇总)', '模块权限：通过菜单进入销售月报表(按客户汇总)模块的权限', '销售月报表', 'BB_XSYBB_AKHHZ_', 100),
            ('2018', '2018', '报表-销售月报表(按仓库汇总)', '模块权限：通过菜单进入销售月报表(按仓库汇总)模块的权限', '销售月报表', 'BB_XSYBB_ACKHZ_', 100),
            ('2019', '2019', '报表-销售月报表(按业务员汇总)', '模块权限：通过菜单进入销售月报表(按业务员汇总)模块的权限', '销售月报表', 'BB_XSYBB_AYWYHZ_', 100),
            ('2020', '2020', '报表-安全库存明细表', '模块权限：通过菜单进入安全库存明细表模块的权限', '库存报表', 'BB_AQKCMXB', 100),
            ('2021', '2021', '报表-应收账款账龄分析表', '模块权限：通过菜单进入应收账款账龄分析表模块的权限', '资金报表', 'BB_YSZKZLFXB', 100),
            ('2022', '2022', '报表-应付账款账龄分析表', '模块权限：通过菜单进入应付账款账龄分析表模块的权限', '资金报表', 'BB_YFZKZLFXB', 100),
            ('2023', '2023', '报表-库存超上限明细表', '模块权限：通过菜单进入库存超上限明细表模块的权限', '库存报表', 'BB_KCCSXMXB', 100),
            ('2024', '2024', '现金收支查询', '模块权限：通过菜单进入现金收支查询模块的权限', '现金管理', 'XJSZCX', 100),
            ('2025', '2025', '预收款管理', '模块权限：通过菜单进入预收款管理模块的权限', '预收款管理', 'YSKGL', 100),
            ('2026', '2026', '预付款管理', '模块权限：通过菜单进入预付款管理模块的权限', '预付款管理', 'YFKGL', 100),
            ('2027', '2027', '采购订单', '模块权限：通过菜单进入采购订单模块的权限', '采购订单', 'CGDD', 100),
            ('2027-01', '2027-01', '采购订单-审核/取消审核', '按钮权限：采购订单模块[审核]按钮和[取消审核]按钮的权限', '采购订单', 'CGDD _ SH_QXSH', 204),
            ('2027-02', '2027-02', '采购订单-生成采购入库单', '按钮权限：采购订单模块[生成采购入库单]按钮权限', '采购订单', 'CGDD _ SCCGRKD', 205),
            ('2027-03', '2027-03', '采购订单-新建采购订单', '按钮权限：采购订单模块[新建采购订单]按钮权限', '采购订单', 'CGDD _ XJCGDD', 201),
            ('2027-04', '2027-04', '采购订单-编辑采购订单', '按钮权限：采购订单模块[编辑采购订单]按钮权限', '采购订单', 'CGDD _ BJCGDD', 202),
            ('2027-05', '2027-05', '采购订单-删除采购订单', '按钮权限：采购订单模块[删除采购订单]按钮权限', '采购订单', 'CGDD _ SCCGDD', 203),
            ('2027-06', '2027-06', '采购订单-关闭订单/取消关闭订单', '按钮权限：采购订单模块[关闭采购订单]和[取消采购订单关闭状态]按钮权限', '采购订单', 'CGDD _ GBDD_QXGBDD', 206),
            ('2027-07', '2027-07', '采购订单-单据生成PDF', '按钮权限：采购订单模块[单据生成PDF]按钮权限', '采购订单', 'CGDD _ DJSCPDF', 207),
            ('2027-08', '2027-08', '采购订单-打印', '按钮权限：采购订单模块[打印预览]和[直接打印]按钮权限', '采购订单', 'CGDD_DY', 208),
            ('2028', '2028', '销售订单', '模块权限：通过菜单进入销售订单模块的权限', '销售订单', 'XSDD', 100),
            ('2028-01', '2028-01', '销售订单-审核/取消审核', '按钮权限：销售订单模块[审核]按钮和[取消审核]按钮的权限', '销售订单', 'XSDD_SH_QXSH', 204),
            ('2028-02', '2028-02', '销售订单-生成销售出库单', '按钮权限：销售订单模块[生成销售出库单]按钮的权限', '销售订单', 'XSDD_SCXSCKD', 206),
            ('2028-03', '2028-03', '销售订单-新建销售订单', '按钮权限：销售订单模块[新建销售订单]按钮的权限', '销售订单', 'XSDD_XJXSDD', 201),
            ('2028-04', '2028-04', '销售订单-编辑销售订单', '按钮权限：销售订单模块[编辑销售订单]按钮的权限', '销售订单', 'XSDD_BJXSDD', 202),
            ('2028-05', '2028-05', '销售订单-删除销售订单', '按钮权限：销售订单模块[删除销售订单]按钮的权限', '销售订单', 'XSDD_SCXSDD', 203),
            ('2028-06', '2028-06', '销售订单-单据生成PDF', '按钮权限：销售订单模块[单据生成PDF]按钮的权限', '销售订单', 'XSDD_DJSCPDF', 207),
            ('2028-07', '2028-07', '销售订单-打印', '按钮权限：销售订单模块[打印预览]和[直接打印]按钮的权限', '销售订单', 'XSDD_DY', 208),
            ('2028-08', '2028-08', '销售订单-生成采购订单', '按钮权限：销售订单模块[生成采购订单]按钮的权限', '销售订单', 'XSDD_SCCGDD', 205),
            ('2028-09', '2028-09', '销售订单-关闭订单/取消关闭订单', '按钮权限：销售订单模块[关闭销售订单]和[取消销售订单关闭状态]按钮的权限', '销售订单', 'XSDD_GBDD', 209),
            ('2029', '2029', '商品品牌', '模块权限：通过菜单进入商品品牌模块的权限', '商品', 'SPPP', 600),
            ('2030-01', '2030-01', '商品构成-新增子商品', '按钮权限：商品模块[新增子商品]按钮权限', '商品', 'SPGC_XZZSP', 209),
            ('2030-02', '2030-02', '商品构成-编辑子商品', '按钮权限：商品模块[编辑子商品]按钮权限', '商品', 'SPGC_BJZSP', 210),
            ('2030-03', '2030-03', '商品构成-删除子商品', '按钮权限：商品模块[删除子商品]按钮权限', '商品', 'SPGC_SCZSP', 211),
            ('2031', '2031', '价格体系', '模块权限：通过菜单进入价格体系模块的权限', '商品', 'JGTX', 700),
            ('2031-01', '2031-01', '商品-设置商品价格体系', '按钮权限：商品模块[设置商品价格体系]按钮权限', '商品', 'JGTX', 701),
            ('2032', '2032', '销售合同', '模块权限：通过菜单进入销售合同模块的权限', '销售合同', 'XSHT', 100),
            ('2032-01', '2032-01', '销售合同-新建销售合同', '按钮权限：销售合同模块[新建销售合同]按钮的权限', '销售合同', 'XSHT_XJXSHT', 201),
            ('2032-02', '2032-02', '销售合同-编辑销售合同', '按钮权限：销售合同模块[编辑销售合同]按钮的权限', '销售合同', 'XSHT_BJXSHT', 202),
            ('2032-03', '2032-03', '销售合同-删除销售合同', '按钮权限：销售合同模块[删除销售合同]按钮的权限', '销售合同', 'XSHT_SCXSHT', 203),
            ('2032-04', '2032-04', '销售合同-审核/取消审核', '按钮权限：销售合同模块[审核]按钮和[取消审核]按钮的权限', '销售合同', 'XSHT_SH_QXSH', 204),
            ('2032-05', '2032-05', '销售合同-生成销售订单', '按钮权限：销售合同模块[生成销售订单]按钮的权限', '销售合同', 'XSHT_SCXSDD', 205),
            ('2032-06', '2032-06', '销售合同-单据生成PDF', '按钮权限：销售合同模块[单据生成PDF]按钮的权限', '销售合同', 'XSHT_DJSCPDF', 206),
            ('2032-07', '2032-07', '销售合同-打印', '按钮权限：销售合同模块[打印预览]和[直接打印]按钮的权限', '销售合同', 'XSHT_DY', 207),
            ('2033', '2033', '存货拆分', '模块权限：通过菜单进入存货拆分模块的权限', '存货拆分', 'CHCF', 100),
            ('2033-01', '2033-01', '存货拆分-新建拆分单', '按钮权限：存货拆分模块[新建拆分单]按钮的权限', '存货拆分', 'CHCFXJCFD', 201),
            ('2033-02', '2033-02', '存货拆分-编辑拆分单', '按钮权限：存货拆分模块[编辑拆分单]按钮的权限', '存货拆分', 'CHCFBJCFD', 202),
            ('2033-03', '2033-03', '存货拆分-删除拆分单', '按钮权限：存货拆分模块[删除拆分单]按钮的权限', '存货拆分', 'CHCFSCCFD', 203),
            ('2033-04', '2033-04', '存货拆分-提交拆分单', '按钮权限：存货拆分模块[提交拆分单]按钮的权限', '存货拆分', 'CHCFTJCFD', 204),
            ('2033-05', '2033-05', '存货拆分-单据生成PDF', '按钮权限：存货拆分模块[单据生成PDF]按钮的权限', '存货拆分', 'CHCFDJSCPDF', 205),
            ('2033-06', '2033-06', '存货拆分-打印', '按钮权限：存货拆分模块[打印预览]和[直接打印]按钮的权限', '存货拆分', 'CHCFDY', 206),
            ('2034', '2034', '工厂', '模块权限：通过菜单进入工厂模块的权限', '工厂', 'GC', 100),
            ('2034-01', '2034-01', '工厂在业务单据中的使用权限', '数据域权限：工厂在业务单据中的使用权限', '工厂', 'GCCYWDJZDSYQX', 301),
            ('2034-02', '2034-02', '工厂分类', '数据域权限：工厂模块中工厂分类的数据权限', '工厂', 'GCFL', 300),
            ('2034-03', '2034-03', '新增工厂分类', '按钮权限：工厂模块[新增工厂分类]按钮权限', '工厂', 'XZGYSFL', 201),
            ('2034-04', '2034-04', '编辑工厂分类', '按钮权限：工厂模块[编辑工厂分类]按钮权限', '工厂', 'BJGYSFL', 202),
            ('2034-05', '2034-05', '删除工厂分类', '按钮权限：工厂模块[删除工厂分类]按钮权限', '工厂', 'SCGYSFL', 203),
            ('2034-06', '2034-06', '新增工厂', '按钮权限：工厂模块[新增工厂]按钮权限', '工厂', 'XZGYS', 204),
            ('2034-07', '2034-07', '编辑工厂', '按钮权限：工厂模块[编辑工厂]按钮权限', '工厂', 'BJGYS', 205),
            ('2034-08', '2034-08', '删除工厂', '按钮权限：工厂模块[删除工厂]按钮权限', '工厂', 'SCGYS', 206),
            ('2035', '2035', '成品委托生产订单', '模块权限：通过菜单进入成品委托生产订单模块的权限', '成品委托生产订单', 'CPWTSCDD', 100),
            ('2035-01', '2035-01', '成品委托生产订单-新建成品委托生产订单', '按钮权限：成品委托生产订单模块[新建成品委托生产订单]按钮的权限', '成品委托生产订单', 'CPWTSCDDXJCPWTSCDD', 201),
            ('2035-02', '2035-02', '成品委托生产订单-编辑成品委托生产订单', '按钮权限：成品委托生产订单模块[编辑成品委托生产订单]按钮的权限', '成品委托生产订单', 'CPWTSCDDBJCPWTSCDD', 202),
            ('2035-03', '2035-03', '成品委托生产订单-删除成品委托生产订单', '按钮权限：成品委托生产订单模块[删除成品委托生产订单]按钮的权限', '成品委托生产订单', 'CPWTSCDDSCCPWTSCDD', 203),
            ('2035-04', '2035-04', '成品委托生产订单-审核/取消审核', '按钮权限：成品委托生产订单模块[审核]和[取消审核]按钮的权限', '成品委托生产订单', 'CPWTSCDDSHQXSH', 204),
            ('2035-05', '2035-05', '成品委托生产订单-生成成品委托生产入库单', '按钮权限：成品委托生产订单模块[生成成品委托生产入库单]按钮的权限', '成品委托生产订单', 'CPWTSCDDSCCPWTSCRKD', 205),
            ('2035-06', '2035-06', '成品委托生产订单-关闭/取消关闭成品委托生产订单', '按钮权限：成品委托生产订单模块[关闭成品委托生产订单]和[取消关闭成品委托生产订单]按钮的权限', '成品委托生产订单', 'CPWTSCDGBJCPWTSCDD', 206),
            ('2035-07', '2035-07', '成品委托生产订单-单据生成PDF', '按钮权限：成品委托生产订单模块[单据生成PDF]按钮的权限', '成品委托生产订单', 'CPWTSCDDDJSCPDF', 207),
            ('2035-08', '2035-08', '成品委托生产订单-打印', '按钮权限：成品委托生产订单模块[打印预览]和[直接打印]按钮的权限', '成品委托生产订单', 'CPWTSCDDDY', 208),
            ('2036', '2036', '成品委托生产入库', '模块权限：通过菜单进入成品委托生产入库模块的权限', '成品委托生产入库', 'CPWTSCRK', 100),
            ('2036-01', '2036-01', '成品委托生产入库-新建成品委托生产入库单', '按钮权限：成品委托生产入库模块[新建成品委托生产入库单]按钮的权限', '成品委托生产入库', 'CPWTSCRKXJCPWTSCRKD', 201),
            ('2036-02', '2036-02', '成品委托生产入库-编辑成品委托生产入库单', '按钮权限：成品委托生产入库模块[编辑成品委托生产入库单]按钮的权限', '成品委托生产入库', 'CPWTSCRKBJCPWTSCRKD', 202),
            ('2036-03', '2036-03', '成品委托生产入库-删除成品委托生产入库单', '按钮权限：成品委托生产入库模块[删除成品委托生产入库单]按钮的权限', '成品委托生产入库', 'CPWTSCRKSCCPWTSCRKD', 203),
            ('2036-04', '2036-04', '成品委托生产入库-提交入库', '按钮权限：成品委托生产入库模块[提交入库]按钮的权限', '成品委托生产入库', 'CPWTSCRKTJRK', 204),
            ('2036-05', '2036-05', '成品委托生产入库-单据生成PDF', '按钮权限：成品委托生产入库模块[单据生成PDF]按钮的权限', '成品委托生产入库', 'CPWTSCRKDJSCPDF', 205),
            ('2036-06', '2036-06', '成品委托生产入库-打印', '按钮权限：成品委托生产入库模块[打印预览]和[直接打印]按钮的权限', '成品委托生产入库', 'CPWTSCRKDY', 206),
            ('2037', '2037', '采购入库明细表', '模块权限：通过菜单进入采购入库明细表模块的权限', '采购报表', 'CGRKMXB', 100),
            ('2101', '2101', '会计科目', '模块权限：通过菜单进入会计科目模块的权限', '会计科目', 'KJKM', 100),
            ('2102', '2102', '银行账户', '模块权限：通过菜单进入银行账户模块的权限', '银行账户', 'YHZH', 100),
            ('2103', '2103', '会计期间', '模块权限：通过菜单进入会计期间模块的权限', '会计期间', 'KJQJ', 100),
            ('3101', '3101', '物料单位', '模块权限：通过菜单进入物料单位模块的权限', '物料', 'WLDW', 500),
            ('3102', '3102', '原材料', '模块权限：通过菜单进入原材料模块的权限', '原材料', '', 100),
            ('3102-01', '3102-01', '原材料在业务单据中的使用权限', '数据域权限：原材料在业务单据中的使用权限', '原材料', '', 300),
            ('3102-02', '3102-02', '原材料分类数据权限', '数据域权限：原材料模块中原材料分类的数据权限', '原材料', '', 301),
            ('3102-03', '3102-03', '新增原材料分类', '按钮权限：原材料模块[新增原材料分类]按钮权限', '原材料', '', 201),
            ('3102-04', '3102-04', '编辑原材料分类', '按钮权限：原材料模块[编辑原材料分类]按钮权限', '原材料', '', 202),
            ('3102-05', '3102-05', '删除原材料分类', '按钮权限：原材料模块[删除原材料分类]按钮权限', '原材料', '', 203),
            ('3102-06', '3102-06', '新增原材料', '按钮权限：原材料模块[新增原材料]按钮权限', '原材料', '', 204),
            ('3102-07', '3102-07', '编辑原材料', '按钮权限：原材料模块[编辑原材料]按钮权限', '原材料', '', 205),
            ('3102-08', '3102-08', '删除原材料', '按钮权限：原材料模块[删除原材料]按钮权限', '原材料', '', 206);
            ";
    $db->execute($sql);
  }

  private function update_20200411_01()
  {
    // 本次更新：t_warehouse新增字段usage_type
    $db = $this->db;

    $tableName = "t_warehouse";
    $columnName = "usage_type";
    if (!$this->columnExists($db, $tableName, $columnName)) {
      $sql = "alter table {$tableName} add {$columnName} int(11) NOT NULL DEFAULT 40;";
      $db->execute($sql);
    }
  }

  private function update_20200410_03()
  {
    // 本次更新：新增模块-物料单位

    $db = $this->db;

    // fid
    $sql = "TRUNCATE TABLE `t_fid`;
            INSERT INTO `t_fid` (`fid`, `name`, `py`, `memo`) VALUES
            ('-7999', '自定义表单', 'ZDYBD', ''),
            ('-7994', '系统数据字典', 'XTSJZD', ''),
            ('-7995', '主菜单维护', 'ZCDWH', ''),
            ('-7996', '码表设置', 'MBSZ', ''),
            ('-7997', '表单视图开发助手', 'BDSTKFZS', ''),
            ('-9999', '重新登录', '', ''),
            ('-9997', '首页', 'SY', ''),
            ('-9996', '修改我的密码', 'XGWDMM', ''),
            ('-9995', '帮助', 'BZ', ''),
            ('-9994', '关于', 'GY', ''),
            ('-9993', '购买商业服务', '', ''),
            ('-8999', '用户管理', 'YHGL', ''),
            ('-8999-01', '组织机构在业务单据中的使用权限', '', ''),
            ('-8999-02', '业务员在业务单据中的使用权限', '', ''),
            ('-8997', '业务日志', 'YWRZ', ''),
            ('-8996', '权限管理', 'QXGL', ''),
            ('1001', '商品', 'SP', ''),
            ('1001-01', '商品在业务单据中的使用权限', '', ''),
            ('1001-02', '商品分类', 'SPFL', ''),
            ('1002', '商品计量单位', 'SPJLDW', ''),
            ('1003', '仓库', 'CK', ''),
            ('1003-01', '仓库在业务单据中的使用权限', '', ''),
            ('1004', '供应商档案', 'GYSDA', ''),
            ('1004-01', '供应商档案在业务单据中的使用权限', '', ''),
            ('1004-02', '供应商分类', '', ''),
            ('1007', '客户资料', 'KHZL', ''),
            ('1007-01', '客户资料在业务单据中的使用权限', '', ''),
            ('1007-02', '客户分类', '', ''),
            ('2000', '库存建账', 'KCJZ', ''),
            ('2001', '采购入库', 'CGRK', ''),
            ('2001-01', '采购入库-新建采购入库单', '', ''),
            ('2001-02', '采购入库-编辑采购入库单', '', ''),
            ('2001-03', '采购入库-删除采购入库单', '', ''),
            ('2001-04', '采购入库-提交入库', '', ''),
            ('2001-05', '采购入库-单据生成PDF', '', ''),
            ('2001-06', '采购入库-采购单价和金额可见', '', ''),
            ('2001-07', '采购入库-打印', '', ''),
            ('2002', '销售出库', 'XSCK', ''),
            ('2002-01', '销售出库-销售出库单允许编辑销售单价', '', ''),
            ('2002-02', '销售出库-新建销售出库单', '', ''),
            ('2002-03', '销售出库-编辑销售出库单', '', ''),
            ('2002-04', '销售出库-删除销售出库单', '', ''),
            ('2002-05', '销售出库-提交出库', '', ''),
            ('2002-06', '销售出库-单据生成PDF', '', ''),
            ('2002-07', '销售出库-打印', '', ''),
            ('2003', '库存账查询', 'KCZCX', ''),
            ('2004', '应收账款管理', 'YSZKGL', ''),
            ('2005', '应付账款管理', 'YFZKGL', ''),
            ('2006', '销售退货入库', 'XSTHRK', ''),
            ('2006-01', '销售退货入库-新建销售退货入库单', '', ''),
            ('2006-02', '销售退货入库-编辑销售退货入库单', '', ''),
            ('2006-03', '销售退货入库-删除销售退货入库单', '', ''),
            ('2006-04', '销售退货入库-提交入库', '', ''),
            ('2006-05', '销售退货入库-单据生成PDF', '', ''),
            ('2006-06', '销售退货入库-打印', '', ''),
            ('2007', '采购退货出库', 'CGTHCK', ''),
            ('2007-01', '采购退货出库-新建采购退货出库单', '', ''),
            ('2007-02', '采购退货出库-编辑采购退货出库单', '', ''),
            ('2007-03', '采购退货出库-删除采购退货出库单', '', ''),
            ('2007-04', '采购退货出库-提交采购退货出库单', '', ''),
            ('2007-05', '采购退货出库-单据生成PDF', '', ''),
            ('2007-06', '采购退货出库-打印', '', ''),
            ('2008', '业务设置', 'YWSZ', ''),
            ('2009', '库间调拨', 'KJDB', ''),
            ('2009-01', '库间调拨-新建调拨单', '', ''),
            ('2009-02', '库间调拨-编辑调拨单', '', ''),
            ('2009-03', '库间调拨-删除调拨单', '', ''),
            ('2009-04', '库间调拨-提交调拨单', '', ''),
            ('2009-05', '库间调拨-单据生成PDF', '', ''),
            ('2009-06', '库间调拨-打印', '', ''),
            ('2010', '库存盘点', 'KCPD', ''),
            ('2010-01', '库存盘点-新建盘点单', '', ''),
            ('2010-02', '库存盘点-编辑盘点单', '', ''),
            ('2010-03', '库存盘点-删除盘点单', '', ''),
            ('2010-04', '库存盘点-提交盘点单', '', ''),
            ('2010-05', '库存盘点-单据生成PDF', '', ''),
            ('2010-06', '库存盘点-打印', '', ''),
            ('2011-01', '首页-销售看板', '', ''),
            ('2011-02', '首页-库存看板', '', ''),
            ('2011-03', '首页-采购看板', '', ''),
            ('2011-04', '首页-资金看板', '', ''),
            ('2012', '报表-销售日报表(按商品汇总)', 'BBXSRBBASPHZ', ''),
            ('2013', '报表-销售日报表(按客户汇总)', 'BBXSRBBAKHHZ', ''),
            ('2014', '报表-销售日报表(按仓库汇总)', 'BBXSRBBACKHZ', ''),
            ('2015', '报表-销售日报表(按业务员汇总)', 'BBXSRBBAYWYHZ', ''),
            ('2016', '报表-销售月报表(按商品汇总)', 'BBXSYBBASPHZ', ''),
            ('2017', '报表-销售月报表(按客户汇总)', 'BBXSYBBAKHHZ', ''),
            ('2018', '报表-销售月报表(按仓库汇总)', 'BBXSYBBACKHZ', ''),
            ('2019', '报表-销售月报表(按业务员汇总)', 'BBXSYBBAYWYHZ', ''),
            ('2020', '报表-安全库存明细表', 'BBAQKCMXB', ''),
            ('2021', '报表-应收账款账龄分析表', 'BBYSZKZLFXB', ''),
            ('2022', '报表-应付账款账龄分析表', 'BBYFZKZLFXB', ''),
            ('2023', '报表-库存超上限明细表', 'BBKCCSXMXB', ''),
            ('2024', '现金收支查询', 'XJSZCX', ''),
            ('2025', '预收款管理', 'YSKGL', ''),
            ('2026', '预付款管理', 'YFKGL', ''),
            ('2027', '采购订单', 'CGDD', ''),
            ('2027-01', '采购订单-审核/取消审核', '', ''),
            ('2027-02', '采购订单-生成采购入库单', '', ''),
            ('2027-03', '采购订单-新建采购订单', '', ''),
            ('2027-04', '采购订单-编辑采购订单', '', ''),
            ('2027-05', '采购订单-删除采购订单', '', ''),
            ('2027-06', '采购订单-关闭订单/取消关闭订单', '', ''),
            ('2027-07', '采购订单-单据生成PDF', '', ''),
            ('2027-08', '采购订单-打印', '', ''),
            ('2028', '销售订单', 'XSDD', ''),
            ('2028-01', '销售订单-审核/取消审核', '', ''),
            ('2028-02', '销售订单-生成销售出库单', '', ''),
            ('2028-03', '销售订单-新建销售订单', '', ''),
            ('2028-04', '销售订单-编辑销售订单', '', ''),
            ('2028-05', '销售订单-删除销售订单', '', ''),
            ('2028-06', '销售订单-单据生成PDF', '', ''),
            ('2028-07', '销售订单-打印', '', ''),
            ('2028-08', '销售订单-生成采购订单', '', ''),
            ('2028-09', '销售订单-关闭订单/取消关闭订单', '', ''),
            ('2029', '商品品牌', 'SPPP', ''),
            ('2030-01', '商品构成-新增子商品', '', ''),
            ('2030-02', '商品构成-编辑子商品', '', ''),
            ('2030-03', '商品构成-删除子商品', '', ''),
            ('2031', '价格体系', 'JGTX', ''),
            ('2031-01', '商品-设置商品价格体系', '', ''),
            ('2032', '销售合同', 'XSHT', ''),
            ('2032-01', '销售合同-新建销售合同', '', ''),
            ('2032-02', '销售合同-编辑销售合同', '', ''),
            ('2032-03', '销售合同-删除销售合同', '', ''),
            ('2032-04', '销售合同-审核/取消审核', '', ''),
            ('2032-05', '销售合同-生成销售订单', '', ''),
            ('2032-06', '销售合同-单据生成PDF', '', ''),
            ('2032-07', '销售合同-打印', '', ''),
            ('2033', '存货拆分', 'CHCF', ''),
            ('2033-01', '存货拆分-新建拆分单', '', ''),
            ('2033-02', '存货拆分-编辑拆分单', '', ''),
            ('2033-03', '存货拆分-删除拆分单', '', ''),
            ('2033-04', '存货拆分-提交拆分单', '', ''),
            ('2033-05', '存货拆分-单据生成PDF', '', ''),
            ('2033-06', '存货拆分-打印', '', ''),
            ('2034', '工厂', 'GC', ''),
            ('2034-01', '工厂在业务单据中的使用权限', '', ''),
            ('2034-02', '工厂分类', '', ''),
            ('2034-03', '工厂-新增工厂分类', '', ''),
            ('2034-04', '工厂-编辑工厂分类', '', ''),
            ('2034-05', '工厂-删除工厂分类', '', ''),
            ('2034-06', '工厂-新增工厂', '', ''),
            ('2034-07', '工厂-编辑工厂', '', ''),
            ('2034-08', '工厂-删除工厂', '', ''),
            ('2035', '成品委托生产订单', 'CPWTSCDD', ''),
            ('2035-01', '成品委托生产订单-新建成品委托生产订单', '', ''),
            ('2035-02', '成品委托生产订单-编辑成品委托生产订单', '', ''),
            ('2035-03', '成品委托生产订单-删除成品委托生产订单', '', ''),
            ('2035-04', '成品委托生产订单-提交成品委托生产订单', '', ''),
            ('2035-05', '成品委托生产订单-审核/取消审核成品委托生产入库单', '', ''),
            ('2035-06', '成品委托生产订单-关闭/取消关闭成品委托生产订单', '', ''),
            ('2035-07', '成品委托生产订单-单据生成PDF', '', ''),
            ('2035-08', '成品委托生产订单-打印', '', ''),
            ('2036', '成品委托生产入库', 'CPWTSCRK', ''),
            ('2036-01', '成品委托生产入库-新建成品委托生产入库单', '', ''),
            ('2036-02', '成品委托生产入库-编辑成品委托生产入库单', '', ''),
            ('2036-03', '成品委托生产入库-删除成品委托生产入库单', '', ''),
            ('2036-04', '成品委托生产入库-提交入库', '', ''),
            ('2036-05', '成品委托生产入库-单据生成PDF', '', ''),
            ('2036-06', '成品委托生产入库-打印', '', ''),
            ('2037', '报表-采购入库明细表', '', ''),
            ('2101', '会计科目', 'KJKM', ''),
            ('2102', '银行账户', 'YHZH', ''),
            ('2103', '会计期间', 'KJQJ', ''),
            ('3101', '物料单位', 'WLDW', '');
            ";
    $db->execute($sql);

    // 主菜单
    $sql = "TRUNCATE TABLE `t_menu_item`;
            INSERT INTO `t_menu_item` (`id`, `caption`, `fid`, `parent_id`, `show_order`, `py`, `memo`) VALUES
            ('01', '文件', NULL, NULL, 1, '', ''),
            ('0101', '首页', '-9997', '01', 1, 'SY', ''),
            ('0102', '重新登录', '-9999', '01', 2, '', ''),
            ('0103', '修改我的密码', '-9996', '01', 3, 'XGWDMM', ''),
            ('02', '采购', NULL, NULL, 2, '', ''),
            ('0200', '采购订单', '2027', '02', 0, 'CGDD', ''),
            ('0201', '采购入库', '2001', '02', 1, 'CGRK', ''),
            ('0202', '采购退货出库', '2007', '02', 2, 'CGTHCK', ''),
            ('03', '库存', NULL, NULL, 3, '', ''),
            ('0301', '库存账查询', '2003', '03', 1, 'KCZCX', ''),
            ('0302', '库存建账', '2000', '03', 2, 'KCJZ', ''),
            ('0303', '库间调拨', '2009', '03', 3, 'KJDB', ''),
            ('0304', '库存盘点', '2010', '03', 4, 'KCPD', ''),
            ('12', '加工', NULL, NULL, 4, '', ''),
            ('1201', '存货拆分', '2033', '12', 1, 'CHCF', ''),
            ('1202', '成品委托生产', NULL, '12', 2, '', ''),
            ('120201', '成品委托生产订单', '2035', '1202', 1, 'CPWTSCDD', ''),
            ('120202', '成品委托生产入库', '2036', '1202', 2, 'CPWTSCRK', ''),
            ('04', '销售', NULL, NULL, 5, '', ''),
            ('0401', '销售合同', '2032', '04', 1, 'XSHT', ''),
            ('0402', '销售订单', '2028', '04', 2, 'XSDD', ''),
            ('0403', '销售出库', '2002', '04', 3, 'XSCK', ''),
            ('0404', '销售退货入库', '2006', '04', 4, 'XSTHRK', ''),
            ('05', '客户关系', NULL, NULL, 6, '', ''),
            ('0501', '客户资料', '1007', '05', 1, 'KHZL', ''),
            ('06', '资金', NULL, NULL, 7, '', ''),
            ('0601', '应收账款管理', '2004', '06', 1, 'YSZKGL', ''),
            ('0602', '应付账款管理', '2005', '06', 2, 'YFZKGL', ''),
            ('0603', '现金收支查询', '2024', '06', 3, 'XJSZCX', ''),
            ('0604', '预收款管理', '2025', '06', 4, 'YSKGL', ''),
            ('0605', '预付款管理', '2026', '06', 5, 'YFKGL', ''),
            ('07', '报表', NULL, NULL, 8, '', ''),
            ('0700', '采购报表', NULL, '07', 0, '', ''),
            ('070001', '采购入库明细表', '2037', '0700', 1, 'CGRKMXB', ''),
            ('0701', '销售日报表', NULL, '07', 1, '', ''),
            ('070101', '销售日报表(按商品汇总)', '2012', '0701', 1, 'XSRBBASPHZ', ''),
            ('070102', '销售日报表(按客户汇总)', '2013', '0701', 2, 'XSRBBAKHHZ', ''),
            ('070103', '销售日报表(按仓库汇总)', '2014', '0701', 3, 'XSRBBACKHZ', ''),
            ('070104', '销售日报表(按业务员汇总)', '2015', '0701', 4, 'XSRBBAYWYHZ', ''),
            ('0702', '销售月报表', NULL, '07', 2, '', ''),
            ('070201', '销售月报表(按商品汇总)', '2016', '0702', 1, 'XSYBBASPHZ', ''),
            ('070202', '销售月报表(按客户汇总)', '2017', '0702', 2, 'XSYBBAKHHZ', ''),
            ('070203', '销售月报表(按仓库汇总)', '2018', '0702', 3, 'XSYBBACKHZ', ''),
            ('070204', '销售月报表(按业务员汇总)', '2019', '0702', 4, 'XSYBBAYWYHZ', ''),
            ('0703', '库存报表', NULL, '07', 3, '', ''),
            ('070301', '安全库存明细表', '2020', '0703', 1, 'AQKCMXB', ''),
            ('070302', '库存超上限明细表', '2023', '0703', 2, 'KCCSXMXB', ''),
            ('0706', '资金报表', NULL, '07', 6, '', ''),
            ('070601', '应收账款账龄分析表', '2021', '0706', 1, 'YSZKZLFXB', ''),
            ('070602', '应付账款账龄分析表', '2022', '0706', 2, 'YFZKZLFXB', ''),
            ('11', '财务总账', NULL, NULL, 9, '', ''),
            ('1101', '基础数据', NULL, '11', 1, '', ''),
            ('110101', '会计科目', '2101', '1101', 1, 'KJKM', ''),
            ('110102', '银行账户', '2102', '1101', 2, 'YHZH', ''),
            ('110103', '会计期间', '2103', '1101', 3, 'KJQJ', ''),
            ('08', '基础数据', NULL, NULL, 10, '', ''),
            ('0801', '商品', NULL, '08', 1, '', ''),
            ('080101', '商品', '1001', '0801', 1, 'SP', ''),
            ('080102', '商品计量单位', '1002', '0801', 2, 'SPJLDW', ''),
            ('080103', '商品品牌', '2029', '0801', 3, '', 'SPPP'),
            ('080104', '价格体系', '2031', '0801', 4, '', 'JGTX'),
            ('0803', '仓库', '1003', '08', 3, 'CK', ''),
            ('0804', '供应商档案', '1004', '08', 4, 'GYSDA', ''),
            ('0805', '工厂', '2034', '08', 5, 'GC', ''),
            ('0806', '物料', NULL, '08', 6, '', ''),
            ('080601', '物料单位', '3101', '0806', 4, 'WLDW', ''),
            ('09', '系统管理', NULL, NULL, 11, '', ''),
            ('0901', '用户管理', '-8999', '09', 1, 'YHGL', ''),
            ('0902', '权限管理', '-8996', '09', 2, 'QXGL', ''),
            ('0903', '业务日志', '-8997', '09', 3, 'YWRZ', ''),
            ('0904', '业务设置', '2008', '09', 4, '', 'YWSZ'),
            ('0905', '二次开发', NULL, '09', 5, '', ''),
            ('090501', '码表设置', '-7996', '0905', 1, 'MBSZ', ''),
            ('090502', '自定义表单', '-7999', '0905', 2, 'ZDYBD', ''),
            ('090503', '表单视图开发助手', '-7997', '0905', 3, 'BDSTKFZS', ''),
            ('090504', '主菜单维护', '-7995', '0905', 4, 'ZCDWH', ''),
            ('090505', '系统数据字典', '-7994', '0905', 5, 'XTSJZD', ''),
            ('10', '帮助', NULL, NULL, 12, '', ''),
            ('1001', '使用帮助', '-9995', '10', 1, 'SYBZ', ''),
            ('1003', '关于', '-9994', '10', 3, 'GY', '');
            ";
    $db->execute($sql);

    // 权限
    $sql = "TRUNCATE TABLE `t_permission`;
            INSERT INTO `t_permission` (`id`, `fid`, `name`, `note`, `category`, `py`, `show_order`) VALUES
            ('-7999', '-7999', '自定义表单', '模块权限：通过菜单进入自定义表单模块的权限', '自定义表单', 'ZDYBD', 100),
            ('-7994', '-7994', '系统数据字典', '模块权限：通过菜单进入系统数据字典模块的权限', '系统数据字典', 'XTSJZD', 100),
            ('-7995', '-7995', '主菜单维护', '模块权限：通过菜单进入主菜单维护模块的权限', '主菜单维护', 'ZCDWH', 100),
            ('-7996', '-7996', '码表设置', '模块权限：通过菜单进入码表设置模块的权限', '码表设置', 'MBSZ', 100),
            ('-8996', '-8996', '权限管理', '模块权限：通过菜单进入权限管理模块的权限', '权限管理', 'QXGL', 100),
            ('-8996-01', '-8996-01', '权限管理-新增角色', '按钮权限：权限管理模块[新增角色]按钮权限', '权限管理', 'QXGL_XZJS', 201),
            ('-8996-02', '-8996-02', '权限管理-编辑角色', '按钮权限：权限管理模块[编辑角色]按钮权限', '权限管理', 'QXGL_BJJS', 202),
            ('-8996-03', '-8996-03', '权限管理-删除角色', '按钮权限：权限管理模块[删除角色]按钮权限', '权限管理', 'QXGL_SCJS', 203),
            ('-8997', '-8997', '业务日志', '模块权限：通过菜单进入业务日志模块的权限', '系统管理', 'YWRZ', 100),
            ('-8999', '-8999', '用户管理', '模块权限：通过菜单进入用户管理模块的权限', '用户管理', 'YHGL', 100),
            ('-8999-01', '-8999-01', '组织机构在业务单据中的使用权限', '数据域权限：组织机构在业务单据中的使用权限', '用户管理', 'ZZJGZYWDJZDSYQX', 300),
            ('-8999-02', '-8999-02', '业务员在业务单据中的使用权限', '数据域权限：业务员在业务单据中的使用权限', '用户管理', 'YWYZYWDJZDSYQX', 301),
            ('-8999-03', '-8999-03', '用户管理-新增组织机构', '按钮权限：用户管理模块[新增组织机构]按钮权限', '用户管理', 'YHGL_XZZZJG', 201),
            ('-8999-04', '-8999-04', '用户管理-编辑组织机构', '按钮权限：用户管理模块[编辑组织机构]按钮权限', '用户管理', 'YHGL_BJZZJG', 202),
            ('-8999-05', '-8999-05', '用户管理-删除组织机构', '按钮权限：用户管理模块[删除组织机构]按钮权限', '用户管理', 'YHGL_SCZZJG', 203),
            ('-8999-06', '-8999-06', '用户管理-新增用户', '按钮权限：用户管理模块[新增用户]按钮权限', '用户管理', 'YHGL_XZYH', 204),
            ('-8999-07', '-8999-07', '用户管理-编辑用户', '按钮权限：用户管理模块[编辑用户]按钮权限', '用户管理', 'YHGL_BJYH', 205),
            ('-8999-08', '-8999-08', '用户管理-删除用户', '按钮权限：用户管理模块[删除用户]按钮权限', '用户管理', 'YHGL_SCYH', 206),
            ('-8999-09', '-8999-09', '用户管理-修改用户密码', '按钮权限：用户管理模块[修改用户密码]按钮权限', '用户管理', 'YHGL_XGYHMM', 207),
            ('1001', '1001', '商品', '模块权限：通过菜单进入商品模块的权限', '商品', 'SP', 100),
            ('1001-01', '1001-01', '商品在业务单据中的使用权限', '数据域权限：商品在业务单据中的使用权限', '商品', 'SPZYWDJZDSYQX', 300),
            ('1001-02', '1001-02', '商品分类', '数据域权限：商品模块中商品分类的数据权限', '商品', 'SPFL', 301),
            ('1001-03', '1001-03', '新增商品分类', '按钮权限：商品模块[新增商品分类]按钮权限', '商品', 'XZSPFL', 201),
            ('1001-04', '1001-04', '编辑商品分类', '按钮权限：商品模块[编辑商品分类]按钮权限', '商品', 'BJSPFL', 202),
            ('1001-05', '1001-05', '删除商品分类', '按钮权限：商品模块[删除商品分类]按钮权限', '商品', 'SCSPFL', 203),
            ('1001-06', '1001-06', '新增商品', '按钮权限：商品模块[新增商品]按钮权限', '商品', 'XZSP', 204),
            ('1001-07', '1001-07', '编辑商品', '按钮权限：商品模块[编辑商品]按钮权限', '商品', 'BJSP', 205),
            ('1001-08', '1001-08', '删除商品', '按钮权限：商品模块[删除商品]按钮权限', '商品', 'SCSP', 206),
            ('1001-09', '1001-09', '导入商品', '按钮权限：商品模块[导入商品]按钮权限', '商品', 'DRSP', 207),
            ('1001-10', '1001-10', '设置商品安全库存', '按钮权限：商品模块[设置安全库存]按钮权限', '商品', 'SZSPAQKC', 208),
            ('1001-11', '1001-11', '导出Excel', '按钮权限：商品模块[导出Excel]按钮权限', '商品', 'DCEXCEL', 209),
            ('1002', '1002', '商品计量单位', '模块权限：通过菜单进入商品计量单位模块的权限', '商品', 'SPJLDW', 500),
            ('1003', '1003', '仓库', '模块权限：通过菜单进入仓库的权限', '仓库', 'CK', 100),
            ('1003-01', '1003-01', '仓库在业务单据中的使用权限', '数据域权限：仓库在业务单据中的使用权限', '仓库', 'CKZYWDJZDSYQX', 300),
            ('1003-02', '1003-02', '新增仓库', '按钮权限：仓库模块[新增仓库]按钮权限', '仓库', 'XZCK', 201),
            ('1003-03', '1003-03', '编辑仓库', '按钮权限：仓库模块[编辑仓库]按钮权限', '仓库', 'BJCK', 202),
            ('1003-04', '1003-04', '删除仓库', '按钮权限：仓库模块[删除仓库]按钮权限', '仓库', 'SCCK', 203),
            ('1003-05', '1003-05', '修改仓库数据域', '按钮权限：仓库模块[修改数据域]按钮权限', '仓库', 'XGCKSJY', 204),
            ('1004', '1004', '供应商档案', '模块权限：通过菜单进入供应商档案的权限', '供应商管理', 'GYSDA', 100),
            ('1004-01', '1004-01', '供应商档案在业务单据中的使用权限', '数据域权限：供应商档案在业务单据中的使用权限', '供应商管理', 'GYSDAZYWDJZDSYQX', 301),
            ('1004-02', '1004-02', '供应商分类', '数据域权限：供应商档案模块中供应商分类的数据权限', '供应商管理', 'GYSFL', 300),
            ('1004-03', '1004-03', '新增供应商分类', '按钮权限：供应商档案模块[新增供应商分类]按钮权限', '供应商管理', 'XZGYSFL', 201),
            ('1004-04', '1004-04', '编辑供应商分类', '按钮权限：供应商档案模块[编辑供应商分类]按钮权限', '供应商管理', 'BJGYSFL', 202),
            ('1004-05', '1004-05', '删除供应商分类', '按钮权限：供应商档案模块[删除供应商分类]按钮权限', '供应商管理', 'SCGYSFL', 203),
            ('1004-06', '1004-06', '新增供应商', '按钮权限：供应商档案模块[新增供应商]按钮权限', '供应商管理', 'XZGYS', 204),
            ('1004-07', '1004-07', '编辑供应商', '按钮权限：供应商档案模块[编辑供应商]按钮权限', '供应商管理', 'BJGYS', 205),
            ('1004-08', '1004-08', '删除供应商', '按钮权限：供应商档案模块[删除供应商]按钮权限', '供应商管理', 'SCGYS', 206),
            ('1007', '1007', '客户资料', '模块权限：通过菜单进入客户资料模块的权限', '客户管理', 'KHZL', 100),
            ('1007-01', '1007-01', '客户资料在业务单据中的使用权限', '数据域权限：客户资料在业务单据中的使用权限', '客户管理', 'KHZLZYWDJZDSYQX', 300),
            ('1007-02', '1007-02', '客户分类', '数据域权限：客户档案模块中客户分类的数据权限', '客户管理', 'KHFL', 301),
            ('1007-03', '1007-03', '新增客户分类', '按钮权限：客户资料模块[新增客户分类]按钮权限', '客户管理', 'XZKHFL', 201),
            ('1007-04', '1007-04', '编辑客户分类', '按钮权限：客户资料模块[编辑客户分类]按钮权限', '客户管理', 'BJKHFL', 202),
            ('1007-05', '1007-05', '删除客户分类', '按钮权限：客户资料模块[删除客户分类]按钮权限', '客户管理', 'SCKHFL', 203),
            ('1007-06', '1007-06', '新增客户', '按钮权限：客户资料模块[新增客户]按钮权限', '客户管理', 'XZKH', 204),
            ('1007-07', '1007-07', '编辑客户', '按钮权限：客户资料模块[编辑客户]按钮权限', '客户管理', 'BJKH', 205),
            ('1007-08', '1007-08', '删除客户', '按钮权限：客户资料模块[删除客户]按钮权限', '客户管理', 'SCKH', 206),
            ('1007-09', '1007-09', '导入客户', '按钮权限：客户资料模块[导入客户]按钮权限', '客户管理', 'DRKH', 207),
            ('2000', '2000', '库存建账', '模块权限：通过菜单进入库存建账模块的权限', '库存建账', 'KCJZ', 100),
            ('2001', '2001', '采购入库', '模块权限：通过菜单进入采购入库模块的权限', '采购入库', 'CGRK', 100),
            ('2001-01', '2001-01', '采购入库-新建采购入库单', '按钮权限：采购入库模块[新建采购入库单]按钮权限', '采购入库', 'CGRK_XJCGRKD', 201),
            ('2001-02', '2001-02', '采购入库-编辑采购入库单', '按钮权限：采购入库模块[编辑采购入库单]按钮权限', '采购入库', 'CGRK_BJCGRKD', 202),
            ('2001-03', '2001-03', '采购入库-删除采购入库单', '按钮权限：采购入库模块[删除采购入库单]按钮权限', '采购入库', 'CGRK_SCCGRKD', 203),
            ('2001-04', '2001-04', '采购入库-提交入库', '按钮权限：采购入库模块[提交入库]按钮权限', '采购入库', 'CGRK_TJRK', 204),
            ('2001-05', '2001-05', '采购入库-单据生成PDF', '按钮权限：采购入库模块[单据生成PDF]按钮权限', '采购入库', 'CGRK_DJSCPDF', 205),
            ('2001-06', '2001-06', '采购入库-采购单价和金额可见', '字段权限：采购入库单的采购单价和金额可以被用户查看', '采购入库', 'CGRK_CGDJHJEKJ', 206),
            ('2001-07', '2001-07', '采购入库-打印', '按钮权限：采购入库模块[打印预览]和[直接打印]按钮权限', '采购入库', 'CGRK_DY', 207),
            ('2002', '2002', '销售出库', '模块权限：通过菜单进入销售出库模块的权限', '销售出库', 'XSCK', 100),
            ('2002-01', '2002-01', '销售出库-销售出库单允许编辑销售单价', '功能权限：销售出库单允许编辑销售单价', '销售出库', 'XSCKDYXBJXSDJ', 101),
            ('2002-02', '2002-02', '销售出库-新建销售出库单', '按钮权限：销售出库模块[新建销售出库单]按钮权限', '销售出库', 'XSCK_XJXSCKD', 201),
            ('2002-03', '2002-03', '销售出库-编辑销售出库单', '按钮权限：销售出库模块[编辑销售出库单]按钮权限', '销售出库', 'XSCK_BJXSCKD', 202),
            ('2002-04', '2002-04', '销售出库-删除销售出库单', '按钮权限：销售出库模块[删除销售出库单]按钮权限', '销售出库', 'XSCK_SCXSCKD', 203),
            ('2002-05', '2002-05', '销售出库-提交出库', '按钮权限：销售出库模块[提交出库]按钮权限', '销售出库', 'XSCK_TJCK', 204),
            ('2002-06', '2002-06', '销售出库-单据生成PDF', '按钮权限：销售出库模块[单据生成PDF]按钮权限', '销售出库', 'XSCK_DJSCPDF', 205),
            ('2002-07', '2002-07', '销售出库-打印', '按钮权限：销售出库模块[打印预览]和[直接打印]按钮权限', '销售出库', 'XSCK_DY', 207),
            ('2003', '2003', '库存账查询', '模块权限：通过菜单进入库存账查询模块的权限', '库存账查询', 'KCZCX', 100),
            ('2004', '2004', '应收账款管理', '模块权限：通过菜单进入应收账款管理模块的权限', '应收账款管理', 'YSZKGL', 100),
            ('2005', '2005', '应付账款管理', '模块权限：通过菜单进入应付账款管理模块的权限', '应付账款管理', 'YFZKGL', 100),
            ('2006', '2006', '销售退货入库', '模块权限：通过菜单进入销售退货入库模块的权限', '销售退货入库', 'XSTHRK', 100),
            ('2006-01', '2006-01', '销售退货入库-新建销售退货入库单', '按钮权限：销售退货入库模块[新建销售退货入库单]按钮权限', '销售退货入库', 'XSTHRK_XJXSTHRKD', 201),
            ('2006-02', '2006-02', '销售退货入库-编辑销售退货入库单', '按钮权限：销售退货入库模块[编辑销售退货入库单]按钮权限', '销售退货入库', 'XSTHRK_BJXSTHRKD', 202),
            ('2006-03', '2006-03', '销售退货入库-删除销售退货入库单', '按钮权限：销售退货入库模块[删除销售退货入库单]按钮权限', '销售退货入库', 'XSTHRK_SCXSTHRKD', 203),
            ('2006-04', '2006-04', '销售退货入库-提交入库', '按钮权限：销售退货入库模块[提交入库]按钮权限', '销售退货入库', 'XSTHRK_TJRK', 204),
            ('2006-05', '2006-05', '销售退货入库-单据生成PDF', '按钮权限：销售退货入库模块[单据生成PDF]按钮权限', '销售退货入库', 'XSTHRK_DJSCPDF', 205),
            ('2006-06', '2006-06', '销售退货入库-打印', '按钮权限：销售退货入库模块[打印预览]和[直接打印]按钮权限', '销售退货入库', 'XSTHRK_DY', 206),
            ('2007', '2007', '采购退货出库', '模块权限：通过菜单进入采购退货出库模块的权限', '采购退货出库', 'CGTHCK', 100),
            ('2007-01', '2007-01', '采购退货出库-新建采购退货出库单', '按钮权限：采购退货出库模块[新建采购退货出库单]按钮权限', '采购退货出库', 'CGTHCK_XJCGTHCKD', 201),
            ('2007-02', '2007-02', '采购退货出库-编辑采购退货出库单', '按钮权限：采购退货出库模块[编辑采购退货出库单]按钮权限', '采购退货出库', 'CGTHCK_BJCGTHCKD', 202),
            ('2007-03', '2007-03', '采购退货出库-删除采购退货出库单', '按钮权限：采购退货出库模块[删除采购退货出库单]按钮权限', '采购退货出库', 'CGTHCK_SCCGTHCKD', 203),
            ('2007-04', '2007-04', '采购退货出库-提交采购退货出库单', '按钮权限：采购退货出库模块[提交采购退货出库单]按钮权限', '采购退货出库', 'CGTHCK_TJCGTHCKD', 204),
            ('2007-05', '2007-05', '采购退货出库-单据生成PDF', '按钮权限：采购退货出库模块[单据生成PDF]按钮权限', '采购退货出库', 'CGTHCK_DJSCPDF', 205),
            ('2007-06', '2007-06', '采购退货出库-打印', '按钮权限：采购退货出库模块[打印预览]和[直接打印]按钮权限', '采购退货出库', 'CGTHCK_DY', 206),
            ('2008', '2008', '业务设置', '模块权限：通过菜单进入业务设置模块的权限', '系统管理', 'YWSZ', 100),
            ('2009', '2009', '库间调拨', '模块权限：通过菜单进入库间调拨模块的权限', '库间调拨', 'KJDB', 100),
            ('2009-01', '2009-01', '库间调拨-新建调拨单', '按钮权限：库间调拨模块[新建调拨单]按钮权限', '库间调拨', 'KJDB_XJDBD', 201),
            ('2009-02', '2009-02', '库间调拨-编辑调拨单', '按钮权限：库间调拨模块[编辑调拨单]按钮权限', '库间调拨', 'KJDB_BJDBD', 202),
            ('2009-03', '2009-03', '库间调拨-删除调拨单', '按钮权限：库间调拨模块[删除调拨单]按钮权限', '库间调拨', 'KJDB_SCDBD', 203),
            ('2009-04', '2009-04', '库间调拨-提交调拨单', '按钮权限：库间调拨模块[提交调拨单]按钮权限', '库间调拨', 'KJDB_TJDBD', 204),
            ('2009-05', '2009-05', '库间调拨-单据生成PDF', '按钮权限：库间调拨模块[单据生成PDF]按钮权限', '库间调拨', 'KJDB_DJSCPDF', 205),
            ('2009-06', '2009-06', '库间调拨-打印', '按钮权限：库间调拨模块[打印预览]和[直接打印]按钮权限', '库间调拨', 'KJDB_DY', 206),
            ('2010', '2010', '库存盘点', '模块权限：通过菜单进入库存盘点模块的权限', '库存盘点', 'KCPD', 100),
            ('2010-01', '2010-01', '库存盘点-新建盘点单', '按钮权限：库存盘点模块[新建盘点单]按钮权限', '库存盘点', 'KCPD_XJPDD', 201),
            ('2010-02', '2010-02', '库存盘点-编辑盘点单', '按钮权限：库存盘点模块[编辑盘点单]按钮权限', '库存盘点', 'KCPD_BJPDD', 202),
            ('2010-03', '2010-03', '库存盘点-删除盘点单', '按钮权限：库存盘点模块[删除盘点单]按钮权限', '库存盘点', 'KCPD_SCPDD', 203),
            ('2010-04', '2010-04', '库存盘点-提交盘点单', '按钮权限：库存盘点模块[提交盘点单]按钮权限', '库存盘点', 'KCPD_TJPDD', 204),
            ('2010-05', '2010-05', '库存盘点-单据生成PDF', '按钮权限：库存盘点模块[单据生成PDF]按钮权限', '库存盘点', 'KCPD_DJSCPDF', 205),
            ('2010-06', '2010-06', '库存盘点-打印', '按钮权限：库存盘点模块[打印预览]和[直接打印]按钮权限', '库存盘点', 'KCPD_DY', 206),
            ('2011-01', '2011-01', '首页-销售看板', '功能权限：在首页显示销售看板', '首页看板', 'SY_XSKB', 100),
            ('2011-02', '2011-02', '首页-库存看板', '功能权限：在首页显示库存看板', '首页看板', 'SY_KCKB', 100),
            ('2011-03', '2011-03', '首页-采购看板', '功能权限：在首页显示采购看板', '首页看板', 'SY_CGKB', 100),
            ('2011-04', '2011-04', '首页-资金看板', '功能权限：在首页显示资金看板', '首页看板', 'SY_ZJKB', 100),
            ('2012', '2012', '报表-销售日报表(按商品汇总)', '模块权限：通过菜单进入销售日报表(按商品汇总)模块的权限', '销售日报表', 'BB_XSRBB_ASPHZ_', 100),
            ('2013', '2013', '报表-销售日报表(按客户汇总)', '模块权限：通过菜单进入销售日报表(按客户汇总)模块的权限', '销售日报表', 'BB_XSRBB_AKHHZ_', 100),
            ('2014', '2014', '报表-销售日报表(按仓库汇总)', '模块权限：通过菜单进入销售日报表(按仓库汇总)模块的权限', '销售日报表', 'BB_XSRBB_ACKHZ_', 100),
            ('2015', '2015', '报表-销售日报表(按业务员汇总)', '模块权限：通过菜单进入销售日报表(按业务员汇总)模块的权限', '销售日报表', 'BB_XSRBB_AYWYHZ_', 100),
            ('2016', '2016', '报表-销售月报表(按商品汇总)', '模块权限：通过菜单进入销售月报表(按商品汇总)模块的权限', '销售月报表', 'BB_XSYBB_ASPHZ_', 100),
            ('2017', '2017', '报表-销售月报表(按客户汇总)', '模块权限：通过菜单进入销售月报表(按客户汇总)模块的权限', '销售月报表', 'BB_XSYBB_AKHHZ_', 100),
            ('2018', '2018', '报表-销售月报表(按仓库汇总)', '模块权限：通过菜单进入销售月报表(按仓库汇总)模块的权限', '销售月报表', 'BB_XSYBB_ACKHZ_', 100),
            ('2019', '2019', '报表-销售月报表(按业务员汇总)', '模块权限：通过菜单进入销售月报表(按业务员汇总)模块的权限', '销售月报表', 'BB_XSYBB_AYWYHZ_', 100),
            ('2020', '2020', '报表-安全库存明细表', '模块权限：通过菜单进入安全库存明细表模块的权限', '库存报表', 'BB_AQKCMXB', 100),
            ('2021', '2021', '报表-应收账款账龄分析表', '模块权限：通过菜单进入应收账款账龄分析表模块的权限', '资金报表', 'BB_YSZKZLFXB', 100),
            ('2022', '2022', '报表-应付账款账龄分析表', '模块权限：通过菜单进入应付账款账龄分析表模块的权限', '资金报表', 'BB_YFZKZLFXB', 100),
            ('2023', '2023', '报表-库存超上限明细表', '模块权限：通过菜单进入库存超上限明细表模块的权限', '库存报表', 'BB_KCCSXMXB', 100),
            ('2024', '2024', '现金收支查询', '模块权限：通过菜单进入现金收支查询模块的权限', '现金管理', 'XJSZCX', 100),
            ('2025', '2025', '预收款管理', '模块权限：通过菜单进入预收款管理模块的权限', '预收款管理', 'YSKGL', 100),
            ('2026', '2026', '预付款管理', '模块权限：通过菜单进入预付款管理模块的权限', '预付款管理', 'YFKGL', 100),
            ('2027', '2027', '采购订单', '模块权限：通过菜单进入采购订单模块的权限', '采购订单', 'CGDD', 100),
            ('2027-01', '2027-01', '采购订单-审核/取消审核', '按钮权限：采购订单模块[审核]按钮和[取消审核]按钮的权限', '采购订单', 'CGDD _ SH_QXSH', 204),
            ('2027-02', '2027-02', '采购订单-生成采购入库单', '按钮权限：采购订单模块[生成采购入库单]按钮权限', '采购订单', 'CGDD _ SCCGRKD', 205),
            ('2027-03', '2027-03', '采购订单-新建采购订单', '按钮权限：采购订单模块[新建采购订单]按钮权限', '采购订单', 'CGDD _ XJCGDD', 201),
            ('2027-04', '2027-04', '采购订单-编辑采购订单', '按钮权限：采购订单模块[编辑采购订单]按钮权限', '采购订单', 'CGDD _ BJCGDD', 202),
            ('2027-05', '2027-05', '采购订单-删除采购订单', '按钮权限：采购订单模块[删除采购订单]按钮权限', '采购订单', 'CGDD _ SCCGDD', 203),
            ('2027-06', '2027-06', '采购订单-关闭订单/取消关闭订单', '按钮权限：采购订单模块[关闭采购订单]和[取消采购订单关闭状态]按钮权限', '采购订单', 'CGDD _ GBDD_QXGBDD', 206),
            ('2027-07', '2027-07', '采购订单-单据生成PDF', '按钮权限：采购订单模块[单据生成PDF]按钮权限', '采购订单', 'CGDD _ DJSCPDF', 207),
            ('2027-08', '2027-08', '采购订单-打印', '按钮权限：采购订单模块[打印预览]和[直接打印]按钮权限', '采购订单', 'CGDD_DY', 208),
            ('2028', '2028', '销售订单', '模块权限：通过菜单进入销售订单模块的权限', '销售订单', 'XSDD', 100),
            ('2028-01', '2028-01', '销售订单-审核/取消审核', '按钮权限：销售订单模块[审核]按钮和[取消审核]按钮的权限', '销售订单', 'XSDD_SH_QXSH', 204),
            ('2028-02', '2028-02', '销售订单-生成销售出库单', '按钮权限：销售订单模块[生成销售出库单]按钮的权限', '销售订单', 'XSDD_SCXSCKD', 206),
            ('2028-03', '2028-03', '销售订单-新建销售订单', '按钮权限：销售订单模块[新建销售订单]按钮的权限', '销售订单', 'XSDD_XJXSDD', 201),
            ('2028-04', '2028-04', '销售订单-编辑销售订单', '按钮权限：销售订单模块[编辑销售订单]按钮的权限', '销售订单', 'XSDD_BJXSDD', 202),
            ('2028-05', '2028-05', '销售订单-删除销售订单', '按钮权限：销售订单模块[删除销售订单]按钮的权限', '销售订单', 'XSDD_SCXSDD', 203),
            ('2028-06', '2028-06', '销售订单-单据生成PDF', '按钮权限：销售订单模块[单据生成PDF]按钮的权限', '销售订单', 'XSDD_DJSCPDF', 207),
            ('2028-07', '2028-07', '销售订单-打印', '按钮权限：销售订单模块[打印预览]和[直接打印]按钮的权限', '销售订单', 'XSDD_DY', 208),
            ('2028-08', '2028-08', '销售订单-生成采购订单', '按钮权限：销售订单模块[生成采购订单]按钮的权限', '销售订单', 'XSDD_SCCGDD', 205),
            ('2028-09', '2028-09', '销售订单-关闭订单/取消关闭订单', '按钮权限：销售订单模块[关闭销售订单]和[取消销售订单关闭状态]按钮的权限', '销售订单', 'XSDD_GBDD', 209),
            ('2029', '2029', '商品品牌', '模块权限：通过菜单进入商品品牌模块的权限', '商品', 'SPPP', 600),
            ('2030-01', '2030-01', '商品构成-新增子商品', '按钮权限：商品模块[新增子商品]按钮权限', '商品', 'SPGC_XZZSP', 209),
            ('2030-02', '2030-02', '商品构成-编辑子商品', '按钮权限：商品模块[编辑子商品]按钮权限', '商品', 'SPGC_BJZSP', 210),
            ('2030-03', '2030-03', '商品构成-删除子商品', '按钮权限：商品模块[删除子商品]按钮权限', '商品', 'SPGC_SCZSP', 211),
            ('2031', '2031', '价格体系', '模块权限：通过菜单进入价格体系模块的权限', '商品', 'JGTX', 700),
            ('2031-01', '2031-01', '商品-设置商品价格体系', '按钮权限：商品模块[设置商品价格体系]按钮权限', '商品', 'JGTX', 701),
            ('2032', '2032', '销售合同', '模块权限：通过菜单进入销售合同模块的权限', '销售合同', 'XSHT', 100),
            ('2032-01', '2032-01', '销售合同-新建销售合同', '按钮权限：销售合同模块[新建销售合同]按钮的权限', '销售合同', 'XSHT_XJXSHT', 201),
            ('2032-02', '2032-02', '销售合同-编辑销售合同', '按钮权限：销售合同模块[编辑销售合同]按钮的权限', '销售合同', 'XSHT_BJXSHT', 202),
            ('2032-03', '2032-03', '销售合同-删除销售合同', '按钮权限：销售合同模块[删除销售合同]按钮的权限', '销售合同', 'XSHT_SCXSHT', 203),
            ('2032-04', '2032-04', '销售合同-审核/取消审核', '按钮权限：销售合同模块[审核]按钮和[取消审核]按钮的权限', '销售合同', 'XSHT_SH_QXSH', 204),
            ('2032-05', '2032-05', '销售合同-生成销售订单', '按钮权限：销售合同模块[生成销售订单]按钮的权限', '销售合同', 'XSHT_SCXSDD', 205),
            ('2032-06', '2032-06', '销售合同-单据生成PDF', '按钮权限：销售合同模块[单据生成PDF]按钮的权限', '销售合同', 'XSHT_DJSCPDF', 206),
            ('2032-07', '2032-07', '销售合同-打印', '按钮权限：销售合同模块[打印预览]和[直接打印]按钮的权限', '销售合同', 'XSHT_DY', 207),
            ('2033', '2033', '存货拆分', '模块权限：通过菜单进入存货拆分模块的权限', '存货拆分', 'CHCF', 100),
            ('2033-01', '2033-01', '存货拆分-新建拆分单', '按钮权限：存货拆分模块[新建拆分单]按钮的权限', '存货拆分', 'CHCFXJCFD', 201),
            ('2033-02', '2033-02', '存货拆分-编辑拆分单', '按钮权限：存货拆分模块[编辑拆分单]按钮的权限', '存货拆分', 'CHCFBJCFD', 202),
            ('2033-03', '2033-03', '存货拆分-删除拆分单', '按钮权限：存货拆分模块[删除拆分单]按钮的权限', '存货拆分', 'CHCFSCCFD', 203),
            ('2033-04', '2033-04', '存货拆分-提交拆分单', '按钮权限：存货拆分模块[提交拆分单]按钮的权限', '存货拆分', 'CHCFTJCFD', 204),
            ('2033-05', '2033-05', '存货拆分-单据生成PDF', '按钮权限：存货拆分模块[单据生成PDF]按钮的权限', '存货拆分', 'CHCFDJSCPDF', 205),
            ('2033-06', '2033-06', '存货拆分-打印', '按钮权限：存货拆分模块[打印预览]和[直接打印]按钮的权限', '存货拆分', 'CHCFDY', 206),
            ('2034', '2034', '工厂', '模块权限：通过菜单进入工厂模块的权限', '工厂', 'GC', 100),
            ('2034-01', '2034-01', '工厂在业务单据中的使用权限', '数据域权限：工厂在业务单据中的使用权限', '工厂', 'GCCYWDJZDSYQX', 301),
            ('2034-02', '2034-02', '工厂分类', '数据域权限：工厂模块中工厂分类的数据权限', '工厂', 'GCFL', 300),
            ('2034-03', '2034-03', '新增工厂分类', '按钮权限：工厂模块[新增工厂分类]按钮权限', '工厂', 'XZGYSFL', 201),
            ('2034-04', '2034-04', '编辑工厂分类', '按钮权限：工厂模块[编辑工厂分类]按钮权限', '工厂', 'BJGYSFL', 202),
            ('2034-05', '2034-05', '删除工厂分类', '按钮权限：工厂模块[删除工厂分类]按钮权限', '工厂', 'SCGYSFL', 203),
            ('2034-06', '2034-06', '新增工厂', '按钮权限：工厂模块[新增工厂]按钮权限', '工厂', 'XZGYS', 204),
            ('2034-07', '2034-07', '编辑工厂', '按钮权限：工厂模块[编辑工厂]按钮权限', '工厂', 'BJGYS', 205),
            ('2034-08', '2034-08', '删除工厂', '按钮权限：工厂模块[删除工厂]按钮权限', '工厂', 'SCGYS', 206),
            ('2035', '2035', '成品委托生产订单', '模块权限：通过菜单进入成品委托生产订单模块的权限', '成品委托生产订单', 'CPWTSCDD', 100),
            ('2035-01', '2035-01', '成品委托生产订单-新建成品委托生产订单', '按钮权限：成品委托生产订单模块[新建成品委托生产订单]按钮的权限', '成品委托生产订单', 'CPWTSCDDXJCPWTSCDD', 201),
            ('2035-02', '2035-02', '成品委托生产订单-编辑成品委托生产订单', '按钮权限：成品委托生产订单模块[编辑成品委托生产订单]按钮的权限', '成品委托生产订单', 'CPWTSCDDBJCPWTSCDD', 202),
            ('2035-03', '2035-03', '成品委托生产订单-删除成品委托生产订单', '按钮权限：成品委托生产订单模块[删除成品委托生产订单]按钮的权限', '成品委托生产订单', 'CPWTSCDDSCCPWTSCDD', 203),
            ('2035-04', '2035-04', '成品委托生产订单-审核/取消审核', '按钮权限：成品委托生产订单模块[审核]和[取消审核]按钮的权限', '成品委托生产订单', 'CPWTSCDDSHQXSH', 204),
            ('2035-05', '2035-05', '成品委托生产订单-生成成品委托生产入库单', '按钮权限：成品委托生产订单模块[生成成品委托生产入库单]按钮的权限', '成品委托生产订单', 'CPWTSCDDSCCPWTSCRKD', 205),
            ('2035-06', '2035-06', '成品委托生产订单-关闭/取消关闭成品委托生产订单', '按钮权限：成品委托生产订单模块[关闭成品委托生产订单]和[取消关闭成品委托生产订单]按钮的权限', '成品委托生产订单', 'CPWTSCDGBJCPWTSCDD', 206),
            ('2035-07', '2035-07', '成品委托生产订单-单据生成PDF', '按钮权限：成品委托生产订单模块[单据生成PDF]按钮的权限', '成品委托生产订单', 'CPWTSCDDDJSCPDF', 207),
            ('2035-08', '2035-08', '成品委托生产订单-打印', '按钮权限：成品委托生产订单模块[打印预览]和[直接打印]按钮的权限', '成品委托生产订单', 'CPWTSCDDDY', 208),
            ('2036', '2036', '成品委托生产入库', '模块权限：通过菜单进入成品委托生产入库模块的权限', '成品委托生产入库', 'CPWTSCRK', 100),
            ('2036-01', '2036-01', '成品委托生产入库-新建成品委托生产入库单', '按钮权限：成品委托生产入库模块[新建成品委托生产入库单]按钮的权限', '成品委托生产入库', 'CPWTSCRKXJCPWTSCRKD', 201),
            ('2036-02', '2036-02', '成品委托生产入库-编辑成品委托生产入库单', '按钮权限：成品委托生产入库模块[编辑成品委托生产入库单]按钮的权限', '成品委托生产入库', 'CPWTSCRKBJCPWTSCRKD', 202),
            ('2036-03', '2036-03', '成品委托生产入库-删除成品委托生产入库单', '按钮权限：成品委托生产入库模块[删除成品委托生产入库单]按钮的权限', '成品委托生产入库', 'CPWTSCRKSCCPWTSCRKD', 203),
            ('2036-04', '2036-04', '成品委托生产入库-提交入库', '按钮权限：成品委托生产入库模块[提交入库]按钮的权限', '成品委托生产入库', 'CPWTSCRKTJRK', 204),
            ('2036-05', '2036-05', '成品委托生产入库-单据生成PDF', '按钮权限：成品委托生产入库模块[单据生成PDF]按钮的权限', '成品委托生产入库', 'CPWTSCRKDJSCPDF', 205),
            ('2036-06', '2036-06', '成品委托生产入库-打印', '按钮权限：成品委托生产入库模块[打印预览]和[直接打印]按钮的权限', '成品委托生产入库', 'CPWTSCRKDY', 206),
            ('2037', '2037', '采购入库明细表', '模块权限：通过菜单进入采购入库明细表模块的权限', '采购报表', 'CGRKMXB', 100),
            ('2101', '2101', '会计科目', '模块权限：通过菜单进入会计科目模块的权限', '会计科目', 'KJKM', 100),
            ('2102', '2102', '银行账户', '模块权限：通过菜单进入银行账户模块的权限', '银行账户', 'YHZH', 100),
            ('2103', '2103', '会计期间', '模块权限：通过菜单进入会计期间模块的权限', '会计期间', 'KJQJ', 100),
            ('3101', '3101', '物料单位', '模块权限：通过菜单进入物料单位模块的权限', '物料', 'WLDW', 500);
            ";
    $db->execute($sql);
  }

  private function update_20200410_02()
  {
    // 本次更新：新增表t_material_unit
    $db = $this->db;

    $tableName = "t_material_unit";
    if (!$this->tableExists($db, $tableName)) {
      $sql = "CREATE TABLE IF NOT EXISTS `t_material_unit` (
                `id` varchar(255) NOT NULL,
                `name` varchar(255) NOT NULL,
                `data_org` varchar(255) DEFAULT NULL,
                `company_id` varchar(255) DEFAULT NULL,
                `code` varchar(255) DEFAULT NULL,
                `record_status` int(11) DEFAULT 1,
                PRIMARY KEY (`id`)
              ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
              ";
      $db->execute($sql);
    }
  }

  private function update_20200410_01()
  {
    // 本次更新：新增表t_raw_material_category、t_raw_material
    $db = $this->db;

    // t_raw_material_category
    $tableName = "t_raw_material_category";
    if (!$this->tableExists($db, $tableName)) {
      $sql = "CREATE TABLE IF NOT EXISTS `t_raw_material_category` (
                `id` varchar(255) NOT NULL,
                `code` varchar(255) NOT NULL,
                `name` varchar(255) NOT NULL,
                `parent_id` varchar(255) DEFAULT NULL,
                `full_name` varchar(1000) DEFAULT NULL,
                `data_org` varchar(255) DEFAULT NULL,
                `company_id` varchar(255) DEFAULT NULL,
                `tax_rate` decimal(19,2) DEFAULT NULL,
                PRIMARY KEY (`id`)
              ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
              ";
      $db->execute($sql);
    }

    // t_raw_material
    $tableName = "t_raw_material";
    if (!$this->tableExists($db, $tableName)) {
      $sql = "CREATE TABLE IF NOT EXISTS `t_raw_material` (
                `id` varchar(255) NOT NULL,
                `category_id` varchar(255) NOT NULL,
                `code` varchar(255) NOT NULL,
                `name` varchar(255) NOT NULL,
                `spec` varchar(255) NOT NULL,
                `unit_id` varchar(255) NOT NULL,
                `purchase_price` decimal(19, 2) DEFAULT NULL,
                `py` varchar(255) DEFAULT NULL,
                `spec_py` varchar(255) DEFAULT NULL,
                `data_org` varchar(255) DEFAULT NULL,
                `memo` varchar(500) DEFAULT NULL,
                `company_id` varchar(255) DEFAULT NULL,
                `record_status` int(11) DEFAULT 1000,
                `tax_rate` decimal(19,2) DEFAULT NULL,
                PRIMARY KEY (`id`)
              ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
              ";
      $db->execute($sql);
    }
  }

  private function update_20200403_01()
  {
    // 本次更新：新增表t_voucher_detail
    $db = $this->db;
    $tableName = "t_voucher_detail";
    if (!$this->tableExists($db, $tableName)) {
      $sql = "CREATE TABLE IF NOT EXISTS `t_voucher_detail` (
                `id` varchar(255) NOT NULL,
                `voucher_id` varchar(255) NOT NULL,
                `subject` varchar(255) NOT NULL,
                `summary` varchar(255) DEFAULT NULL,
                `debit` decimal(19, 2) DEFAULT NULL,
                `credit` decimal(19, 2) DEFAULT NULL,
                `show_order` int(11) NOT NULL,
                PRIMARY KEY (`id`)
              ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
              ";
      $db->execute($sql);
    }
  }

  private function update_20200402_01()
  {
    // 本次更新：新增表t_voucher
    $db = $this->db;
    $tableName = "t_voucher";
    if (!$this->tableExists($db, $tableName)) {
      $sql = "CREATE TABLE IF NOT EXISTS `t_voucher` (
                `id` varchar(255) NOT NULL,
                `v_dt` datetime NOT NULL,
                `ref` varchar(255) NOT NULL,
                `input_user_id` varchar(255) NOT NULL,
                `input_user_name` varchar(255) NOT NULL,
                `confirm_user_id` varchar(255) DEFAULT NULL,
                `confirm_user_name` varchar(255) DEFAULT NULL,
                `gl_user_id` varchar(255) DEFAULT NULL,
                `gl_user_name` varchar(255) DEFAULT NULL,
                `charge_user_id` varchar(255) DEFAULT NULL,
                `charge_user_name` varchar(255) DEFAULT NULL,
                `cash_user_id` varchar(255) DEFAULT NULL,
                `cash_user_name` varchar(255) DEFAULT NULL,
                `bill_number` int(11) NOT NULL DEFAULT 0,
                `company_id` varchar(255) NOT NULL,
                PRIMARY KEY (`id`)
              ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
              ";
      $db->execute($sql);
    }
  }

  private function update_20200313_01()
  {
    // 本次更新：新增模块采购入库明细表
    $db = $this->db;

    // fid
    $sql = "TRUNCATE TABLE `t_fid`;
            INSERT INTO `t_fid` (`fid`, `name`, `py`, `memo`) VALUES
            ('-7999', '自定义表单', 'ZDYBD', ''),
            ('-7994', '系统数据字典', 'XTSJZD', ''),
            ('-7995', '主菜单维护', 'ZCDWH', ''),
            ('-7996', '码表设置', 'MBSZ', ''),
            ('-7997', '表单视图开发助手', 'BDSTKFZS', ''),
            ('-9999', '重新登录', '', ''),
            ('-9997', '首页', 'SY', ''),
            ('-9996', '修改我的密码', 'XGWDMM', ''),
            ('-9995', '帮助', 'BZ', ''),
            ('-9994', '关于', 'GY', ''),
            ('-9993', '购买商业服务', '', ''),
            ('-8999', '用户管理', 'YHGL', ''),
            ('-8999-01', '组织机构在业务单据中的使用权限', '', ''),
            ('-8999-02', '业务员在业务单据中的使用权限', '', ''),
            ('-8997', '业务日志', 'YWRZ', ''),
            ('-8996', '权限管理', 'QXGL', ''),
            ('1001', '商品', 'SP', ''),
            ('1001-01', '商品在业务单据中的使用权限', '', ''),
            ('1001-02', '商品分类', 'SPFL', ''),
            ('1002', '商品计量单位', 'SPJLDW', ''),
            ('1003', '仓库', 'CK', ''),
            ('1003-01', '仓库在业务单据中的使用权限', '', ''),
            ('1004', '供应商档案', 'GYSDA', ''),
            ('1004-01', '供应商档案在业务单据中的使用权限', '', ''),
            ('1004-02', '供应商分类', '', ''),
            ('1007', '客户资料', 'KHZL', ''),
            ('1007-01', '客户资料在业务单据中的使用权限', '', ''),
            ('1007-02', '客户分类', '', ''),
            ('2000', '库存建账', 'KCJZ', ''),
            ('2001', '采购入库', 'CGRK', ''),
            ('2001-01', '采购入库-新建采购入库单', '', ''),
            ('2001-02', '采购入库-编辑采购入库单', '', ''),
            ('2001-03', '采购入库-删除采购入库单', '', ''),
            ('2001-04', '采购入库-提交入库', '', ''),
            ('2001-05', '采购入库-单据生成PDF', '', ''),
            ('2001-06', '采购入库-采购单价和金额可见', '', ''),
            ('2001-07', '采购入库-打印', '', ''),
            ('2002', '销售出库', 'XSCK', ''),
            ('2002-01', '销售出库-销售出库单允许编辑销售单价', '', ''),
            ('2002-02', '销售出库-新建销售出库单', '', ''),
            ('2002-03', '销售出库-编辑销售出库单', '', ''),
            ('2002-04', '销售出库-删除销售出库单', '', ''),
            ('2002-05', '销售出库-提交出库', '', ''),
            ('2002-06', '销售出库-单据生成PDF', '', ''),
            ('2002-07', '销售出库-打印', '', ''),
            ('2003', '库存账查询', 'KCZCX', ''),
            ('2004', '应收账款管理', 'YSZKGL', ''),
            ('2005', '应付账款管理', 'YFZKGL', ''),
            ('2006', '销售退货入库', 'XSTHRK', ''),
            ('2006-01', '销售退货入库-新建销售退货入库单', '', ''),
            ('2006-02', '销售退货入库-编辑销售退货入库单', '', ''),
            ('2006-03', '销售退货入库-删除销售退货入库单', '', ''),
            ('2006-04', '销售退货入库-提交入库', '', ''),
            ('2006-05', '销售退货入库-单据生成PDF', '', ''),
            ('2006-06', '销售退货入库-打印', '', ''),
            ('2007', '采购退货出库', 'CGTHCK', ''),
            ('2007-01', '采购退货出库-新建采购退货出库单', '', ''),
            ('2007-02', '采购退货出库-编辑采购退货出库单', '', ''),
            ('2007-03', '采购退货出库-删除采购退货出库单', '', ''),
            ('2007-04', '采购退货出库-提交采购退货出库单', '', ''),
            ('2007-05', '采购退货出库-单据生成PDF', '', ''),
            ('2007-06', '采购退货出库-打印', '', ''),
            ('2008', '业务设置', 'YWSZ', ''),
            ('2009', '库间调拨', 'KJDB', ''),
            ('2009-01', '库间调拨-新建调拨单', '', ''),
            ('2009-02', '库间调拨-编辑调拨单', '', ''),
            ('2009-03', '库间调拨-删除调拨单', '', ''),
            ('2009-04', '库间调拨-提交调拨单', '', ''),
            ('2009-05', '库间调拨-单据生成PDF', '', ''),
            ('2009-06', '库间调拨-打印', '', ''),
            ('2010', '库存盘点', 'KCPD', ''),
            ('2010-01', '库存盘点-新建盘点单', '', ''),
            ('2010-02', '库存盘点-编辑盘点单', '', ''),
            ('2010-03', '库存盘点-删除盘点单', '', ''),
            ('2010-04', '库存盘点-提交盘点单', '', ''),
            ('2010-05', '库存盘点-单据生成PDF', '', ''),
            ('2010-06', '库存盘点-打印', '', ''),
            ('2011-01', '首页-销售看板', '', ''),
            ('2011-02', '首页-库存看板', '', ''),
            ('2011-03', '首页-采购看板', '', ''),
            ('2011-04', '首页-资金看板', '', ''),
            ('2012', '报表-销售日报表(按商品汇总)', 'BBXSRBBASPHZ', ''),
            ('2013', '报表-销售日报表(按客户汇总)', 'BBXSRBBAKHHZ', ''),
            ('2014', '报表-销售日报表(按仓库汇总)', 'BBXSRBBACKHZ', ''),
            ('2015', '报表-销售日报表(按业务员汇总)', 'BBXSRBBAYWYHZ', ''),
            ('2016', '报表-销售月报表(按商品汇总)', 'BBXSYBBASPHZ', ''),
            ('2017', '报表-销售月报表(按客户汇总)', 'BBXSYBBAKHHZ', ''),
            ('2018', '报表-销售月报表(按仓库汇总)', 'BBXSYBBACKHZ', ''),
            ('2019', '报表-销售月报表(按业务员汇总)', 'BBXSYBBAYWYHZ', ''),
            ('2020', '报表-安全库存明细表', 'BBAQKCMXB', ''),
            ('2021', '报表-应收账款账龄分析表', 'BBYSZKZLFXB', ''),
            ('2022', '报表-应付账款账龄分析表', 'BBYFZKZLFXB', ''),
            ('2023', '报表-库存超上限明细表', 'BBKCCSXMXB', ''),
            ('2024', '现金收支查询', 'XJSZCX', ''),
            ('2025', '预收款管理', 'YSKGL', ''),
            ('2026', '预付款管理', 'YFKGL', ''),
            ('2027', '采购订单', 'CGDD', ''),
            ('2027-01', '采购订单-审核/取消审核', '', ''),
            ('2027-02', '采购订单-生成采购入库单', '', ''),
            ('2027-03', '采购订单-新建采购订单', '', ''),
            ('2027-04', '采购订单-编辑采购订单', '', ''),
            ('2027-05', '采购订单-删除采购订单', '', ''),
            ('2027-06', '采购订单-关闭订单/取消关闭订单', '', ''),
            ('2027-07', '采购订单-单据生成PDF', '', ''),
            ('2027-08', '采购订单-打印', '', ''),
            ('2028', '销售订单', 'XSDD', ''),
            ('2028-01', '销售订单-审核/取消审核', '', ''),
            ('2028-02', '销售订单-生成销售出库单', '', ''),
            ('2028-03', '销售订单-新建销售订单', '', ''),
            ('2028-04', '销售订单-编辑销售订单', '', ''),
            ('2028-05', '销售订单-删除销售订单', '', ''),
            ('2028-06', '销售订单-单据生成PDF', '', ''),
            ('2028-07', '销售订单-打印', '', ''),
            ('2028-08', '销售订单-生成采购订单', '', ''),
            ('2028-09', '销售订单-关闭订单/取消关闭订单', '', ''),
            ('2029', '商品品牌', 'SPPP', ''),
            ('2030-01', '商品构成-新增子商品', '', ''),
            ('2030-02', '商品构成-编辑子商品', '', ''),
            ('2030-03', '商品构成-删除子商品', '', ''),
            ('2031', '价格体系', 'JGTX', ''),
            ('2031-01', '商品-设置商品价格体系', '', ''),
            ('2032', '销售合同', 'XSHT', ''),
            ('2032-01', '销售合同-新建销售合同', '', ''),
            ('2032-02', '销售合同-编辑销售合同', '', ''),
            ('2032-03', '销售合同-删除销售合同', '', ''),
            ('2032-04', '销售合同-审核/取消审核', '', ''),
            ('2032-05', '销售合同-生成销售订单', '', ''),
            ('2032-06', '销售合同-单据生成PDF', '', ''),
            ('2032-07', '销售合同-打印', '', ''),
            ('2033', '存货拆分', 'CHCF', ''),
            ('2033-01', '存货拆分-新建拆分单', '', ''),
            ('2033-02', '存货拆分-编辑拆分单', '', ''),
            ('2033-03', '存货拆分-删除拆分单', '', ''),
            ('2033-04', '存货拆分-提交拆分单', '', ''),
            ('2033-05', '存货拆分-单据生成PDF', '', ''),
            ('2033-06', '存货拆分-打印', '', ''),
            ('2034', '工厂', 'GC', ''),
            ('2034-01', '工厂在业务单据中的使用权限', '', ''),
            ('2034-02', '工厂分类', '', ''),
            ('2034-03', '工厂-新增工厂分类', '', ''),
            ('2034-04', '工厂-编辑工厂分类', '', ''),
            ('2034-05', '工厂-删除工厂分类', '', ''),
            ('2034-06', '工厂-新增工厂', '', ''),
            ('2034-07', '工厂-编辑工厂', '', ''),
            ('2034-08', '工厂-删除工厂', '', ''),
            ('2035', '成品委托生产订单', 'CPWTSCDD', ''),
            ('2035-01', '成品委托生产订单-新建成品委托生产订单', '', ''),
            ('2035-02', '成品委托生产订单-编辑成品委托生产订单', '', ''),
            ('2035-03', '成品委托生产订单-删除成品委托生产订单', '', ''),
            ('2035-04', '成品委托生产订单-提交成品委托生产订单', '', ''),
            ('2035-05', '成品委托生产订单-审核/取消审核成品委托生产入库单', '', ''),
            ('2035-06', '成品委托生产订单-关闭/取消关闭成品委托生产订单', '', ''),
            ('2035-07', '成品委托生产订单-单据生成PDF', '', ''),
            ('2035-08', '成品委托生产订单-打印', '', ''),
            ('2036', '成品委托生产入库', 'CPWTSCRK', ''),
            ('2036-01', '成品委托生产入库-新建成品委托生产入库单', '', ''),
            ('2036-02', '成品委托生产入库-编辑成品委托生产入库单', '', ''),
            ('2036-03', '成品委托生产入库-删除成品委托生产入库单', '', ''),
            ('2036-04', '成品委托生产入库-提交入库', '', ''),
            ('2036-05', '成品委托生产入库-单据生成PDF', '', ''),
            ('2036-06', '成品委托生产入库-打印', '', ''),
            ('2037', '报表-采购入库明细表', '', ''),
            ('2101', '会计科目', 'KJKM', ''),
            ('2102', '银行账户', 'YHZH', ''),
            ('2103', '会计期间', 'KJQJ', '');
            ";
    $db->execute($sql);

    // 主菜单
    $sql = "TRUNCATE TABLE `t_menu_item`;
            INSERT INTO `t_menu_item` (`id`, `caption`, `fid`, `parent_id`, `show_order`, `py`, `memo`) VALUES
            ('01', '文件', NULL, NULL, 1, '', ''),
            ('0101', '首页', '-9997', '01', 1, 'SY', ''),
            ('0102', '重新登录', '-9999', '01', 2, '', ''),
            ('0103', '修改我的密码', '-9996', '01', 3, 'XGWDMM', ''),
            ('02', '采购', NULL, NULL, 2, '', ''),
            ('0200', '采购订单', '2027', '02', 0, 'CGDD', ''),
            ('0201', '采购入库', '2001', '02', 1, 'CGRK', ''),
            ('0202', '采购退货出库', '2007', '02', 2, 'CGTHCK', ''),
            ('03', '库存', NULL, NULL, 3, '', ''),
            ('0301', '库存账查询', '2003', '03', 1, 'KCZCX', ''),
            ('0302', '库存建账', '2000', '03', 2, 'KCJZ', ''),
            ('0303', '库间调拨', '2009', '03', 3, 'KJDB', ''),
            ('0304', '库存盘点', '2010', '03', 4, 'KCPD', ''),
            ('12', '加工', NULL, NULL, 4, '', ''),
            ('1201', '存货拆分', '2033', '12', 1, 'CHCF', ''),
            ('1202', '成品委托生产', NULL, '12', 2, '', ''),
            ('120201', '成品委托生产订单', '2035', '1202', 1, 'CPWTSCDD', ''),
            ('120202', '成品委托生产入库', '2036', '1202', 2, 'CPWTSCRK', ''),
            ('04', '销售', NULL, NULL, 5, '', ''),
            ('0401', '销售合同', '2032', '04', 1, 'XSHT', ''),
            ('0402', '销售订单', '2028', '04', 2, 'XSDD', ''),
            ('0403', '销售出库', '2002', '04', 3, 'XSCK', ''),
            ('0404', '销售退货入库', '2006', '04', 4, 'XSTHRK', ''),
            ('05', '客户关系', NULL, NULL, 6, '', ''),
            ('0501', '客户资料', '1007', '05', 1, 'KHZL', ''),
            ('06', '资金', NULL, NULL, 7, '', ''),
            ('0601', '应收账款管理', '2004', '06', 1, 'YSZKGL', ''),
            ('0602', '应付账款管理', '2005', '06', 2, 'YFZKGL', ''),
            ('0603', '现金收支查询', '2024', '06', 3, 'XJSZCX', ''),
            ('0604', '预收款管理', '2025', '06', 4, 'YSKGL', ''),
            ('0605', '预付款管理', '2026', '06', 5, 'YFKGL', ''),
            ('07', '报表', NULL, NULL, 8, '', ''),
            ('0700', '采购报表', NULL, '07', 0, '', ''),
            ('070001', '采购入库明细表', '2037', '0700', 1, 'CGRKMXB', ''),
            ('0701', '销售日报表', NULL, '07', 1, '', ''),
            ('070101', '销售日报表(按商品汇总)', '2012', '0701', 1, 'XSRBBASPHZ', ''),
            ('070102', '销售日报表(按客户汇总)', '2013', '0701', 2, 'XSRBBAKHHZ', ''),
            ('070103', '销售日报表(按仓库汇总)', '2014', '0701', 3, 'XSRBBACKHZ', ''),
            ('070104', '销售日报表(按业务员汇总)', '2015', '0701', 4, 'XSRBBAYWYHZ', ''),
            ('0702', '销售月报表', NULL, '07', 2, '', ''),
            ('070201', '销售月报表(按商品汇总)', '2016', '0702', 1, 'XSYBBASPHZ', ''),
            ('070202', '销售月报表(按客户汇总)', '2017', '0702', 2, 'XSYBBAKHHZ', ''),
            ('070203', '销售月报表(按仓库汇总)', '2018', '0702', 3, 'XSYBBACKHZ', ''),
            ('070204', '销售月报表(按业务员汇总)', '2019', '0702', 4, 'XSYBBAYWYHZ', ''),
            ('0703', '库存报表', NULL, '07', 3, '', ''),
            ('070301', '安全库存明细表', '2020', '0703', 1, 'AQKCMXB', ''),
            ('070302', '库存超上限明细表', '2023', '0703', 2, 'KCCSXMXB', ''),
            ('0706', '资金报表', NULL, '07', 6, '', ''),
            ('070601', '应收账款账龄分析表', '2021', '0706', 1, 'YSZKZLFXB', ''),
            ('070602', '应付账款账龄分析表', '2022', '0706', 2, 'YFZKZLFXB', ''),
            ('11', '财务总账', NULL, NULL, 9, '', ''),
            ('1101', '基础数据', NULL, '11', 1, '', ''),
            ('110101', '会计科目', '2101', '1101', 1, 'KJKM', ''),
            ('110102', '银行账户', '2102', '1101', 2, 'YHZH', ''),
            ('110103', '会计期间', '2103', '1101', 3, 'KJQJ', ''),
            ('08', '基础数据', NULL, NULL, 10, '', ''),
            ('0801', '商品', NULL, '08', 1, '', ''),
            ('080101', '商品', '1001', '0801', 1, 'SP', ''),
            ('080102', '商品计量单位', '1002', '0801', 2, 'SPJLDW', ''),
            ('080103', '商品品牌', '2029', '0801', 3, '', 'SPPP'),
            ('080104', '价格体系', '2031', '0801', 4, '', 'JGTX'),
            ('0803', '仓库', '1003', '08', 3, 'CK', ''),
            ('0804', '供应商档案', '1004', '08', 4, 'GYSDA', ''),
            ('0805', '工厂', '2034', '08', 5, 'GC', ''),
            ('09', '系统管理', NULL, NULL, 11, '', ''),
            ('0901', '用户管理', '-8999', '09', 1, 'YHGL', ''),
            ('0902', '权限管理', '-8996', '09', 2, 'QXGL', ''),
            ('0903', '业务日志', '-8997', '09', 3, 'YWRZ', ''),
            ('0904', '业务设置', '2008', '09', 4, '', 'YWSZ'),
            ('0905', '二次开发', NULL, '09', 5, '', ''),
            ('090501', '码表设置', '-7996', '0905', 1, 'MBSZ', ''),
            ('090502', '自定义表单', '-7999', '0905', 2, 'ZDYBD', ''),
            ('090503', '表单视图开发助手', '-7997', '0905', 3, 'BDSTKFZS', ''),
            ('090504', '主菜单维护', '-7995', '0905', 4, 'ZCDWH', ''),
            ('090505', '系统数据字典', '-7994', '0905', 5, 'XTSJZD', ''),
            ('10', '帮助', NULL, NULL, 12, '', ''),
            ('1001', '使用帮助', '-9995', '10', 1, 'SYBZ', ''),
            ('1003', '关于', '-9994', '10', 3, 'GY', '');
            ";
    $db->execute($sql);

    // 权限
    $sql = "TRUNCATE TABLE `t_permission`;
            INSERT INTO `t_permission` (`id`, `fid`, `name`, `note`, `category`, `py`, `show_order`) VALUES
            ('-7999', '-7999', '自定义表单', '模块权限：通过菜单进入自定义表单模块的权限', '自定义表单', 'ZDYBD', 100),
            ('-7994', '-7994', '系统数据字典', '模块权限：通过菜单进入系统数据字典模块的权限', '系统数据字典', 'XTSJZD', 100),
            ('-7995', '-7995', '主菜单维护', '模块权限：通过菜单进入主菜单维护模块的权限', '主菜单维护', 'ZCDWH', 100),
            ('-7996', '-7996', '码表设置', '模块权限：通过菜单进入码表设置模块的权限', '码表设置', 'MBSZ', 100),
            ('-8996', '-8996', '权限管理', '模块权限：通过菜单进入权限管理模块的权限', '权限管理', 'QXGL', 100),
            ('-8996-01', '-8996-01', '权限管理-新增角色', '按钮权限：权限管理模块[新增角色]按钮权限', '权限管理', 'QXGL_XZJS', 201),
            ('-8996-02', '-8996-02', '权限管理-编辑角色', '按钮权限：权限管理模块[编辑角色]按钮权限', '权限管理', 'QXGL_BJJS', 202),
            ('-8996-03', '-8996-03', '权限管理-删除角色', '按钮权限：权限管理模块[删除角色]按钮权限', '权限管理', 'QXGL_SCJS', 203),
            ('-8997', '-8997', '业务日志', '模块权限：通过菜单进入业务日志模块的权限', '系统管理', 'YWRZ', 100),
            ('-8999', '-8999', '用户管理', '模块权限：通过菜单进入用户管理模块的权限', '用户管理', 'YHGL', 100),
            ('-8999-01', '-8999-01', '组织机构在业务单据中的使用权限', '数据域权限：组织机构在业务单据中的使用权限', '用户管理', 'ZZJGZYWDJZDSYQX', 300),
            ('-8999-02', '-8999-02', '业务员在业务单据中的使用权限', '数据域权限：业务员在业务单据中的使用权限', '用户管理', 'YWYZYWDJZDSYQX', 301),
            ('-8999-03', '-8999-03', '用户管理-新增组织机构', '按钮权限：用户管理模块[新增组织机构]按钮权限', '用户管理', 'YHGL_XZZZJG', 201),
            ('-8999-04', '-8999-04', '用户管理-编辑组织机构', '按钮权限：用户管理模块[编辑组织机构]按钮权限', '用户管理', 'YHGL_BJZZJG', 202),
            ('-8999-05', '-8999-05', '用户管理-删除组织机构', '按钮权限：用户管理模块[删除组织机构]按钮权限', '用户管理', 'YHGL_SCZZJG', 203),
            ('-8999-06', '-8999-06', '用户管理-新增用户', '按钮权限：用户管理模块[新增用户]按钮权限', '用户管理', 'YHGL_XZYH', 204),
            ('-8999-07', '-8999-07', '用户管理-编辑用户', '按钮权限：用户管理模块[编辑用户]按钮权限', '用户管理', 'YHGL_BJYH', 205),
            ('-8999-08', '-8999-08', '用户管理-删除用户', '按钮权限：用户管理模块[删除用户]按钮权限', '用户管理', 'YHGL_SCYH', 206),
            ('-8999-09', '-8999-09', '用户管理-修改用户密码', '按钮权限：用户管理模块[修改用户密码]按钮权限', '用户管理', 'YHGL_XGYHMM', 207),
            ('1001', '1001', '商品', '模块权限：通过菜单进入商品模块的权限', '商品', 'SP', 100),
            ('1001-01', '1001-01', '商品在业务单据中的使用权限', '数据域权限：商品在业务单据中的使用权限', '商品', 'SPZYWDJZDSYQX', 300),
            ('1001-02', '1001-02', '商品分类', '数据域权限：商品模块中商品分类的数据权限', '商品', 'SPFL', 301),
            ('1001-03', '1001-03', '新增商品分类', '按钮权限：商品模块[新增商品分类]按钮权限', '商品', 'XZSPFL', 201),
            ('1001-04', '1001-04', '编辑商品分类', '按钮权限：商品模块[编辑商品分类]按钮权限', '商品', 'BJSPFL', 202),
            ('1001-05', '1001-05', '删除商品分类', '按钮权限：商品模块[删除商品分类]按钮权限', '商品', 'SCSPFL', 203),
            ('1001-06', '1001-06', '新增商品', '按钮权限：商品模块[新增商品]按钮权限', '商品', 'XZSP', 204),
            ('1001-07', '1001-07', '编辑商品', '按钮权限：商品模块[编辑商品]按钮权限', '商品', 'BJSP', 205),
            ('1001-08', '1001-08', '删除商品', '按钮权限：商品模块[删除商品]按钮权限', '商品', 'SCSP', 206),
            ('1001-09', '1001-09', '导入商品', '按钮权限：商品模块[导入商品]按钮权限', '商品', 'DRSP', 207),
            ('1001-10', '1001-10', '设置商品安全库存', '按钮权限：商品模块[设置安全库存]按钮权限', '商品', 'SZSPAQKC', 208),
            ('1001-11', '1001-11', '导出Excel', '按钮权限：商品模块[导出Excel]按钮权限', '商品', 'DCEXCEL', 209),
            ('1002', '1002', '商品计量单位', '模块权限：通过菜单进入商品计量单位模块的权限', '商品', 'SPJLDW', 500),
            ('1003', '1003', '仓库', '模块权限：通过菜单进入仓库的权限', '仓库', 'CK', 100),
            ('1003-01', '1003-01', '仓库在业务单据中的使用权限', '数据域权限：仓库在业务单据中的使用权限', '仓库', 'CKZYWDJZDSYQX', 300),
            ('1003-02', '1003-02', '新增仓库', '按钮权限：仓库模块[新增仓库]按钮权限', '仓库', 'XZCK', 201),
            ('1003-03', '1003-03', '编辑仓库', '按钮权限：仓库模块[编辑仓库]按钮权限', '仓库', 'BJCK', 202),
            ('1003-04', '1003-04', '删除仓库', '按钮权限：仓库模块[删除仓库]按钮权限', '仓库', 'SCCK', 203),
            ('1003-05', '1003-05', '修改仓库数据域', '按钮权限：仓库模块[修改数据域]按钮权限', '仓库', 'XGCKSJY', 204),
            ('1004', '1004', '供应商档案', '模块权限：通过菜单进入供应商档案的权限', '供应商管理', 'GYSDA', 100),
            ('1004-01', '1004-01', '供应商档案在业务单据中的使用权限', '数据域权限：供应商档案在业务单据中的使用权限', '供应商管理', 'GYSDAZYWDJZDSYQX', 301),
            ('1004-02', '1004-02', '供应商分类', '数据域权限：供应商档案模块中供应商分类的数据权限', '供应商管理', 'GYSFL', 300),
            ('1004-03', '1004-03', '新增供应商分类', '按钮权限：供应商档案模块[新增供应商分类]按钮权限', '供应商管理', 'XZGYSFL', 201),
            ('1004-04', '1004-04', '编辑供应商分类', '按钮权限：供应商档案模块[编辑供应商分类]按钮权限', '供应商管理', 'BJGYSFL', 202),
            ('1004-05', '1004-05', '删除供应商分类', '按钮权限：供应商档案模块[删除供应商分类]按钮权限', '供应商管理', 'SCGYSFL', 203),
            ('1004-06', '1004-06', '新增供应商', '按钮权限：供应商档案模块[新增供应商]按钮权限', '供应商管理', 'XZGYS', 204),
            ('1004-07', '1004-07', '编辑供应商', '按钮权限：供应商档案模块[编辑供应商]按钮权限', '供应商管理', 'BJGYS', 205),
            ('1004-08', '1004-08', '删除供应商', '按钮权限：供应商档案模块[删除供应商]按钮权限', '供应商管理', 'SCGYS', 206),
            ('1007', '1007', '客户资料', '模块权限：通过菜单进入客户资料模块的权限', '客户管理', 'KHZL', 100),
            ('1007-01', '1007-01', '客户资料在业务单据中的使用权限', '数据域权限：客户资料在业务单据中的使用权限', '客户管理', 'KHZLZYWDJZDSYQX', 300),
            ('1007-02', '1007-02', '客户分类', '数据域权限：客户档案模块中客户分类的数据权限', '客户管理', 'KHFL', 301),
            ('1007-03', '1007-03', '新增客户分类', '按钮权限：客户资料模块[新增客户分类]按钮权限', '客户管理', 'XZKHFL', 201),
            ('1007-04', '1007-04', '编辑客户分类', '按钮权限：客户资料模块[编辑客户分类]按钮权限', '客户管理', 'BJKHFL', 202),
            ('1007-05', '1007-05', '删除客户分类', '按钮权限：客户资料模块[删除客户分类]按钮权限', '客户管理', 'SCKHFL', 203),
            ('1007-06', '1007-06', '新增客户', '按钮权限：客户资料模块[新增客户]按钮权限', '客户管理', 'XZKH', 204),
            ('1007-07', '1007-07', '编辑客户', '按钮权限：客户资料模块[编辑客户]按钮权限', '客户管理', 'BJKH', 205),
            ('1007-08', '1007-08', '删除客户', '按钮权限：客户资料模块[删除客户]按钮权限', '客户管理', 'SCKH', 206),
            ('1007-09', '1007-09', '导入客户', '按钮权限：客户资料模块[导入客户]按钮权限', '客户管理', 'DRKH', 207),
            ('2000', '2000', '库存建账', '模块权限：通过菜单进入库存建账模块的权限', '库存建账', 'KCJZ', 100),
            ('2001', '2001', '采购入库', '模块权限：通过菜单进入采购入库模块的权限', '采购入库', 'CGRK', 100),
            ('2001-01', '2001-01', '采购入库-新建采购入库单', '按钮权限：采购入库模块[新建采购入库单]按钮权限', '采购入库', 'CGRK_XJCGRKD', 201),
            ('2001-02', '2001-02', '采购入库-编辑采购入库单', '按钮权限：采购入库模块[编辑采购入库单]按钮权限', '采购入库', 'CGRK_BJCGRKD', 202),
            ('2001-03', '2001-03', '采购入库-删除采购入库单', '按钮权限：采购入库模块[删除采购入库单]按钮权限', '采购入库', 'CGRK_SCCGRKD', 203),
            ('2001-04', '2001-04', '采购入库-提交入库', '按钮权限：采购入库模块[提交入库]按钮权限', '采购入库', 'CGRK_TJRK', 204),
            ('2001-05', '2001-05', '采购入库-单据生成PDF', '按钮权限：采购入库模块[单据生成PDF]按钮权限', '采购入库', 'CGRK_DJSCPDF', 205),
            ('2001-06', '2001-06', '采购入库-采购单价和金额可见', '字段权限：采购入库单的采购单价和金额可以被用户查看', '采购入库', 'CGRK_CGDJHJEKJ', 206),
            ('2001-07', '2001-07', '采购入库-打印', '按钮权限：采购入库模块[打印预览]和[直接打印]按钮权限', '采购入库', 'CGRK_DY', 207),
            ('2002', '2002', '销售出库', '模块权限：通过菜单进入销售出库模块的权限', '销售出库', 'XSCK', 100),
            ('2002-01', '2002-01', '销售出库-销售出库单允许编辑销售单价', '功能权限：销售出库单允许编辑销售单价', '销售出库', 'XSCKDYXBJXSDJ', 101),
            ('2002-02', '2002-02', '销售出库-新建销售出库单', '按钮权限：销售出库模块[新建销售出库单]按钮权限', '销售出库', 'XSCK_XJXSCKD', 201),
            ('2002-03', '2002-03', '销售出库-编辑销售出库单', '按钮权限：销售出库模块[编辑销售出库单]按钮权限', '销售出库', 'XSCK_BJXSCKD', 202),
            ('2002-04', '2002-04', '销售出库-删除销售出库单', '按钮权限：销售出库模块[删除销售出库单]按钮权限', '销售出库', 'XSCK_SCXSCKD', 203),
            ('2002-05', '2002-05', '销售出库-提交出库', '按钮权限：销售出库模块[提交出库]按钮权限', '销售出库', 'XSCK_TJCK', 204),
            ('2002-06', '2002-06', '销售出库-单据生成PDF', '按钮权限：销售出库模块[单据生成PDF]按钮权限', '销售出库', 'XSCK_DJSCPDF', 205),
            ('2002-07', '2002-07', '销售出库-打印', '按钮权限：销售出库模块[打印预览]和[直接打印]按钮权限', '销售出库', 'XSCK_DY', 207),
            ('2003', '2003', '库存账查询', '模块权限：通过菜单进入库存账查询模块的权限', '库存账查询', 'KCZCX', 100),
            ('2004', '2004', '应收账款管理', '模块权限：通过菜单进入应收账款管理模块的权限', '应收账款管理', 'YSZKGL', 100),
            ('2005', '2005', '应付账款管理', '模块权限：通过菜单进入应付账款管理模块的权限', '应付账款管理', 'YFZKGL', 100),
            ('2006', '2006', '销售退货入库', '模块权限：通过菜单进入销售退货入库模块的权限', '销售退货入库', 'XSTHRK', 100),
            ('2006-01', '2006-01', '销售退货入库-新建销售退货入库单', '按钮权限：销售退货入库模块[新建销售退货入库单]按钮权限', '销售退货入库', 'XSTHRK_XJXSTHRKD', 201),
            ('2006-02', '2006-02', '销售退货入库-编辑销售退货入库单', '按钮权限：销售退货入库模块[编辑销售退货入库单]按钮权限', '销售退货入库', 'XSTHRK_BJXSTHRKD', 202),
            ('2006-03', '2006-03', '销售退货入库-删除销售退货入库单', '按钮权限：销售退货入库模块[删除销售退货入库单]按钮权限', '销售退货入库', 'XSTHRK_SCXSTHRKD', 203),
            ('2006-04', '2006-04', '销售退货入库-提交入库', '按钮权限：销售退货入库模块[提交入库]按钮权限', '销售退货入库', 'XSTHRK_TJRK', 204),
            ('2006-05', '2006-05', '销售退货入库-单据生成PDF', '按钮权限：销售退货入库模块[单据生成PDF]按钮权限', '销售退货入库', 'XSTHRK_DJSCPDF', 205),
            ('2006-06', '2006-06', '销售退货入库-打印', '按钮权限：销售退货入库模块[打印预览]和[直接打印]按钮权限', '销售退货入库', 'XSTHRK_DY', 206),
            ('2007', '2007', '采购退货出库', '模块权限：通过菜单进入采购退货出库模块的权限', '采购退货出库', 'CGTHCK', 100),
            ('2007-01', '2007-01', '采购退货出库-新建采购退货出库单', '按钮权限：采购退货出库模块[新建采购退货出库单]按钮权限', '采购退货出库', 'CGTHCK_XJCGTHCKD', 201),
            ('2007-02', '2007-02', '采购退货出库-编辑采购退货出库单', '按钮权限：采购退货出库模块[编辑采购退货出库单]按钮权限', '采购退货出库', 'CGTHCK_BJCGTHCKD', 202),
            ('2007-03', '2007-03', '采购退货出库-删除采购退货出库单', '按钮权限：采购退货出库模块[删除采购退货出库单]按钮权限', '采购退货出库', 'CGTHCK_SCCGTHCKD', 203),
            ('2007-04', '2007-04', '采购退货出库-提交采购退货出库单', '按钮权限：采购退货出库模块[提交采购退货出库单]按钮权限', '采购退货出库', 'CGTHCK_TJCGTHCKD', 204),
            ('2007-05', '2007-05', '采购退货出库-单据生成PDF', '按钮权限：采购退货出库模块[单据生成PDF]按钮权限', '采购退货出库', 'CGTHCK_DJSCPDF', 205),
            ('2007-06', '2007-06', '采购退货出库-打印', '按钮权限：采购退货出库模块[打印预览]和[直接打印]按钮权限', '采购退货出库', 'CGTHCK_DY', 206),
            ('2008', '2008', '业务设置', '模块权限：通过菜单进入业务设置模块的权限', '系统管理', 'YWSZ', 100),
            ('2009', '2009', '库间调拨', '模块权限：通过菜单进入库间调拨模块的权限', '库间调拨', 'KJDB', 100),
            ('2009-01', '2009-01', '库间调拨-新建调拨单', '按钮权限：库间调拨模块[新建调拨单]按钮权限', '库间调拨', 'KJDB_XJDBD', 201),
            ('2009-02', '2009-02', '库间调拨-编辑调拨单', '按钮权限：库间调拨模块[编辑调拨单]按钮权限', '库间调拨', 'KJDB_BJDBD', 202),
            ('2009-03', '2009-03', '库间调拨-删除调拨单', '按钮权限：库间调拨模块[删除调拨单]按钮权限', '库间调拨', 'KJDB_SCDBD', 203),
            ('2009-04', '2009-04', '库间调拨-提交调拨单', '按钮权限：库间调拨模块[提交调拨单]按钮权限', '库间调拨', 'KJDB_TJDBD', 204),
            ('2009-05', '2009-05', '库间调拨-单据生成PDF', '按钮权限：库间调拨模块[单据生成PDF]按钮权限', '库间调拨', 'KJDB_DJSCPDF', 205),
            ('2009-06', '2009-06', '库间调拨-打印', '按钮权限：库间调拨模块[打印预览]和[直接打印]按钮权限', '库间调拨', 'KJDB_DY', 206),
            ('2010', '2010', '库存盘点', '模块权限：通过菜单进入库存盘点模块的权限', '库存盘点', 'KCPD', 100),
            ('2010-01', '2010-01', '库存盘点-新建盘点单', '按钮权限：库存盘点模块[新建盘点单]按钮权限', '库存盘点', 'KCPD_XJPDD', 201),
            ('2010-02', '2010-02', '库存盘点-编辑盘点单', '按钮权限：库存盘点模块[编辑盘点单]按钮权限', '库存盘点', 'KCPD_BJPDD', 202),
            ('2010-03', '2010-03', '库存盘点-删除盘点单', '按钮权限：库存盘点模块[删除盘点单]按钮权限', '库存盘点', 'KCPD_SCPDD', 203),
            ('2010-04', '2010-04', '库存盘点-提交盘点单', '按钮权限：库存盘点模块[提交盘点单]按钮权限', '库存盘点', 'KCPD_TJPDD', 204),
            ('2010-05', '2010-05', '库存盘点-单据生成PDF', '按钮权限：库存盘点模块[单据生成PDF]按钮权限', '库存盘点', 'KCPD_DJSCPDF', 205),
            ('2010-06', '2010-06', '库存盘点-打印', '按钮权限：库存盘点模块[打印预览]和[直接打印]按钮权限', '库存盘点', 'KCPD_DY', 206),
            ('2011-01', '2011-01', '首页-销售看板', '功能权限：在首页显示销售看板', '首页看板', 'SY_XSKB', 100),
            ('2011-02', '2011-02', '首页-库存看板', '功能权限：在首页显示库存看板', '首页看板', 'SY_KCKB', 100),
            ('2011-03', '2011-03', '首页-采购看板', '功能权限：在首页显示采购看板', '首页看板', 'SY_CGKB', 100),
            ('2011-04', '2011-04', '首页-资金看板', '功能权限：在首页显示资金看板', '首页看板', 'SY_ZJKB', 100),
            ('2012', '2012', '报表-销售日报表(按商品汇总)', '模块权限：通过菜单进入销售日报表(按商品汇总)模块的权限', '销售日报表', 'BB_XSRBB_ASPHZ_', 100),
            ('2013', '2013', '报表-销售日报表(按客户汇总)', '模块权限：通过菜单进入销售日报表(按客户汇总)模块的权限', '销售日报表', 'BB_XSRBB_AKHHZ_', 100),
            ('2014', '2014', '报表-销售日报表(按仓库汇总)', '模块权限：通过菜单进入销售日报表(按仓库汇总)模块的权限', '销售日报表', 'BB_XSRBB_ACKHZ_', 100),
            ('2015', '2015', '报表-销售日报表(按业务员汇总)', '模块权限：通过菜单进入销售日报表(按业务员汇总)模块的权限', '销售日报表', 'BB_XSRBB_AYWYHZ_', 100),
            ('2016', '2016', '报表-销售月报表(按商品汇总)', '模块权限：通过菜单进入销售月报表(按商品汇总)模块的权限', '销售月报表', 'BB_XSYBB_ASPHZ_', 100),
            ('2017', '2017', '报表-销售月报表(按客户汇总)', '模块权限：通过菜单进入销售月报表(按客户汇总)模块的权限', '销售月报表', 'BB_XSYBB_AKHHZ_', 100),
            ('2018', '2018', '报表-销售月报表(按仓库汇总)', '模块权限：通过菜单进入销售月报表(按仓库汇总)模块的权限', '销售月报表', 'BB_XSYBB_ACKHZ_', 100),
            ('2019', '2019', '报表-销售月报表(按业务员汇总)', '模块权限：通过菜单进入销售月报表(按业务员汇总)模块的权限', '销售月报表', 'BB_XSYBB_AYWYHZ_', 100),
            ('2020', '2020', '报表-安全库存明细表', '模块权限：通过菜单进入安全库存明细表模块的权限', '库存报表', 'BB_AQKCMXB', 100),
            ('2021', '2021', '报表-应收账款账龄分析表', '模块权限：通过菜单进入应收账款账龄分析表模块的权限', '资金报表', 'BB_YSZKZLFXB', 100),
            ('2022', '2022', '报表-应付账款账龄分析表', '模块权限：通过菜单进入应付账款账龄分析表模块的权限', '资金报表', 'BB_YFZKZLFXB', 100),
            ('2023', '2023', '报表-库存超上限明细表', '模块权限：通过菜单进入库存超上限明细表模块的权限', '库存报表', 'BB_KCCSXMXB', 100),
            ('2024', '2024', '现金收支查询', '模块权限：通过菜单进入现金收支查询模块的权限', '现金管理', 'XJSZCX', 100),
            ('2025', '2025', '预收款管理', '模块权限：通过菜单进入预收款管理模块的权限', '预收款管理', 'YSKGL', 100),
            ('2026', '2026', '预付款管理', '模块权限：通过菜单进入预付款管理模块的权限', '预付款管理', 'YFKGL', 100),
            ('2027', '2027', '采购订单', '模块权限：通过菜单进入采购订单模块的权限', '采购订单', 'CGDD', 100),
            ('2027-01', '2027-01', '采购订单-审核/取消审核', '按钮权限：采购订单模块[审核]按钮和[取消审核]按钮的权限', '采购订单', 'CGDD _ SH_QXSH', 204),
            ('2027-02', '2027-02', '采购订单-生成采购入库单', '按钮权限：采购订单模块[生成采购入库单]按钮权限', '采购订单', 'CGDD _ SCCGRKD', 205),
            ('2027-03', '2027-03', '采购订单-新建采购订单', '按钮权限：采购订单模块[新建采购订单]按钮权限', '采购订单', 'CGDD _ XJCGDD', 201),
            ('2027-04', '2027-04', '采购订单-编辑采购订单', '按钮权限：采购订单模块[编辑采购订单]按钮权限', '采购订单', 'CGDD _ BJCGDD', 202),
            ('2027-05', '2027-05', '采购订单-删除采购订单', '按钮权限：采购订单模块[删除采购订单]按钮权限', '采购订单', 'CGDD _ SCCGDD', 203),
            ('2027-06', '2027-06', '采购订单-关闭订单/取消关闭订单', '按钮权限：采购订单模块[关闭采购订单]和[取消采购订单关闭状态]按钮权限', '采购订单', 'CGDD _ GBDD_QXGBDD', 206),
            ('2027-07', '2027-07', '采购订单-单据生成PDF', '按钮权限：采购订单模块[单据生成PDF]按钮权限', '采购订单', 'CGDD _ DJSCPDF', 207),
            ('2027-08', '2027-08', '采购订单-打印', '按钮权限：采购订单模块[打印预览]和[直接打印]按钮权限', '采购订单', 'CGDD_DY', 208),
            ('2028', '2028', '销售订单', '模块权限：通过菜单进入销售订单模块的权限', '销售订单', 'XSDD', 100),
            ('2028-01', '2028-01', '销售订单-审核/取消审核', '按钮权限：销售订单模块[审核]按钮和[取消审核]按钮的权限', '销售订单', 'XSDD_SH_QXSH', 204),
            ('2028-02', '2028-02', '销售订单-生成销售出库单', '按钮权限：销售订单模块[生成销售出库单]按钮的权限', '销售订单', 'XSDD_SCXSCKD', 206),
            ('2028-03', '2028-03', '销售订单-新建销售订单', '按钮权限：销售订单模块[新建销售订单]按钮的权限', '销售订单', 'XSDD_XJXSDD', 201),
            ('2028-04', '2028-04', '销售订单-编辑销售订单', '按钮权限：销售订单模块[编辑销售订单]按钮的权限', '销售订单', 'XSDD_BJXSDD', 202),
            ('2028-05', '2028-05', '销售订单-删除销售订单', '按钮权限：销售订单模块[删除销售订单]按钮的权限', '销售订单', 'XSDD_SCXSDD', 203),
            ('2028-06', '2028-06', '销售订单-单据生成PDF', '按钮权限：销售订单模块[单据生成PDF]按钮的权限', '销售订单', 'XSDD_DJSCPDF', 207),
            ('2028-07', '2028-07', '销售订单-打印', '按钮权限：销售订单模块[打印预览]和[直接打印]按钮的权限', '销售订单', 'XSDD_DY', 208),
            ('2028-08', '2028-08', '销售订单-生成采购订单', '按钮权限：销售订单模块[生成采购订单]按钮的权限', '销售订单', 'XSDD_SCCGDD', 205),
            ('2028-09', '2028-09', '销售订单-关闭订单/取消关闭订单', '按钮权限：销售订单模块[关闭销售订单]和[取消销售订单关闭状态]按钮的权限', '销售订单', 'XSDD_GBDD', 209),
            ('2029', '2029', '商品品牌', '模块权限：通过菜单进入商品品牌模块的权限', '商品', 'SPPP', 600),
            ('2030-01', '2030-01', '商品构成-新增子商品', '按钮权限：商品模块[新增子商品]按钮权限', '商品', 'SPGC_XZZSP', 209),
            ('2030-02', '2030-02', '商品构成-编辑子商品', '按钮权限：商品模块[编辑子商品]按钮权限', '商品', 'SPGC_BJZSP', 210),
            ('2030-03', '2030-03', '商品构成-删除子商品', '按钮权限：商品模块[删除子商品]按钮权限', '商品', 'SPGC_SCZSP', 211),
            ('2031', '2031', '价格体系', '模块权限：通过菜单进入价格体系模块的权限', '商品', 'JGTX', 700),
            ('2031-01', '2031-01', '商品-设置商品价格体系', '按钮权限：商品模块[设置商品价格体系]按钮权限', '商品', 'JGTX', 701),
            ('2032', '2032', '销售合同', '模块权限：通过菜单进入销售合同模块的权限', '销售合同', 'XSHT', 100),
            ('2032-01', '2032-01', '销售合同-新建销售合同', '按钮权限：销售合同模块[新建销售合同]按钮的权限', '销售合同', 'XSHT_XJXSHT', 201),
            ('2032-02', '2032-02', '销售合同-编辑销售合同', '按钮权限：销售合同模块[编辑销售合同]按钮的权限', '销售合同', 'XSHT_BJXSHT', 202),
            ('2032-03', '2032-03', '销售合同-删除销售合同', '按钮权限：销售合同模块[删除销售合同]按钮的权限', '销售合同', 'XSHT_SCXSHT', 203),
            ('2032-04', '2032-04', '销售合同-审核/取消审核', '按钮权限：销售合同模块[审核]按钮和[取消审核]按钮的权限', '销售合同', 'XSHT_SH_QXSH', 204),
            ('2032-05', '2032-05', '销售合同-生成销售订单', '按钮权限：销售合同模块[生成销售订单]按钮的权限', '销售合同', 'XSHT_SCXSDD', 205),
            ('2032-06', '2032-06', '销售合同-单据生成PDF', '按钮权限：销售合同模块[单据生成PDF]按钮的权限', '销售合同', 'XSHT_DJSCPDF', 206),
            ('2032-07', '2032-07', '销售合同-打印', '按钮权限：销售合同模块[打印预览]和[直接打印]按钮的权限', '销售合同', 'XSHT_DY', 207),
            ('2033', '2033', '存货拆分', '模块权限：通过菜单进入存货拆分模块的权限', '存货拆分', 'CHCF', 100),
            ('2033-01', '2033-01', '存货拆分-新建拆分单', '按钮权限：存货拆分模块[新建拆分单]按钮的权限', '存货拆分', 'CHCFXJCFD', 201),
            ('2033-02', '2033-02', '存货拆分-编辑拆分单', '按钮权限：存货拆分模块[编辑拆分单]按钮的权限', '存货拆分', 'CHCFBJCFD', 202),
            ('2033-03', '2033-03', '存货拆分-删除拆分单', '按钮权限：存货拆分模块[删除拆分单]按钮的权限', '存货拆分', 'CHCFSCCFD', 203),
            ('2033-04', '2033-04', '存货拆分-提交拆分单', '按钮权限：存货拆分模块[提交拆分单]按钮的权限', '存货拆分', 'CHCFTJCFD', 204),
            ('2033-05', '2033-05', '存货拆分-单据生成PDF', '按钮权限：存货拆分模块[单据生成PDF]按钮的权限', '存货拆分', 'CHCFDJSCPDF', 205),
            ('2033-06', '2033-06', '存货拆分-打印', '按钮权限：存货拆分模块[打印预览]和[直接打印]按钮的权限', '存货拆分', 'CHCFDY', 206),
            ('2034', '2034', '工厂', '模块权限：通过菜单进入工厂模块的权限', '工厂', 'GC', 100),
            ('2034-01', '2034-01', '工厂在业务单据中的使用权限', '数据域权限：工厂在业务单据中的使用权限', '工厂', 'GCCYWDJZDSYQX', 301),
            ('2034-02', '2034-02', '工厂分类', '数据域权限：工厂模块中工厂分类的数据权限', '工厂', 'GCFL', 300),
            ('2034-03', '2034-03', '新增工厂分类', '按钮权限：工厂模块[新增工厂分类]按钮权限', '工厂', 'XZGYSFL', 201),
            ('2034-04', '2034-04', '编辑工厂分类', '按钮权限：工厂模块[编辑工厂分类]按钮权限', '工厂', 'BJGYSFL', 202),
            ('2034-05', '2034-05', '删除工厂分类', '按钮权限：工厂模块[删除工厂分类]按钮权限', '工厂', 'SCGYSFL', 203),
            ('2034-06', '2034-06', '新增工厂', '按钮权限：工厂模块[新增工厂]按钮权限', '工厂', 'XZGYS', 204),
            ('2034-07', '2034-07', '编辑工厂', '按钮权限：工厂模块[编辑工厂]按钮权限', '工厂', 'BJGYS', 205),
            ('2034-08', '2034-08', '删除工厂', '按钮权限：工厂模块[删除工厂]按钮权限', '工厂', 'SCGYS', 206),
            ('2035', '2035', '成品委托生产订单', '模块权限：通过菜单进入成品委托生产订单模块的权限', '成品委托生产订单', 'CPWTSCDD', 100),
            ('2035-01', '2035-01', '成品委托生产订单-新建成品委托生产订单', '按钮权限：成品委托生产订单模块[新建成品委托生产订单]按钮的权限', '成品委托生产订单', 'CPWTSCDDXJCPWTSCDD', 201),
            ('2035-02', '2035-02', '成品委托生产订单-编辑成品委托生产订单', '按钮权限：成品委托生产订单模块[编辑成品委托生产订单]按钮的权限', '成品委托生产订单', 'CPWTSCDDBJCPWTSCDD', 202),
            ('2035-03', '2035-03', '成品委托生产订单-删除成品委托生产订单', '按钮权限：成品委托生产订单模块[删除成品委托生产订单]按钮的权限', '成品委托生产订单', 'CPWTSCDDSCCPWTSCDD', 203),
            ('2035-04', '2035-04', '成品委托生产订单-审核/取消审核', '按钮权限：成品委托生产订单模块[审核]和[取消审核]按钮的权限', '成品委托生产订单', 'CPWTSCDDSHQXSH', 204),
            ('2035-05', '2035-05', '成品委托生产订单-生成成品委托生产入库单', '按钮权限：成品委托生产订单模块[生成成品委托生产入库单]按钮的权限', '成品委托生产订单', 'CPWTSCDDSCCPWTSCRKD', 205),
            ('2035-06', '2035-06', '成品委托生产订单-关闭/取消关闭成品委托生产订单', '按钮权限：成品委托生产订单模块[关闭成品委托生产订单]和[取消关闭成品委托生产订单]按钮的权限', '成品委托生产订单', 'CPWTSCDGBJCPWTSCDD', 206),
            ('2035-07', '2035-07', '成品委托生产订单-单据生成PDF', '按钮权限：成品委托生产订单模块[单据生成PDF]按钮的权限', '成品委托生产订单', 'CPWTSCDDDJSCPDF', 207),
            ('2035-08', '2035-08', '成品委托生产订单-打印', '按钮权限：成品委托生产订单模块[打印预览]和[直接打印]按钮的权限', '成品委托生产订单', 'CPWTSCDDDY', 208),
            ('2036', '2036', '成品委托生产入库', '模块权限：通过菜单进入成品委托生产入库模块的权限', '成品委托生产入库', 'CPWTSCRK', 100),
            ('2036-01', '2036-01', '成品委托生产入库-新建成品委托生产入库单', '按钮权限：成品委托生产入库模块[新建成品委托生产入库单]按钮的权限', '成品委托生产入库', 'CPWTSCRKXJCPWTSCRKD', 201),
            ('2036-02', '2036-02', '成品委托生产入库-编辑成品委托生产入库单', '按钮权限：成品委托生产入库模块[编辑成品委托生产入库单]按钮的权限', '成品委托生产入库', 'CPWTSCRKBJCPWTSCRKD', 202),
            ('2036-03', '2036-03', '成品委托生产入库-删除成品委托生产入库单', '按钮权限：成品委托生产入库模块[删除成品委托生产入库单]按钮的权限', '成品委托生产入库', 'CPWTSCRKSCCPWTSCRKD', 203),
            ('2036-04', '2036-04', '成品委托生产入库-提交入库', '按钮权限：成品委托生产入库模块[提交入库]按钮的权限', '成品委托生产入库', 'CPWTSCRKTJRK', 204),
            ('2036-05', '2036-05', '成品委托生产入库-单据生成PDF', '按钮权限：成品委托生产入库模块[单据生成PDF]按钮的权限', '成品委托生产入库', 'CPWTSCRKDJSCPDF', 205),
            ('2036-06', '2036-06', '成品委托生产入库-打印', '按钮权限：成品委托生产入库模块[打印预览]和[直接打印]按钮的权限', '成品委托生产入库', 'CPWTSCRKDY', 206),
            ('2037', '2037', '采购入库明细表', '模块权限：通过菜单进入采购入库明细表模块的权限', '采购报表', 'CGRKMXB', 100),
            ('2101', '2101', '会计科目', '模块权限：通过菜单进入会计科目模块的权限', '会计科目', 'KJKM', 100),
            ('2102', '2102', '银行账户', '模块权限：通过菜单进入银行账户模块的权限', '银行账户', 'YHZH', 100),
            ('2103', '2103', '会计期间', '模块权限：通过菜单进入会计期间模块的权限', '会计期间', 'KJQJ', 100);
            ";
    $db->execute($sql);
  }

  private function update_20200310_01()
  {
    // 本次更新：新增表t_sysdict_form_editor_xtype，及相关数据
    $db = $this->db;
    $tableName = "t_sysdict_form_editor_xtype";
    if (!$this->tableExists($db, $tableName)) {
      $sql = "CREATE TABLE IF NOT EXISTS `t_sysdict_form_editor_xtype` (
                `id` varchar(255) NOT NULL,
                `code` varchar(255) NOT NULL,
                `code_int` int(11) NOT NULL,
                `name` varchar(255) NOT NULL,
                `py` varchar(255) NOT NULL,
                `memo` varchar(255) NOT NULL,
                `show_order` int(11) DEFAULT NULL,
                PRIMARY KEY (`id`)
              ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
      $db->execute($sql);
    }

    // 新增相关数据
    $sql = "TRUNCATE TABLE `t_dict_table_category`;
            INSERT INTO `t_dict_table_category` (`id`, `code`, `name`, `parent_id`) VALUES
            ('01', '01', '码表', NULL),
            ('02', '02', '自定义表单', NULL);
            ";
    $db->execute($sql);

    $sql = "TRUNCATE TABLE `t_dict_table_md`;
            INSERT INTO `t_dict_table_md` (`id`, `code`, `name`, `table_name`, `category_id`, `memo`, `py`) VALUES
            ('0101', '0101', '码表记录状态', 't_sysdict_record_status', '01', '码表记录的状态', 'MBJLZT'),
            ('0102', '0102', '码表字段编辑器类型', 't_sysdict_editor_xtype', '01', '码表字段编辑器的类型', 'MBZDBJQLX'),
            ('0201', '0201', '表单字段编辑器类型', 't_sysdict_form_editor_xtype', '02', '表单字段编辑器的类型', 'BDZDBJQLX');
            ";
    $db->execute($sql);

    $sql = "TRUNCATE TABLE `t_sysdict_form_editor_xtype`;
            INSERT INTO `t_sysdict_form_editor_xtype` (`id`, `code`, `code_int`, `name`, `py`, `memo`, `show_order`) VALUES
            ('133BC834-62A4-11EA-BE39-F0BF9790E21F', '1', 1, 'textfield', 'textfield', '字符串编辑器', 1),
            ('2E01A0A4-62A4-11EA-BE39-F0BF9790E21F', '2', 2, 'numberfield', 'numberfield', '数值编辑器', 2);
            ";
    $db->execute($sql);
  }

  private function update_20191126_01()
  {
    // 本次更新：t_form新增字段table_name
    $db = $this->db;

    $tableName = "t_form";
    $columnName = "table_name";
    if (!$this->columnExists($db, $tableName, $columnName)) {
      $sql = "alter table {$tableName} add {$columnName} varchar(255) NOT NULL;";
      $db->execute($sql);
    }
  }

  private function update_20191125_03()
  {
    // 本次更新：新增表t_form_detail_cols
    $db = $this->db;
    $tableName = "t_form_detail_cols";
    if (!$this->tableExists($db, $tableName)) {
      $sql = "CREATE TABLE IF NOT EXISTS `t_form_detail_cols` (
                `id` varchar(255) NOT NULL,
                `detail_id` varchar(255) NOT NULL,
                `caption` varchar(255) NOT NULL,
                `db_field_name` varchar(255) NOT NULL,
                `db_field_type` varchar(255) NOT NULL,
                `db_field_length` int(11) NOT NULL,
                `db_field_decimal` int(11) NOT NULL,
                `show_order` int(11) NOT NULL,
                `width_in_view` int(11) NOT NULL,
                `value_from` int(11) DEFAULT NULL,
                `value_from_table_name` varchar(255) DEFAULT NULL,
                `value_from_col_name` varchar(255) DEFAULT NULL,
                `value_from_col_name_display` varchar(255) DEFAULT NULL,
                `must_input` int(11) DEFAULT 1,
                `sys_col` int(11) DEFAULT 1,
                `is_visible` int(11) DEFAULT 1,
                `note` varchar(1000) DEFAULT NULL,
                `editor_xtype` varchar(255) NOT NULL DEFAULT 'textfield',
                PRIMARY KEY (`id`)
              ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
              ";
      $db->execute($sql);
    }
  }

  private function update_20191125_02()
  {
    // 本次更新：新增表t_form_detail
    $db = $this->db;
    $tableName = "t_form_detail";
    if (!$this->tableExists($db, $tableName)) {
      $sql = "CREATE TABLE IF NOT EXISTS `t_form_detail` (
                `id` varchar(255) NOT NULL,
                `form_id` varchar(255) NOT NULL,
                `name` varchar(255) NOT NULL,
                `table_name` varchar(255) NOT NULL,
                `fk_name` varchar(255) NOT NULL,
                `show_order` int(11) NOT NULL,
                PRIMARY KEY (`id`)
              ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
              ";
      $db->execute($sql);
    }
  }

  private function update_20191125_01()
  {
    // 本次更新：新增表t_form_cols
    $db = $this->db;
    $tableName = "t_form_cols";
    if (!$this->tableExists($db, $tableName)) {
      $sql = "CREATE TABLE IF NOT EXISTS `t_form_cols` (
                `id` varchar(255) NOT NULL,
                `form_id` varchar(255) NOT NULL,
                `caption` varchar(255) NOT NULL,
                `db_field_name` varchar(255) NOT NULL,
                `db_field_type` varchar(255) NOT NULL,
                `db_field_length` int(11) NOT NULL,
                `db_field_decimal` int(11) NOT NULL,
                `show_order` int(11) NOT NULL,
                `col_span` int(11) NOT NULL,
                `value_from` int(11) DEFAULT NULL,
                `value_from_table_name` varchar(255) DEFAULT NULL,
                `value_from_col_name` varchar(255) DEFAULT NULL,
                `value_from_col_name_display` varchar(255) DEFAULT NULL,
                `must_input` int(11) DEFAULT 1,
                `sys_col` int(11) DEFAULT 1,
                `is_visible` int(11) DEFAULT 1,
                `note` varchar(1000) DEFAULT NULL,
                `editor_xtype` varchar(255) NOT NULL DEFAULT 'textfield',
                PRIMARY KEY (`id`)
              ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
              ";
      $db->execute($sql);
    }
  }

  private function update_20191123_01()
  {
    // 本次更新：t_form新增字段md_version和memo
    $db = $this->db;

    $tableName = "t_form";
    $columnName = "md_version";
    if (!$this->columnExists($db, $tableName, $columnName)) {
      $sql = "alter table {$tableName} add {$columnName} int(11) NOT NULL DEFAULT 1;";
      $db->execute($sql);
    }

    $columnName = "memo";
    if (!$this->columnExists($db, $tableName, $columnName)) {
      $sql = "alter table {$tableName} add {$columnName} varchar(1000) DEFAULT NULL;";
      $db->execute($sql);
    }
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

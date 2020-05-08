<?php

namespace Home\Service;

/**
 * 安装Service
 *
 * @author 李静波
 */
class InstallService extends PSIBaseExService
{

  /**
   * 首次运行PSI的时候，自动初始化数据库(创建表和往表里面插入初始化数据)
   *
   * 只在MoPaaS下有效
   */
  public function autoInstallWhenFirstRun()
  {
    if (!$this->isMoPaaS()) {
      return;
    }

    $db = $this->db();
    $tableName = "t_biz_log";

    // 用 t_biz_log 这个表是否存在 来判断是否已经初始化了数据库
    if ($this->tableExists($db, $tableName)) {
      return;
    }

    $this->createTables();
    $this->insertInitData();
  }

  /**
   * 创建表
   */
  private function createTables()
  {
    $db = $this->db();

    // think_session
    $sql = "CREATE TABLE `think_session` (
              `session_id` varchar(255) NOT NULL,
              `session_expire` int(11) NOT NULL,
              `session_data` blob,
              UNIQUE KEY `session_id` (`session_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
		";
    $db->execute($sql);

    // t_biz_log
    $sql = "CREATE TABLE IF NOT EXISTS `t_biz_log` (
              `id` bigint(20) NOT NULL AUTO_INCREMENT,
              `date_created` datetime DEFAULT NULL,
              `info` varchar(1000) NOT NULL,
              `ip` varchar(255) NOT NULL,
              `user_id` varchar(255) NOT NULL,
              `log_category` varchar(50) NOT NULL,
              `data_org` varchar(255) DEFAULT NULL,
              `ip_from` varchar(255) DEFAULT NULL,
              `company_id` varchar(255) DEFAULT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
		";
    $db->execute($sql);

    // t_fid
    $sql = "CREATE TABLE IF NOT EXISTS `t_fid` (
              `fid` varchar(255) NOT NULL,
              `name` varchar(255) NOT NULL,
              `py` varchar(255) DEFAULT NULL,
              `memo` varchar(255) DEFAULT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
            ";
    $db->execute($sql);

    // t_menu_item
    $sql = "CREATE TABLE IF NOT EXISTS `t_menu_item` (
              `id` varchar(255) NOT NULL,
              `caption` varchar(255) NOT NULL,
              `fid` varchar(255) DEFAULT NULL,
              `parent_id` varchar(255) DEFAULT NULL,
              `show_order` int(11) NOT NULL,
              `py` varchar(255) DEFAULT NULL,
              `memo` varchar(255) DEFAULT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
            ";
    $db->execute($sql);

    // t_org
    $sql = "CREATE TABLE IF NOT EXISTS `t_org` (
				  `id` varchar(255) NOT NULL,
				  `full_name` varchar(1000) NOT NULL,
				  `name` varchar(255) NOT NULL,
				  `org_code` varchar(255) NOT NULL,
				  `parent_id` varchar(255) DEFAULT NULL,
				  `data_org` varchar(255) DEFAULT NULL,
				  `org_type` int(11) DEFAULT NULL,
				  PRIMARY KEY (`id`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8;
		";
    $db->execute($sql);

    // t_permission
    $sql = "CREATE TABLE IF NOT EXISTS `t_permission` (
              `id` varchar(255) NOT NULL,
              `fid` varchar(255) NOT NULL,
              `name` varchar(255) NOT NULL,
              `note` varchar(255) DEFAULT NULL,
              `category` varchar(255) DEFAULT NULL,
              `py` varchar(255) DEFAULT NULL,
              `show_order` int(11) DEFAULT NULL,
              `parent_fid` varchar(255) DEFAULT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
            ";
    $db->execute($sql);

    // t_recent_fid
    $sql = "CREATE TABLE IF NOT EXISTS `t_recent_fid` (
				  `fid` varchar(255) NOT NULL,
				  `user_id` varchar(255) NOT NULL,
				  `click_count` int(11) NOT NULL
				) ENGINE=InnoDB DEFAULT CHARSET=utf8;
		";
    $db->execute($sql);

    // t_role
    $sql = "CREATE TABLE IF NOT EXISTS `t_role` (
				  `id` varchar(255) NOT NULL,
				  `name` varchar(255) NOT NULL,
				  `data_org` varchar(255) DEFAULT NULL,
				  `company_id` varchar(255) DEFAULT NULL,
				  `code` varchar(255) DEFAULT NULL,
				  PRIMARY KEY (`id`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8;
		";
    $db->execute($sql);

    // t_role_permission
    $sql = "CREATE TABLE IF NOT EXISTS `t_role_permission` (
				  `role_id` varchar(255) DEFAULT NULL,
				  `permission_id` varchar(255) DEFAULT NULL,
				  `data_org` varchar(255) DEFAULT NULL
				) ENGINE=InnoDB DEFAULT CHARSET=utf8;
		";
    $db->execute($sql);

    // t_role_user
    $sql = "CREATE TABLE IF NOT EXISTS `t_role_user` (
				  `role_id` varchar(255) DEFAULT NULL,
				  `user_id` varchar(255) DEFAULT NULL
				) ENGINE=InnoDB DEFAULT CHARSET=utf8;
		";
    $db->execute($sql);

    // t_user
    $sql = "CREATE TABLE IF NOT EXISTS `t_user` (
				  `id` varchar(255) NOT NULL,
				  `enabled` int(11) NOT NULL,
				  `login_name` varchar(255) NOT NULL,
				  `name` varchar(255) NOT NULL,
				  `org_id` varchar(255) NOT NULL,
				  `org_code` varchar(255) NOT NULL,
				  `password` varchar(255) NOT NULL,
				  `py` varchar(255) DEFAULT NULL,
				  `gender` varchar(255) DEFAULT NULL,
				  `birthday` varchar(255) DEFAULT NULL,
				  `id_card_number` varchar(255) DEFAULT NULL,
				  `tel` varchar(255) DEFAULT NULL,
				  `tel02` varchar(255) DEFAULT NULL,
				  `address` varchar(255) DEFAULT NULL,
				  `data_org` varchar(255) DEFAULT NULL,
				  `company_id` varchar(255) DEFAULT NULL,
				  PRIMARY KEY (`id`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8;
		";
    $db->execute($sql);

    // t_warehouse
    $sql = "CREATE TABLE IF NOT EXISTS `t_warehouse` (
              `id` varchar(255) NOT NULL,
              `code` varchar(255) NOT NULL,
              `inited` int(11) NOT NULL,
              `name` varchar(255) NOT NULL,
              `py` varchar(255) DEFAULT NULL,
              `data_org` varchar(255) DEFAULT NULL,
              `company_id` varchar(255) DEFAULT NULL,
              `enabled` int(11) NOT NULL DEFAULT 1,
              `org_id` varchar(255) DEFAULT NULL,
              `sale_area` decimal(19,2) DEFAULT NULL,
              `usage_type` int(11) NOT NULL DEFAULT 40,
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
            ";
    $db->execute($sql);

    // t_supplier
    $sql = "CREATE TABLE IF NOT EXISTS `t_supplier` (
				  `id` varchar(255) NOT NULL,
				  `category_id` varchar(255) NOT NULL,
				  `code` varchar(255) NOT NULL,
				  `name` varchar(255) NOT NULL,
				  `contact01` varchar(255) DEFAULT NULL,
				  `qq01` varchar(255) DEFAULT NULL,
				  `tel01` varchar(255) DEFAULT NULL,
				  `mobile01` varchar(255) DEFAULT NULL,
				  `contact02` varchar(255) DEFAULT NULL,
				  `qq02` varchar(255) DEFAULT NULL,
				  `tel02` varchar(255) DEFAULT NULL,
				  `mobile02` varchar(255) DEFAULT NULL,
				  `address` varchar(255) DEFAULT NULL,
				  `address_shipping` varchar(255) DEFAULT NULL,
				  `address_receipt` varchar(255) DEFAULT NULL,
				  `py` varchar(255) DEFAULT NULL,
				  `init_receivables` decimal(19,2) DEFAULT NULL, 
				  `init_receivables_dt` datetime DEFAULT NULL, 
				  `init_payables` decimal(19,2) DEFAULT NULL, 
				  `init_payables_dt` datetime DEFAULT NULL, 
				  `bank_name` varchar(255) DEFAULT NULL,
				  `bank_account` varchar(255) DEFAULT NULL,
				  `tax_number` varchar(255) DEFAULT NULL,
				  `fax` varchar(255) DEFAULT NULL,
				  `note` varchar(255) DEFAULT NULL,
				  `data_org` varchar(255) DEFAULT NULL,
				  `company_id` varchar(255) DEFAULT NULL,
				  `tax_rate` int(11) DEFAULT NULL,
				  `record_status` int(11) DEFAULT 1000,
				  `goods_range` int(11) DEFAULT 1,
				  PRIMARY KEY (`id`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8;
		";
    $db->execute($sql);

    // t_supplier_category
    $sql = "CREATE TABLE IF NOT EXISTS `t_supplier_category` (
				  `id` varchar(255) NOT NULL,
				  `code` varchar(255) NOT NULL,
				  `name` varchar(255) NOT NULL,
				  `parent_id` varchar(255) DEFAULT NULL,
				  `data_org` varchar(255) DEFAULT NULL,
				  `company_id` varchar(255) DEFAULT NULL,
				  `full_name` varchar(1000) DEFAULT NULL,
				  PRIMARY KEY (`id`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8;
		";
    $db->execute($sql);

    // t_goods
    $sql = "CREATE TABLE IF NOT EXISTS `t_goods` (
				  `id` varchar(255) NOT NULL,
				  `category_id` varchar(255) NOT NULL,
				  `code` varchar(255) NOT NULL,
				  `name` varchar(255) NOT NULL,
				  `sale_price` decimal(19,2) NOT NULL,
				  `spec` varchar(255) NOT NULL,
				  `unit_id` varchar(255) NOT NULL,
				  `purchase_price` decimal(19, 2) DEFAULT NULL,
				  `py` varchar(255) DEFAULT NULL,
				  `spec_py` varchar(255) DEFAULT NULL,
				  `bar_code` varchar(255) DEFAULT NULL,
				  `data_org` varchar(255) DEFAULT NULL,
				  `memo` varchar(500) DEFAULT NULL,
				  `company_id` varchar(255) DEFAULT NULL,
				  `brand_id` varchar(255) DEFAULT NULL,
				  `record_status` int(11) DEFAULT 1000,
				  `tax_rate` decimal(19,2) DEFAULT NULL,
				  PRIMARY KEY (`id`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8;
		";
    $db->execute($sql);

    // t_goods_category
    $sql = "CREATE TABLE IF NOT EXISTS `t_goods_category` (
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

    // t_goods_unit
    $sql = "CREATE TABLE IF NOT EXISTS `t_goods_unit` (
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

    // t_customer
    $sql = "CREATE TABLE IF NOT EXISTS `t_customer` (
				  `id` varchar(255) NOT NULL,
				  `category_id` varchar(255) NOT NULL,
				  `code` varchar(255) NOT NULL,
				  `name` varchar(255) NOT NULL,
				  `contact01` varchar(255) DEFAULT NULL,
				  `qq01` varchar(255) DEFAULT NULL,
				  `tel01` varchar(255) DEFAULT NULL,
				  `mobile01` varchar(255) DEFAULT NULL,
				  `contact02` varchar(255) DEFAULT NULL,
				  `qq02` varchar(255) DEFAULT NULL,
				  `tel02` varchar(255) DEFAULT NULL,
				  `mobile02` varchar(255) DEFAULT NULL,
				  `address` varchar(255) DEFAULT NULL,
				  `address_shipping` varchar(255) DEFAULT NULL,
				  `address_receipt` varchar(255) DEFAULT NULL,
				  `py` varchar(255) DEFAULT NULL,
				  `init_receivables` decimal(19,2) DEFAULT NULL, 
				  `init_receivables_dt` datetime DEFAULT NULL, 
				  `init_payables` decimal(19,2) DEFAULT NULL, 
				  `init_payables_dt` datetime DEFAULT NULL, 
				  `bank_name` varchar(255) DEFAULT NULL,
				  `bank_account` varchar(255) DEFAULT NULL,
				  `tax_number` varchar(255) DEFAULT NULL,
				  `fax` varchar(255) DEFAULT NULL,
				  `note` varchar(255) DEFAULT NULL,
				  `data_org` varchar(255) DEFAULT NULL,
				  `company_id` varchar(255) DEFAULT NULL,
				  `sales_warehouse_id` varchar(255) DEFAULT NULL,
				  `record_status` int(11) DEFAULT 1000,
				  PRIMARY KEY (`id`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8;
		";
    $db->execute($sql);

    // t_customer_category
    $sql = "CREATE TABLE IF NOT EXISTS `t_customer_category` (
				  `id` varchar(255) NOT NULL,
				  `code` varchar(255) NOT NULL,
				  `name` varchar(255) NOT NULL,
				  `parent_id` varchar(255) DEFAULT NULL,
				  `data_org` varchar(255) DEFAULT NULL,
				  `company_id` varchar(255) DEFAULT NULL,
				  `ps_id` varchar(255) DEFAULT NULL,
				  PRIMARY KEY (`id`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8;
		";
    $db->execute($sql);

    // t_inventory
    $sql = "CREATE TABLE IF NOT EXISTS `t_inventory` (
				  `id` bigint(20) NOT NULL AUTO_INCREMENT,
				  `balance_count` decimal(19,8) NOT NULL,
				  `balance_money` decimal(19,2) NOT NULL,
				  `balance_price` decimal(19,2) NOT NULL,
				  `goods_id` varchar(255) NOT NULL,
				  `in_count` decimal(19,8) DEFAULT NULL,
				  `in_money` decimal(19,2) DEFAULT NULL,
				  `in_price` decimal(19,2) DEFAULT NULL,
				  `out_count` decimal(19,8) DEFAULT NULL,
				  `out_money` decimal(19,2) DEFAULT NULL,
				  `out_price` decimal(19,2) DEFAULT NULL,
				  `afloat_count` decimal(19,8) DEFAULT NULL,
				  `afloat_money` decimal(19,2) DEFAULT NULL,
				  `afloat_price` decimal(19,2) DEFAULT NULL,
				  `warehouse_id` varchar(255) NOT NULL,
				  `data_org` varchar(255) DEFAULT NULL,
				  `company_id` varchar(255) DEFAULT NULL,
				  PRIMARY KEY (`id`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
		";
    $db->execute($sql);

    // t_inventory_detail
    $sql = "CREATE TABLE IF NOT EXISTS `t_inventory_detail` (
				  `id` bigint(20) NOT NULL AUTO_INCREMENT,
				  `balance_count` decimal(19,8) NOT NULL,
				  `balance_money` decimal(19,2) NOT NULL,
				  `balance_price` decimal(19,2) NOT NULL,
				  `biz_date` datetime NOT NULL,
				  `biz_user_id` varchar(255) NOT NULL,
				  `date_created` datetime DEFAULT NULL,
				  `goods_id` varchar(255) NOT NULL,
				  `in_count` decimal(19,8) DEFAULT NULL,
				  `in_money` decimal(19,2) DEFAULT NULL,
				  `in_price` decimal(19,2) DEFAULT NULL,
				  `out_count` decimal(19,8) DEFAULT NULL,
				  `out_money` decimal(19,2) DEFAULT NULL,
				  `out_price` decimal(19,2) DEFAULT NULL,
				  `ref_number` varchar(255) DEFAULT NULL,
				  `ref_type` varchar(255) NOT NULL,
				  `warehouse_id` varchar(255) NOT NULL,
				  `data_org` varchar(255) DEFAULT NULL,
				  `company_id` varchar(255) DEFAULT NULL,
				  PRIMARY KEY (`id`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
		";
    $db->execute($sql);

    // t_pw_bill
    $sql = "CREATE TABLE IF NOT EXISTS `t_pw_bill` (
				  `id` varchar(255) NOT NULL,
				  `bill_status` int(11) NOT NULL,
				  `biz_dt` datetime NOT NULL,
				  `biz_user_id` varchar(255) NOT NULL,
				  `date_created` datetime DEFAULT NULL,
				  `goods_money` decimal(19,2) NOT NULL,
				  `input_user_id` varchar(255) NOT NULL,
				  `ref` varchar(255) NOT NULL,
				  `supplier_id` varchar(255) NOT NULL,
				  `warehouse_id` varchar(255) NOT NULL,
				  `payment_type` int(11) NOT NULL DEFAULT 0,
				  `data_org` varchar(255) DEFAULT NULL,
				  `company_id` varchar(255) DEFAULT NULL,
				  `expand_by_bom` int(11) NOT NULL DEFAULT 0,
				  `bill_memo` varchar(255) DEFAULT NULL,
				  `wspbill_id` varchar(255) DEFAULT NULL,
				  `tax` decimal(19,2) DEFAULT NULL,
				  `money_with_tax` decimal(19,2) DEFAULT NULL,
				  PRIMARY KEY (`id`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8;
		";
    $db->execute($sql);

    // t_pw_bill_detail
    $sql = "CREATE TABLE IF NOT EXISTS `t_pw_bill_detail` (
				  `id` varchar(255) NOT NULL,
				  `date_created` datetime DEFAULT NULL,
				  `goods_id` varchar(255) NOT NULL,
				  `goods_count` decimal(19,8) NOT NULL,
				  `goods_money` decimal(19,2) NOT NULL,
				  `goods_price` decimal(19,2) NOT NULL,
				  `pwbill_id` varchar(255) NOT NULL,
				  `show_order` int(11) NOT NULL,
				  `data_org` varchar(255) DEFAULT NULL,
				  `memo` varchar(1000) DEFAULT NULL,
				  `company_id` varchar(255) DEFAULT NULL,
				  `pobilldetail_id` varchar(255) DEFAULT NULL,
				  `tax_rate` decimal(19,2) DEFAULT NULL,
				  `tax` decimal(19,2) DEFAULT NULL,
				  `money_with_tax` decimal(19,2) DEFAULT NULL,
				  `goods_price_with_tax` decimal(19,2) DEFAULT NULL,
				  PRIMARY KEY (`id`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8;
		";
    $db->execute($sql);

    // t_payables
    $sql = "CREATE TABLE IF NOT EXISTS `t_payables` (
				  `id` varchar(255) NOT NULL,
				  `act_money` decimal(19,2) NOT NULL,
				  `balance_money` decimal(19,2) NOT NULL,
				  `ca_id` varchar(255) NOT NULL,
				  `ca_type` varchar(255) NOT NULL,
				  `pay_money` decimal(19,2) NOT NULL,
				  `data_org` varchar(255) DEFAULT NULL,
				  `company_id` varchar(255) DEFAULT NULL,
				  PRIMARY KEY (`id`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8;
		";
    $db->execute($sql);

    // t_payables_detail
    $sql = "CREATE TABLE IF NOT EXISTS `t_payables_detail` (
				  `id` varchar(255) NOT NULL,
				  `act_money` decimal(19,2) NOT NULL,
				  `balance_money` decimal(19,2) NOT NULL,
				  `ca_id` varchar(255) NOT NULL,
				  `ca_type` varchar(255) NOT NULL,
				  `biz_date` datetime DEFAULT NULL,
				  `date_created` datetime DEFAULT NULL,
				  `pay_money` decimal(19,2) NOT NULL,
				  `ref_number` varchar(255) NOT NULL,
				  `ref_type` varchar(255) NOT NULL,
				  `data_org` varchar(255) DEFAULT NULL,
				  `company_id` varchar(255) DEFAULT NULL,
				  PRIMARY KEY (`id`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8;
		";
    $db->execute($sql);

    // t_receivables
    $sql = "CREATE TABLE IF NOT EXISTS `t_receivables` (
				  `id` varchar(255) NOT NULL,
				  `act_money` decimal(19,2) NOT NULL,
				  `balance_money` decimal(19,2) NOT NULL,
				  `ca_id` varchar(255) NOT NULL,
				  `ca_type` varchar(255) NOT NULL,
				  `rv_money` decimal(19,2) NOT NULL,
				  `data_org` varchar(255) DEFAULT NULL,
				  `company_id` varchar(255) DEFAULT NULL,
				  PRIMARY KEY (`id`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8;
		";
    $db->execute($sql);

    // t_receivables_detail
    $sql = "CREATE TABLE IF NOT EXISTS `t_receivables_detail` (
				  `id` varchar(255) NOT NULL,
				  `act_money` decimal(19,2) NOT NULL,
				  `balance_money` decimal(19,2) NOT NULL,
				  `ca_id` varchar(255) NOT NULL,
				  `ca_type` varchar(255) NOT NULL,
				  `biz_date` datetime DEFAULT NULL,
				  `date_created` datetime DEFAULT NULL,
				  `ref_number` varchar(255) NOT NULL,
				  `ref_type` varchar(255) NOT NULL,
				  `rv_money` decimal(19,2) NOT NULL,
				  `data_org` varchar(255) DEFAULT NULL,
				  `company_id` varchar(255) DEFAULT NULL,
				  PRIMARY KEY (`id`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8;
		";
    $db->execute($sql);

    // t_payment
    $sql = "CREATE TABLE IF NOT EXISTS `t_payment` (
				  `id` varchar(255) NOT NULL,
				  `act_money` decimal(19,2) NOT NULL,
				  `biz_date` datetime NOT NULL,
				  `date_created` datetime DEFAULT NULL,
				  `input_user_id` varchar(255) NOT NULL,
				  `pay_user_id` varchar(255) NOT NULL,
				  `bill_id` varchar(255) NOT NULL,
				  `ref_type` varchar(255) NOT NULL,
				  `ref_number` varchar(255) NOT NULL,
				  `remark` varchar(255) NOT NULL,
				  `data_org` varchar(255) DEFAULT NULL,
				  `company_id` varchar(255) DEFAULT NULL,
				  PRIMARY KEY (`id`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8;
		";
    $db->execute($sql);

    // t_ws_bill
    $sql = "CREATE TABLE IF NOT EXISTS `t_ws_bill` (
				  `id` varchar(255) NOT NULL,
				  `bill_status` int(11) NOT NULL,
				  `bizdt` datetime NOT NULL,
				  `biz_user_id` varchar(255) NOT NULL,
				  `customer_id` varchar(255) NOT NULL,
				  `date_created` datetime DEFAULT NULL,
				  `input_user_id` varchar(255) NOT NULL,
				  `inventory_money` decimal(19,2) DEFAULT NULL,
				  `profit` decimal(19,2) DEFAULT NULL,
				  `ref` varchar(255) NOT NULL,
				  `sale_money` decimal(19,2) DEFAULT NULL,
				  `warehouse_id` varchar(255) NOT NULL,
				  `receiving_type` int(11) NOT NULL DEFAULT 0,
				  `data_org` varchar(255) DEFAULT NULL,
				  `company_id` varchar(255) DEFAULT NULL,
				  `memo` varchar(1000) DEFAULT NULL,
				  `deal_address` varchar(255) DEFAULT NULL,
				  `tax` decimal(19,2) DEFAULT NULL,
				  `money_with_tax` decimal(19,2) DEFAULT NULL,
				  PRIMARY KEY (`id`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8;
		";
    $db->execute($sql);

    // t_ws_bill_detail
    $sql = "CREATE TABLE IF NOT EXISTS `t_ws_bill_detail` (
				  `id` varchar(255) NOT NULL,
				  `date_created` datetime DEFAULT NULL,
				  `goods_id` varchar(255) NOT NULL,
				  `goods_count` decimal(19,8) NOT NULL,
				  `goods_money` decimal(19,2) NOT NULL,
				  `goods_price` decimal(19,2) NOT NULL,
				  `inventory_money` decimal(19,2) DEFAULT NULL,
				  `inventory_price` decimal(19,2) DEFAULT NULL,
				  `show_order` int(11) NOT NULL,
				  `wsbill_id` varchar(255) NOT NULL,
				  `sn_note` varchar(255) DEFAULT NULL,
				  `data_org` varchar(255) DEFAULT NULL,
				  `memo` varchar(1000) DEFAULT NULL,
				  `company_id` varchar(255) DEFAULT NULL,
				  `sobilldetail_id` varchar(255) DEFAULT NULL,
				  `tax_rate` decimal(19,2) DEFAULT NULL,
				  `tax` decimal(19,2) DEFAULT NULL,
				  `money_with_tax` decimal(19,2) DEFAULT NULL,
				  `goods_price_with_tax` decimal(19,2) DEFAULT NULL,
				  PRIMARY KEY (`id`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8;
		";
    $db->execute($sql);

    // t_receiving
    $sql = "CREATE TABLE IF NOT EXISTS `t_receiving` (
				  `id` varchar(255) NOT NULL,
				  `act_money` decimal(19,2) NOT NULL,
				  `biz_date` datetime NOT NULL,
				  `date_created` datetime DEFAULT NULL,
				  `input_user_id` varchar(255) NOT NULL,
				  `remark` varchar(255) NOT NULL,
				  `rv_user_id` varchar(255) NOT NULL,
				  `bill_id` varchar(255) NOT NULL,
				  `ref_number` varchar(255) NOT NULL,
				  `ref_type` varchar(255) NOT NULL,
				  `data_org` varchar(255) DEFAULT NULL,
				  `company_id` varchar(255) DEFAULT NULL,
				  PRIMARY KEY (`id`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8;
		";
    $db->execute($sql);

    // t_sr_bill
    $sql = "CREATE TABLE IF NOT EXISTS `t_sr_bill` (
              `id` varchar(255) NOT NULL,
              `bill_status` int(11) NOT NULL,
              `bizdt` datetime NOT NULL,
              `biz_user_id` varchar(255) NOT NULL,
              `customer_id` varchar(255) NOT NULL,
              `date_created` datetime DEFAULT NULL,
              `input_user_id` varchar(255) NOT NULL,
              `inventory_money` decimal(19,2) DEFAULT NULL,
              `profit` decimal(19,2) DEFAULT NULL,
              `ref` varchar(255) NOT NULL,
              `rejection_sale_money` decimal(19,2) DEFAULT NULL,
              `warehouse_id` varchar(255) NOT NULL,
              `ws_bill_id` varchar(255) NOT NULL,
              `payment_type` int(11) NOT NULL DEFAULT 0,
              `data_org` varchar(255) DEFAULT NULL,
              `company_id` varchar(255) DEFAULT NULL,
              `bill_memo` varchar(255) DEFAULT NULL,
              `tax` decimal(19,2) DEFAULT NULL,
              `rejection_sale_money_with_tax` decimal(19,2) DEFAULT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
    ";
    $db->execute($sql);

    // t_sr_bill_detail
    $sql = "CREATE TABLE IF NOT EXISTS `t_sr_bill_detail` (
              `id` varchar(255) NOT NULL,
              `date_created` datetime DEFAULT NULL,
              `goods_id` varchar(255) NOT NULL,
              `goods_count` decimal(19,8) NOT NULL,
              `goods_money` decimal(19,2) NOT NULL,
              `goods_price` decimal(19,2) NOT NULL,
              `inventory_money` decimal(19,2) NOT NULL,
              `inventory_price` decimal(19,2) NOT NULL,
              `rejection_goods_count` decimal(19,8) NOT NULL,
              `rejection_goods_price` decimal(19,2) NOT NULL,
              `rejection_sale_money` decimal(19,2) NOT NULL,
              `show_order` int(11) NOT NULL,
              `srbill_id` varchar(255) NOT NULL,
              `wsbilldetail_id` varchar(255) NOT NULL,
              `sn_note` varchar(255) DEFAULT NULL,
              `data_org` varchar(255) DEFAULT NULL,
              `company_id` varchar(255) DEFAULT NULL,
              `memo` varchar(255) DEFAULT NULL,
              `tax_rate` decimal(19,2) DEFAULT NULL,
              `tax` decimal(19,2) DEFAULT NULL,
              `rejection_sale_money_with_tax` decimal(19,2) DEFAULT NULL,
              `rejection_goods_price_with_tax` decimal(19,2) DEFAULT NULL,
              `goods_money_with_tax` decimal(19,2) DEFAULT NULL,
              `goods_price_with_tax` decimal(19,2) DEFAULT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
            ";
    $db->execute($sql);

    // t_it_bill
    $sql = "CREATE TABLE IF NOT EXISTS `t_it_bill` (
				  `id` varchar(255) NOT NULL,
				  `bill_status` int(11) NOT NULL,
				  `bizdt` datetime NOT NULL,
				  `biz_user_id` varchar(255) NOT NULL,
				  `date_created` datetime DEFAULT NULL,
				  `input_user_id` varchar(255) NOT NULL,
				  `ref` varchar(255) NOT NULL,
				  `from_warehouse_id` varchar(255) NOT NULL,
				  `to_warehouse_id` varchar(255) NOT NULL,
				  `data_org` varchar(255) DEFAULT NULL,
				  `company_id` varchar(255) DEFAULT NULL,
				  `bill_memo` varchar(255) DEFAULT NULL,
				  PRIMARY KEY (`id`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8;
		";
    $db->execute($sql);

    // t_it_bill_detail
    $sql = "CREATE TABLE IF NOT EXISTS `t_it_bill_detail` (
				  `id` varchar(255) NOT NULL,
				  `date_created` datetime DEFAULT NULL,
				  `goods_id` varchar(255) NOT NULL,
				  `goods_count` decimal(19,8) NOT NULL,
				  `show_order` int(11) NOT NULL,
				  `itbill_id` varchar(255) NOT NULL,
				  `data_org` varchar(255) DEFAULT NULL,
				  `company_id` varchar(255) DEFAULT NULL,
				  `memo` varchar(255) DEFAULT NULL,
				  PRIMARY KEY (`id`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8;
		";
    $db->execute($sql);

    // t_ic_bill
    $sql = "CREATE TABLE IF NOT EXISTS `t_ic_bill` (
				  `id` varchar(255) NOT NULL,
				  `bill_status` int(11) NOT NULL,
				  `bizdt` datetime NOT NULL,
				  `biz_user_id` varchar(255) NOT NULL,
				  `date_created` datetime DEFAULT NULL,
				  `input_user_id` varchar(255) NOT NULL,
				  `ref` varchar(255) NOT NULL,
				  `warehouse_id` varchar(255) NOT NULL,
				  `data_org` varchar(255) DEFAULT NULL,
				  `company_id` varchar(255) DEFAULT NULL,
				  `bill_memo` varchar(255) DEFAULT NULL,
				  PRIMARY KEY (`id`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8;
		";
    $db->execute($sql);

    // t_ic_bill_detail
    $sql = "CREATE TABLE IF NOT EXISTS `t_ic_bill_detail` (
				  `id` varchar(255) NOT NULL,
				  `date_created` datetime DEFAULT NULL,
				  `goods_id` varchar(255) NOT NULL,
				  `goods_count` decimal(19,8) NOT NULL,
				  `goods_money` decimal(19,2) NOT NULL,
				  `show_order` int(11) NOT NULL,
				  `icbill_id` varchar(255) NOT NULL,
				  `data_org` varchar(255) DEFAULT NULL,
				  `company_id` varchar(255) DEFAULT NULL,
				  `memo` varchar(255) DEFAULT NULL,
				  PRIMARY KEY (`id`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8;
		";
    $db->execute($sql);

    // t_pr_bill
    $sql = "CREATE TABLE IF NOT EXISTS `t_pr_bill` (
              `id` varchar(255) NOT NULL,
              `bill_status` int(11) NOT NULL,
              `bizdt` datetime NOT NULL,
              `biz_user_id` varchar(255) NOT NULL,
              `supplier_id` varchar(255) NOT NULL,
              `date_created` datetime DEFAULT NULL,
              `input_user_id` varchar(255) NOT NULL,
              `inventory_money` decimal(19,2) DEFAULT NULL,
              `ref` varchar(255) NOT NULL,
              `rejection_money` decimal(19,2) DEFAULT NULL,
              `warehouse_id` varchar(255) NOT NULL,
              `pw_bill_id` varchar(255) NOT NULL,
              `receiving_type` int(11) NOT NULL DEFAULT 0,
              `data_org` varchar(255) DEFAULT NULL,
              `company_id` varchar(255) DEFAULT NULL,
              `bill_memo` varchar(255) DEFAULT NULL,
              `tax` decimal(19,2) DEFAULT NULL,
              `rejection_money_with_tax` decimal(19,2) DEFAULT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
            ";
    $db->execute($sql);

    // t_pr_bill_detail
    $sql = "CREATE TABLE IF NOT EXISTS `t_pr_bill_detail` (
              `id` varchar(255) NOT NULL,
              `date_created` datetime DEFAULT NULL,
              `goods_id` varchar(255) NOT NULL,
              `goods_count` decimal(19,8) NOT NULL,
              `goods_money` decimal(19,2) NOT NULL,
              `goods_price` decimal(19,2) NOT NULL,
              `inventory_money` decimal(19,2) NOT NULL,
              `inventory_price` decimal(19,2) NOT NULL,
              `rejection_goods_count` decimal(19,8) NOT NULL,
              `rejection_goods_price` decimal(19,2) NOT NULL,
              `rejection_money` decimal(19,2) NOT NULL,
              `show_order` int(11) NOT NULL,
              `prbill_id` varchar(255) NOT NULL,
              `pwbilldetail_id` varchar(255) NOT NULL,
              `data_org` varchar(255) DEFAULT NULL,
              `company_id` varchar(255) DEFAULT NULL,
              `memo` varchar(255) DEFAULT NULL,
              `tax_rate` decimal(19,2) DEFAULT NULL,
              `tax` decimal(19,2) DEFAULT NULL,
              `rejection_money_with_tax` decimal(19,2) DEFAULT NULL,
              `rejection_goods_price_with_tax` decimal(19,2) DEFAULT NULL,
              `goods_money_with_tax` decimal(19,2) DEFAULT NULL,
              `goods_price_with_tax` decimal(19,2) DEFAULT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
            ";
    $db->execute($sql);

    // t_config
    $sql = "CREATE TABLE IF NOT EXISTS `t_config` (
				  `id` varchar(255) NOT NULL,
				  `name` varchar(255) NOT NULL,
				  `value` varchar(255) NOT NULL,
				  `note` varchar(255) NOT NULL,
				  `show_order` int(11) DEFAULT NULL,
				  `company_id` varchar(255) DEFAULT NULL
				) ENGINE=InnoDB DEFAULT CHARSET=utf8;
		";
    $db->execute($sql);

    // t_psi_db_version
    $sql = "CREATE TABLE IF NOT EXISTS `t_psi_db_version` (
				  `db_version` varchar(255) NOT NULL,
				  `update_dt` datetime NOT NULL
				) ENGINE=InnoDB DEFAULT CHARSET=utf8;
		";
    $db->execute($sql);

    // t_goods_si
    $sql = "CREATE TABLE IF NOT EXISTS `t_goods_si` (
				  `id` varchar(255) NOT NULL,
				  `goods_id` varchar(255) NOT NULL,
				  `warehouse_id` varchar(255) NOT NULL,
				  `safety_inventory` decimal(19,2) NOT NULL,
				  `inventory_upper` decimal(19,2) DEFAULT NULL,
				  `data_org` varchar(255) DEFAULT NULL,
				  `company_id` varchar(255) DEFAULT NULL,
				  PRIMARY KEY (`id`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8;
		";
    $db->execute($sql);

    // t_cash
    $sql = "CREATE TABLE IF NOT EXISTS `t_cash` (
				  `id` bigint(20) NOT NULL AUTO_INCREMENT,
				  `biz_date` datetime NOT NULL,
				  `in_money` decimal(19,2) DEFAULT NULL,
				  `out_money` decimal(19,2) DEFAULT NULL,
				  `balance_money` decimal(19,2) NOT NULL,
				  `data_org` varchar(255) DEFAULT NULL,
				  `company_id` varchar(255) DEFAULT NULL,
				  PRIMARY KEY (`id`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8;
		";
    $db->execute($sql);

    // t_cash_detail
    $sql = "CREATE TABLE IF NOT EXISTS `t_cash_detail` (
				  `id` bigint(20) NOT NULL AUTO_INCREMENT,
				  `biz_date` datetime NOT NULL,
				  `in_money` decimal(19,2) DEFAULT NULL,
				  `out_money` decimal(19,2) DEFAULT NULL,
				  `balance_money` decimal(19,2) NOT NULL,
				  `ref_number` varchar(255) NOT NULL,
				  `ref_type` varchar(255) NOT NULL,
				  `date_created` datetime NOT NULL,
				  `data_org` varchar(255) DEFAULT NULL,
				  `company_id` varchar(255) DEFAULT NULL,
				  PRIMARY KEY (`id`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8;
		";
    $db->execute($sql);

    // t_pre_receiving
    $sql = "CREATE TABLE IF NOT EXISTS `t_pre_receiving` (
				  `id` varchar(255) NOT NULL,
				  `customer_id` varchar(255) NOT NULL,
				  `in_money` decimal(19,2) DEFAULT NULL,
				  `out_money` decimal(19,2) DEFAULT NULL,
				  `balance_money` decimal(19,2) NOT NULL,
				  `data_org` varchar(255) DEFAULT NULL,
				  `company_id` varchar(255) DEFAULT NULL,
				  PRIMARY KEY (`id`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8;
		";
    $db->execute($sql);

    // t_pre_receiving_detail
    $sql = "CREATE TABLE IF NOT EXISTS `t_pre_receiving_detail` (
              `id` varchar(255) NOT NULL,
              `customer_id` varchar(255) NOT NULL,
              `in_money` decimal(19,2) DEFAULT NULL,
              `out_money` decimal(19,2) DEFAULT NULL,
              `balance_money` decimal(19,2) NOT NULL,
              `biz_date` datetime DEFAULT NULL,
              `date_created` datetime DEFAULT NULL,
              `ref_number` varchar(255) NOT NULL,
              `ref_type` varchar(255) NOT NULL,
              `biz_user_id` varchar(255) NOT NULL,
              `input_user_id` varchar(255) NOT NULL,
              `data_org` varchar(255) DEFAULT NULL,
              `company_id` varchar(255) DEFAULT NULL,
              `memo` varchar(1000) DEFAULT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
            ";
    $db->execute($sql);

    // t_pre_payment
    $sql = "CREATE TABLE IF NOT EXISTS `t_pre_payment` (
				  `id` varchar(255) NOT NULL,
				  `supplier_id` varchar(255) NOT NULL,
				  `in_money` decimal(19,2) DEFAULT NULL,
				  `out_money` decimal(19,2) DEFAULT NULL,
				  `balance_money` decimal(19,2) NOT NULL,
				  `data_org` varchar(255) DEFAULT NULL,
				  `company_id` varchar(255) DEFAULT NULL,
				  PRIMARY KEY (`id`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8;
		";
    $db->execute($sql);

    // t_pre_payment_detail
    $sql = "CREATE TABLE IF NOT EXISTS `t_pre_payment_detail` (
              `id` varchar(255) NOT NULL,
              `supplier_id` varchar(255) NOT NULL,
              `in_money` decimal(19,2) DEFAULT NULL,
              `out_money` decimal(19,2) DEFAULT NULL,
              `balance_money` decimal(19,2) NOT NULL,
              `biz_date` datetime DEFAULT NULL,
              `date_created` datetime DEFAULT NULL,
              `ref_number` varchar(255) NOT NULL,
              `ref_type` varchar(255) NOT NULL,
              `biz_user_id` varchar(255) NOT NULL,
              `input_user_id` varchar(255) NOT NULL,
              `data_org` varchar(255) DEFAULT NULL,
              `company_id` varchar(255) DEFAULT NULL,
              `memo` varchar(1000) DEFAULT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
            ";
    $db->execute($sql);

    // t_po_bill
    $sql = "CREATE TABLE IF NOT EXISTS `t_po_bill` (
				  `id` varchar(255) NOT NULL,
				  `bill_status` int(11) NOT NULL,
				  `biz_dt` datetime NOT NULL,
				  `deal_date` datetime NOT NULL,
				  `org_id` varchar(255) NOT NULL,
				  `biz_user_id` varchar(255) NOT NULL,
				  `date_created` datetime DEFAULT NULL,
				  `goods_money` decimal(19,2) NOT NULL,
				  `tax` decimal(19,2) NOT NULL,
				  `money_with_tax` decimal(19,2) NOT NULL,
				  `input_user_id` varchar(255) NOT NULL,
				  `ref` varchar(255) NOT NULL,
				  `supplier_id` varchar(255) NOT NULL,
				  `contact` varchar(255) NOT NULL,
				  `tel` varchar(255) DEFAULT NULL,
				  `fax` varchar(255) DEFAULT NULL,
				  `deal_address` varchar(255) DEFAULT NULL,
				  `bill_memo` varchar(255) DEFAULT NULL,
				  `payment_type` int(11) NOT NULL DEFAULT 0,
				  `confirm_user_id` varchar(255) DEFAULT NULL,
				  `confirm_date` datetime DEFAULT NULL,
				  `data_org` varchar(255) DEFAULT NULL,
				  `company_id` varchar(255) DEFAULT NULL,
				  PRIMARY KEY (`id`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8;
		";
    $db->execute($sql);

    // t_po_bill_detail
    $sql = "CREATE TABLE IF NOT EXISTS `t_po_bill_detail` (
				  `id` varchar(255) NOT NULL,
				  `date_created` datetime DEFAULT NULL,
				  `goods_id` varchar(255) NOT NULL,
				  `goods_count` decimal(19,8) NOT NULL,
				  `goods_money` decimal(19,2) NOT NULL,
				  `goods_price` decimal(19,2) NOT NULL,
				  `pobill_id` varchar(255) NOT NULL,
				  `tax_rate` decimal(19,2) NOT NULL,
				  `tax` decimal(19,2) NOT NULL,
				  `money_with_tax` decimal(19,2) NOT NULL,
				  `pw_count` decimal(19,8) NOT NULL,
				  `left_count` decimal(19,8) NOT NULL,
				  `show_order` int(11) NOT NULL,
				  `data_org` varchar(255) DEFAULT NULL,
				  `company_id` varchar(255) DEFAULT NULL,
				  `memo` varchar(255) DEFAULT NULL,
				  `goods_price_with_tax` decimal(19,2) DEFAULT NULL,
				  PRIMARY KEY (`id`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8;
		";
    $db->execute($sql);

    // t_po_pw
    $sql = "CREATE TABLE IF NOT EXISTS `t_po_pw` (
				  `po_id` varchar(255) NOT NULL,
				  `pw_id` varchar(255) NOT NULL
				) ENGINE=InnoDB DEFAULT CHARSET=utf8;
		";
    $db->execute($sql);

    // t_role_permission_dataorg
    $sql = "CREATE TABLE IF NOT EXISTS `t_role_permission_dataorg` (
				  `role_id` varchar(255) DEFAULT NULL,
				  `permission_id` varchar(255) DEFAULT NULL,
				  `data_org` varchar(255) DEFAULT NULL
				) ENGINE=InnoDB DEFAULT CHARSET=utf8;
		";
    $db->execute($sql);

    // t_inventory_fifo
    $sql = "CREATE TABLE IF NOT EXISTS `t_inventory_fifo` (
				  `id` bigint(20) NOT NULL AUTO_INCREMENT,
				  `balance_count` decimal(19,8) NOT NULL,
				  `balance_money` decimal(19,2) NOT NULL,
				  `balance_price` decimal(19,2) NOT NULL,
				  `date_created` datetime DEFAULT NULL,
				  `goods_id` varchar(255) NOT NULL,
				  `in_count` decimal(19,8) DEFAULT NULL,
				  `in_money` decimal(19,2) DEFAULT NULL,
				  `in_price` decimal(19,2) DEFAULT NULL,
				  `out_count` decimal(19,8) DEFAULT NULL,
				  `out_money` decimal(19,2) DEFAULT NULL,
				  `out_price` decimal(19,2) DEFAULT NULL,
				  `in_ref` varchar(255) DEFAULT NULL,
				  `in_ref_type` varchar(255) NOT NULL,
				  `warehouse_id` varchar(255) NOT NULL,
				  `data_org` varchar(255) DEFAULT NULL,
				  `pwbilldetail_id` varchar(255) DEFAULT NULL,
				  PRIMARY KEY (`id`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
		";
    $db->execute($sql);

    // t_inventory_fifo_detail
    $sql = "CREATE TABLE IF NOT EXISTS `t_inventory_fifo_detail` (
				  `id` bigint(20) NOT NULL AUTO_INCREMENT,
				  `balance_count` decimal(19,8) NOT NULL,
				  `balance_money` decimal(19,2) NOT NULL,
				  `balance_price` decimal(19,2) NOT NULL,
				  `date_created` datetime DEFAULT NULL,
				  `goods_id` varchar(255) NOT NULL,
				  `in_count` decimal(19,8) DEFAULT NULL,
				  `in_money` decimal(19,2) DEFAULT NULL,
				  `in_price` decimal(19,2) DEFAULT NULL,
				  `out_count` decimal(19,8) DEFAULT NULL,
				  `out_money` decimal(19,2) DEFAULT NULL,
				  `out_price` decimal(19,2) DEFAULT NULL,
				  `warehouse_id` varchar(255) NOT NULL,
				  `data_org` varchar(255) DEFAULT NULL,
				  `pwbilldetail_id` varchar(255) DEFAULT NULL,
				  `wsbilldetail_id` varchar(255) DEFAULT NULL,
				  PRIMARY KEY (`id`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
		";
    $db->execute($sql);

    // t_so_bill
    $sql = "CREATE TABLE IF NOT EXISTS `t_so_bill` (
				  `id` varchar(255) NOT NULL,
				  `bill_status` int(11) NOT NULL,
				  `biz_dt` datetime NOT NULL,
				  `deal_date` datetime NOT NULL,
				  `org_id` varchar(255) NOT NULL,
				  `biz_user_id` varchar(255) NOT NULL,
				  `date_created` datetime DEFAULT NULL,
				  `goods_money` decimal(19,2) NOT NULL,
				  `tax` decimal(19,2) NOT NULL,
				  `money_with_tax` decimal(19,2) NOT NULL,
				  `input_user_id` varchar(255) NOT NULL,
				  `ref` varchar(255) NOT NULL,
				  `customer_id` varchar(255) NOT NULL,
				  `contact` varchar(255) NOT NULL,
				  `tel` varchar(255) DEFAULT NULL,
				  `fax` varchar(255) DEFAULT NULL,
				  `deal_address` varchar(255) DEFAULT NULL,
				  `bill_memo` varchar(255) DEFAULT NULL,
				  `receiving_type` int(11) NOT NULL DEFAULT 0,
				  `confirm_user_id` varchar(255) DEFAULT NULL,
				  `confirm_date` datetime DEFAULT NULL,
				  `data_org` varchar(255) DEFAULT NULL,
				  `company_id` varchar(255) DEFAULT NULL,
				  PRIMARY KEY (`id`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8;
		";
    $db->execute($sql);

    // t_so_bill_detail
    $sql = "CREATE TABLE IF NOT EXISTS `t_so_bill_detail` (
				  `id` varchar(255) NOT NULL,
				  `date_created` datetime DEFAULT NULL,
				  `goods_id` varchar(255) NOT NULL,
				  `goods_count` decimal(19,8) NOT NULL,
				  `goods_money` decimal(19,2) NOT NULL,
				  `goods_price` decimal(19,2) NOT NULL,
				  `sobill_id` varchar(255) NOT NULL,
				  `tax_rate` decimal(19,2) NOT NULL,
				  `tax` decimal(19,2) NOT NULL,
				  `money_with_tax` decimal(19,2) NOT NULL,
				  `ws_count` decimal(19,8) NOT NULL,
				  `left_count` decimal(19,8) NOT NULL,
				  `show_order` int(11) NOT NULL,
				  `data_org` varchar(255) DEFAULT NULL,
				  `company_id` varchar(255) DEFAULT NULL,
				  `memo` varchar(500) DEFAULT NULL,
				  `scbilldetail_id` varchar(255) DEFAULT NULL,
				  `goods_price_with_tax` decimal(19,2) DEFAULT NULL,
				  PRIMARY KEY (`id`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8;
		";
    $db->execute($sql);

    // t_so_ws
    $sql = "CREATE TABLE IF NOT EXISTS `t_so_ws` (
				  `so_id` varchar(255) NOT NULL,
				  `ws_id` varchar(255) NOT NULL
				) ENGINE=InnoDB DEFAULT CHARSET=utf8;
				";
    $db->execute($sql);

    // t_goods_brand
    $sql = "CREATE TABLE IF NOT EXISTS `t_goods_brand` (
				  `id` varchar(255) NOT NULL,
				  `name` varchar(255) NOT NULL,
				  `parent_id` varchar(255) DEFAULT NULL,
				  `full_name` varchar(1000) DEFAULT NULL,
				  `data_org` varchar(255) DEFAULT NULL,
				  `company_id` varchar(255) DEFAULT NULL,
				  `py` varchar(255) DEFAULT NULL,
				  `record_status` int(11) DEFAULT 1,
				  PRIMARY KEY (`id`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8;
		";
    $db->execute($sql);

    // t_goods_bom
    $sql = "CREATE TABLE IF NOT EXISTS `t_goods_bom` (
				  `id` varchar(255) NOT NULL,
				  `goods_id` varchar(255) NOT NULL,
				  `sub_goods_id` varchar(255) NOT NULL,
				  `parent_id` varchar(255) DEFAULT NULL,
				  `sub_goods_count` decimal(19,8) NOT NULL,
				  `cost_weight` int(11) DEFAULT 1,
				  PRIMARY KEY (`id`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8;
				";
    $db->execute($sql);

    // t_subject
    $sql = "CREATE TABLE IF NOT EXISTS `t_subject` (
				  `id` varchar(255) NOT NULL,
				  `category` int NOT NULL,
				  `code` varchar(255) NOT NULL,
				  `name` varchar(255) NOT NULL,
				  `is_leaf` int NOT NULL,
				  `py` varchar(255) DEFAULT NULL,
				  `data_org` varchar(255) DEFAULT NULL,
				  `company_id` varchar(255) DEFAULT NULL,
				  `parent_id` varchar(255) DEFAULT NULL,
				  PRIMARY KEY (`id`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8;
				";
    $db->execute($sql);

    // t_price_system
    $sql = "CREATE TABLE IF NOT EXISTS `t_price_system` (
				  `id` varchar(255) NOT NULL,
				  `name` varchar(255) NOT NULL,
				  `data_org` varchar(255) DEFAULT NULL,
				  `company_id` varchar(255) DEFAULT NULL,
				  `factor` decimal(19,2) DEFAULT NULL,
				  PRIMARY KEY (`id`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8;
			";
    $db->execute($sql);

    // t_goods_price
    $sql = "CREATE TABLE IF NOT EXISTS `t_goods_price` (
				  `id` varchar(255) NOT NULL,
				  `goods_id` varchar(255) NOT NULL,
				  `ps_id` varchar(255) NOT NULL,
				  `price` decimal(19,2) NOT NULL,
				  `data_org` varchar(255) DEFAULT NULL,
				  `company_id` varchar(255) DEFAULT NULL,
				  PRIMARY KEY (`id`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8;
			";
    $db->execute($sql);

    // t_bank_account
    $sql = "CREATE TABLE IF NOT EXISTS `t_bank_account` (
				  `id` varchar(255) NOT NULL,
				  `bank_name` varchar(255) NOT NULL,
				  `bank_number` varchar(255) NOT NULL,
				  `memo` varchar(255) NOT NULL,
				  `date_created` datetime DEFAULT NULL,
				  `data_org` varchar(255) NOT NULL,
				  `company_id` varchar(255) NOT NULL,
				  PRIMARY KEY (`id`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8;
			";
    $db->execute($sql);

    // t_menu_item_h5
    $sql = "CREATE TABLE IF NOT EXISTS `t_menu_item_h5` (
				  `id` varchar(255) NOT NULL,
				  `caption` varchar(255) NOT NULL,
				  `fid` varchar(255) DEFAULT NULL,
				  `parent_id` varchar(255) DEFAULT NULL,
				  `show_order` int(11) NOT NULL,
				  PRIMARY KEY (`id`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8;
		";
    $db->execute($sql);

    // t_acc_fmt
    $sql = "CREATE TABLE IF NOT EXISTS `t_acc_fmt` (
				  `id` varchar(255) NOT NULL,
				  `acc_number` varchar(255) NOT NULL,
				  `subject_code` varchar(255) NOT NULL,
				  `memo` varchar(255) DEFAULT NULL,
				  `date_created` datetime DEFAULT NULL,
				  `data_org` varchar(255) DEFAULT NULL,
				  `company_id` varchar(255) NOT NULL,
				  `in_use` int(11) DEFAULT 1,
				  `db_table_name_prefix` varchar(255) NOT NULL,
				  PRIMARY KEY (`id`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8;
		";
    $db->execute($sql);

    // t_acc_fmt_cols
    $sql = "CREATE TABLE IF NOT EXISTS `t_acc_fmt_cols` (
              `id` varchar(255) NOT NULL,
              `fmt_id` varchar(255) NOT NULL,
              `db_field_name` varchar(255) NOT NULL,
              `db_field_type` varchar(255) DEFAULT NULL,
              `db_field_length` int(11) NOT NULL,
              `db_field_decimal` int(11) NOT NULL,
              `show_order` int(11) NOT NULL,
              `caption` varchar(255) NOT NULL,
              `sys_col` int(11) DEFAULT NULL,
              `use_code_table` int(11) NOT NULL DEFAULT 0,
              `code_table_name` varchar(255) DEFAULT NULL,
              `code_table_field_name` varchar(255) DEFAULT NULL,
              `code_table_field_name_fk` varchar(255) DEFAULT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
            ";
    $db->execute($sql);

    // t_fv_md
    $sql = "CREATE TABLE IF NOT EXISTS `t_fv_md` (
				  `id` varchar(255) NOT NULL,
				  `parent_id` varchar(255) DEFAULT NULL,
				  `prop_name` varchar(255) NOT NULL,
				  `prop_value` varchar(1000) NOT NULL,
				  `show_order` int(11) NOT NULL DEFAULT 0,
				  PRIMARY KEY (`id`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8;
		";
    $db->execute($sql);

    // t_acc_period
    $sql = "CREATE TABLE IF NOT EXISTS `t_acc_period` (
				  `id` varchar(255) NOT NULL,
				  `acc_year` int(11) NOT NULL,
				  `acc_month` int(11) NOT NULL,
				  `company_id` varchar(255) NOT NULL,
				  `acc_gl_kept` int(11) NOT NULL,
				  `acc_gl_closed` int(11) NOT NULL,
				  `acc_detail_kept` int(11) NOT NULL,
				  `acc_detail_closed` int(11) NOT NULL,
				  `period_closed` int(11) NOT NULL,
				  `year_forward` int(11) NOT NULL,
				  PRIMARY KEY (`id`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8;
		";
    $db->execute($sql);

    // t_sc_bill
    $sql = "CREATE TABLE IF NOT EXISTS `t_sc_bill` (
				  `id` varchar(255) NOT NULL,
				  `ref` varchar(255) NOT NULL,
				  `customer_id` varchar(255) NOT NULL,
				  `org_id` varchar(255) NOT NULL,
				  `biz_user_id` varchar(255) NOT NULL,
				  `biz_dt` datetime NOT NULL,
				  `input_user_id` varchar(255) NOT NULL,
				  `date_created` datetime DEFAULT NULL,
				  `bill_status` int(11) NOT NULL,
				  `goods_money` decimal(19,2) NOT NULL,
				  `tax` decimal(19,2) NOT NULL,
				  `money_with_tax` decimal(19,2) NOT NULL,
				  `deal_date` datetime NOT NULL,
				  `deal_address` varchar(255) DEFAULT NULL,
				  `confirm_user_id` varchar(255) DEFAULT NULL,
				  `confirm_date` datetime DEFAULT NULL,
				  `bill_memo` varchar(255) DEFAULT NULL,
				  `data_org` varchar(255) DEFAULT NULL,
				  `company_id` varchar(255) DEFAULT NULL,
				  `begin_dt` date NOT NULL,
				  `end_dt` date NOT NULL,
				  `discount` int(11) NOT NULL,
				  `quality_clause` varchar(500) DEFAULT NULL,
				  `insurance_clause` varchar(500) DEFAULT NULL,
				  `transport_clause` varchar(500) DEFAULT NULL,
				  `other_clause` varchar(500) DEFAULT NULL,
				  PRIMARY KEY (`id`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8;
		";
    $db->execute($sql);

    // t_sc_bill_detail
    $sql = "CREATE TABLE IF NOT EXISTS `t_sc_bill_detail` (
				  `id` varchar(255) NOT NULL,
				  `scbill_id` varchar(255) NOT NULL,
				  `show_order` int(11) NOT NULL,
				  `goods_id` varchar(255) NOT NULL,
				  `goods_count` decimal(19,8) NOT NULL,
				  `goods_money` decimal(19,2) NOT NULL,
				  `goods_price` decimal(19,2) NOT NULL,
				  `tax_rate` decimal(19,2) NOT NULL,
				  `tax` decimal(19,2) NOT NULL,
				  `money_with_tax` decimal(19,2) NOT NULL,
				  `date_created` datetime DEFAULT NULL,
				  `data_org` varchar(255) DEFAULT NULL,
				  `company_id` varchar(255) DEFAULT NULL,
				  `memo` varchar(500) DEFAULT NULL,
				  `discount` int(11) NOT NULL,
				  `so_count` decimal(19,8) NOT NULL,
				  `left_count` decimal(19,8) NOT NULL,
				  `goods_price_with_tax` decimal(19,2) DEFAULT NULL,
				  PRIMARY KEY (`id`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8;
		";
    $db->execute($sql);

    // t_sc_so
    $sql = "CREATE TABLE IF NOT EXISTS `t_sc_so` (
				  `sc_id` varchar(255) NOT NULL,
				  `so_id` varchar(255) NOT NULL
				) ENGINE=InnoDB DEFAULT CHARSET=utf8;
		";
    $db->execute($sql);

    // t_wsp_bill
    $sql = "CREATE TABLE IF NOT EXISTS `t_wsp_bill` (
				  `id` varchar(255) NOT NULL,
				  `ref` varchar(255) NOT NULL,
				  `from_warehouse_id` varchar(255) NOT NULL,
				  `to_warehouse_id` varchar(255) NOT NULL,
				  `bill_status` int(11) NOT NULL,
				  `bizdt` datetime NOT NULL,
				  `biz_user_id` varchar(255) NOT NULL,
				  `date_created` datetime DEFAULT NULL,
				  `input_user_id` varchar(255) NOT NULL,
				  `data_org` varchar(255) DEFAULT NULL,
				  `company_id` varchar(255) DEFAULT NULL,
				  `bill_memo` varchar(1000) DEFAULT NULL,
				  PRIMARY KEY (`id`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8;
		";
    $db->execute($sql);

    // t_wsp_bill_detail
    $sql = "CREATE TABLE IF NOT EXISTS `t_wsp_bill_detail` (
				  `id` varchar(255) NOT NULL,
				  `wspbill_id` varchar(255) NOT NULL,
				  `show_order` int(11) NOT NULL,
				  `goods_id` varchar(255) NOT NULL,
				  `goods_count` decimal(19,8) NOT NULL,
				  `date_created` datetime DEFAULT NULL,
				  `data_org` varchar(255) DEFAULT NULL,
				  `company_id` varchar(255) DEFAULT NULL,
				  `memo` varchar(1000) DEFAULT NULL,
				  PRIMARY KEY (`id`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8;
		";
    $db->execute($sql);

    // t_wsp_bill_detail_ex
    $sql = "CREATE TABLE IF NOT EXISTS `t_wsp_bill_detail_ex` (
				  `id` varchar(255) NOT NULL,
				  `wspbill_id` varchar(255) NOT NULL,
				  `show_order` int(11) NOT NULL,
				  `goods_id` varchar(255) NOT NULL,
				  `goods_count` decimal(19,8) NOT NULL,
				  `date_created` datetime DEFAULT NULL,
				  `data_org` varchar(255) DEFAULT NULL,
				  `company_id` varchar(255) DEFAULT NULL,
				  `from_goods_id` varchar(255) NOT NULL,
				  `wspbilldetail_id` varchar(255) NOT NULL,
				  PRIMARY KEY (`id`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8;
		";
    $db->execute($sql);

    // t_wsp_bill_bom
    $sql = "CREATE TABLE IF NOT EXISTS `t_wsp_bill_detail_bom` (
				  `id` varchar(255) NOT NULL,
				  `wspbilldetail_id` varchar(255) NOT NULL,
				  `goods_id` varchar(255) NOT NULL,
				  `sub_goods_id` varchar(255) NOT NULL,
				  `parent_id` varchar(255) DEFAULT NULL,
				  `sub_goods_count` decimal(19,8) NOT NULL,
				  `cost_weight` int(11) DEFAULT 1,
				  PRIMARY KEY (`id`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8;
		";
    $db->execute($sql);

    // t_factory
    $sql = "CREATE TABLE IF NOT EXISTS `t_factory` (
				  `id` varchar(255) NOT NULL,
				  `category_id` varchar(255) NOT NULL,
				  `code` varchar(255) NOT NULL,
				  `name` varchar(255) NOT NULL,
				  `contact01` varchar(255) DEFAULT NULL,
				  `tel01` varchar(255) DEFAULT NULL,
				  `mobile01` varchar(255) DEFAULT NULL,
				  `contact02` varchar(255) DEFAULT NULL,
				  `tel02` varchar(255) DEFAULT NULL,
				  `mobile02` varchar(255) DEFAULT NULL,
				  `address` varchar(255) DEFAULT NULL,
				  `py` varchar(255) DEFAULT NULL,
				  `init_receivables` decimal(19,2) DEFAULT NULL, 
				  `init_receivables_dt` datetime DEFAULT NULL, 
				  `init_payables` decimal(19,2) DEFAULT NULL, 
				  `init_payables_dt` datetime DEFAULT NULL, 
				  `bank_name` varchar(255) DEFAULT NULL,
				  `bank_account` varchar(255) DEFAULT NULL,
				  `tax_number` varchar(255) DEFAULT NULL,
				  `fax` varchar(255) DEFAULT NULL,
				  `note` varchar(255) DEFAULT NULL,
				  `data_org` varchar(255) DEFAULT NULL,
				  `company_id` varchar(255) DEFAULT NULL,
				  `record_status` int(11) DEFAULT 1000,
				  PRIMARY KEY (`id`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8;
		";
    $db->execute($sql);

    // t_factory_category
    $sql = "CREATE TABLE IF NOT EXISTS `t_factory_category` (
				  `id` varchar(255) NOT NULL,
				  `code` varchar(255) NOT NULL,
				  `name` varchar(255) NOT NULL,
				  `parent_id` varchar(255) DEFAULT NULL,
				  `data_org` varchar(255) DEFAULT NULL,
				  `company_id` varchar(255) DEFAULT NULL,
				  `full_name` varchar(1000) DEFAULT NULL,
				  PRIMARY KEY (`id`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8;
		";
    $db->execute($sql);

    // t_dmo_bill
    $sql = "CREATE TABLE IF NOT EXISTS `t_dmo_bill` (
				  `id` varchar(255) NOT NULL,
				  `ref` varchar(255) NOT NULL,
				  `factory_id` varchar(255) NOT NULL,
				  `contact` varchar(255) NOT NULL,
				  `tel` varchar(255) DEFAULT NULL,
				  `fax` varchar(255) DEFAULT NULL,
				  `org_id` varchar(255) NOT NULL,
				  `biz_user_id` varchar(255) NOT NULL,
				  `biz_dt` datetime NOT NULL,
				  `input_user_id` varchar(255) NOT NULL,
				  `date_created` datetime DEFAULT NULL,
				  `bill_status` int(11) NOT NULL,
				  `goods_money` decimal(19,2) NOT NULL,
				  `tax` decimal(19,2) NOT NULL,
				  `money_with_tax` decimal(19,2) NOT NULL,
				  `payment_type` int(11) NOT NULL DEFAULT 0,
				  `deal_date` datetime NOT NULL,
				  `deal_address` varchar(255) DEFAULT NULL,
				  `confirm_user_id` varchar(255) DEFAULT NULL,
				  `confirm_date` datetime DEFAULT NULL,
				  `bill_memo` varchar(255) DEFAULT NULL,
				  `data_org` varchar(255) DEFAULT NULL,
				  `company_id` varchar(255) DEFAULT NULL,
				  PRIMARY KEY (`id`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8;
		";
    $db->execute($sql);

    // t_dmo_bill_detail
    $sql = "CREATE TABLE IF NOT EXISTS `t_dmo_bill_detail` (
				  `id` varchar(255) NOT NULL,
				  `dmobill_id` varchar(255) NOT NULL,
				  `show_order` int(11) NOT NULL,
				  `goods_id` varchar(255) NOT NULL,
				  `goods_count` decimal(19,8) NOT NULL,
				  `goods_money` decimal(19,2) NOT NULL,
				  `goods_price` decimal(19,2) NOT NULL,
				  `tax_rate` decimal(19,2) NOT NULL,
				  `tax` decimal(19,2) NOT NULL,
				  `money_with_tax` decimal(19,2) NOT NULL,
				  `dmw_count` decimal(19,8) NOT NULL,
				  `left_count` decimal(19,8) NOT NULL,
				  `date_created` datetime DEFAULT NULL,
				  `data_org` varchar(255) DEFAULT NULL,
				  `company_id` varchar(255) DEFAULT NULL,
				  `memo` varchar(255) DEFAULT NULL,
				  `goods_price_with_tax` decimal(19,2) DEFAULT NULL,
				  PRIMARY KEY (`id`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8;
		";
    $db->execute($sql);

    // t_dmw_bill
    $sql = "CREATE TABLE IF NOT EXISTS `t_dmw_bill` (
				  `id` varchar(255) NOT NULL,
				  `ref` varchar(255) NOT NULL,
				  `factory_id` varchar(255) NOT NULL,
				  `warehouse_id` varchar(255) NOT NULL,
				  `biz_user_id` varchar(255) NOT NULL,
				  `biz_dt` datetime NOT NULL,
				  `input_user_id` varchar(255) NOT NULL,
				  `date_created` datetime DEFAULT NULL,
				  `bill_status` int(11) NOT NULL,
				  `goods_money` decimal(19,2) NOT NULL,
				  `payment_type` int(11) NOT NULL DEFAULT 0,
				  `data_org` varchar(255) DEFAULT NULL,
				  `company_id` varchar(255) DEFAULT NULL,
				  `bill_memo` varchar(255) DEFAULT NULL,
				  `tax` decimal(19,2) DEFAULT NULL,
				  `money_with_tax` decimal(19,2) DEFAULT NULL,
				  PRIMARY KEY (`id`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8;
		";
    $db->execute($sql);

    // t_dmw_bill_detail
    $sql = "CREATE TABLE IF NOT EXISTS `t_dmw_bill_detail` (
				  `id` varchar(255) NOT NULL,
				  `dmwbill_id` varchar(255) NOT NULL,
				  `show_order` int(11) NOT NULL,
				  `goods_id` varchar(255) NOT NULL,
				  `goods_count` decimal(19,8) NOT NULL,
				  `goods_money` decimal(19,2) NOT NULL,
				  `goods_price` decimal(19,2) NOT NULL,
				  `date_created` datetime DEFAULT NULL,
				  `memo` varchar(1000) DEFAULT NULL,
				  `data_org` varchar(255) DEFAULT NULL,
				  `company_id` varchar(255) DEFAULT NULL,
				  `dmobilldetail_id` varchar(255) DEFAULT NULL,
				  `tax_rate` decimal(19,2) DEFAULT NULL,
				  `tax` decimal(19,2) DEFAULT NULL,
				  `money_with_tax` decimal(19,2) DEFAULT NULL,
				  `goods_price_with_tax` decimal(19,2) DEFAULT NULL,
				  PRIMARY KEY (`id`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8;
		";
    $db->execute($sql);

    // t_dmo_dmw
    $sql = "CREATE TABLE IF NOT EXISTS `t_dmo_dmw` (
				  `dmo_id` varchar(255) NOT NULL,
				  `dmw_id` varchar(255) NOT NULL
				) ENGINE=InnoDB DEFAULT CHARSET=utf8;
		";
    $db->execute($sql);

    // t_supplier_goods_range
    $sql = "CREATE TABLE IF NOT EXISTS `t_supplier_goods_range` (
				  `id` varchar(255) NOT NULL,
				  `supplier_id` varchar(255) NOT NULL,
				  `g_id` varchar(255) NOT NULL,
				  `g_id_type` int(11) NOT NULL,
				  PRIMARY KEY (`id`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8;
		";
    $db->execute($sql);

    // t_so_po
    $sql = "CREATE TABLE IF NOT EXISTS `t_so_po` (
				  `so_id` varchar(255) NOT NULL,
				  `po_id` varchar(255) NOT NULL
				) ENGINE=InnoDB DEFAULT CHARSET=utf8;
		";
    $db->execute($sql);

    // t_dict_table_category
    $sql = "CREATE TABLE IF NOT EXISTS `t_dict_table_category` (
				  `id` varchar(255) NOT NULL,
				  `code` varchar(255) NOT NULL,
				  `name` varchar(255) NOT NULL,
				  `parent_id` varchar(255) DEFAULT NULL,
				  PRIMARY KEY (`id`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8;
		";
    $db->execute($sql);

    // t_dict_table_md
    $sql = "CREATE TABLE IF NOT EXISTS `t_dict_table_md` (
				  `id` varchar(255) NOT NULL,
				  `code` varchar(255) NOT NULL,
				  `name` varchar(255) NOT NULL,
				  `table_name` varchar(255) NOT NULL,
				  `category_id` varchar(255) NOT NULL,
				  `memo` varchar(1000) DEFAULT NULL,
				  `py` varchar(255) DEFAULT NULL,
				  PRIMARY KEY (`id`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8;
		";
    $db->execute($sql);

    // t_code_table_category
    $sql = "CREATE TABLE IF NOT EXISTS `t_code_table_category` (
              `id` varchar(255) NOT NULL,
              `code` varchar(255) NOT NULL,
              `name` varchar(255) NOT NULL,
              `parent_id` varchar(255) DEFAULT NULL,
              `is_system` int(11) NOT NULL DEFAULT 2,
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
            ";
    $db->execute($sql);

    // t_code_table_md
    $sql = "CREATE TABLE IF NOT EXISTS `t_code_table_md` (
              `id` varchar(255) NOT NULL,
              `code` varchar(255) NOT NULL,
              `name` varchar(255) NOT NULL,
              `table_name` varchar(255) NOT NULL,
              `category_id` varchar(255) NOT NULL,
              `memo` varchar(1000) DEFAULT NULL,
              `py` varchar(255) DEFAULT NULL,
              `fid` varchar(255) DEFAULT NULL,
              `md_version` int(11) NOT NULL DEFAULT 1,
              `is_fixed` int(11) NOT NULL DEFAULT 2,
              `enable_parent_id` int(11) NOT NULL DEFAULT 0,
              `handler_class_name` varchar(255) DEFAULT NULL,
              `module_name` varchar(255) DEFAULT NULL,
              `edit_col_cnt` int(11) NOT NULL DEFAULT 1,
              `view_paging` int(11) NOT NULL DEFAULT 2,
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
            ";
    $db->execute($sql);

    // t_code_table_cols_md
    $sql = "CREATE TABLE IF NOT EXISTS `t_code_table_cols_md` (
              `id` varchar(255) NOT NULL,
              `table_id` varchar(255) NOT NULL,
              `caption` varchar(255) NOT NULL,
              `db_field_name` varchar(255) NOT NULL,
              `db_field_type` varchar(255) NOT NULL,
              `db_field_length` int(11) NOT NULL,
              `db_field_decimal` int(11) NOT NULL,
              `show_order` int(11) NOT NULL,
              `value_from` int(11) DEFAULT NULL,
              `value_from_table_name` varchar(255) DEFAULT NULL,
              `value_from_col_name` varchar(255) DEFAULT NULL,
              `value_from_col_name_display` varchar(255) DEFAULT NULL,
              `must_input` int(11) DEFAULT 1,
              `sys_col` int(11) DEFAULT 1,
              `is_visible` int(11) DEFAULT 1,
              `width_in_view` int(11) DEFAULT NULL,
              `note` varchar(1000) DEFAULT NULL,
              `show_order_in_view` int(11) NOT NULL DEFAULT -1,
              `editor_xtype` varchar(255) NOT NULL DEFAULT 'textfield',
              `col_span` int(11) NOT NULL DEFAULT 1,
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
            ";
    $db->execute($sql);

    // t_fid_plus
    $sql = "CREATE TABLE IF NOT EXISTS `t_fid_plus` (
              `fid` varchar(255) NOT NULL,
              `name` varchar(255) NOT NULL,
              `py` varchar(255) DEFAULT NULL,
              `memo` varchar(255) DEFAULT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
            ";
    $db->execute($sql);

    // t_menu_item_plus
    $sql = "CREATE TABLE IF NOT EXISTS `t_menu_item_plus` (
              `id` varchar(255) NOT NULL,
              `caption` varchar(255) NOT NULL,
              `fid` varchar(255) DEFAULT NULL,
              `parent_id` varchar(255) DEFAULT NULL,
              `show_order` int(11) NOT NULL,
              `py` varchar(255) DEFAULT NULL,
              `memo` varchar(255) DEFAULT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
            ";
    $db->execute($sql);

    // t_permission_plus
    $sql = "CREATE TABLE IF NOT EXISTS `t_permission_plus` (
              `id` varchar(255) NOT NULL,
              `fid` varchar(255) NOT NULL,
              `name` varchar(255) NOT NULL,
              `note` varchar(255) DEFAULT NULL,
              `category` varchar(255) DEFAULT NULL,
              `py` varchar(255) DEFAULT NULL,
              `show_order` int(11) DEFAULT NULL,
              `parent_fid` varchar(255) DEFAULT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
            ";
    $db->execute($sql);

    // t_sysdict_record_status
    $sql = "CREATE TABLE IF NOT EXISTS `t_sysdict_record_status` (
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

    // t_sysdict_editor_xtype
    $sql = "CREATE TABLE IF NOT EXISTS `t_sysdict_editor_xtype` (
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

    // t_form_category
    $sql = "CREATE TABLE IF NOT EXISTS `t_form_category` (
              `id` varchar(255) NOT NULL,
              `code` varchar(255) NOT NULL,
              `name` varchar(255) NOT NULL,
              `parent_id` varchar(255) DEFAULT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
            ";
    $db->execute($sql);

    // t_form_md
    $sql = "CREATE TABLE IF NOT EXISTS `t_form_md` (
              `id` varchar(255) NOT NULL,
              `parent_id` varchar(255) DEFAULT NULL,
              `prop_name` varchar(255) NOT NULL,
              `prop_value` varchar(1000) NOT NULL,
              `show_order` int(11) NOT NULL DEFAULT 0,
              `form_id` varchar(255) NOT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
            ";
    $db->execute($sql);

    // t_form
    $sql = "CREATE TABLE IF NOT EXISTS `t_form` (
              `id` varchar(255) NOT NULL,
              `code` varchar(255) NOT NULL,
              `name` varchar(1000) NOT NULL,
              `category_id` varchar(255) NOT NULL,
              `sys_form` int(11) NOT NULL DEFAULT 0,
              `md_version` int(11) NOT NULL DEFAULT 1,
              `memo` varchar(1000) DEFAULT NULL,
              `table_name` varchar(255) NOT NULL,
              `fid` varchar(255) DEFAULT NULL,
              `module_name` varchar(255) DEFAULT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
            ";
    $db->execute($sql);

    // t_form_cols
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
              `data_index` varchar(255) DEFAULT NULL,
              `width_in_view` int(11) NOT NULL DEFAULT 120,
              `show_order_in_view` int(11) NOT NULL DEFAULT -1,
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
            ";
    $db->execute($sql);

    // t_form_detail
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

    // t_form_detail_cols
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
              `data_index` varchar(255) DEFAULT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
            ";
    $db->execute($sql);

    // t_sysdict_form_editor_xtype
    $sql = "CREATE TABLE IF NOT EXISTS `t_sysdict_form_editor_xtype` (
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

    // t_voucher
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

    // t_voucher_detail
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

    // t_raw_material_category
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

    // t_raw_material
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

    // t_material_unit
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

    // t_sysdict_tax_rate
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

    // t_code_table_buttons
    $sql = "CREATE TABLE IF NOT EXISTS `t_code_table_buttons` (
              `id` varchar(255) NOT NULL,
              `table_id` varchar(255) NOT NULL,
              `caption` varchar(255) NOT NULL,
              `fid` varchar(255) NOT NULL,
              `on_click_frontend` varchar(255) DEFAULT NULL,
              `on_click_backend` varchar(255) DEFAULT NULL,
              `show_order` int(11) NOT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
            ";
    $db->execute($sql);

    // t_fv_category
    $sql = "CREATE TABLE IF NOT EXISTS `t_fv_category` (
              `id` varchar(255) NOT NULL,
              `code` varchar(255) NOT NULL,
              `name` varchar(255) NOT NULL,
              `parent_id` varchar(255) DEFAULT NULL,
              `is_system` int(11) NOT NULL DEFAULT 2,
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
            ";
    $db->execute($sql);

    // t_fv
    $sql = "CREATE TABLE IF NOT EXISTS `t_fv` (
              `id` varchar(255) NOT NULL,
              `code` varchar(255) NOT NULL,
              `name` varchar(255) NOT NULL,
              `category_id` varchar(255) NOT NULL,
              `memo` varchar(1000) DEFAULT NULL,
              `py` varchar(255) DEFAULT NULL,
              `fid` varchar(255) DEFAULT NULL,
              `md_version` int(11) NOT NULL DEFAULT 1,
              `is_fixed` int(11) NOT NULL DEFAULT 2,
              `parent_id` varchar(255) DEFAULT NULL,
              `module_name` varchar(255) DEFAULT NULL,
              `xtype` varchar(255) NOT NULL,
              `region` varchar(255) DEFAULT NULL,
              `width_or_height` varchar(255) DEFAULT NULL,
              `layout_type` int(11) NOT NULL DEFAULT 1,
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
            ";
    $db->execute($sql);

    // t_sysdict_fv_xtype
    $sql = "CREATE TABLE IF NOT EXISTS `t_sysdict_fv_xtype` (
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

  /**
   * 插入初始化数据
   */
  private function insertInitData()
  {
    $db = $this->db();

    $db->startTrans();

    // t_fid
    $sql = "INSERT INTO `t_fid` (`fid`, `name`, `py`, `memo`) VALUES
            ('-7999', '自定义表单', 'ZDYBD', ''),
            ('-7994', '系统数据字典', 'XTSJZD', ''),
            ('-7995', '主菜单维护', 'ZCDWH', ''),
            ('-7996', '码表设置', 'MBSZ', ''),
            ('-7997', '视图开发助手', 'STKFZS', ''),
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
            ('2103', '会计期间', 'KJQJ', '');
            ";
    $db->execute($sql);

    // t_menu_item
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
            ('090503', '视图开发助手', '-7997', '0905', 3, 'STKFZS', ''),
            ('090504', '主菜单维护', '-7995', '0905', 4, 'ZCDWH', ''),
            ('090505', '系统数据字典', '-7994', '0905', 5, 'XTSJZD', ''),
            ('10', '帮助', NULL, NULL, 12, '', ''),
            ('1001', '使用帮助', '-9995', '10', 1, 'SYBZ', ''),
            ('1003', '关于', '-9994', '10', 3, 'GY', ''),
            ('13', 'LowCode演示', NULL, NULL, 13, '', '');
            ";
    $db->execute($sql);

    // t_org
    $sql = "INSERT INTO `t_org` (`id`, `full_name`, `name`, `org_code`, `data_org`, `parent_id`) VALUES
					('4D74E1E4-A129-11E4-9B6A-782BCBD7746B', '公司', '公司', '01', '01', NULL),
					('5EBDBE11-A129-11E4-9B6A-782BCBD7746B', '公司\\\\信息部', '信息部', '0199', '0101', '4D74E1E4-A129-11E4-9B6A-782BCBD7746B');
		";
    $db->execute($sql);

    // t_permission
    $sql = "INSERT INTO `t_permission` (`id`, `fid`, `name`, `note`, `category`, `py`, `show_order`) VALUES
            ('-7999', '-7999', '自定义表单', '模块权限：通过菜单进入自定义表单模块的权限', '自定义表单', 'ZDYBD', 100),
            ('-7994', '-7994', '系统数据字典', '模块权限：通过菜单进入系统数据字典模块的权限', '系统数据字典', 'XTSJZD', 100),
            ('-7995', '-7995', '主菜单维护', '模块权限：通过菜单进入主菜单维护模块的权限', '主菜单维护', 'ZCDWH', 100),
            ('-7996', '-7996', '码表设置', '模块权限：通过菜单进入码表设置模块的权限', '码表设置', 'MBSZ', 100),
            ('-7997', '-7997', '视图开发助手', '模块权限：通过菜单进入视图开发助手模块的权限', '视图开发助手', 'STKFZS', 100),
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
            ('2103', '2103', '会计期间', '模块权限：通过菜单进入会计期间模块的权限', '会计期间', 'KJQJ', 100);
            ";
    $db->execute($sql);

    // t_role
    $sql = "INSERT INTO `t_role` (`id`, `name`, `data_org`, `company_id`, `code`) VALUES
				('A83F617E-A153-11E4-A9B8-782BCBD7746B', '系统管理', '01010001', '4D74E1E4-A129-11E4-9B6A-782BCBD7746B', '01');
				";
    $db->execute($sql);

    // t_role_permission
    $sql = "INSERT INTO `t_role_permission` (`role_id`, `permission_id`) VALUES
					('A83F617E-A153-11E4-A9B8-782BCBD7746B', '-8999'),
					('A83F617E-A153-11E4-A9B8-782BCBD7746B', '-8997'),
					('A83F617E-A153-11E4-A9B8-782BCBD7746B', '-8996'),
					('A83F617E-A153-11E4-A9B8-782BCBD7746B', '1001'),
					('A83F617E-A153-11E4-A9B8-782BCBD7746B', '1002'),
					('A83F617E-A153-11E4-A9B8-782BCBD7746B', '1003'),
					('A83F617E-A153-11E4-A9B8-782BCBD7746B', '1004'),
					('A83F617E-A153-11E4-A9B8-782BCBD7746B', '1007'),
					('A83F617E-A153-11E4-A9B8-782BCBD7746B', '2000'),
					('A83F617E-A153-11E4-A9B8-782BCBD7746B', '2001'),
					('A83F617E-A153-11E4-A9B8-782BCBD7746B', '2002'),
					('A83F617E-A153-11E4-A9B8-782BCBD7746B', '2002-01'),
					('A83F617E-A153-11E4-A9B8-782BCBD7746B', '2003'),
					('A83F617E-A153-11E4-A9B8-782BCBD7746B', '2004'),
					('A83F617E-A153-11E4-A9B8-782BCBD7746B', '2005'),
					('A83F617E-A153-11E4-A9B8-782BCBD7746B', '2006'),
					('A83F617E-A153-11E4-A9B8-782BCBD7746B', '2007'),
					('A83F617E-A153-11E4-A9B8-782BCBD7746B', '2008'),
					('A83F617E-A153-11E4-A9B8-782BCBD7746B', '2009'),
					('A83F617E-A153-11E4-A9B8-782BCBD7746B', '2010'),
					('A83F617E-A153-11E4-A9B8-782BCBD7746B', '2011-01'),
					('A83F617E-A153-11E4-A9B8-782BCBD7746B', '2011-02'),
					('A83F617E-A153-11E4-A9B8-782BCBD7746B', '2011-03'),
					('A83F617E-A153-11E4-A9B8-782BCBD7746B', '2011-04'),
					('A83F617E-A153-11E4-A9B8-782BCBD7746B', '2012'),
					('A83F617E-A153-11E4-A9B8-782BCBD7746B', '2013'),
					('A83F617E-A153-11E4-A9B8-782BCBD7746B', '2014'),
					('A83F617E-A153-11E4-A9B8-782BCBD7746B', '2015'),
					('A83F617E-A153-11E4-A9B8-782BCBD7746B', '2016'),
					('A83F617E-A153-11E4-A9B8-782BCBD7746B', '2017'),
					('A83F617E-A153-11E4-A9B8-782BCBD7746B', '2018'),
					('A83F617E-A153-11E4-A9B8-782BCBD7746B', '2019'),
					('A83F617E-A153-11E4-A9B8-782BCBD7746B', '2020'),
					('A83F617E-A153-11E4-A9B8-782BCBD7746B', '2021'),
					('A83F617E-A153-11E4-A9B8-782BCBD7746B', '2022'),
					('A83F617E-A153-11E4-A9B8-782BCBD7746B', '2023'),
					('A83F617E-A153-11E4-A9B8-782BCBD7746B', '2024'),
					('A83F617E-A153-11E4-A9B8-782BCBD7746B', '2025'),
					('A83F617E-A153-11E4-A9B8-782BCBD7746B', '2026'),
					('A83F617E-A153-11E4-A9B8-782BCBD7746B', '2027'),
					('A83F617E-A153-11E4-A9B8-782BCBD7746B', '2027-01'),
					('A83F617E-A153-11E4-A9B8-782BCBD7746B', '2027-02'),
					('A83F617E-A153-11E4-A9B8-782BCBD7746B', '2028'),
					('A83F617E-A153-11E4-A9B8-782BCBD7746B', '2029'),
					('A83F617E-A153-11E4-A9B8-782BCBD7746B', '2030-01'),
					('A83F617E-A153-11E4-A9B8-782BCBD7746B', '2030-02'),
					('A83F617E-A153-11E4-A9B8-782BCBD7746B', '2030-03'),
					('A83F617E-A153-11E4-A9B8-782BCBD7746B', '2031');
		";
    $db->execute($sql);

    // t_role_user
    $sql = "INSERT INTO `t_role_user` (`role_id`, `user_id`) VALUES
					('A83F617E-A153-11E4-A9B8-782BCBD7746B', '6C2A09CD-A129-11E4-9B6A-782BCBD7746B');
		";
    $db->execute($sql);

    // t_user
    $sql = "INSERT INTO `t_user` (`id`, `enabled`, `login_name`, `name`, `org_id`, `org_code`, `data_org`, `password`, `py`) VALUES
					('6C2A09CD-A129-11E4-9B6A-782BCBD7746B', '1', 'admin', '系统管理员', '5EBDBE11-A129-11E4-9B6A-782BCBD7746B', '019901', '01010001', '21232f297a57a5a743894a0e4a801fc3', 'XTGLY');
		";
    $db->execute($sql);

    // t_config
    $sql = "INSERT INTO `t_config` (`id`, `name`, `value`, `note`, `show_order`) VALUES
					('9000-01', '公司名称', '', '', 100),
					('9000-02', '公司地址', '', '', 101),
					('9000-03', '公司电话', '', '', 102),
					('9000-04', '公司传真', '', '', 103),
					('9000-05', '公司邮编', '', '', 104),
					('2001-01', '采购入库默认仓库', '', '', 200),
					('2002-02', '销售出库默认仓库', '', '', 300),
					('2002-01', '销售出库单允许编辑销售单价', '0', '当允许编辑的时候，还需要给用户赋予权限[销售出库单允许编辑销售单价]', 301),
					('1003-02', '存货计价方法', '0', '', 401),
					('9001-01', '增值税税率', '17', '', 501),
					('9002-01', '产品名称', 'PSI', '', 0),
					('9003-01', '采购订单单号前缀', 'PO', '', 601),
					('9003-02', '采购入库单单号前缀', 'PW', '', 602),
					('9003-03', '采购退货出库单单号前缀', 'PR', '', 603),
					('9003-04', '销售出库单单号前缀', 'WS', '', 604),
					('9003-05', '销售退货入库单单号前缀', 'SR', '', 605),
					('9003-06', '调拨单单号前缀', 'IT', '', 606),
					('9003-07', '盘点单单号前缀', 'IC', '', 607),
					('9003-08', '销售订单单号前缀', 'SO', '', 608);
				";
    $db->execute($sql);

    $sql = "update t_config set company_id = '4D74E1E4-A129-11E4-9B6A-782BCBD7746B' ;";
    $db->execute($sql);

    // t_menu_item_h5
    $sql = "INSERT INTO `t_menu_item_h5` (`id`, `caption`, `fid`, `parent_id`, `show_order`) VALUES
				('01', '销售', NULL, NULL, 1),
				('0101', '销售订单', '2028', '01', 1),
				('02', '客户关系', NULL, NULL, 2),
				('0201', '客户资料', '1007', '02', 1);
		";
    $db->execute($sql);

    // t_dict_table_category
    $sql = "INSERT INTO `t_dict_table_category` (`id`, `code`, `name`, `parent_id`) VALUES
            ('01', '01', '码表', NULL),
            ('02', '02', '自定义表单', NULL),
            ('03', '03', '视图', NULL),
            ('10', '10', '基础数据', NULL);
            ";
    $db->execute($sql);

    // t_dict_table_md
    $sql = "INSERT INTO `t_dict_table_md` (`id`, `code`, `name`, `table_name`, `category_id`, `memo`, `py`) VALUES
            ('0101', '0101', '码表记录状态', 't_sysdict_record_status', '01', '码表记录的状态', 'MBJLZT'),
            ('0102', '0102', '码表字段编辑器类型', 't_sysdict_editor_xtype', '01', '码表字段编辑器的类型', 'MBZDBJQLX'),
            ('0201', '0201', '表单字段编辑器类型', 't_sysdict_form_editor_xtype', '02', '表单字段编辑器的类型', 'BDZDBJQLX'),
            ('0301', '0301', '视图xtype', 't_sysdict_fv_xtype', '03', '视图的组件类型(xtype)', 'STXTYPE'),
            ('1001', '1001', '税率', 't_sysdict_tax_rate', '10', '', 'SL');
            ";
    $db->execute($sql);

    // t_sysdict_record_status
    $sql = "INSERT INTO `t_sysdict_record_status` (`id`, `code`, `code_int`, `name`, `py`, `memo`, `show_order`) VALUES
				('9B90C56E-696E-11E9-B2BF-782BCBD7746B', '1000', 1000, '启用', 'QY', '记录处于启用状态', 1),
				('AC7F3FAB-696E-11E9-B2BF-782BCBD7746B', '0', 0, '停用', 'TY', '记录处于停用状态', 2);
		";
    $db->execute($sql);

    // t_sysdict_editor_xtype
    $sql = "INSERT INTO `t_sysdict_editor_xtype` (`id`, `code`, `code_int`, `name`, `py`, `memo`, `show_order`) VALUES
            ('5104A62E-9F97-11E9-9BDF-F0BF9790E21F', '1', 1, 'textfield', 'textfield', '字符串编辑器', 1),
            ('7B795BEF-9F97-11E9-9BDF-F0BF9790E21F', '2', 2, 'numberfield', 'numberfield', '数值编辑器', 2),
            ('FD1F4CF4-9F97-11E9-9BDF-F0BF9790E21F', '3', 3, 'psi_codetable_parentidfield', 'psi_codetable_parentidfield', '上级记录编辑器', 3),
            ('9929B6FA-AD1A-11E9-B2D3-F0BF9790E21F', '4', 4, 'psi_codetable_recordreffield', 'psi_codetable_recordreffield', '码表记录引用字段编辑器', 4);
            ";
    $db->execute($sql);

    // t_sysdict_form_editor_xtype
    $sql = "INSERT INTO `t_sysdict_form_editor_xtype` (`id`, `code`, `code_int`, `name`, `py`, `memo`, `show_order`) VALUES
            ('133BC834-62A4-11EA-BE39-F0BF9790E21F', '1', 1, 'textfield', 'textfield', '字符字段编辑器', 1),
            ('2E01A0A4-62A4-11EA-BE39-F0BF9790E21F', '2', 2, 'numberfield', 'numberfield', '数值字段编辑器', 2),
            ('28D248CD-843D-11EA-8C00-E86A641ED142', '3', 3, 'datefield', 'datefield', '日期字段编辑器', 3),
            ('BF6F569E-843D-11EA-8C00-E86A641ED142', '4', 4, 'displayfield', 'displayfield', '不使用编辑器', 4),
            ('CD0B859B-843D-11EA-8C00-E86A641ED142', '5', 5, 'psi_userfield', 'psi_userfield', '用户字段编辑器', 5);
            ";
    $db->execute($sql);

    // t_sysdict_fv_xtype
    $sql = "INSERT INTO `t_sysdict_fv_xtype` (`id`, `code`, `code_int`, `name`, `py`, `memo`, `show_order`) VALUES
            ('882978F6-90CA-11EA-B303-E86A641ED142', '1', 1, 'psi_codetable_view_cmp', 'psi_codetable_view_cmp', '数据来自码表的视图', 1);
            ";
    $db->execute($sql);

    // t_sysdict_tax_rate
    $sql = "INSERT INTO `t_sysdict_tax_rate` (`id`, `code`, `code_int`, `name`, `py`, `memo`, `show_order`) VALUES
            ('F2AEB999-8869-11EA-B3D2-E86A641ED142', '-1', -1, '[不设定]', '', '', -1),
            ('118BF94A-886A-11EA-B3D2-E86A641ED142', '0', 0, '0%', '', '', 0),
            ('1C249830-886A-11EA-B3D2-E86A641ED142', '1', 1, '1%', '', '', 1),
            ('24A26BFF-886A-11EA-B3D2-E86A641ED142', '2', 2, '2%', '', '', 2),
            ('2D865B3E-886A-11EA-B3D2-E86A641ED142', '3', 3, '3%', '', '', 3),
            ('386B7BDB-886A-11EA-B3D2-E86A641ED142', '4', 4, '4%', '', '', 4),
            ('43387B7C-886A-11EA-B3D2-E86A641ED142', '5', 5, '5%', '', '', 5),
            ('4B158D04-886A-11EA-B3D2-E86A641ED142', '6', 6, '6%', '', '', 6),
            ('56D55F4C-886A-11EA-B3D2-E86A641ED142', '7', 7, '7%', '', '', 7),
            ('61EA46D7-886A-11EA-B3D2-E86A641ED142', '8', 8, '8%', '', '', 8),
            ('6D4CDDB1-886A-11EA-B3D2-E86A641ED142', '9', 9, '9%', '', '', 9),
            ('77AA107A-886A-11EA-B3D2-E86A641ED142', '10', 10, '10%', '', '', 10),
            ('80D5BB11-886A-11EA-B3D2-E86A641ED142', '11', 11, '11%', '', '', 11),
            ('8DFEA286-886A-11EA-B3D2-E86A641ED142', '12', 12, '12%', '', '', 12),
            ('9A016A14-886A-11EA-B3D2-E86A641ED142', '13', 13, '13%', '', '', 13),
            ('A50DD214-886A-11EA-B3D2-E86A641ED142', '14', 14, '14%', '', '', 14),
            ('AF2AFC03-886A-11EA-B3D2-E86A641ED142', '15', 15, '15%', '', '', 15),
            ('B8DC0446-886A-11EA-B3D2-E86A641ED142', '16', 16, '16%', '', '', 16),
            ('C19955AE-886A-11EA-B3D2-E86A641ED142', '17', 17, '17%', '', '', 17);
            ";
    $db->execute($sql);

    // t_code_table_category
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
            ('2FCB8D75-851A-11EA-B071-E86A641ED142', 'PSI-0006', '工厂', NULL, 1);
            ";
    $db->execute($sql);

    // t_org码表元数据
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

    // t_user码表元数据
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

    // t_warehouse码表元数据
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

    // t_goods_category码表元数据
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

    // t_goods_unit码表元数据
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

    // t_goods_brand码表元数据
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

    // t_psi_db_version
    $sql = "INSERT INTO `t_psi_db_version` (`db_version`, `update_dt`) VALUES
					('%s', now());
		";
    $db->execute($sql, $this->CURRENT_DB_VERSION);

    $db->commit();

    return true;
  }
}

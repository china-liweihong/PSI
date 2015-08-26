<?php

namespace Home\Service;

/**
 * 业务日志Service
 *
 * @author 李静波
 */
class BizlogService extends PSIBaseService {

	public function logList($params) {
		if ($this->isNotOnline()) {
			return $this->emptyResult();
		}
		
		$page = $params["page"];
		$start = $params["start"];
		$limit = $params["limit"];
		
		$db = M();
		
		$sql = "select b.id, u.login_name, u.name, b.ip, b.info, b.date_created, b.log_category 
				from t_biz_log b, t_user u
				where b.user_id = u.id
				order by b.date_created desc
				limit %d, %d ";
		$data = $db->query($sql, $start, $limit);
		$result = array();
		
		foreach ( $data as $i => $v ) {
			$result[$i]["id"] = $v["id"];
			$result[$i]["loginName"] = $v["login_name"];
			$result[$i]["userName"] = $v["name"];
			$result[$i]["ip"] = $v["ip"];
			$result[$i]["content"] = $v["info"];
			$result[$i]["dt"] = $v["date_created"];
			$result[$i]["logCategory"] = $v["log_category"];
		}
		
		$sql = "select count(*) as cnt 
				from t_biz_log b, t_user u
				where b.user_id = u.id";
		$data = $db->query($sql);
		$cnt = $data[0]["cnt"];
		
		return array(
				"logs" => $result,
				"totalCount" => $cnt
		);
	}

	public function insertBizlog($log, $category = "系统") {
		try {
			$us = new UserService();
			if ($us->getLoginUserId() == null) {
				return;
			}
			
			$sql = "insert into t_biz_log (user_id, info, ip, date_created, log_category) 
					values ('%s', '%s', '%s',  now(), '%s')";
			M()->execute($sql, $us->getLoginUserId(), $log, $this->getClientIP(), $category);
		} catch ( Exception $ex ) {
		}
	}

	private function getClientIP() {
		if ($this->isMOPAAS()) {
			// 部署在http://psi.oschina.mopaas.com
			
			// 下面的代码参考：http://git.oschina.net/silentboy/testphp/blob/master/index.php
			$ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
			if ($ip) {
				$result = explode(",", $ip);
				if ($result) {
					return $result[0];
				}
			}
			
			if ($_SERVER["HTTP_CLIENT_IP"]) {
				$ip = $_SERVER["HTTP_CLIENT_IP"];
			} else {
				$ip = $_SERVER["REMOTE_ADDR"];
			}
			
			if ($ip) {
				return $ip;
			}
		}
		
		return get_client_ip();
	}

	private function tableExists($db, $tableName) {
		$dbName = C('DB_NAME');
		$sql = "select count(*) as cnt
				from information_schema.columns
				where table_schema = '%s' 
					and table_name = '%s' ";
		$data = $db->query($sql, $dbName, $tableName);
		return $data[0]["cnt"] != 0;
	}

	private function columnExists($db, $tableName, $columnName) {
		$dbName = C('DB_NAME');
		
		$sql = "select count(*) as cnt
				from information_schema.columns
				where table_schema = '%s' 
					and table_name = '%s'
					and column_name = '%s' ";
		$data = $db->query($sql, $dbName, $tableName, $columnName);
		$cnt = $data[0]["cnt"];
		return $cnt == 1;
	}
	private $CURRENT_DB_VERSION = "20150823-001";

	public function updateDatabase() {
		if ($this->isNotOnline()) {
			return $this->notOnlineError();
		}
		
		$db = M();
		
		// 检查t_psi_db_version是否存在
		if (! $this->tableExists($db, "t_psi_db_version")) {
			return $this->bad("表t_psi_db_db_version不存在，数据库结构实在是太久远了，无法升级");
		}
		
		// 检查t_psi_db_version中的版本号
		$sql = "select db_version from t_psi_db_version";
		$data = $db->query($sql);
		$dbVersion = $data[0]["db_version"];
		if ($dbVersion == $this->CURRENT_DB_VERSION) {
			return $this->bad("当前数据库是最新版本，不用升级");
		}
		
		$this->t_config($db);
		$this->t_customer($db);
		$this->t_supplier($db);
		
		$sql = "truncate table t_psi_db_version;
				insert into t_psi_db_version (db_version, update_dt) 
				values ('%s', now());";
		$db->execute($sql, $this->CURRENT_DB_VERSION);
		
		$this->insertBizlog("升级数据库，数据库版本 = " . $this->CURRENT_DB_VERSION);
		
		return $this->ok();
	}

	private function t_config($db) {
		$sql = "
			TRUNCATE TABLE `t_config`;
			INSERT INTO `t_config` (`id`, `name`, `value`, `note`, `show_order`) VALUES
			('9000-01', '公司名称', '', '', 100),
			('9000-02', '公司地址', '', '', 101),
			('9000-03', '公司电话', '', '', 102),
			('9000-04', '公司传真', '', '', 103),
			('9000-05', '公司邮编', '', '', 104),
			('2001-01', '采购入库默认仓库', '', '', 200),
			('2002-02', '销售出库默认仓库', '', '', 300),
			('2002-01', '销售出库单允许编辑销售单价', '0', '当允许编辑的时候，还需要给用户赋予权限[销售出库单允许编辑销售单价]', 301),
			('1003-01', '仓库需指定组织机构', '0', '当仓库需要指定组织机构的时候，就意味着可以控制仓库的使用人', 401);
				";
		$db->execute($sql);
	}

	private function t_customer($db) {
		$tableName = "t_customer";
		
		$columnName = "bank_name";
		if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} varchar(255) default null;";
			$db->execute($sql);
		}
		
		$columnName = "bank_account";
		if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} varchar(255) default null;";
			$db->execute($sql);
		}
		
		$columnName = "tax_number";
		if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} varchar(255) default null;";
			$db->execute($sql);
		}
		
		$columnName = "fax";
		if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} varchar(255) default null;";
			$db->execute($sql);
		}
		
		$columnName = "note";
		if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} varchar(255) default null;";
			$db->execute($sql);
		}
	}

	private function t_supplier($db) {
		$tableName = "t_supplier";
		
		$columnName = "bank_name";
		if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} varchar(255) default null;";
			$db->execute($sql);
		}
		
		$columnName = "bank_account";
		if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} varchar(255) default null;";
			$db->execute($sql);
		}
		
		$columnName = "tax_number";
		if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} varchar(255) default null;";
			$db->execute($sql);
		}
		
		$columnName = "fax";
		if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} varchar(255) default null;";
			$db->execute($sql);
		}
		
		$columnName = "note";
		if (! $this->columnExists($db, $tableName, $columnName)) {
			$sql = "alter table {$tableName} add {$columnName} varchar(255) default null;";
			$db->execute($sql);
		}
	}
}
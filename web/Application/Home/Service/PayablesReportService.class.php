<?php

namespace Home\Service;

/**
 * 应付账款报表Service
 *
 * @author 李静波
 */
class PayablesReportService extends PSIBaseService {

	private function caTypeToName($caType) {
		switch ($caType) {
			case "customer" :
				return "客户";
			case "supplier" :
				return "供应商";
			case "factory" :
				return "工厂";
			default :
				return "";
		}
	}

	/**
	 * 应付账款账龄分析
	 */
	public function payablesAgeQueryData($params) {
		if ($this->isNotOnline()) {
			return $this->emptyResult();
		}
		
		$page = $params["page"];
		$start = $params["start"];
		$limit = intval($params["limit"]);
		$showAllData = $limit == - 1;
		
		$result = array();
		
		$us = new UserService();
		$companyId = $us->getCompanyId();
		
		$db = M();
		$sql = "select t.ca_type, t.id, t.code, t.name, t.balance_money
				from (
					select p.ca_type, c.id, c.code, c.name, p.balance_money
					from t_payables p, t_customer c
					where p.ca_id = c.id and p.ca_type = 'customer'
						and p.company_id = '%s'
					union
					select p.ca_type, s.id, s.code, s.name, p.balance_money
					from t_payables p, t_supplier s
					where p.ca_id = s.id and p.ca_type = 'supplier'
						and p.company_id = '%s'
					union
					select p.ca_type, f.id, f.code, f.name, p.balance_money
					from t_payables p, t_factory f
					where p.ca_id = f.id and p.ca_type = 'factory'
						and p.company_id = '%s'
				) t
				order by t.ca_type desc, t.code ";
		if (! $showAllData) {
			$sql .= " limit %d, %d";
		}
		$data = $showAllData ? $db->query($sql, $companyId, $companyId, $companyId) : $db->query(
				$sql, $companyId, $companyId, $companyId, $start, $limit);
		
		foreach ( $data as $i => $v ) {
			$caType = $v["ca_type"];
			$result[$i]["caType"] = $this->caTypeToName($caType);
			$caId = $v["id"];
			$result[$i]["caCode"] = $v["code"];
			$result[$i]["caName"] = $v["name"];
			$result[$i]["balanceMoney"] = $v["balance_money"];
			
			// 账龄30天内
			$sql = "select sum(balance_money) as balance_money
					from t_payables_detail
					where ca_type = '%s' and ca_id = '%s'
						and datediff(current_date(), biz_date) < 30
						and company_id = '%s'
					";
			$data = $db->query($sql, $caType, $caId, $companyId);
			$bm = $data[0]["balance_money"];
			if (! $bm) {
				$bm = 0;
			}
			$result[$i]["money30"] = $bm;
			
			// 账龄30-60天
			$sql = "select sum(balance_money) as balance_money
					from t_payables_detail
					where ca_type = '%s' and ca_id = '%s'
						and datediff(current_date(), biz_date) <= 60
						and datediff(current_date(), biz_date) >= 30
						and company_id = '%s'
					";
			$data = $db->query($sql, $caType, $caId, $companyId);
			$bm = $data[0]["balance_money"];
			if (! $bm) {
				$bm = 0;
			}
			$result[$i]["money30to60"] = $bm;
			
			// 账龄60-90天
			$sql = "select sum(balance_money) as balance_money
					from t_payables_detail
					where ca_type = '%s' and ca_id = '%s'
						and datediff(current_date(), biz_date) <= 90
						and datediff(current_date(), biz_date) > 60
						and company_id = '%s'
					";
			$data = $db->query($sql, $caType, $caId, $companyId);
			$bm = $data[0]["balance_money"];
			if (! $bm) {
				$bm = 0;
			}
			$result[$i]["money60to90"] = $bm;
			
			// 账龄90天以上
			$sql = "select sum(balance_money) as balance_money
					from t_payables_detail
					where ca_type = '%s' and ca_id = '%s'
						and datediff(current_date(), biz_date) > 90
						and company_id = '%s'
					";
			$data = $db->query($sql, $caType, $caId, $companyId);
			$bm = $data[0]["balance_money"];
			if (! $bm) {
				$bm = 0;
			}
			$result[$i]["money90"] = $bm;
		}
		
		$sql = "select count(*) as cnt
				from t_payables
				where company_id = '%s'
				";
		$data = $db->query($sql, $companyId);
		$cnt = $data[0]["cnt"];
		
		return array(
				"dataList" => $result,
				"totalCount" => $cnt
		);
	}

	public function payablesSummaryQueryData() {
		if ($this->isNotOnline()) {
			return $this->emptyResult();
		}
		
		$db = M();
		$result = array();
		$us = new UserService();
		$companyId = $us->getCompanyId();
		
		$sql = "select sum(balance_money) as balance_money
				from t_payables
				where company_id = '%s' ";
		$data = $db->query($sql, $companyId);
		$balance = $data[0]["balance_money"];
		if (! $balance) {
			$balance = 0;
		}
		$result[0]["balanceMoney"] = $balance;
		
		// 账龄30天内
		$sql = "select sum(balance_money) as balance_money
				from t_payables_detail
				where datediff(current_date(), biz_date) < 30
					and company_id = '%s' ";
		$data = $db->query($sql, $companyId);
		$balance = $data[0]["balance_money"];
		if (! $balance) {
			$balance = 0;
		}
		$result[0]["money30"] = $balance;
		
		// 账龄30-60天
		$sql = "select sum(balance_money) as balance_money
				from t_payables_detail
				where datediff(current_date(), biz_date) <= 60
					and datediff(current_date(), biz_date) >= 30
					and company_id = '%s' ";
		$data = $db->query($sql, $companyId);
		$balance = $data[0]["balance_money"];
		if (! $balance) {
			$balance = 0;
		}
		$result[0]["money30to60"] = $balance;
		
		// 账龄60-90天
		$sql = "select sum(balance_money) as balance_money
				from t_payables_detail
				where datediff(current_date(), biz_date) <= 90
					and datediff(current_date(), biz_date) > 60
					and company_id = '%s' ";
		$data = $db->query($sql, $companyId);
		$balance = $data[0]["balance_money"];
		if (! $balance) {
			$balance = 0;
		}
		$result[0]["money60to90"] = $balance;
		
		// 账龄大于90天
		$sql = "select sum(balance_money) as balance_money
				from t_payables_detail
				where datediff(current_date(), biz_date) > 90
					and company_id = '%s' ";
		$data = $db->query($sql, $companyId);
		$balance = $data[0]["balance_money"];
		if (! $balance) {
			$balance = 0;
		}
		$result[0]["money90"] = $balance;
		
		return $result;
	}

	/**
	 * 应付账款账龄分析表 - 查询数据，用于Lodop打印
	 *
	 * @param array $params        	
	 */
	public function getPayablesAgeDataForLodopPrint($params) {
		if ($this->isNotOnline()) {
			return $this->emptyResult();
		}
		
		$items = $this->payablesAgeQueryData($params);
		
		$data = $this->payablesSummaryQueryData();
		$v = $data[0];
		
		return [
				"balanceMoney" => $v["balanceMoney"],
				"money30" => $v["money30"],
				"money30to60" => $v["money30to60"],
				"money60to90" => $v["money60to90"],
				"money90" => $v["money90"],
				"printDT" => date("Y-m-d H:i:s"),
				"items" => $items["dataList"]
		];
	}
}
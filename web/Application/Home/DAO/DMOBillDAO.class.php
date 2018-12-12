<?php

namespace Home\DAO;

use Home\Common\FIdConst;

/**
 * 成品委托生产订单 DAO
 *
 * @author 李静波
 */
class DMOBillDAO extends PSIBaseExDAO {

	/**
	 * 获得成品委托生产订单的信息
	 */
	public function dmoBillInfo($params) {
		$db = $this->db;
		
		$companyId = $params["companyId"];
		if ($this->companyIdNotExists($companyId)) {
			return $this->emptyResult();
		}
		
		// 订单id
		$id = $params["id"];
		
		$result = [];
		
		$bcDAO = new BizConfigDAO($db);
		$result["taxRate"] = $bcDAO->getTaxRate($companyId);
		
		$dataScale = $bcDAO->getGoodsCountDecNumber($companyId);
		$fmt = "decimal(19, " . $dataScale . ")";
		
		if ($id) {
			// 编辑采购订单
			$sql = "select p.ref, p.deal_date, p.deal_address, p.factory_id,
						f.name as factory_name, p.contact, p.tel, p.fax,
						p.org_id, o.full_name, p.biz_user_id, u.name as biz_user_name,
						p.payment_type, p.bill_memo, p.bill_status
					from t_dmo_bill p, t_factory f, t_user u, t_org o
					where p.id = '%s' and p.supplier_id = f.id
						and p.biz_user_id = u.id
						and p.org_id = o.id";
			$data = $db->query($sql, $id);
			if ($data) {
				$v = $data[0];
				$result["ref"] = $v["ref"];
				$result["dealDate"] = $this->toYMD($v["deal_date"]);
				$result["dealAddress"] = $v["deal_address"];
				$result["factoryId"] = $v["factory_id"];
				$result["factoryName"] = $v["factory_name"];
				$result["contact"] = $v["contact"];
				$result["tel"] = $v["tel"];
				$result["fax"] = $v["fax"];
				$result["orgId"] = $v["org_id"];
				$result["orgFullName"] = $v["full_name"];
				$result["bizUserId"] = $v["biz_user_id"];
				$result["bizUserName"] = $v["biz_user_name"];
				$result["paymentType"] = $v["payment_type"];
				$result["billMemo"] = $v["bill_memo"];
				$result["billStatus"] = $v["bill_status"];
				
				// 明细表
				$sql = "select p.id, p.goods_id, g.code, g.name, g.spec,
							convert(p.goods_count, " . $fmt . ") as goods_count,
							p.goods_price, p.goods_money,
							p.tax_rate, p.tax, p.money_with_tax, u.name as unit_name, p.memo
						from t_dmo_bill_detail p, t_goods g, t_goods_unit u
						where p.pobill_id = '%s' and p.goods_id = g.id and g.unit_id = u.id
						order by p.show_order";
				$items = [];
				$data = $db->query($sql, $id);
				
				foreach ( $data as $v ) {
					$items[] = [
							"goodsId" => $v["goods_id"],
							"goodsCode" => $v["code"],
							"goodsName" => $v["name"],
							"goodsSpec" => $v["spec"],
							"goodsCount" => $v["goods_count"],
							"goodsPrice" => $v["goods_price"],
							"goodsMoney" => $v["goods_money"],
							"taxRate" => $v["tax_rate"],
							"tax" => $v["tax"],
							"moneyWithTax" => $v["money_with_tax"],
							"unitName" => $v["unit_name"],
							"memo" => $v["memo"]
					];
				}
				
				$result["items"] = $items;
			}
		} else {
			// 新建
			$loginUserId = $params["loginUserId"];
			$result["bizUserId"] = $loginUserId;
			$result["bizUserName"] = $params["loginUserName"];
			
			$sql = "select o.id, o.full_name
					from t_org o, t_user u
					where o.id = u.org_id and u.id = '%s' ";
			$data = $db->query($sql, $loginUserId);
			if ($data) {
				$result["orgId"] = $data[0]["id"];
				$result["orgFullName"] = $data[0]["full_name"];
			}
		}
		
		return $result;
	}

	/**
	 * 新建成品委托生产订单
	 *
	 * @param array $bill        	
	 * @return array|null
	 */
	public function addDMOBill(& $bill) {
		$db = $this->db;
		
		return $this->todo();
	}

	/**
	 * 编辑成品委托生产订单
	 *
	 * @param array $bill        	
	 * @return array|null
	 */
	public function updateDMOBill(& $bill) {
		$db = $this->db;
		
		return $this->todo();
	}

	/**
	 * 获得成品委托生产订单主表信息列表
	 */
	public function dmobillList($params) {
		$db = $this->db;
		
		$start = $params["start"];
		$limit = $params["limit"];
		
		$billStatus = $params["billStatus"];
		$ref = $params["ref"];
		$fromDT = $params["fromDT"];
		$toDT = $params["toDT"];
		$factoryId = $params["factoryId"];
		
		$loginUserId = $params["loginUserId"];
		if ($this->loginUserIdNotExists($loginUserId)) {
			return $this->emptyResult();
		}
		
		$queryParams = [];
		
		$result = [];
		$sql = "select d.id, d.ref, d.bill_status, d.goods_money, d.tax, d.money_with_tax,
					f.name as factory_name, d.contact, d.tel, d.fax, d.deal_address,
					d.deal_date, d.payment_type, d.bill_memo, d.date_created,
					o.full_name as org_name, u1.name as biz_user_name, u2.name as input_user_name,
					d.confirm_user_id, d.confirm_date
				from t_dmo_bill d, t_factory f, t_org o, t_user u1, t_user u2
				where (d.factory_id = f.id) and (d.org_id = o.id)
					and (d.biz_user_id = u1.id) and (d.input_user_id = u2.id) ";
		
		$ds = new DataOrgDAO($db);
		$rs = $ds->buildSQL(FIdConst::DMO, "d", $loginUserId);
		if ($rs) {
			$sql .= " and " . $rs[0];
			$queryParams = $rs[1];
		}
		
		if ($billStatus != - 1) {
			if ($billStatus < 4000) {
				$sql .= " and (d.bill_status = %d) ";
			} else {
				// 订单关闭 - 有多种状态
				$sql .= " and (d.bill_status >= %d) ";
			}
			$queryParams[] = $billStatus;
		}
		if ($ref) {
			$sql .= " and (d.ref like '%s') ";
			$queryParams[] = "%$ref%";
		}
		if ($fromDT) {
			$sql .= " and (d.deal_date >= '%s')";
			$queryParams[] = $fromDT;
		}
		if ($toDT) {
			$sql .= " and (d.deal_date <= '%s')";
			$queryParams[] = $toDT;
		}
		if ($factoryId) {
			$sql .= " and (d.factory_id = '%s')";
			$queryParams[] = $factoryId;
		}
		$sql .= " order by d.deal_date desc, d.ref desc
				  limit %d , %d";
		$queryParams[] = $start;
		$queryParams[] = $limit;
		$data = $db->query($sql, $queryParams);
		foreach ( $data as $v ) {
			$confirmUserName = null;
			$confirmDate = null;
			$confirmUserId = $v["confirm_user_id"];
			if ($confirmUserId) {
				$sql = "select name from t_user where id = '%s' ";
				$d = $db->query($sql, $confirmUserId);
				if ($d) {
					$confirmUserName = $d[0]["name"];
					$confirmDate = $v["confirm_date"];
				}
			}
			
			$result[] = [
					"id" => $v["id"],
					"ref" => $v["ref"],
					"billStatus" => $v["bill_status"],
					"dealDate" => $this->toYMD($v["deal_date"]),
					"dealAddress" => $v["deal_address"],
					"factoryName" => $v["factory_name"],
					"contact" => $v["contact"],
					"tel" => $v["tel"],
					"fax" => $v["fax"],
					"goodsMoney" => $v["goods_money"],
					"tax" => $v["tax"],
					"moneyWithTax" => $v["money_with_tax"],
					"paymentType" => $v["payment_type"],
					"billMemo" => $v["bill_memo"],
					"bizUserName" => $v["biz_user_name"],
					"orgName" => $v["org_name"],
					"inputUserName" => $v["input_user_name"],
					"dateCreated" => $v["date_created"],
					"confirmUserName" => $confirmUserName,
					"confirmDate" => $confirmDate
			];
		}
		
		$sql = "select count(*) as cnt
				from t_dmo_bill d, t_factory f, t_org o, t_user u1, t_user u2
				where (d.factory_id = f.id) and (d.org_id = o.id)
					and (d.biz_user_id = u1.id) and (d.input_user_id = u2.id)
				";
		$queryParams = [];
		$ds = new DataOrgDAO($db);
		$rs = $ds->buildSQL(FIdConst::DMO, "d", $loginUserId);
		if ($rs) {
			$sql .= " and " . $rs[0];
			$queryParams = $rs[1];
		}
		if ($billStatus != - 1) {
			if ($billStatus < 4000) {
				$sql .= " and (d.bill_status = %d) ";
			} else {
				// 订单关闭 - 有多种状态
				$sql .= " and (d.bill_status >= %d) ";
			}
			$queryParams[] = $billStatus;
		}
		if ($ref) {
			$sql .= " and (d.ref like '%s') ";
			$queryParams[] = "%$ref%";
		}
		if ($fromDT) {
			$sql .= " and (d.deal_date >= '%s')";
			$queryParams[] = $fromDT;
		}
		if ($toDT) {
			$sql .= " and (d.deal_date <= '%s')";
			$queryParams[] = $toDT;
		}
		if ($factoryId) {
			$sql .= " and (d.factory_id = '%s')";
			$queryParams[] = $factoryId;
		}
		$data = $db->query($sql, $queryParams);
		$cnt = $data[0]["cnt"];
		
		return [
				"dataList" => $result,
				"totalCount" => $cnt
		];
	}
}
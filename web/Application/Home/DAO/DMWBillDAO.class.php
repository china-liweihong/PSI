<?php

namespace Home\DAO;

use Home\Common\FIdConst;

/**
 * 成品委托生产入库单 DAO
 *
 * @author 李静波
 */
class DMWBillDAO extends PSIBaseExDAO {

	/**
	 * 成品委托生产入库单 - 单据详情
	 */
	public function dmwBillInfo($params) {
		$db = $this->db;
		
		// 公司id
		$companyId = $params["companyId"];
		if ($this->companyIdNotExists($companyId)) {
			return $this->emptyResult();
		}
		
		$bcDAO = new BizConfigDAO($db);
		$dataScale = $bcDAO->getGoodsCountDecNumber($companyId);
		$fmt = "decimal(19, " . $dataScale . ")";
		
		// id: 成品委托生产入库单id
		$id = $params["id"];
		// dmobillRef: 成品委托生产订单单号，可以为空，为空表示直接录入入库单；不为空表示是从成品委托生产订单生成入库单
		$dmobillRef = $params["dmobillRef"];
		
		$result = [
				"id" => $id
		];
		
		$sql = "select p.ref, p.bill_status, p.factory_id, f.name as factory_name,
					p.warehouse_id, w.name as  warehouse_name,
					p.biz_user_id, u.name as biz_user_name, p.biz_dt, p.payment_type,
					p.bill_memo
				from t_dmw_bill p, t_factory f, t_warehouse w, t_user u
				where p.id = '%s' and p.factory_id = f.id and p.warehouse_id = w.id
				  and p.biz_user_id = u.id";
		$data = $db->query($sql, $id);
		if ($data) {
			$v = $data[0];
			$result["ref"] = $v["ref"];
			$result["billStatus"] = $v["bill_status"];
			$result["factoryId"] = $v["factory_id"];
			$result["factoryName"] = $v["factory_name"];
			$result["warehouseId"] = $v["warehouse_id"];
			$result["warehouseName"] = $v["warehouse_name"];
			$result["bizUserId"] = $v["biz_user_id"];
			$result["bizUserName"] = $v["biz_user_name"];
			$result["bizDT"] = $this->toYMD($v["biz_dt"]);
			$result["paymentType"] = $v["payment_type"];
			$result["billMemo"] = $v["bill_memo"];
			
			// 商品明细
			$items = [];
			$sql = "select p.id, p.goods_id, g.code, g.name, g.spec, u.name as unit_name,
						convert(p.goods_count, $fmt) as goods_count, p.goods_price, p.goods_money, p.memo,
						p.dmobilldetail_id
					from t_dmw_bill_detail p, t_goods g, t_goods_unit u
					where p.goods_Id = g.id and g.unit_id = u.id and p.dmwbill_id = '%s'
					order by p.show_order";
			$data = $db->query($sql, $id);
			foreach ( $data as $v ) {
				$items[] = [
						"id" => $v["id"],
						"goodsId" => $v["goods_id"],
						"goodsCode" => $v["code"],
						"goodsName" => $v["name"],
						"goodsSpec" => $v["spec"],
						"unitName" => $v["unit_name"],
						"goodsCount" => $v["goods_count"],
						"goodsPrice" => $v["goods_price"],
						"goodsMoney" => $v["goods_money"],
						"memo" => $v["memo"],
						"dmoBillDetailId" => $v["dmobilldetail_id"]
				];
			}
			
			$result["items"] = $items;
			
			// 查询该单据是否是由成品委托生产订单生成的
			$sql = "select dmo_id from t_dmo_dmw where dmw_id = '%s' ";
			$data = $db->query($sql, $id);
			if ($data) {
				$result["genBill"] = true;
			} else {
				$result["genBill"] = false;
			}
		} else {
			// 新建成品委托生产入库单
			$result["bizUserId"] = $params["loginUserId"];
			$result["bizUserName"] = $params["loginUserName"];
			
			if ($dmobillRef) {
				// 由成品委托生产订单生成入库单
				$sql = "select p.id, p.factory_id, f.name as factory_name, p.deal_date,
							p.payment_type, p.bill_memo
						from t_dmo_bill p, t_factory f
						where p.ref = '%s' and p.supplier_id = s.id ";
				$data = $db->query($sql, $pobillRef);
				if ($data) {
					$v = $data[0];
					$result["factoryId"] = $v["factory_id"];
					$result["factoryName"] = $v["factory_name"];
					$result["dealDate"] = $this->toYMD($v["deal_date"]);
					$result["paymentType"] = $v["payment_type"];
					$result["billMemo"] = $v["bill_memo"];
					
					$dmobillId = $v["id"];
					// 明细
					$items = [];
					$sql = "select p.id, p.goods_id, g.code, g.name, g.spec, u.name as unit_name,
								convert(p.goods_count, $fmt) as goods_count,
								p.goods_price, p.goods_money,
								convert(p.left_count, $fmt) as left_count, p.memo
							from t_dmo_bill_detail p, t_goods g, t_goods_unit u
							where p.dmobill_id = '%s' and p.goods_id = g.id and g.unit_id = u.id
							order by p.show_order ";
					$data = $db->query($sql, $pobillId);
					foreach ( $data as $v ) {
						$items[] = [
								"id" => $v["id"],
								"dmoBillDetailId" => $v["id"],
								"goodsId" => $v["goods_id"],
								"goodsCode" => $v["code"],
								"goodsName" => $v["name"],
								"goodsSpec" => $v["spec"],
								"unitName" => $v["unit_name"],
								"goodsCount" => $v["left_count"],
								"goodsPrice" => $v["goods_price"],
								"goodsMoney" => $v["left_count"] * $v["goods_price"],
								"memo" => $v["memo"]
						];
					}
					
					$result["items"] = $items;
				}
			}
		}
		
		return $result;
	}

	/**
	 * 新建成品委托生产入库单
	 *
	 * @param array $bill        	
	 * @return array|null
	 */
	public function addDMWBill(& $bill) {
		return $this->todo();
	}

	/**
	 * 编辑成品委托生产入库单
	 *
	 * @param array $bill        	
	 * @return array|null
	 */
	public function updateDMWBill(& $bill) {
		return $this->todo();
	}

	/**
	 * 获得成品委托生产入库单主表列表
	 */
	public function dmwbillList($params) {
		$db = $this->db;
		
		$start = $params["start"];
		$limit = $params["limit"];
		
		// 订单状态
		$billStatus = $params["billStatus"];
		
		// 单号
		$ref = $params["ref"];
		
		// 业务日期 -起
		$fromDT = $params["fromDT"];
		// 业务日期-止
		$toDT = $params["toDT"];
		
		// 仓库id
		$warehouseId = $params["warehouseId"];
		
		// 工厂id
		$factoryId = $params["factoryId"];
		
		$loginUserId = $params["loginUserId"];
		if ($this->loginUserIdNotExists($loginUserId)) {
			return $this->emptyResult();
		}
		
		$queryParams = [];
		$sql = "select d.id, d.bill_status, d.ref, d.biz_dt, u1.name as biz_user_name, u2.name as input_user_name,
					d.goods_money, w.name as warehouse_name, f.name as factory_name,
					d.date_created, d.payment_type, d.bill_memo
				from t_dmw_bill d, t_warehouse w, t_factory f, t_user u1, t_user u2
				where (d.warehouse_id = w.id) and (d.factory_id = f.id)
				and (d.biz_user_id = u1.id) and (d.input_user_id = u2.id) ";
		
		$ds = new DataOrgDAO($db);
		// 构建数据域SQL
		$rs = $ds->buildSQL(FIdConst::DMW, "d", $loginUserId);
		if ($rs) {
			$sql .= " and " . $rs[0];
			$queryParams = $rs[1];
		}
		
		if ($billStatus != - 1) {
			$sql .= " and (d.bill_status = %d) ";
			$queryParams[] = $billStatus;
		}
		if ($ref) {
			$sql .= " and (d.ref like '%s') ";
			$queryParams[] = "%{$ref}%";
		}
		if ($fromDT) {
			$sql .= " and (d.biz_dt >= '%s') ";
			$queryParams[] = $fromDT;
		}
		if ($toDT) {
			$sql .= " and (d.biz_dt <= '%s') ";
			$queryParams[] = $toDT;
		}
		if ($factoryId) {
			$sql .= " and (d.factory_id = '%s') ";
			$queryParams[] = $factoryId;
		}
		if ($warehouseId) {
			$sql .= " and (d.warehouse_id = '%s') ";
			$queryParams[] = $warehouseId;
		}
		
		$sql .= " order by d.biz_dt desc, d.ref desc
				limit %d, %d";
		$queryParams[] = $start;
		$queryParams[] = $limit;
		$data = $db->query($sql, $queryParams);
		$result = [];
		
		foreach ( $data as $v ) {
			$result[] = [
					"id" => $v["id"],
					"ref" => $v["ref"],
					"bizDate" => $this->toYMD($v["biz_dt"]),
					"factoryName" => $v["factory_name"],
					"warehouseName" => $v["warehouse_name"],
					"inputUserName" => $v["input_user_name"],
					"bizUserName" => $v["biz_user_name"],
					"billStatus" => $this->billStatusCodeToName($v["bill_status"]),
					"amount" => $v["goods_money"],
					"dateCreated" => $v["date_created"],
					"paymentType" => $v["payment_type"],
					"billMemo" => $v["bill_memo"]
			];
		}
		
		$sql = "select count(*) as cnt
				from t_dmw_bill d, t_warehouse w, t_factory f, t_user u1, t_user u2
				where (d.warehouse_id = w.id) and (d.factory_id = f.id)
				and (d.biz_user_id = u1.id) and (d.input_user_id = u2.id)";
		$queryParams = [];
		$ds = new DataOrgDAO($db);
		$rs = $ds->buildSQL(FIdConst::DMW, "d", $loginUserId);
		if ($rs) {
			$sql .= " and " . $rs[0];
			$queryParams = $rs[1];
		}
		if ($billStatus != - 1) {
			$sql .= " and (d.bill_status = %d) ";
			$queryParams[] = $billStatus;
		}
		if ($ref) {
			$sql .= " and (d.ref like '%s') ";
			$queryParams[] = "%{$ref}%";
		}
		if ($fromDT) {
			$sql .= " and (d.biz_dt >= '%s') ";
			$queryParams[] = $fromDT;
		}
		if ($toDT) {
			$sql .= " and (d.biz_dt <= '%s') ";
			$queryParams[] = $toDT;
		}
		if ($factoryId) {
			$sql .= " and (d.factory_id = '%s') ";
			$queryParams[] = $factoryId;
		}
		if ($warehouseId) {
			$sql .= " and (d.warehouse_id = '%s') ";
			$queryParams[] = $warehouseId;
		}
		
		$data = $db->query($sql, $queryParams);
		$cnt = $data[0]["cnt"];
		
		return [
				"dataList" => $result,
				"totalCount" => $cnt
		];
	}
}
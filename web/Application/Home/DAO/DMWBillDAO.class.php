<?php

namespace Home\DAO;

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
}
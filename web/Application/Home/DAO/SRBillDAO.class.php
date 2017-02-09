<?php

namespace Home\DAO;

use Home\Common\FIdConst;

/**
 * 销售退货入库单 DAO
 *
 * @author 李静波
 */
class SRBillDAO extends PSIBaseExDAO {

	/**
	 * 销售退货入库单主表信息列表
	 */
	public function srbillList($params) {
		$db = $this->db;
		
		$loginUserId = $params["loginUserId"];
		if ($this->loginUserIdNotExists($loginUserId)) {
			return $this->emptyResult();
		}
		
		$page = $params["page"];
		$start = $params["start"];
		$limit = $params["limit"];
		
		$billStatus = $params["billStatus"];
		$ref = $params["ref"];
		$fromDT = $params["fromDT"];
		$toDT = $params["toDT"];
		$warehouseId = $params["warehouseId"];
		$customerId = $params["customerId"];
		$sn = $params["sn"];
		$paymentType = $params["paymentType"];
		
		$sql = "select w.id, w.ref, w.bizdt, c.name as customer_name, u.name as biz_user_name,
				 	user.name as input_user_name, h.name as warehouse_name, w.rejection_sale_money,
				 	w.bill_status, w.date_created, w.payment_type
				 from t_sr_bill w, t_customer c, t_user u, t_user user, t_warehouse h
				 where (w.customer_id = c.id) and (w.biz_user_id = u.id)
				 and (w.input_user_id = user.id) and (w.warehouse_id = h.id) ";
		$queryParams = array();
		
		$ds = new DataOrgDAO($db);
		$rs = $ds->buildSQL(FIdConst::SALE_REJECTION, "w", $loginUserId);
		if ($rs) {
			$sql .= " and " . $rs[0];
			$queryParams = $rs[1];
		}
		
		if ($billStatus != - 1) {
			$sql .= " and (w.bill_status = %d) ";
			$queryParams[] = $billStatus;
		}
		if ($ref) {
			$sql .= " and (w.ref like '%s') ";
			$queryParams[] = "%{$ref}%";
		}
		if ($fromDT) {
			$sql .= " and (w.bizdt >= '%s') ";
			$queryParams[] = $fromDT;
		}
		if ($toDT) {
			$sql .= " and (w.bizdt <= '%s') ";
			$queryParams[] = $toDT;
		}
		if ($customerId) {
			$sql .= " and (w.customer_id = '%s') ";
			$queryParams[] = $customerId;
		}
		if ($warehouseId) {
			$sql .= " and (w.warehouse_id = '%s') ";
			$queryParams[] = $warehouseId;
		}
		if ($sn) {
			$sql .= " and (w.id in (
					  select d.srbill_id
					  from t_sr_bill_detail d
					  where d.sn_note like '%s')) ";
			$queryParams[] = "%$sn%";
		}
		if ($paymentType != - 1) {
			$sql .= " and (w.payment_type = %d) ";
			$queryParams[] = $paymentType;
		}
		
		$sql .= " order by w.bizdt desc, w.ref desc
				 limit %d, %d";
		$queryParams[] = $start;
		$queryParams[] = $limit;
		$data = $db->query($sql, $queryParams);
		$result = array();
		
		foreach ( $data as $i => $v ) {
			$result[$i]["id"] = $v["id"];
			$result[$i]["ref"] = $v["ref"];
			$result[$i]["bizDate"] = date("Y-m-d", strtotime($v["bizdt"]));
			$result[$i]["customerName"] = $v["customer_name"];
			$result[$i]["warehouseName"] = $v["warehouse_name"];
			$result[$i]["inputUserName"] = $v["input_user_name"];
			$result[$i]["bizUserName"] = $v["biz_user_name"];
			$result[$i]["billStatus"] = $v["bill_status"] == 0 ? "待入库" : "已入库";
			$result[$i]["amount"] = $v["rejection_sale_money"];
			$result[$i]["dateCreated"] = $v["date_created"];
			$result[$i]["paymentType"] = $v["payment_type"];
		}
		
		$sql = "select count(*) as cnt
				 from t_sr_bill w, t_customer c, t_user u, t_user user, t_warehouse h
				 where (w.customer_id = c.id) and (w.biz_user_id = u.id)
				 and (w.input_user_id = user.id) and (w.warehouse_id = h.id) ";
		$queryParams = array();
		
		$ds = new DataOrgDAO($db);
		$rs = $ds->buildSQL(FIdConst::SALE_REJECTION, "w", $loginUserId);
		if ($rs) {
			$sql .= " and " . $rs[0];
			$queryParams = $rs[1];
		}
		
		if ($billStatus != - 1) {
			$sql .= " and (w.bill_status = %d) ";
			$queryParams[] = $billStatus;
		}
		if ($ref) {
			$sql .= " and (w.ref like '%s') ";
			$queryParams[] = "%{$ref}%";
		}
		if ($fromDT) {
			$sql .= " and (w.bizdt >= '%s') ";
			$queryParams[] = $fromDT;
		}
		if ($toDT) {
			$sql .= " and (w.bizdt <= '%s') ";
			$queryParams[] = $toDT;
		}
		if ($customerId) {
			$sql .= " and (w.customer_id = '%s') ";
			$queryParams[] = $customerId;
		}
		if ($warehouseId) {
			$sql .= " and (w.warehouse_id = '%s') ";
			$queryParams[] = $warehouseId;
		}
		if ($sn) {
			$sql .= " and (w.id in (
					  select d.srbill_id
					  from t_sr_bill_detail d
					  where d.sn_note like '%s')) ";
			$queryParams[] = "%$sn%";
		}
		if ($paymentType != - 1) {
			$sql .= " and (w.payment_type = %d) ";
			$queryParams[] = $paymentType;
		}
		
		$data = $db->query($sql, $queryParams);
		$cnt = $data[0]["cnt"];
		
		return array(
				"dataList" => $result,
				"totalCount" => $cnt
		);
	}

	/**
	 * 销售退货入库单明细信息列表
	 */
	public function srBillDetailList($params) {
		$db = $this->db;
		
		$id = $params["id"];
		
		$sql = "select s.id, g.code, g.name, g.spec, u.name as unit_name,
				   s.rejection_goods_count, s.rejection_goods_price, s.rejection_sale_money,
					s.sn_note
				from t_sr_bill_detail s, t_goods g, t_goods_unit u
				where s.srbill_id = '%s' and s.goods_id = g.id and g.unit_id = u.id
					and s.rejection_goods_count > 0
				order by s.show_order";
		$data = $db->query($sql, $id);
		
		$result = array();
		
		foreach ( $data as $i => $v ) {
			$result[$i]["id"] = $v["id"];
			$result[$i]["goodsCode"] = $v["code"];
			$result[$i]["goodsName"] = $v["name"];
			$result[$i]["goodsSpec"] = $v["spec"];
			$result[$i]["unitName"] = $v["unit_name"];
			$result[$i]["rejCount"] = $v["rejection_goods_count"];
			$result[$i]["rejPrice"] = $v["rejection_goods_price"];
			$result[$i]["rejSaleMoney"] = $v["rejection_sale_money"];
			$result[$i]["sn"] = $v["sn_note"];
		}
		return $result;
	}

	/**
	 * 列出要选择的可以做退货入库的销售出库单
	 */
	public function selectWSBillList($params) {
		$db = $this->db;
		
		$loginUserId = $params["loginUserId"];
		if ($this->loginUserIdNotExists($loginUserId)) {
			return $this->emptyResult();
		}
		
		$page = $params["page"];
		$start = $params["start"];
		$limit = $params["limit"];
		
		$ref = $params["ref"];
		$customerId = $params["customerId"];
		$warehouseId = $params["warehouseId"];
		$fromDT = $params["fromDT"];
		$toDT = $params["toDT"];
		$sn = $params["sn"];
		
		$sql = "select w.id, w.ref, w.bizdt, c.name as customer_name, u.name as biz_user_name,
				 user.name as input_user_name, h.name as warehouse_name, w.sale_money
				 from t_ws_bill w, t_customer c, t_user u, t_user user, t_warehouse h
				 where (w.customer_id = c.id) and (w.biz_user_id = u.id)
				 and (w.input_user_id = user.id) and (w.warehouse_id = h.id)
				 and (w.bill_status = 1000) ";
		$queryParamas = array();
		
		$ds = new DataOrgDAO($db);
		$rs = $ds->buildSQL(FIdConst::SALE_REJECTION, "w", $loginUserId);
		if ($rs) {
			$sql .= " and " . $rs[0];
			$queryParamas = $rs[1];
		}
		
		if ($ref) {
			$sql .= " and (w.ref like '%s') ";
			$queryParamas[] = "%$ref%";
		}
		if ($customerId) {
			$sql .= " and (w.customer_id = '%s') ";
			$queryParamas[] = $customerId;
		}
		if ($warehouseId) {
			$sql .= " and (w.warehouse_id = '%s') ";
			$queryParamas[] = $warehouseId;
		}
		if ($fromDT) {
			$sql .= " and (w.bizdt >= '%s') ";
			$queryParamas[] = $fromDT;
		}
		if ($toDT) {
			$sql .= " and (w.bizdt <= '%s') ";
			$queryParamas[] = $toDT;
		}
		if ($sn) {
			$sql .= " and (w.id in (
						select wsbill_id
						from t_ws_bill_detail d
						where d.sn_note like '%s'
					))";
			$queryParamas[] = "%$sn%";
		}
		$sql .= " order by w.ref desc
				 limit %d, %d";
		$queryParamas[] = $start;
		$queryParamas[] = $limit;
		$data = $db->query($sql, $queryParamas);
		$result = array();
		
		foreach ( $data as $i => $v ) {
			$result[$i]["id"] = $v["id"];
			$result[$i]["ref"] = $v["ref"];
			$result[$i]["bizDate"] = date("Y-m-d", strtotime($v["bizdt"]));
			$result[$i]["customerName"] = $v["customer_name"];
			$result[$i]["warehouseName"] = $v["warehouse_name"];
			$result[$i]["inputUserName"] = $v["input_user_name"];
			$result[$i]["bizUserName"] = $v["biz_user_name"];
			$result[$i]["amount"] = $v["sale_money"];
		}
		
		$sql = "select count(*) as cnt
				 from t_ws_bill w, t_customer c, t_user u, t_user user, t_warehouse h
				 where (w.customer_id = c.id) and (w.biz_user_id = u.id)
				 and (w.input_user_id = user.id) and (w.warehouse_id = h.id)
				 and (w.bill_status = 1000) ";
		$queryParamas = array();
		$ds = new DataOrgDAO($db);
		$rs = $ds->buildSQL(FIdConst::SALE_REJECTION, "w", $loginUserId);
		if ($rs) {
			$sql .= " and " . $rs[0];
			$queryParamas = $rs[1];
		}
		
		if ($ref) {
			$sql .= " and (w.ref like '%s') ";
			$queryParamas[] = "%$ref%";
		}
		if ($customerId) {
			$sql .= " and (w.customer_id = '%s') ";
			$queryParamas[] = $customerId;
		}
		if ($warehouseId) {
			$sql .= " and (w.warehouse_id = '%s') ";
			$queryParamas[] = $warehouseId;
		}
		if ($fromDT) {
			$sql .= " and (w.bizdt >= '%s') ";
			$queryParamas[] = $fromDT;
		}
		if ($toDT) {
			$sql .= " and (w.bizdt <= '%s') ";
			$queryParamas[] = $toDT;
		}
		if ($sn) {
			$sql .= " and (w.id in (
						select wsbill_id
						from t_ws_bill_detail d
						where d.sn_note like '%s'
					))";
			$queryParamas[] = "%$sn%";
		}
		
		$data = $db->query($sql, $queryParamas);
		$cnt = $data[0]["cnt"];
		
		return array(
				"dataList" => $result,
				"totalCount" => $cnt
		);
	}

	/**
	 * 获得销售出库单的信息
	 */
	public function getWSBillInfoForSRBill($params) {
		$db = $this->db;
		
		$result = array();
		
		$id = $params["id"];
		
		$sql = "select c.name as customer_name, w.ref, h.id as warehouse_id,
				  h.name as warehouse_name, c.id as customer_id
				from t_ws_bill w, t_customer c, t_warehouse h
				where w.id = '%s' and w.customer_id = c.id and w.warehouse_id = h.id ";
		$data = $db->query($sql, $id);
		if (! $data) {
			return $result;
		}
		
		$result["ref"] = $data[0]["ref"];
		$result["customerName"] = $data[0]["customer_name"];
		$result["warehouseId"] = $data[0]["warehouse_id"];
		$result["warehouseName"] = $data[0]["warehouse_name"];
		$result["customerId"] = $data[0]["customer_id"];
		
		$sql = "select d.id, g.id as goods_id, g.code, g.name, g.spec, u.name as unit_name, d.goods_count,
					d.goods_price, d.goods_money, d.sn_note
				from t_ws_bill_detail d, t_goods g, t_goods_unit u
				where d.wsbill_id = '%s' and d.goods_id = g.id and g.unit_id = u.id
				order by d.show_order";
		$data = $db->query($sql, $id);
		$items = array();
		
		foreach ( $data as $i => $v ) {
			$items[$i]["id"] = $v["id"];
			$items[$i]["goodsId"] = $v["goods_id"];
			$items[$i]["goodsCode"] = $v["code"];
			$items[$i]["goodsName"] = $v["name"];
			$items[$i]["goodsSpec"] = $v["spec"];
			$items[$i]["unitName"] = $v["unit_name"];
			$items[$i]["goodsCount"] = $v["goods_count"];
			$items[$i]["goodsPrice"] = $v["goods_price"];
			$items[$i]["goodsMoney"] = $v["goods_money"];
			$items[$i]["rejPrice"] = $v["goods_price"];
			$items[$i]["sn"] = $v["sn_note"];
		}
		
		$result["items"] = $items;
		
		return $result;
	}
}
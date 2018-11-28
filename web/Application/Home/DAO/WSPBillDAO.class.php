<?php

namespace Home\DAO;

use Home\Common\FIdConst;

/**
 * 拆分单 DAO
 *
 * @author 李静波
 */
class WSPBillDAO extends PSIBaseExDAO {

	/**
	 * 生成新的拆分单单号
	 *
	 * @param string $companyId        	
	 * @return string
	 */
	private function genNewBillRef($companyId) {
		$db = $this->db;
		
		$bs = new BizConfigDAO($db);
		$pre = $bs->getWSPBillRefPre($companyId);
		
		$mid = date("Ymd");
		
		$sql = "select ref from t_wsp_bill where ref like '%s' order by ref desc limit 1";
		$data = $db->query($sql, $pre . $mid . "%");
		$sufLength = 3;
		$suf = str_pad("1", $sufLength, "0", STR_PAD_LEFT);
		if ($data) {
			$ref = $data[0]["ref"];
			$nextNumber = intval(substr($ref, strlen($pre . $mid))) + 1;
			$suf = str_pad($nextNumber, $sufLength, "0", STR_PAD_LEFT);
		}
		
		return $pre . $mid . $suf;
	}

	/**
	 * 获得某个拆分单的商品构成
	 *
	 * @param array $params        	
	 */
	public function goodsBOM($params) {
		$db = $this->db;
		
		// id - 拆分单明细id
		$id = $params["id"];
		
		$result = [];
		if (! $id) {
			return $result;
		}
		
		$companyId = $params["companyId"];
		if ($this->companyIdNotExists($companyId)) {
			return $this->emptyResult();
		}
		
		$bcDAO = new BizConfigDAO($db);
		$dataScale = $bcDAO->getGoodsCountDecNumber($companyId);
		$fmt = "decimal(19, " . $dataScale . ")";
		
		$sql = "select d.goods_id, g.code, g.name, g.spec, u.name as unit_name, 
					convert(d.goods_count, $fmt) as goods_count 
				from t_wsp_bill_detail d, t_goods g, t_goods_unit u
				where d.id = '%s' and d.goods_id = g.id and g.unit_id = u.id";
		$data = $db->query($sql, $id);
		if (! $data) {
			return $result;
		}
		$v = $data[0];
		$goodsCount = $v["goods_count"];
		
		$iconCls = "PSI-GoodsBOM";
		
		$top = [
				"id" => $id,
				"text" => $v["code"],
				"goodsName" => $v["name"],
				"goodsSpec" => $v["spec"],
				"unitName" => $v["unit_name"],
				"bomCount" => 1,
				"goodsCount" => $goodsCount,
				"iconCls" => $iconCls,
				"expanded" => true
		];
		
		// 当前实现只展开一层BOM
		$iconClsItem = "PSI-GoodsBOMItem";
		$goodsId = $v["goods_id"];
		$sql = "select b.id, g.code, g.name, g.spec, u.name as unit_name,
						convert(b.sub_goods_count, $fmt) as sub_goods_count
				from t_wsp_bill_detail_bom b, t_goods g, t_goods_unit u
				where b.wspbilldetail_id = '%s' and b.goods_id = '%s' 
					and b.sub_goods_id = g.id and g.unit_id = u.id
				order by g.code";
		$data = $db->query($sql, $id, $goodsId);
		$children = [];
		foreach ( $data as $v ) {
			$children[] = [
					"id" => $v["id"],
					"text" => $v["code"],
					"goodsName" => $v["name"],
					"goodsSpec" => $v["spec"],
					"unitName" => $v["unit_name"],
					"bomCount" => $v["sub_goods_count"],
					"goodsCount" => $v["sub_goods_count"] * $goodsCount,
					"iconCls" => $iconClsItem,
					"expanded" => true,
					"leaf" => true
			];
		}
		
		$top["children"] = $children;
		$top["leaf"] = count($children) == 0;
		
		$result[] = $top;
		
		return $result;
	}

	/**
	 * 拆分单详情
	 */
	public function wspBillInfo($params) {
		$db = $this->db;
		
		$companyId = $params["companyId"];
		if ($this->companyIdNotExists($companyId)) {
			return $this->emptyResult();
		}
		
		$bcDAO = new BizConfigDAO($db);
		$dataScale = $bcDAO->getGoodsCountDecNumber($companyId);
		$fmt = "decimal(19, " . $dataScale . ")";
		
		$result = [];
		
		// 拆分单主表id
		$id = $params["id"];
		
		if ($id) {
			// 编辑
			$sql = "select w.ref, w.bizdt, w.bill_status,
						w.from_warehouse_id, w.to_warehouse_id,
						fw.name as from_warehouse_name,
						tw.name as to_warehouse_name,
						w.biz_user_id,
						u.name as biz_user_name,
						w.bill_memo
					from t_wsp_bill w, t_warehouse fw, t_warehouse tw,
						t_user u
					where (w.from_warehouse_id = fw.id)
						and (w.to_warehouse_id = tw.id)
						and (w.biz_user_id = u.id)
						and w.id = '%s' ";
			$data = $db->query($sql, $id);
			if (! $data) {
				return $result;
			}
			
			$v = $data[0];
			$result = [
					"ref" => $v["ref"],
					"billStatus" => $v["bill_status"],
					"bizDT" => $this->toYMD($v["bizdt"]),
					"fromWarehouseId" => $v["from_warehouse_id"],
					"fromWarehouseName" => $v["from_warehouse_name"],
					"toWarehouseId" => $v["to_warehouse_id"],
					"toWarehouseName" => $v["to_warehouse_name"],
					"bizUserId" => $v["biz_user_id"],
					"bizUserName" => $v["biz_user_name"],
					"billMemo" => $v["bill_memo"]
			];
			
			// 明细记录
			
			$sql = "select w.id, g.id as goods_id, g.code, g.name, g.spec, u.name as unit_name,
						convert(w.goods_count, $fmt) as goods_count, w.memo
					from t_wsp_bill_detail w, t_goods g, t_goods_unit u
						where w.wspbill_id = '%s' and w.goods_id = g.id and g.unit_id = u.id
						order by w.show_order ";
			
			$data = $db->query($sql, $id);
			$items = [];
			foreach ( $data as $v ) {
				$items[] = [
						"id" => $v["id"],
						"goodsId" => $v["goods_id"],
						"goodsCode" => $v["code"],
						"goodsName" => $v["name"],
						"goodsSpec" => $v["spec"],
						"unitName" => $v["unit_name"],
						"goodsCount" => $v["goods_count"],
						"memo" => $v["memo"]
				];
			}
			
			$result["items"] = $items;
		} else {
			// 新建
			$result["bizUserId"] = $params["loginUserId"];
			$result["bizUserName"] = $params["loginUserName"];
		}
		
		return $result;
	}

	/**
	 * 新建拆分单
	 *
	 * @param array $bill        	
	 */
	public function addWSPBill(& $bill) {
		$db = $this->db;
		
		$bizDT = $bill["bizDT"];
		$fromWarehouseId = $bill["fromWarehouseId"];
		$toWarehouseId = $bill["toWarehouseId"];
		$bizUserId = $bill["bizUserId"];
		$billMemo = $bill["billMemo"];
		
		// 检查业务日期
		if (! $this->dateIsValid($bizDT)) {
			return $this->bad("业务日期不正确");
		}
		
		// 检查仓库
		$warehouseDAO = new WarehouseDAO($db);
		$w = $warehouseDAO->getWarehouseById($fromWarehouseId);
		if (! $w) {
			return $this->bad("仓库不存在");
		}
		
		$w = $warehouseDAO->getWarehouseById($toWarehouseId);
		if (! $w) {
			return $this->bad("拆分后调入仓库不存在");
		}
		
		// 检查业务员
		$userDAO = new UserDAO($db);
		$user = $userDAO->getUserById($bizUserId);
		if (! $user) {
			return $this->bad("选择的业务员不存在，无法保存数据");
		}
		
		$items = $bill["items"];
		
		$dataOrg = $bill["dataOrg"];
		$companyId = $bill["companyId"];
		$loginUserId = $bill["loginUserId"];
		if ($this->dataOrgNotExists($dataOrg)) {
			return $this->badParam("dataOrg");
		}
		if ($this->companyIdNotExists($companyId)) {
			return $this->badParam("companyId");
		}
		if ($this->loginUserIdNotExists($loginUserId)) {
			return $this->badParam("loginUserId");
		}
		
		$bcDAO = new BizConfigDAO($db);
		$dataScale = $bcDAO->getGoodsCountDecNumber($companyId);
		$fmt = "decimal(19, " . $dataScale . ")";
		
		// 主表
		$id = $this->newId();
		$ref = $this->genNewBillRef($companyId);
		$sql = "insert into t_wsp_bill (id, ref, from_warehouse_id, to_warehouse_id,
					bill_status, bizdt, biz_user_id, date_created,
					input_user_id, data_org, company_id, bill_memo)
				values ('%s', '%s', '%s', '%s',
					0, '%s', '%s', now(),
					'%s', '%s', '%s', '%s')";
		$rc = $db->execute($sql, $id, $ref, $fromWarehouseId, $toWarehouseId, $bizDT, $bizUserId, 
				$loginUserId, $dataOrg, $companyId, $billMemo);
		if ($rc === false) {
			return $this->sqlError(__METHOD__, __LINE__);
		}
		
		// 明细表
		foreach ( $items as $showOrder => $v ) {
			$goodsId = $v["goodsId"];
			if (! $goodsId) {
				continue;
			}
			
			$goodsCount = $v["goodsCount"];
			$memo = $v["memo"];
			
			// 检查商品是否有子商品
			// 拆分单的明细表中不允许保存没有子商品的商品
			// 一个商品没有子商品，就不能做拆分业务
			$sql = "select count(*) as cnt from t_goods_bom where goods_id = '%s' ";
			$data = $db->query($sql, $goodsId);
			$cnt = $data[0]["cnt"];
			if ($cnt == 0) {
				$rowIndex = $showOrder + 1;
				return $this->bad("第{$rowIndex}记录中的商品没有子商品，不能做拆分业务");
			}
			
			// 检查拆分数量
			if ($goodsCount <= 0) {
				$rowIndex = $showOrder + 1;
				return $this->bad("第{$rowIndex}记录中的商品的拆分数量需要大于0");
			}
			
			$detailId = $this->newId();
			$sql = "insert into t_wsp_bill_detail (id, wspbill_id, show_order, goods_id,
						goods_count, date_created, data_org, company_id, memo)
					values ('%s', '%s', %d, '%s',
						convert(%f, $fmt), now(), '%s', '%s', '%s')";
			$rc = $db->execute($sql, $detailId, $id, $showOrder, $goodsId, $goodsCount, $dataOrg, 
					$companyId, $memo);
			if ($rc === false) {
				return $this->sqlError(__METHOD__, __LINE__);
			}
			
			// 复制当前商品构成BOM
			$this->copyGoodsBOM($detailId, $goodsId, $fmt);
			
			// 展开当前商品BOM
			$this->expandGoodsBOM($id, $fmt);
		}
		
		// 操作成功
		$bill["id"] = $id;
		$bill["ref"] = $ref;
		return null;
	}

	// 复制当前商品构成BOM
	// 目前的实现只复制一层BOM
	private function copyGoodsBOM($wspbillDetailId, $goodsId, $fmt) {
		$db = $this->db;
		
		$sql = "select sub_goods_id, convert(sub_goods_count, $fmt) as sub_goods_count 
				from t_goods_bom 
				where goods_id = '%s'";
		$data = $db->query($sql, $goodsId);
		foreach ( $data as $v ) {
			$subGoodsId = $v["sub_goods_id"];
			$subGoodsCount = $v["sub_goods_count"];
			
			$sql = "insert into t_wsp_bill_detail_bom (id, wspbilldetail_id, goods_id, sub_goods_id,
						parent_id, sub_goods_count) 
					values ('%s', '%s', '%s', '%s',
						null, convert(%f, $fmt))";
			$rc = $db->execute($sql, $this->newId(), $wspbillDetailId, $goodsId, $subGoodsId, 
					$subGoodsCount);
			if ($rc === false) {
				return $this->sqlError(__METHOD__, __LINE__);
			}
		}
	}

	// 展开商品BOM
	// 目前的实现只展开一层BOM
	private function expandGoodsBOM($wspbillId, $fmt) {
		$db = $this->db;
		
		$sql = "select id, goods_id, convert(goods_count, $fmt) as goods_count,
					data_org, company_id
				from t_wsp_bill_detail
				where wspbill_id = '%s'
				order by show_order";
		$data = $db->query($sql, $wspbillId);
		
		$showOrder = 0;
		foreach ( $data as $v ) {
			$wspbillDetailId = $v["id"];
			$goodsId = $v["goods_id"];
			$goodsCount = $v["goods_count"];
			$dataOrg = $v["data_org"];
			$companyId = $v["company_id"];
			
			$sql = "select sub_goods_id, convert(sub_goods_count, $fmt) as sub_goods_count
					from t_wsp_bill_detail_bom
					where wspbilldetail_id = '%s' and goods_id = '%s' ";
			$subData = $db->query($sql, $wspbillDetailId, $goodsId);
			foreach ( $subData as $sv ) {
				$showOrder += 1;
				
				$subGoodsId = $sv["sub_goods_id"];
				$subGoodsCount = $sv["sub_goods_count"] * $goodsCount;
				
				$sql = "insert into t_wsp_bill_detail_ex (id, wspbill_id, show_order, goods_id,
							goods_count, date_created, data_org, company_id, from_goods_id,
							wspbilldetail_id)
						values ('%s', '%s', %d, '%s',
							convert(%f, $fmt), now(), '%s', '%s', '%s',
							'%s')";
				
				$rc = $db->execute($sql, $this->newId(), $wspbillId, $showOrder, $subGoodsId, 
						$subGoodsCount, $dataOrg, $companyId, $goodsId, $wspbillDetailId);
				if ($rc === false) {
					return $this->sqlError(__METHOD__, __LINE__);
				}
			}
		}
	}

	/**
	 * 编辑拆分单
	 *
	 * @param array $bill        	
	 */
	public function updateWSPBill(& $bill) {
		$db = $this->db;
		
		$id = $bill["id"];
		$oldBill = $this->getWSPBillById($id);
		if (! $oldBill) {
			return $this->bad("要编辑的拆分单不存在");
		}
		$ref = $oldBill["ref"];
		$billStatus = $oldBill["billStatus"];
		if ($billStatus > 0) {
			return $this->bad("拆分单[单号：{$ref}]已经提交，不能被编辑了");
		}
		
		$bizDT = $bill["bizDT"];
		$fromWarehouseId = $bill["fromWarehouseId"];
		$toWarehouseId = $bill["toWarehouseId"];
		$bizUserId = $bill["bizUserId"];
		$billMemo = $bill["billMemo"];
		
		// 检查业务日期
		if (! $this->dateIsValid($bizDT)) {
			return $this->bad("业务日期不正确");
		}
		
		// 检查仓库
		$warehouseDAO = new WarehouseDAO($db);
		$w = $warehouseDAO->getWarehouseById($fromWarehouseId);
		if (! $w) {
			return $this->bad("仓库不存在");
		}
		
		$w = $warehouseDAO->getWarehouseById($toWarehouseId);
		if (! $w) {
			return $this->bad("拆分后调入仓库不存在");
		}
		
		// 检查业务员
		$userDAO = new UserDAO($db);
		$user = $userDAO->getUserById($bizUserId);
		if (! $user) {
			return $this->bad("选择的业务员不存在，无法保存数据");
		}
		
		$companyId = $bill["companyId"];
		if ($this->companyIdNotExists($companyId)) {
			return $this->badParam("companyId");
		}
		
		$bcDAO = new BizConfigDAO($db);
		$dataScale = $bcDAO->getGoodsCountDecNumber($companyId);
		$fmt = "decimal(19, " . $dataScale . ")";
		
		// 主表
		$sql = "update t_wsp_bill
				set bizdt = '%s', from_warehouse_id = '%s',
					to_warehouse_id = '%s', biz_user_id = '%s',
					bill_memo = '%s'
				where id = '%s' ";
		$rc = $db->execute($sql, $bizDT, $fromWarehouseId, $toWarehouseId, $bizUserId, $billMemo, 
				$id);
		if ($rc === false) {
			return $this->sqlError(__METHOD__, __LINE__);
		}
		
		// 明细表
		
		// 先清空明细数据
		$sql = "select id from t_wsp_bill_detail where wspbill_id = '%s' ";
		$data = $db->query($sql, $id);
		foreach ( $data as $v ) {
			$detailId = $v["id"];
			
			$sql = "delete from t_wsp_bill_detail_bom where wspbilldetail_id = '%s' ";
			$rc = $db->execute($sql, $detailId);
			if ($rc === false) {
				return $this->sqlError(__METHOD__, __LINE__);
			}
			
			$sql = "delete from t_wsp_bill_detail_ex where wspbilldetail_id = '%s' ";
			$rc = $db->execute($sql, $detailId);
			if ($rc === false) {
				return $this->sqlError(__METHOD__, __LINE__);
			}
			
			$sql = "delete from t_wsp_bill_detail where id = '%s' ";
			$rc = $db->execute($sql, $detailId);
			if ($rc === false) {
				return $this->sqlError(__METHOD__, __LINE__);
			}
		}
		
		$items = $bill["items"];
		
		// 清空明细数据后，再插入新的明细数据
		foreach ( $items as $showOrder => $v ) {
			$goodsId = $v["goodsId"];
			if (! $goodsId) {
				continue;
			}
			
			$goodsCount = $v["goodsCount"];
			$memo = $v["memo"];
			
			// 检查商品是否有子商品
			// 拆分单的明细表中不允许保存没有子商品的商品
			// 一个商品没有子商品，就不能做拆分业务
			$sql = "select count(*) as cnt from t_goods_bom where goods_id = '%s' ";
			$data = $db->query($sql, $goodsId);
			$cnt = $data[0]["cnt"];
			if ($cnt == 0) {
				$rowIndex = $showOrder + 1;
				return $this->bad("第{$rowIndex}记录中的商品没有子商品，不能做拆分业务");
			}
			
			// 检查拆分数量
			if ($goodsCount <= 0) {
				$rowIndex = $showOrder + 1;
				return $this->bad("第{$rowIndex}记录中的商品的拆分数量需要大于0");
			}
			
			$detailId = $this->newId();
			$sql = "insert into t_wsp_bill_detail (id, wspbill_id, show_order, goods_id,
						goods_count, date_created, data_org, company_id, memo)
					values ('%s', '%s', %d, '%s',
						convert(%f, $fmt), now(), '%s', '%s', '%s')";
			$rc = $db->execute($sql, $detailId, $id, $showOrder, $goodsId, $goodsCount, $dataOrg, 
					$companyId, $memo);
			if ($rc === false) {
				return $this->sqlError(__METHOD__, __LINE__);
			}
			
			// 复制当前商品构成BOM
			$this->copyGoodsBOM($detailId, $goodsId, $fmt);
			
			// 展开当前商品BOM
			$this->expandGoodsBOM($id, $fmt);
		}
		
		// 操作成功
		$bill["ref"] = $ref;
		return null;
	}

	/**
	 * 拆分单主表列表
	 */
	public function wspbillList($params) {
		$db = $this->db;
		
		$loginUserId = $params["loginUserId"];
		if ($this->loginUserIdNotExists($loginUserId)) {
			return $this->emptyResult();
		}
		
		$start = $params["start"];
		$limit = $params["limit"];
		
		$billStatus = $params["billStatus"];
		$ref = $params["ref"];
		$fromDT = $params["fromDT"];
		$toDT = $params["toDT"];
		$fromWarehouseId = $params["fromWarehouseId"];
		$toWarehouseId = $params["toWarehouseId"];
		
		$sql = "select w.id, w.ref, w.bizdt, w.bill_status,
					fw.name as from_warehouse_name,
					tw.name as to_warehouse_name,
					u.name as biz_user_name,
					u1.name as input_user_name,
					w.date_created, w.bill_memo
				from t_wsp_bill w, t_warehouse fw, t_warehouse tw,
				   t_user u, t_user u1
				where (w.from_warehouse_id = fw.id)
				  and (w.to_warehouse_id = tw.id)
				  and (w.biz_user_id = u.id)
				  and (w.input_user_id = u1.id) ";
		$queryParams = [];
		
		$ds = new DataOrgDAO($db);
		$rs = $ds->buildSQL(FIdConst::WSP, "w", $loginUserId);
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
		if ($fromWarehouseId) {
			$sql .= " and (w.from_warehouse_id = '%s') ";
			$queryParams[] = $fromWarehouseId;
		}
		if ($toWarehouseId) {
			$sql .= " and (w.to_warehouse_id = '%s') ";
			$queryParams[] = $toWarehouseId;
		}
		
		$sql .= " order by w.bizdt desc, w.ref desc
				limit %d , %d
				";
		$queryParams[] = $start;
		$queryParams[] = $limit;
		$data = $db->query($sql, $queryParams);
		
		$result = [];
		foreach ( $data as $v ) {
			$result[] = [
					"id" => $v["id"],
					"ref" => $v["ref"],
					"bizDate" => $this->toYMD($v["bizdt"]),
					"billStatus" => $v["bill_status"],
					"fromWarehouseName" => $v["from_warehouse_name"],
					"toWarehouseName" => $v["to_warehouse_name"],
					"bizUserName" => $v["biz_user_name"],
					"inputUserName" => $v["input_user_name"],
					"dateCreated" => $v["date_created"],
					"billMemo" => $v["bill_memo"]
			];
		}
		
		$sql = "select count(*) as cnt
				from t_wsp_bill w, t_warehouse fw, t_warehouse tw,
				   t_user u, t_user u1
				where (w.from_warehouse_id = fw.id)
				  and (w.to_warehouse_id = tw.id)
				  and (w.biz_user_id = u.id)
				  and (w.input_user_id = u1.id) ";
		$queryParams = [];
		
		$ds = new DataOrgDAO($db);
		$rs = $ds->buildSQL(FIdConst::WSP, "w", $loginUserId);
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
		if ($fromWarehouseId) {
			$sql .= " and (w.from_warehouse_id = '%s') ";
			$queryParams[] = $fromWarehouseId;
		}
		if ($toWarehouseId) {
			$sql .= " and (w.to_warehouse_id = '%s') ";
			$queryParams[] = $toWarehouseId;
		}
		$data = $db->query($sql, $queryParams);
		$cnt = $data[0]["cnt"];
		
		return [
				"dataList" => $result,
				"totalCount" => $cnt
		];
	}

	/**
	 * 拆分单明细
	 */
	public function wspBillDetailList($params) {
		$db = $this->db;
		
		$companyId = $params["companyId"];
		if ($this->companyIdNotExists($companyId)) {
			return $this->emptyResult();
		}
		
		$bcDAO = new BizConfigDAO($db);
		$dataScale = $bcDAO->getGoodsCountDecNumber($companyId);
		$fmt = "decimal(19, " . $dataScale . ")";
		
		// id: 拆分单id
		$id = $params["id"];
		
		$result = [];
		
		$sql = "select w.id, g.code, g.name, g.spec, u.name as unit_name, 
					convert(w.goods_count, $fmt) as goods_count, w.memo
				from t_wsp_bill_detail w, t_goods g, t_goods_unit u
				where w.wspbill_id = '%s' and w.goods_id = g.id and g.unit_id = u.id
				order by w.show_order ";
		
		$data = $db->query($sql, $id);
		foreach ( $data as $v ) {
			$result[] = [
					"id" => $v["id"],
					"goodsCode" => $v["code"],
					"goodsName" => $v["name"],
					"goodsSpec" => $v["spec"],
					"unitName" => $v["unit_name"],
					"goodsCount" => $v["goods_count"],
					"memo" => $v["memo"]
			];
		}
		
		return $result;
	}

	/**
	 * 拆分单明细 - 拆分后明细
	 */
	public function wspBillDetailExList($params) {
		$db = $this->db;
		
		$companyId = $params["companyId"];
		if ($this->companyIdNotExists($companyId)) {
			return $this->emptyResult();
		}
		
		$bcDAO = new BizConfigDAO($db);
		$dataScale = $bcDAO->getGoodsCountDecNumber($companyId);
		$fmt = "decimal(19, " . $dataScale . ")";
		
		// id: 拆分单id
		$id = $params["id"];
		
		$result = [];
		
		$sql = "select w.id, g.code, g.name, g.spec, u.name as unit_name,
					convert(w.goods_count, $fmt) as goods_count
				from t_wsp_bill_detail_ex w, t_goods g, t_goods_unit u
				where w.wspbill_id = '%s' and w.goods_id = g.id and g.unit_id = u.id
				order by w.show_order ";
		
		$data = $db->query($sql, $id);
		foreach ( $data as $v ) {
			$result[] = [
					"id" => $v["id"],
					"goodsCode" => $v["code"],
					"goodsName" => $v["name"],
					"goodsSpec" => $v["spec"],
					"unitName" => $v["unit_name"],
					"goodsCount" => $v["goods_count"]
			];
		}
		
		return $result;
	}

	public function getWSPBillById($id) {
		$db = $this->db;
		
		$sql = "select ref, bill_status from t_wsp_bill where id = '%s' ";
		$data = $db->query($sql, $id);
		if ($data) {
			return [
					"ref" => $data[0]["ref"],
					"billStatus" => $data[0]["bill_status"]
			];
		} else {
			return null;
		}
	}

	/**
	 * 删除拆分单
	 */
	public function deleteWSPBill(& $params) {
		$db = $this->db;
		
		// 拆分单id
		$id = $params["id"];
		
		$bill = $this->getWSPBillById($id);
		if (! $bill) {
			return $this->bad("要删除的拆分单不存在");
		}
		
		$ref = $bill["ref"];
		$billStatus = $bill["billStatus"];
		
		if ($billStatus > 0) {
			return $this->bad("拆分单[单号：{$ref}]已经提交，不能再删除");
		}
		
		// 明细
		$sql = "select id from t_wsp_bill_detail where wspbill_id = '%s' ";
		$data = $db->query($sql, $id);
		foreach ( $data as $v ) {
			$detailId = $v["id"];
			
			$sql = "delete from t_wsp_bill_detail_bom where wspbilldetail_id = '%s' ";
			$rc = $db->execute($sql, $detailId);
			if ($rc === false) {
				return $this->sqlError(__METHOD__, __LINE__);
			}
			
			$sql = "delete from t_wsp_bill_detail_ex where wspbilldetail_id = '%s' ";
			$rc = $db->execute($sql, $detailId);
			if ($rc === false) {
				return $this->sqlError(__METHOD__, __LINE__);
			}
			
			$sql = "delete from t_wsp_bill_detail where id = '%s' ";
			$rc = $db->execute($sql, $detailId);
			if ($rc === false) {
				return $this->sqlError(__METHOD__, __LINE__);
			}
		}
		
		// 主表
		$sql = "delete from t_wsp_bill where id = '%s' ";
		$rc = $db->execute($sql, $id);
		if ($rc === false) {
			return $this->sqlError(__METHOD__, __LINE__);
		}
		
		// 操作成功
		$params["ref"] = $ref;
		return null;
	}
}
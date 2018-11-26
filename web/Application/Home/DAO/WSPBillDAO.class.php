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
		return [];
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
		
		$id = $params["id"];
		
		if ($id) {
			// 编辑
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
		return $this->todo();
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
}
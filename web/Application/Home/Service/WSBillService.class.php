<?php

namespace Home\Service;

use Home\Common\FIdConst;
use Home\DAO\WSBillDAO;

/**
 * 销售出库Service
 *
 * @author 李静波
 */
class WSBillService extends PSIBaseExService {
	private $LOG_CATEGORY = "销售出库";

	/**
	 * 新建或编辑的时候，获得销售出库单的详情
	 */
	public function wsBillInfo($params) {
		if ($this->isNotOnline()) {
			return $this->emptyResult();
		}
		
		$params["loginUserId"] = $this->getLoginUserId();
		$params["loginUserName"] = $this->getLoginUserName();
		$params["companyId"] = $this->getCompanyId();
		
		$dao = new WSBillDAO($this->db());
		return $dao->wsBillInfo($params);
	}

	/**
	 * 判断是否可以编辑商品销售单价
	 *
	 * @return boolean true:可以编辑销售单价
	 */
	private function canEditGoodsPrice() {
		// 首先判断业务设置中是否允许销售出库编辑销售单价（全局控制）
		$db = M();
		$sql = "select value from t_config where id = '2002-01' ";
		$data = $db->query($sql);
		if (! $data) {
			return false;
		}
		
		$v = intval($data[0]["value"]);
		if ($v == 0) {
			return false;
		}
		
		$us = new UserService();
		// 在业务设置中启用编辑的前提下，还需要判断对应的权限（具体的用户）
		return $us->hasPermission("2002-01");
	}

	/**
	 * 新增或编辑销售出库单
	 */
	public function editWSBill($params) {
		if ($this->isNotOnline()) {
			return $this->notOnlineError();
		}
		
		$json = $params["jsonStr"];
		$bill = json_decode(html_entity_decode($json), true);
		if ($bill == null) {
			return $this->bad("传入的参数错误，不是正确的JSON格式");
		}
		
		$id = $bill["id"];
		
		$sobillRef = $bill["sobillRef"];
		
		$db = $this->db();
		$db->startTrans();
		
		$dao = new WSBillDAO($db);
		
		$log = null;
		
		if ($id) {
			// 编辑
			
			$bill["loginUserId"] = $this->getLoginUserId();
			
			$rc = $dao->updateWSBill($bill);
			if ($rc) {
				$db->rollback();
				return $rc;
			}
			
			$ref = $bill["ref"];
			$log = "编辑销售出库单，单号 = {$ref}";
		} else {
			// 新建销售出库单
			
			$bill["dataOrg"] = $this->getLoginUserDataOrg();
			$bill["companyId"] = $this->getCompanyId();
			$bill["loginUserId"] = $this->getLoginUserId();
			
			$rc = $dao->addWSBill($bill);
			if ($rc) {
				$db->rollback();
				return $rc;
			}
			
			$id = $bill["id"];
			$ref = $bill["ref"];
			if ($sobillRef) {
				// 从销售订单生成销售出库单
				$log = "从销售订单(单号：{$sobillRef})生成销售出库单: 单号 = {$ref}";
			} else {
				// 手工新建销售出库单
				$log = "新增销售出库单，单号 = {$ref}";
			}
		}
		
		// 记录业务日志
		$bs = new BizlogService($db);
		$bs->insertBizlog($log, $this->LOG_CATEGORY);
		
		$db->commit();
		
		return $this->ok($id);
	}

	/**
	 * 获得销售出库单主表列表
	 */
	public function wsbillList($params) {
		if ($this->isNotOnline()) {
			return $this->emptyResult();
		}
		
		$params["loginUserId"] = $this->getLoginUserId();
		
		$dao = new WSBillDAO($this->db());
		return $dao->wsbillList($params);
	}

	/**
	 * 获得某个销售出库单的明细记录列表
	 */
	public function wsBillDetailList($params) {
		if ($this->isNotOnline()) {
			return $this->emptyResult();
		}
		
		$dao = new WSBillDAO($this->db());
		return $dao->wsBillDetailList($params);
	}

	/**
	 * 删除销售出库单
	 */
	public function deleteWSBill($params) {
		if ($this->isNotOnline()) {
			return $this->notOnlineError();
		}
		
		$id = $params["id"];
		$db = M();
		$db->startTrans();
		
		$sql = "select ref, bill_status from t_ws_bill where id = '%s' ";
		$data = $db->query($sql, $id);
		if (! $data) {
			$db->rollback();
			return $this->bad("要删除的销售出库单不存在");
		}
		$ref = $data[0]["ref"];
		$billStatus = $data[0]["bill_status"];
		if ($billStatus != 0) {
			$db->rollback();
			return $this->bad("销售出库单已经提交出库，不能删除");
		}
		
		$sql = "delete from t_ws_bill_detail where wsbill_id = '%s' ";
		$rc = $db->execute($sql, $id);
		if ($rc === false) {
			$db->rollback();
			return $this->sqlError(__LINE__);
		}
		
		$sql = "delete from t_ws_bill where id = '%s' ";
		$rc = $db->execute($sql, $id);
		if ($rc === false) {
			$db->rollback();
			return $this->sqlError(__LINE__);
		}
		
		// 删除从销售订单生成的记录
		$sql = "delete from t_so_ws where ws_id = '%s' ";
		$rc = $db->execute($sql, $id);
		if ($rc === false) {
			$db->rollback();
			return $this->sqlError(__LINE__);
		}
		
		$log = "删除销售出库单，单号: {$ref}";
		$bs = new BizlogService();
		$bs->insertBizlog($log, $this->LOG_CATEGORY);
		
		$db->commit();
		
		return $this->ok();
	}

	/**
	 * 提交销售出库单
	 */
	public function commitWSBill($params) {
		if ($this->isNotOnline()) {
			return $this->notOnlineError();
		}
		
		$db = M();
		$db->startTrans();
		
		$bs = new BizConfigService();
		// true: 先进先出
		$fifo = $bs->getInventoryMethod() == 1;
		
		$id = $params["id"];
		
		$sql = "select ref, bill_status, customer_id, warehouse_id, biz_user_id, bizdt, sale_money,
					receiving_type, company_id
				from t_ws_bill where id = '%s' ";
		$data = $db->query($sql, $id);
		if (! data) {
			$db->rollback();
			return $this->bad("要提交的销售出库单不存在");
		}
		$ref = $data[0]["ref"];
		$bizDT = $data[0]["bizdt"];
		$bizUserId = $data[0]["biz_user_id"];
		$billStatus = $data[0]["bill_status"];
		$receivingType = $data[0]["receiving_type"];
		$saleMoney = $data[0]["sale_money"];
		$companyId = $data[0]["company_id"];
		if ($billStatus != 0) {
			$db->rollback();
			return $this->bad("销售出库单已经提交出库，不能再次提交");
		}
		$customerId = $data[0]["customer_id"];
		$warehouseId = $data[0]["warehouse_id"];
		$sql = "select count(*) as cnt from t_customer where id = '%s' ";
		$data = $db->query($sql, $customerId);
		$cnt = $data[0]["cnt"];
		if ($cnt != 1) {
			$db->rollback();
			return $this->bad("客户不存在");
		}
		$sql = "select name, inited from t_warehouse where id = '%s' ";
		$data = $db->query($sql, $warehouseId);
		if (! $data) {
			$db->rollback();
			return $this->bad("仓库不存在");
		}
		$warehouseName = $data[0]["name"];
		$inited = $data[0]["inited"];
		if ($inited != 1) {
			$db->rollback();
			return $this->bad("仓库 [{$warehouseName}]还没有建账，不能进行出库操作");
		}
		$sql = "select name as cnt from t_user where id = '%s' ";
		$data = $db->query($sql, $bizUserId);
		if (! $data) {
			$db->rollback();
			return $this->bad("业务员不存在");
		}
		
		$allReceivingType = array(
				0,
				1,
				2
		);
		
		if (! in_array($receivingType, $allReceivingType)) {
			$db->rollback();
			return $this->bad("收款方式不正确，无法完成提交操作");
		}
		
		$sql = "select id, goods_id, goods_count,  goods_price 
					from t_ws_bill_detail 
					where wsbill_id = '%s' 
					order by show_order ";
		$items = $db->query($sql, $id);
		if (! $items) {
			$db->rollback();
			return $this->bad("销售出库单没有出库商品明细记录，无法出库");
		}
		
		foreach ( $items as $v ) {
			$itemId = $v["id"];
			$goodsId = $v["goods_id"];
			$goodsCount = intval($v["goods_count"]);
			$goodsPrice = floatval($v["goods_price"]);
			
			$sql = "select code, name from t_goods where id = '%s' ";
			$data = $db->query($sql, $goodsId);
			if (! $data) {
				$db->rollback();
				return $this->bad("要出库的商品不存在(商品后台id = {$goodsId})");
			}
			$goodsCode = $data[0]["code"];
			$goodsName = $data[0]["name"];
			if ($goodsCount <= 0) {
				$db->rollback();
				return $this->bad("商品[{$goodsCode} {$goodsName}]的出库数量需要是正数");
			}
			
			if ($fifo) {
				// 先进先出法
				
				// 库存总账
				$sql = "select out_count, out_money, balance_count, balance_price,
						balance_money from t_inventory 
						where warehouse_id = '%s' and goods_id = '%s' ";
				$data = $db->query($sql, $warehouseId, $goodsId);
				if (! $data) {
					$db->rollback();
					return $this->bad(
							"商品 [{$goodsCode} {$goodsName}] 在仓库 [{$warehouseName}] 中没有存货，无法出库");
				}
				$balanceCount = $data[0]["balance_count"];
				if ($balanceCount < $goodsCount) {
					$db->rollback();
					return $this->bad(
							"商品 [{$goodsCode} {$goodsName}] 在仓库 [{$warehouseName}] 中存货数量不足，无法出库");
				}
				$balancePrice = $data[0]["balance_price"];
				$balanceMoney = $data[0]["balance_money"];
				$outCount = $data[0]["out_count"];
				$outMoney = $data[0]["out_money"];
				
				$sql = "select id, balance_count, balance_price, balance_money,
								out_count, out_price, out_money, date_created
							from t_inventory_fifo
							where warehouse_id = '%s' and goods_id = '%s'
								and balance_count > 0
							order by date_created ";
				$data = $db->query($sql, $warehouseId, $goodsId);
				if (! $data) {
					$db->rollback();
					return $this->sqlError(__LINE__);
				}
				
				$gc = $goodsCount;
				$fifoMoneyTotal = 0;
				for($i = 0; $i < count($data); $i ++) {
					if ($gc == 0) {
						break;
					}
					
					$fv = $data[$i];
					$fvBalanceCount = $fv["balance_count"];
					$fvId = $fv["id"];
					$fvBalancePrice = $fv["balance_price"];
					$fvBalanceMoney = $fv["balance_money"];
					$fvOutCount = $fv["out_count"];
					$fvOutMoney = $fv["out_money"];
					$fvDateCreated = $fv["date_created"];
					
					if ($fvBalanceCount >= $gc) {
						if ($fvBalanceCount > $gc) {
							$fifoMoney = $fvBalancePrice * $gc;
						} else {
							$fifoMoney = $fvBalanceMoney;
						}
						$fifoMoneyTotal += $fifoMoney;
						
						$fifoPrice = $fifoMoney / $gc;
						
						$fvOutCount += $gc;
						$fvOutMoney += $fifoMoney;
						$fvOutPrice = $fvOutMoney / $fvOutCount;
						
						$fvBalanceCount -= $gc;
						$fvBalanceMoney -= $fifoMoney;
						
						$sql = "update t_inventory_fifo
									set out_count = %d, out_price = %f, out_money = %f,
										balance_count = %d, balance_money = %f
									where id = %d ";
						$rc = $db->execute($sql, $fvOutCount, $fvOutPrice, $fvOutMoney, 
								$fvBalanceCount, $fvBalanceMoney, $fvId);
						if ($rc === false) {
							$db->rollback();
							return $this->sqlError(__LINE__);
						}
						
						// fifo 的明细记录
						$sql = "insert into t_inventory_fifo_detail(out_count, out_price, out_money,
									balance_count, balance_price, balance_money, warehouse_id, goods_id,
									date_created, wsbilldetail_id) 
									values (%d, %f, %f, %d, %f, %f, '%s', '%s', '%s', '%s')";
						$rc = $db->execute($sql, $gc, $fifoPrice, $fifoMoney, $fvBalanceCount, 
								$fvBalancePrice, $fvBalanceMoney, $warehouseId, $goodsId, 
								$fvDateCreated, $itemId);
						if ($rc === false) {
							$db->rollback();
							return $this->sqlError(__LINE__);
						}
						
						$gc = 0;
					} else {
						$fifoMoneyTotal += $fvBalanceMoney;
						
						$sql = "update t_inventory_fifo
									set out_count = in_count, out_price = in_price, out_money = in_money,
										balance_count = 0, balance_money = 0
									where id = %d ";
						$rc = $db->execute($sql, $fvId);
						if ($rc === false) {
							$db->rollback();
							return $this->sqlError(__LINE__);
						}
						
						// fifo 的明细记录
						$sql = "insert into t_inventory_fifo_detail(out_count, out_price, out_money,
									balance_count, balance_price, balance_money, warehouse_id, goods_id,
									date_created, wsbilldetail_id) 
									values (%d, %f, %f, %d, %f, %f, '%s', '%s', '%s', '%s')";
						$rc = $db->execute($sql, $fvBalanceCount, $fvBalancePrice, $fvBalanceMoney, 
								0, 0, 0, $warehouseId, $goodsId, $fvDateCreated, $itemId);
						if ($rc === false) {
							$db->rollback();
							return $this->sqlError(__LINE__);
						}
						
						$gc -= $fvBalanceCount;
					}
				}
				
				$fifoPrice = $fifoMoneyTotal / $goodsCount;
				
				// 更新总账
				$outCount += $goodsCount;
				$outMoney += $fifoMoneyTotal;
				$outPrice = $outMoney / $outCount;
				$balanceCount -= $goodsCount;
				if ($balanceCount == 0) {
					$balanceMoney = 0;
					$balancePrice = 0;
				} else {
					$balanceMoney -= $fifoMoneyTotal;
					$balancePrice = $balanceMoney / $balanceCount;
				}
				
				$sql = "update t_inventory
						set out_count = %d, out_price = %f, out_money = %f,
						    balance_count = %d, balance_price = %f, balance_money = %f
						where warehouse_id = '%s' and goods_id = '%s' ";
				$rc = $db->execute($sql, $outCount, $outPrice, $outMoney, $balanceCount, 
						$balancePrice, $balanceMoney, $warehouseId, $goodsId);
				if ($rc === false) {
					$db->rollback();
					return $this->sqlError(__LINE__);
				}
				
				// 更新明细账
				$sql = "insert into t_inventory_detail(out_count, out_price, out_money,
						balance_count, balance_price, balance_money, warehouse_id,
						goods_id, biz_date, biz_user_id, date_created, ref_number, ref_type)
						values(%d, %f, %f, %d, %f, %f, '%s', '%s', '%s', '%s', now(), '%s', '销售出库')";
				$rc = $db->execute($sql, $goodsCount, $fifoPrice, $fifoMoneyTotal, $balanceCount, 
						$balancePrice, $balanceMoney, $warehouseId, $goodsId, $bizDT, $bizUserId, 
						$ref);
				if ($rc === false) {
					$db->rollback();
					return $this->sqlError(__LINE__);
				}
				
				// 更新单据本身的记录
				$sql = "update t_ws_bill_detail 
						set inventory_price = %f, inventory_money = %f
						where id = '%s' ";
				$rc = $db->execute($sql, $fifoPrice, $fifoMoneyTotal, $id);
				if ($rc === false) {
					$db->rollback();
					return $this->sqlError(__LINE__);
				}
			} else {
				// 移动平均法
				
				// 库存总账
				$sql = "select out_count, out_money, balance_count, balance_price,
						balance_money from t_inventory 
						where warehouse_id = '%s' and goods_id = '%s' ";
				$data = $db->query($sql, $warehouseId, $goodsId);
				if (! $data) {
					$db->rollback();
					return $this->bad(
							"商品 [{$goodsCode} {$goodsName}] 在仓库 [{$warehouseName}] 中没有存货，无法出库");
				}
				$balanceCount = $data[0]["balance_count"];
				if ($balanceCount < $goodsCount) {
					$db->rollback();
					return $this->bad(
							"商品 [{$goodsCode} {$goodsName}] 在仓库 [{$warehouseName}] 中存货数量不足，无法出库");
				}
				$balancePrice = $data[0]["balance_price"];
				$balanceMoney = $data[0]["balance_money"];
				$outCount = $data[0]["out_count"];
				$outMoney = $data[0]["out_money"];
				$balanceCount -= $goodsCount;
				if ($balanceCount == 0) {
					// 当全部出库的时候，金额也需要全部转出去
					$outMoney += $balanceMoney;
					$outPriceDetail = $balanceMoney / $goodsCount;
					$outMoneyDetail = $balanceMoney;
					$balanceMoney = 0;
				} else {
					$outMoney += $goodsCount * $balancePrice;
					$outPriceDetail = $balancePrice;
					$outMoneyDetail = $goodsCount * $balancePrice;
					$balanceMoney -= $goodsCount * $balancePrice;
				}
				$outCount += $goodsCount;
				$outPrice = $outMoney / $outCount;
				
				$sql = "update t_inventory 
						set out_count = %d, out_price = %f, out_money = %f,
						    balance_count = %d, balance_money = %f 
						where warehouse_id = '%s' and goods_id = '%s' ";
				$rc = $db->execute($sql, $outCount, $outPrice, $outMoney, $balanceCount, 
						$balanceMoney, $warehouseId, $goodsId);
				if ($rc === false) {
					$db->rollback();
					return $this->sqlError(__LINE__);
				}
				
				// 库存明细账
				$sql = "insert into t_inventory_detail(out_count, out_price, out_money, 
						balance_count, balance_price, balance_money, warehouse_id,
						goods_id, biz_date, biz_user_id, date_created, ref_number, ref_type) 
						values(%d, %f, %f, %d, %f, %f, '%s', '%s', '%s', '%s', now(), '%s', '销售出库')";
				$rc = $db->execute($sql, $goodsCount, $outPriceDetail, $outMoneyDetail, 
						$balanceCount, $balancePrice, $balanceMoney, $warehouseId, $goodsId, $bizDT, 
						$bizUserId, $ref);
				if ($rc === false) {
					$db->rollback();
					return $this->sqlError(__LINE__);
				}
				
				// 单据本身的记录
				$sql = "update t_ws_bill_detail 
						set inventory_price = %f, inventory_money = %f
						where id = '%s' ";
				$rc = $db->execute($sql, $outPriceDetail, $outMoneyDetail, $itemId);
				if ($rc === false) {
					$db->rollback();
					return $this->sqlError(__LINE__);
				}
			}
		}
		
		if ($receivingType == 0) {
			$idGen = new IdGenService();
			
			// 记应收账款
			// 应收总账
			$sql = "select rv_money, balance_money 
					from t_receivables 
					where ca_id = '%s' and ca_type = 'customer' and company_id = '%s' ";
			$data = $db->query($sql, $customerId, $companyId);
			if ($data) {
				$rvMoney = $data[0]["rv_money"];
				$balanceMoney = $data[0]["balance_money"];
				
				$rvMoney += $saleMoney;
				$balanceMoney += $saleMoney;
				
				$sql = "update t_receivables
						set rv_money = %f,  balance_money = %f 
						where ca_id = '%s' and ca_type = 'customer' 
							and company_id = '%s' ";
				$rc = $db->execute($sql, $rvMoney, $balanceMoney, $customerId, $companyId);
				if ($rc === false) {
					$db->rollback();
					return $this->sqlError(__LINE__);
				}
			} else {
				$sql = "insert into t_receivables (id, rv_money, act_money, balance_money,
							ca_id, ca_type, company_id) 
						values ('%s', %f, 0, %f, '%s', 'customer', '%s')";
				$rc = $db->execute($sql, $idGen->newId(), $saleMoney, $saleMoney, $customerId, 
						$companyId);
				if ($rc === false) {
					$db->rollback();
					return $this->sqlError(__LINE__);
				}
			}
			
			// 应收明细账
			$sql = "insert into t_receivables_detail (id, rv_money, act_money, balance_money,
					ca_id, ca_type, date_created, ref_number, ref_type, biz_date, company_id) 
					values('%s', %f, 0, %f, '%s', 'customer', now(), '%s', '销售出库', '%s', '%s')";
			
			$rc = $db->execute($sql, $idGen->newId(), $saleMoney, $saleMoney, $customerId, $ref, 
					$bizDT, $companyId);
			if ($rc === false) {
				$db->rollback();
				return $this->sqlError(__LINE__);
			}
		} else if ($receivingType == 1) {
			// 现金收款
			$inCash = $saleMoney;
			
			$sql = "select in_money, out_money, balance_money 
					from t_cash 
					where biz_date = '%s' and company_id = '%s' ";
			$data = $db->query($sql, $bizDT, $companyId);
			if (! $data) {
				// 当天首次发生现金业务
				$sql = "select sum(in_money) as sum_in_money, sum(out_money) as sum_out_money
							from t_cash
							where biz_date <= '%s' and company_id = '%s' ";
				$data = $db->query($sql, $bizDT, $companyId);
				$sumInMoney = $data[0]["sum_in_money"];
				$sumOutMoney = $data[0]["sum_out_money"];
				if (! $sumInMoney) {
					$sumInMoney = 0;
				}
				if (! $sumOutMoney) {
					$sumOutMoney = 0;
				}
				
				$balanceCash = $sumInMoney - $sumOutMoney + $inCash;
				$sql = "insert into t_cash(in_money, balance_money, biz_date, company_id)
							values (%f, %f, '%s', '%s')";
				$rc = $db->execute($sql, $inCash, $balanceCash, $bizDT, $companyId);
				if ($rc === false) {
					$db->rollback();
					return $this->sqlError(__LINE__);
				}
				
				// 记现金明细账
				$sql = "insert into t_cash_detail(in_money, balance_money, biz_date, ref_type,
								ref_number, date_created, company_id)
							values (%f, %f, '%s', '销售出库', '%s', now(), '%s')";
				$rc = $db->execute($sql, $inCash, $balanceCash, $bizDT, $ref, $companyId);
				if ($rc === false) {
					$db->rollback();
					return $this->sqlError(__LINE__);
				}
			} else {
				$balanceCash = $data[0]["balance_money"] + $inCash;
				$sumInMoney = $data[0]["in_money"] + $inCash;
				$sql = "update t_cash
						set in_money = %f, balance_money = %f
						where biz_date = '%s' and company_id = '%s' ";
				$rc = $db->execute($sql, $sumInMoney, $balanceCash, $bizDT, $companyId);
				if ($rc === false) {
					$db->rollback();
					return $this->sqlError(__LINE__);
				}
				
				// 记现金明细账
				$sql = "insert into t_cash_detail(in_money, balance_money, biz_date, ref_type,
							ref_number, date_created, company_id)
						values (%f, %f, '%s', '销售出库', '%s', now(), '%s')";
				$rc = $db->execute($sql, $inCash, $balanceCash, $bizDT, $ref, $companyId);
				if ($rc === false) {
					$db->rollback();
					return $this->sqlError(__LINE__);
				}
			}
			
			// 调整业务日期之后的现金总账和明细账的余额
			$sql = "update t_cash
					set balance_money = balance_money + %f
					where biz_date > '%s' and company_id = '%s' ";
			$rc = $db->execute($sql, $inCash, $bizDT, $companyId);
			if ($rc === false) {
				$db->rollback();
				return $this->sqlError(__LINE__);
			}
			
			$sql = "update t_cash_detail
					set balance_money = balance_money + %f
					where biz_date > '%s' and company_id = '%s' ";
			$rc = $db->execute($sql, $inCash, $bizDT, $companyId);
			if ($rc === false) {
				$db->rollback();
				return $this->sqlError(__LINE__);
			}
		} else if ($receivingType == 2) {
			// 2: 用预收款支付
			
			$outMoney = $saleMoney;
			
			// 预收款总账
			$sql = "select out_money, balance_money from t_pre_receiving
						where customer_id = '%s' and company_id = '%s' ";
			$data = $db->query($sql, $customerId, $companyId);
			$totalOutMoney = $data[0]["out_money"];
			if (! $totalOutMoney) {
				$totalOutMoney = 0;
			}
			$totalBalanceMoney = $data[0]["balance_money"];
			if (! $totalBalanceMoney) {
				$totalBalanceMoney = 0;
			}
			if ($totalBalanceMoney < $outMoney) {
				$db->rollback();
				return $this->bad("付余款余额是{$totalBalanceMoney}元，小于销售金额，无法付款");
			}
			
			$totalOutMoney += $outMoney;
			$totalBalanceMoney -= $outMoney;
			$sql = "update t_pre_receiving
					set out_money = %f, balance_money = %f
					where customer_id = '%s' and company_id = '%s' ";
			$rc = $db->execute($sql, $totalOutMoney, $totalBalanceMoney, $customerId, $companyId);
			if ($rc === false) {
				$db->rollback();
				return $this->sqlError();
			}
			
			// 预收款明细账
			$sql = "insert into t_pre_receiving_detail (id, customer_id, out_money, balance_money,
						biz_date, date_created, ref_number, ref_type, biz_user_id, input_user_id, company_id)
					values ('%s', '%s', %f, %f, '%s', now(), '%s', '销售出库', '%s', '%s', '%s')";
			$idGen = new IdGenService();
			$us = new UserService();
			$rc = $db->execute($sql, $idGen->newId(), $customerId, $outMoney, $totalBalanceMoney, 
					$bizDT, $ref, $bizUserId, $us->getLoginUserId(), $companyId);
			if ($rc === false) {
				$db->rollback();
				return $this->sqlError();
			}
		}
		
		// 把单据本身设置为已经提交出库
		$sql = "select sum(inventory_money) as sum_inventory_money 
					from t_ws_bill_detail 
					where wsbill_id = '%s' ";
		$data = $db->query($sql, $id);
		$sumInventoryMoney = $data[0]["sum_inventory_money"];
		if (! $sumInventoryMoney) {
			$sumInventoryMoney = 0;
		}
		
		$profit = $saleMoney - $sumInventoryMoney;
		
		$sql = "update t_ws_bill 
					set bill_status = 1000, inventory_money = %f, profit = %f 
					where id = '%s' ";
		$rc = $db->execute($sql, $sumInventoryMoney, $profit, $id);
		if ($rc === false) {
			$db->rollback();
			return $this->sqlError(__LINE__);
		}
		
		$log = "提交销售出库单，单号 = {$ref}";
		$bs = new BizlogService();
		$bs->insertBizlog($log, $this->LOG_CATEGORY);
		
		$db->commit();
		
		return $this->ok($id);
	}

	/**
	 * 销售出库单生成pdf文件
	 */
	public function pdf($params) {
		if ($this->isNotOnline()) {
			return;
		}
		
		$ref = $params["ref"];
		$db = M();
		$sql = "select w.id, w.bizdt, c.name as customer_name,
					  u.name as biz_user_name,
					  h.name as warehouse_name,
					  w.sale_money
					from t_ws_bill w, t_customer c, t_user u, t_warehouse h
					where w.customer_id = c.id and w.biz_user_id = u.id
					  and w.warehouse_id = h.id
					  and w.ref = '%s' ";
		$data = $db->query($sql, $ref);
		if (! $data) {
			return;
		}
		
		$id = $data[0]["id"];
		
		$bill["bizDT"] = date("Y-m-d", strtotime($data[0]["bizdt"]));
		$bill["customerName"] = $data[0]["customer_name"];
		$bill["warehouseName"] = $data[0]["warehouse_name"];
		$bill["bizUserName"] = $data[0]["biz_user_name"];
		$bill["saleMoney"] = $data[0]["sale_money"];
		
		// 明细表
		$sql = "select g.code, g.name, g.spec, u.name as unit_name, d.goods_count,
					d.goods_price, d.goods_money, d.sn_note
					from t_ws_bill_detail d, t_goods g, t_goods_unit u
					where d.wsbill_id = '%s' and d.goods_id = g.id and g.unit_id = u.id
					order by d.show_order";
		$data = $db->query($sql, $id);
		$items = array();
		foreach ( $data as $i => $v ) {
			$items[$i]["goodsCode"] = $v["code"];
			$items[$i]["goodsName"] = $v["name"];
			$items[$i]["goodsSpec"] = $v["spec"];
			$items[$i]["unitName"] = $v["unit_name"];
			$items[$i]["goodsCount"] = $v["goods_count"];
			$items[$i]["goodsPrice"] = $v["goods_price"];
			$items[$i]["goodsMoney"] = $v["goods_money"];
			$items[$i]["sn"] = $v["sn_note"];
		}
		$bill["items"] = $items;
		
		$ps = new PDFService();
		$pdf = $ps->getInstance();
		$pdf->SetTitle("销售出库单，单号：{$ref}");
		
		$pdf->setHeaderFont(Array(
				"stsongstdlight",
				"",
				16
		));
		
		$pdf->setFooterFont(Array(
				"stsongstdlight",
				"",
				14
		));
		
		$pdf->SetHeaderData("", 0, "开源进销存PSI", "销售出库单");
		
		$pdf->SetFont("stsongstdlight", "", 10);
		$pdf->AddPage();
		
		$html = '
				<table>
					<tr><td colspan="2">单号：' . $ref . '</td></tr>
					<tr><td colspan="2">客户：' . $bill["customerName"] . '</td></tr>
					<tr><td>业务日期：' . $bill["bizDT"] . '</td><td>出库仓库:' . $bill["warehouseName"] . '</td></tr>
					<tr><td>业务员：' . $bill["bizUserName"] . '</td><td></td></tr>
					<tr><td colspan="2">销售金额:' . $bill["saleMoney"] . '</td></tr>
				</table>
				';
		$pdf->writeHTML($html);
		
		$html = '<table border="1" cellpadding="1">
					<tr><td>商品编号</td><td>商品名称</td><td>规格型号</td><td>数量</td><td>单位</td>
						<td>单价</td><td>销售金额</td><td>序列号</td>
					</tr>
				';
		foreach ( $bill["items"] as $v ) {
			$html .= '<tr>';
			$html .= '<td>' . $v["goodsCode"] . '</td>';
			$html .= '<td>' . $v["goodsName"] . '</td>';
			$html .= '<td>' . $v["goodsSpec"] . '</td>';
			$html .= '<td align="right">' . $v["goodsCount"] . '</td>';
			$html .= '<td>' . $v["unitName"] . '</td>';
			$html .= '<td align="right">' . $v["goodsPrice"] . '</td>';
			$html .= '<td align="right">' . $v["goodsMoney"] . '</td>';
			$html .= '<td>' . $v["sn"] . '</td>';
			$html .= '</tr>';
		}
		
		$html .= "";
		
		$html .= '</table>';
		$pdf->writeHTML($html, true, false, true, false, '');
		
		$pdf->Output("$ref.pdf", "I");
	}
}
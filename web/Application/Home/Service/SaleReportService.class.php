<?php

namespace Home\Service;

use Home\DAO\SaleReportDAO;

/**
 * 销售报表Service
 *
 * @author 李静波
 */
class SaleReportService extends PSIBaseExService {
	private $LOG_CATEGORY = "销售报表";

	/**
	 * 销售日报表(按商品汇总) - 查询数据
	 */
	public function saleDayByGoodsQueryData($params) {
		if ($this->isNotOnline()) {
			return $this->emptyResult();
		}
		
		$params["companyId"] = $this->getCompanyId();
		
		$dao = new SaleReportDAO($this->db());
		return $dao->saleDayByGoodsQueryData($params);
	}

	/**
	 * 销售日报表(按商品汇总) - 查询数据，用于Lodop打印
	 *
	 * @param array $params        	
	 * @return array
	 */
	public function getSaleDayByGoodsDataForLodopPrint($params) {
		if ($this->isNotOnline()) {
			return $this->emptyResult();
		}
		
		$params["companyId"] = $this->getCompanyId();
		
		$dao = new SaleReportDAO($this->db());
		$items = $dao->saleDayByGoodsQueryData($params);
		
		$data = $this->saleDaySummaryQueryData($params);
		$v = $data[0];
		
		return [
				"bizDate" => $params["dt"],
				"printDT" => date("Y-m-d H:i:s"),
				"saleMoney" => $v["saleMoney"],
				"rejMoney" => $v["rejMoney"],
				"m" => $v["m"],
				"profit" => $v["profit"],
				"rate" => $v["rate"],
				"items" => $items["dataList"]
		];
	}

	/**
	 * 销售日报表(按商品汇总) - 生成PDF文件
	 *
	 * @param array $params        	
	 */
	public function saleDayByGoodsPdf($params) {
		if ($this->isNotOnline()) {
			return;
		}
		
		$params["companyId"] = $this->getCompanyId();
		
		$bizDT = $params["dt"];
		
		$bs = new BizConfigService();
		$productionName = $bs->getProductionName();
		
		$dao = new SaleReportDAO($this->db());
		
		$data = $dao->saleDayByGoodsQueryData($params);
		$items = $data["dataList"];
		
		$data = $this->saleDaySummaryQueryData($params);
		$summary = $data[0];
		
		// 记录业务日志
		$log = "销售日报表(按商品汇总)导出PDF文件";
		$bls = new BizlogService($this->db());
		$bls->insertBizlog($log, $this->LOG_CATEGORY);
		
		ob_start();
		
		$ps = new PDFService();
		$pdf = $ps->getInstanceForReport();
		$pdf->SetTitle("销售日报表(按商品汇总)");
		
		$pdf->setHeaderFont(array(
				"stsongstdlight",
				"",
				16
		));
		
		$pdf->setFooterFont(array(
				"stsongstdlight",
				"",
				14
		));
		
		$pdf->SetHeaderData("", 0, $productionName, "销售日报表(按商品汇总)");
		
		$pdf->SetFont("stsongstdlight", "", 10);
		$pdf->AddPage();
		
		/**
		 * 注意：
		 * TCPDF中，用来拼接HTML的字符串需要用单引号，否则HTML中元素的属性就不会被解析
		 */
		$html = '
				<table>
					<tr>
						<td>业务日期：' . $bizDT . '</td>
						<td>销售出库金额：' . $summary["saleMoney"] . '</td>
						<td>退回入库金额：' . $summary["rejMoney"] . '</td>
						<td>净销售金额：' . $summary["m"] . '</td>
					</tr>
					<tr>
						<td>毛利：' . $summary["profit"] . '</td>
						<td>毛利率：' . $summary["rate"] . '</td>
						<td></td>
						<td></td>
					</tr>
				</table>
				';
		$pdf->writeHTML($html);
		
		$html = '<table border="1" cellpadding="1">
					<tr><td>商品编号</td><td>商品名称</td><td>规格型号</td><td>销售出库数量</td><td>单位</td>
						<td>销售出库金额</td><td>退货入库数量</td><td>退货入库金额</td><td>净销售数量</td>
						<td>净销售金额</td><td>毛利</td><td>毛利率</td>
					</tr>
				';
		foreach ( $items as $v ) {
			$html .= '<tr>';
			$html .= '<td>' . $v["goodsCode"] . '</td>';
			$html .= '<td>' . $v["goodsName"] . '</td>';
			$html .= '<td>' . $v["goodsSpec"] . '</td>';
			$html .= '<td align="right">' . $v["saleCount"] . '</td>';
			$html .= '<td>' . $v["unitName"] . '</td>';
			$html .= '<td align="right">' . $v["saleMoney"] . '</td>';
			$html .= '<td align="right">' . $v["rejCount"] . '</td>';
			$html .= '<td align="right">' . $v["rejMoney"] . '</td>';
			$html .= '<td align="right">' . $v["c"] . '</td>';
			$html .= '<td align="right">' . $v["m"] . '</td>';
			$html .= '<td align="right">' . $v["profit"] . '</td>';
			$html .= '<td align="right">' . $v["rate"] . '</td>';
			$html .= '</tr>';
		}
		
		$html .= '</table>';
		$pdf->writeHTML($html, true, false, true, false, '');
		
		ob_end_clean();
		
		$pdf->Output("销售日报表(按商品汇总).pdf", "I");
	}

	/**
	 * 销售日报表(按商品汇总) - 生成Excel文件
	 *
	 * @param array $params        	
	 */
	public function saleDayByGoodsExcel($params) {
		if ($this->isNotOnline()) {
			return;
		}
		
		$params["companyId"] = $this->getCompanyId();
		
		$bizDT = $params["dt"];
		
		$bs = new BizConfigService();
		$productionName = $bs->getProductionName();
		
		$dao = new SaleReportDAO($this->db());
		
		$data = $dao->saleDayByGoodsQueryData($params);
		$items = $data["dataList"];
		
		$data = $this->saleDaySummaryQueryData($params);
		$summary = $data[0];
		
		// 记录业务日志
		$log = "销售日报表(按商品汇总)导出Excel文件";
		$bls = new BizlogService($this->db());
		$bls->insertBizlog($log, $this->LOG_CATEGORY);
		
		require __DIR__ . '/../Common/Excel/PHPExcel/IOFactory.php';
		
		$excel = new \PHPExcel();
		
		$sheet = $excel->getActiveSheet();
		if (! $sheet) {
			$sheet = $excel->createSheet();
		}
		
		$sheet->setTitle("销售日报表(按商品汇总)");
		
		$sheet->getRowDimension('1')->setRowHeight(22);
		$info = "业务日期: " . $bizDT . " 销售出库金额: " . $summary["saleMoney"] . " 退货入库金额: " . $summary["rejMoney"] . " 毛利: " . $summary["profit"] . " 毛利率: " . $summary["rate"];
		$sheet->setCellValue("A1", $info);
		
		$sheet->getColumnDimension('A')->setWidth(15);
		$sheet->setCellValue("A2", "商品编码");
		
		$sheet->getColumnDimension('B')->setWidth(40);
		$sheet->setCellValue("B2", "商品名称");
		
		$sheet->getColumnDimension('C')->setWidth(40);
		$sheet->setCellValue("C2", "规格型号");
		
		$sheet->getColumnDimension('D')->setWidth(15);
		$sheet->setCellValue("D2", "销售出库数量");
		
		$sheet->getColumnDimension('E')->setWidth(10);
		$sheet->setCellValue("E2", "单位");
		
		$sheet->getColumnDimension('F')->setWidth(15);
		$sheet->setCellValue("F2", "销售出库金额");
		
		$sheet->getColumnDimension('G')->setWidth(15);
		$sheet->setCellValue("G2", "退货入库数量");
		
		$sheet->getColumnDimension('H')->setWidth(15);
		$sheet->setCellValue("H2", "退货入库金额");
		
		$sheet->getColumnDimension('I')->setWidth(15);
		$sheet->setCellValue("I2", "净销售数量");
		
		$sheet->getColumnDimension('J')->setWidth(15);
		$sheet->setCellValue("J2", "净销售金额");
		
		$sheet->getColumnDimension('K')->setWidth(15);
		$sheet->setCellValue("K2", "毛利");
		
		$sheet->getColumnDimension('L')->setWidth(15);
		$sheet->setCellValue("L2", "毛利率");
		
		foreach ( $items as $i => $v ) {
			$row = $i + 3;
			$sheet->setCellValue("A" . $row, $v["goodsCode"]);
			$sheet->setCellValue("B" . $row, $v["goodsName"]);
			$sheet->setCellValue("C" . $row, $v["goodsSpec"]);
			$sheet->setCellValue("D" . $row, $v["saleCount"]);
			$sheet->setCellValue("E" . $row, $v["unitName"]);
			$sheet->setCellValue("F" . $row, $v["saleMoney"]);
			$sheet->setCellValue("G" . $row, $v["rejCount"]);
			$sheet->setCellValue("H" . $row, $v["rejMoney"]);
			$sheet->setCellValue("I" . $row, $v["c"]);
			$sheet->setCellValue("J" . $row, $v["m"]);
			$sheet->setCellValue("K" . $row, $v["profit"]);
			$sheet->setCellValue("L" . $row, $v["rate"]);
		}
		
		// 画表格边框
		$styleArray = [
				'borders' => [
						'allborders' => [
								'style' => 'thin'
						]
				]
		];
		$lastRow = count($items) + 2;
		$sheet->getStyle('A2:L' . $lastRow)->applyFromArray($styleArray);
		
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="销售日报表(按商品汇总).xlsx"');
		header('Cache-Control: max-age=0');
		
		$writer = \PHPExcel_IOFactory::createWriter($excel, "Excel2007");
		$writer->save("php://output");
	}

	private function saleDaySummaryQueryData($params) {
		$dt = $params["dt"];
		
		$result = array();
		$result[0]["bizDT"] = $dt;
		
		$us = new UserService();
		$companyId = $us->getCompanyId();
		
		$db = M();
		$sql = "select sum(d.goods_money) as goods_money, sum(d.inventory_money) as inventory_money
					from t_ws_bill w, t_ws_bill_detail d
					where w.id = d.wsbill_id and w.bizdt = '%s'
						and w.bill_status >= 1000 and w.company_id = '%s' ";
		$data = $db->query($sql, $dt, $companyId);
		$saleMoney = $data[0]["goods_money"];
		if (! $saleMoney) {
			$saleMoney = 0;
		}
		$saleInventoryMoney = $data[0]["inventory_money"];
		if (! $saleInventoryMoney) {
			$saleInventoryMoney = 0;
		}
		$result[0]["saleMoney"] = $saleMoney;
		
		$sql = "select  sum(d.rejection_sale_money) as rej_money,
						sum(d.inventory_money) as rej_inventory_money
					from t_sr_bill s, t_sr_bill_detail d
					where s.id = d.srbill_id and s.bizdt = '%s'
						and s.bill_status = 1000 and s.company_id = '%s' ";
		$data = $db->query($sql, $dt, $companyId);
		$rejSaleMoney = $data[0]["rej_money"];
		if (! $rejSaleMoney) {
			$rejSaleMoney = 0;
		}
		$rejInventoryMoney = $data[0]["rej_inventory_money"];
		if (! $rejInventoryMoney) {
			$rejInventoryMoney = 0;
		}
		
		$result[0]["rejMoney"] = $rejSaleMoney;
		
		$m = $saleMoney - $rejSaleMoney;
		$result[0]["m"] = $m;
		$profit = $saleMoney - $rejSaleMoney - $saleInventoryMoney + $rejInventoryMoney;
		$result[0]["profit"] = $profit;
		if ($m > 0) {
			$result[0]["rate"] = sprintf("%0.2f", $profit / $m * 100) . "%";
		}
		
		return $result;
	}

	/**
	 * 销售日报表(按商品汇总) - 查询汇总数据
	 */
	public function saleDayByGoodsSummaryQueryData($params) {
		if ($this->isNotOnline()) {
			return $this->emptyResult();
		}
		
		return $this->saleDaySummaryQueryData($params);
	}

	/**
	 * 销售日报表(按客户汇总) - 查询数据
	 */
	public function saleDayByCustomerQueryData($params) {
		if ($this->isNotOnline()) {
			return $this->emptyResult();
		}
		
		$params["companyId"] = $this->getCompanyId();
		
		$dao = new SaleReportDAO($this->db());
		return $dao->saleDayByCustomerQueryData($params);
	}

	/**
	 * 销售日报表(按客户汇总) - 查询汇总数据
	 */
	public function saleDayByCustomerSummaryQueryData($params) {
		if ($this->isNotOnline()) {
			return $this->emptyResult();
		}
		
		return $this->saleDaySummaryQueryData($params);
	}

	/**
	 * 销售日报表(按客户汇总) - 查询数据，用于Lodop打印
	 *
	 * @param array $params        	
	 * @return array
	 */
	public function getSaleDayByCustomerDataForLodopPrint($params) {
		if ($this->isNotOnline()) {
			return $this->emptyResult();
		}
		
		$params["companyId"] = $this->getCompanyId();
		
		$dao = new SaleReportDAO($this->db());
		$items = $dao->saleDayByCustomerQueryData($params);
		
		$data = $this->saleDaySummaryQueryData($params);
		$v = $data[0];
		
		return [
				"bizDate" => $params["dt"],
				"printDT" => date("Y-m-d H:i:s"),
				"saleMoney" => $v["saleMoney"],
				"rejMoney" => $v["rejMoney"],
				"m" => $v["m"],
				"profit" => $v["profit"],
				"rate" => $v["rate"],
				"items" => $items["dataList"]
		];
	}

	/**
	 * 销售日报表(按仓库汇总) - 查询数据
	 */
	public function saleDayByWarehouseQueryData($params) {
		if ($this->isNotOnline()) {
			return $this->emptyResult();
		}
		
		$params["companyId"] = $this->getCompanyId();
		
		$dao = new SaleReportDAO($this->db());
		return $dao->saleDayByWarehouseQueryData($params);
	}

	/**
	 * 销售日报表(按仓库汇总) - 查询汇总数据
	 */
	public function saleDayByWarehouseSummaryQueryData($params) {
		if ($this->isNotOnline()) {
			return $this->emptyResult();
		}
		
		return $this->saleDaySummaryQueryData($params);
	}

	/**
	 * 销售日报表(按业务员汇总) - 查询数据
	 */
	public function saleDayByBizuserQueryData($params) {
		if ($this->isNotOnline()) {
			return $this->emptyResult();
		}
		
		$params["companyId"] = $this->getCompanyId();
		
		$dao = new SaleReportDAO($this->db());
		return $dao->saleDayByBizuserQueryData($params);
	}

	/**
	 * 销售日报表(按业务员汇总) - 查询汇总数据
	 */
	public function saleDayByBizuserSummaryQueryData($params) {
		if ($this->isNotOnline()) {
			return $this->emptyResult();
		}
		
		return $this->saleDaySummaryQueryData($params);
	}

	/**
	 * 销售月报表(按商品汇总) - 查询数据
	 */
	public function saleMonthByGoodsQueryData($params) {
		if ($this->isNotOnline()) {
			return $this->emptyResult();
		}
		
		$params["companyId"] = $this->getCompanyId();
		
		$dao = new SaleReportDAO($this->db());
		return $dao->saleMonthByGoodsQueryData($params);
	}

	private function saleMonthSummaryQueryData($params) {
		$year = $params["year"];
		$month = $params["month"];
		
		$result = array();
		if ($month < 10) {
			$result[0]["bizDT"] = "$year-0$month";
		} else {
			$result[0]["bizDT"] = "$year-$month";
		}
		
		$us = new UserService();
		$companyId = $us->getCompanyId();
		
		$db = M();
		$sql = "select sum(d.goods_money) as goods_money, sum(d.inventory_money) as inventory_money
					from t_ws_bill w, t_ws_bill_detail d
					where w.id = d.wsbill_id and year(w.bizdt) = %d and month(w.bizdt) = %d
						and w.bill_status >= 1000 and w.company_id = '%s' ";
		$data = $db->query($sql, $year, $month, $companyId);
		$saleMoney = $data[0]["goods_money"];
		if (! $saleMoney) {
			$saleMoney = 0;
		}
		$saleInventoryMoney = $data[0]["inventory_money"];
		if (! $saleInventoryMoney) {
			$saleInventoryMoney = 0;
		}
		$result[0]["saleMoney"] = $saleMoney;
		
		$sql = "select  sum(d.rejection_sale_money) as rej_money,
						sum(d.inventory_money) as rej_inventory_money
					from t_sr_bill s, t_sr_bill_detail d
					where s.id = d.srbill_id and year(s.bizdt) = %d and month(s.bizdt) = %d
						and s.bill_status = 1000 and s.company_id = '%s' ";
		$data = $db->query($sql, $year, $month, $companyId);
		$rejSaleMoney = $data[0]["rej_money"];
		if (! $rejSaleMoney) {
			$rejSaleMoney = 0;
		}
		$rejInventoryMoney = $data[0]["rej_inventory_money"];
		if (! $rejInventoryMoney) {
			$rejInventoryMoney = 0;
		}
		
		$result[0]["rejMoney"] = $rejSaleMoney;
		
		$m = $saleMoney - $rejSaleMoney;
		$result[0]["m"] = $m;
		$profit = $saleMoney - $rejSaleMoney - $saleInventoryMoney + $rejInventoryMoney;
		$result[0]["profit"] = $profit;
		if ($m > 0) {
			$result[0]["rate"] = sprintf("%0.2f", $profit / $m * 100) . "%";
		}
		
		return $result;
	}

	/**
	 * 销售月报表(按商品汇总) - 查询汇总数据
	 */
	public function saleMonthByGoodsSummaryQueryData($params) {
		if ($this->isNotOnline()) {
			return $this->emptyResult();
		}
		
		return $this->saleMonthSummaryQueryData($params);
	}

	/**
	 * 销售月报表(按客户汇总) - 查询数据
	 */
	public function saleMonthByCustomerQueryData($params) {
		if ($this->isNotOnline()) {
			return $this->emptyResult();
		}
		
		$params["companyId"] = $this->getCompanyId();
		
		$dao = new SaleReportDAO($this->db());
		return $dao->saleMonthByCustomerQueryData($params);
	}

	/**
	 * 销售月报表(按客户汇总) - 查询汇总数据
	 */
	public function saleMonthByCustomerSummaryQueryData($params) {
		if ($this->isNotOnline()) {
			return $this->emptyResult();
		}
		
		return $this->saleMonthSummaryQueryData($params);
	}

	/**
	 * 销售月报表(按仓库汇总) - 查询数据
	 */
	public function saleMonthByWarehouseQueryData($params) {
		if ($this->isNotOnline()) {
			return $this->emptyResult();
		}
		
		$params["companyId"] = $this->getCompanyId();
		
		$dao = new SaleReportDAO($this->db());
		return $dao->saleMonthByWarehouseQueryData($params);
	}

	/**
	 * 销售月报表(按仓库汇总) - 查询汇总数据
	 */
	public function saleMonthByWarehouseSummaryQueryData($params) {
		if ($this->isNotOnline()) {
			return $this->emptyResult();
		}
		
		return $this->saleMonthSummaryQueryData($params);
	}

	/**
	 * 销售月报表(按业务员汇总) - 查询数据
	 */
	public function saleMonthByBizuserQueryData($params) {
		if ($this->isNotOnline()) {
			return $this->emptyResult();
		}
		
		$params["companyId"] = $this->getCompanyId();
		
		$dao = new SaleReportDAO($this->db());
		return $dao->saleMonthByBizuserQueryData($params);
	}

	/**
	 * 销售月报表(按业务员汇总) - 查询汇总数据
	 */
	public function saleMonthByBizuserSummaryQueryData($params) {
		if ($this->isNotOnline()) {
			return $this->emptyResult();
		}
		
		return $this->saleMonthSummaryQueryData($params);
	}
}
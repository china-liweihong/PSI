<?php

namespace Home\Service;

use Home\DAO\PurchaseReportDAO;

/**
 * 采购报表Service
 *
 * @author 李静波
 */
class PurchaseReportService extends PSIBaseExService
{
  private $LOG_CATEGORY = "采购报表";

  /**
   * 采购入库明细表 - 数据查询
   */
  public function purchaseDetailQueryData($params)
  {
    if ($this->isNotOnline()) {
      return $this->emptyResult();
    }

    $params["companyId"] = $this->getCompanyId();

    $dao = new PurchaseReportDAO($this->db());

    return $dao->purchaseDetailQueryData($params);
  }

  /**
   * 采购入库明细表 - 导出Excel
   */
  public function purchaseDetailExcel($params)
  {
    if ($this->isNotOnline()) {
      return;
    }

    $params["companyId"] = $this->getCompanyId();

    $dao = new PurchaseReportDAO($this->db());
    $data = $dao->purchaseDetailQueryData($params);

    $items = $data["dataList"];

    // 记录业务日志
    $log = "采购入库明细表导出Excel文件";
    $bls = new BizlogService($this->db());
    $bls->insertBizlog($log, $this->LOG_CATEGORY);

    $excel = new \PHPExcel();

    $sheet = $excel->getActiveSheet();
    if (!$sheet) {
      $sheet = $excel->createSheet();
    }

    $sheet->setTitle("采购入库明细表");

    $sheet->getRowDimension('1')->setRowHeight(22);

    $sheet->getColumnDimension('A')->setWidth(15);
    $sheet->setCellValue("A2", "采购单号");

    $sheet->getColumnDimension('B')->setWidth(15);
    $sheet->setCellValue("B2", "入库单单号");

    $sheet->getColumnDimension('C')->setWidth(15);
    $sheet->setCellValue("C2", "入库单业务日期");

    $sheet->getColumnDimension('D')->setWidth(40);
    $sheet->setCellValue("D2", "入库仓库");

    $sheet->getColumnDimension('E')->setWidth(40);
    $sheet->setCellValue("E2", "供应商");

    $sheet->getColumnDimension('F')->setWidth(15);
    $sheet->setCellValue("F2", "商品编码");

    $sheet->getColumnDimension('G')->setWidth(15);
    $sheet->setCellValue("G2", "商品名称");

    $sheet->getColumnDimension('H')->setWidth(15);
    $sheet->setCellValue("H2", "规格型号");

    $sheet->getColumnDimension('I')->setWidth(15);
    $sheet->setCellValue("I2", "入库数量");

    $sheet->getColumnDimension('J')->setWidth(15);
    $sheet->setCellValue("J2", "单位");

    $sheet->getColumnDimension('K')->setWidth(15);
    $sheet->setCellValue("K2", "采购单价");

    $sheet->getColumnDimension('L')->setWidth(15);
    $sheet->setCellValue("L2", "采购金额");

    $sheet->getColumnDimension('M')->setWidth(15);
    $sheet->setCellValue("M2", "税率(%)");

    $sheet->getColumnDimension('N')->setWidth(15);
    $sheet->setCellValue("N2", "税金");

    $sheet->getColumnDimension('O')->setWidth(15);
    $sheet->setCellValue("O2", "加税合计");

    $sheet->getColumnDimension('P')->setWidth(15);
    $sheet->setCellValue("P2", "含税价");

    $sheet->getColumnDimension('Q')->setWidth(30);
    $sheet->setCellValue("Q2", "备注");

    foreach ($items as $i => $v) {
      $row = $i + 3;
      $sheet->setCellValue("A" . $row, $v["poBillRef"]);
      $sheet->setCellValue("B" . $row, $v["pwBillRef"]);
      $sheet->setCellValue("C" . $row, $v["bizDate"]);
      $sheet->setCellValue("D" . $row, $v["warehouseName"]);
      $sheet->setCellValue("E" . $row, $v["supplierName"]);
      $sheet->setCellValue("F" . $row, $v["goodsCode"]);
      $sheet->setCellValue("G" . $row, $v["goodsName"]);
      $sheet->setCellValue("H" . $row, $v["goodsSpec"]);
      $sheet->setCellValue("I" . $row, $v["goodsCount"]);
      $sheet->setCellValue("J" . $row, $v["unitName"]);
      $sheet->setCellValue("K" . $row, $v["goodsPrice"]);
      $sheet->setCellValue("L" . $row, $v["goodsMoney"]);
      $sheet->setCellValue("M" . $row, $v["taxRate"]);
      $sheet->setCellValue("N" . $row, $v["tax"]);
      $sheet->setCellValue("O" . $row, $v["moneyWithTax"]);
      $sheet->setCellValue("P" . $row, $v["goodsPriceWithTax"]);
      $sheet->setCellValue("Q" . $row, $v["memo"]);
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
    $sheet->getStyle('A2:Q' . $lastRow)->applyFromArray($styleArray);

    $dt = date("YmdHis");

    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="采购入库明细表_' . $dt . '.xlsx"');
    header('Cache-Control: max-age=0');

    $writer = \PHPExcel_IOFactory::createWriter($excel, "Excel2007");
    $writer->save("php://output");
  }
}

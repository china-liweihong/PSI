<?php

namespace Home\DAO;

/**
 * 自定义表单 DAO
 *
 * @author 李静波
 */
class FormDAO extends PSIBaseExDAO
{

  /**
   * 表单分类列表
   */
  public function categoryList($params)
  {
    $db = $this->db;

    $sql = "select id, code, name
            from t_form_category
            order by code";
    $data = $db->query($sql);

    $result = [];
    foreach ($data as $v) {
      $result[] = [
        "id" => $v["id"],
        "code" => $v["code"],
        "name" => $v["name"]
      ];
    }

    return $result;
  }

  /**
   * 新增表单分类
   */
  public function addFormCategory(&$params)
  {
    $db = $this->db;

    $code = $params["code"] ?? "";
    $code = strtoupper($code);
    $name = $params["name"];

    // 检查编码是否存在
    if ($code) {
      $sql = "select count(*) as cnt from t_form_category where code = '%s' ";
      $data = $db->query($sql, $code);
      $cnt = $data[0]["cnt"];
      if ($cnt) {
        return $this->bad("表单分类编码[{$code}]已经存在");
      }
    } else {
      $code = "";
    }

    // 检查分类名称是否存在
    $sql = "select count(*) as cnt from t_form_category where name = '%s' ";
    $data = $db->query($sql, $name);
    $cnt = $data[0]["cnt"];
    if ($cnt) {
      return $this->bad("表单分类[{$name}]已经存在");
    }

    $id = $this->newId();
    $sql = "insert into t_form_category (id, code, name, parent_id)
            values ('%s', '%s', '%s', null)";

    $rc = $db->execute($sql, $id, $code, $name);
    if ($rc === false) {
      return $this->sqlError(__METHOD__, __LINE__);
    }

    // 操作成功
    $params["id"] = $id;
    return null;
  }

  public function getCategoryById($id)
  {
    $db = $this->db;

    $sql = "select code, name from t_form_category where id = '%s' ";
    $data = $db->query($sql, $id);
    if ($data) {
      return [
        "code" => $data[0]["code"],
        "name" => $data[0]["name"]
      ];
    } else {
      return null;
    }
  }

  /**
   * 编辑表单分类
   */
  public function updateFormCategory(&$params)
  {
    $db = $this->db;

    $id = $params["id"];
    $code = $params["code"] ?? "";
    $code = strtoupper($code);
    $name = $params["name"];

    $category = $this->getCategoryById($id);
    if (!$category) {
      return $this->bad("要编辑的表单分类不存在");
    }

    // 检查编码是否已经存在
    if ($code) {
      $sql = "select count(*) as cnt from t_form_category 
              where code = '%s' and id <> '%s' ";
      $data = $db->query($sql, $code, $id);
      $cnt = $data[0]["cnt"];
      if ($cnt) {
        return $this->bad("表单分类编码[{$code}]已经存在");
      }
    } else {
      $code = "";
    }

    // 检查分类名称是否已经存在
    $sql = "select count(*) as cnt from t_form_category 
            where name = '%s' and id <> '%s' ";
    $data = $db->query($sql, $name, $id);
    $cnt = $data[0]["cnt"];
    if ($cnt) {
      return $this->bad("表单分类[{$name}]已经存在");
    }

    $sql = "update t_form_category
            set code = '%s', name = '%s'
            where id = '%s' ";
    $rc = $db->execute($sql, $code, $name, $id);
    if ($rc === false) {
      return $this->sqlError(__METHOD__, __LINE__);
    }

    // 操作成功
    $params["id"] = $id;
    return null;
  }

  /**
   * 删除表单分类
   */
  public function deleteFormCategory(&$params)
  {
    $db = $this->db;

    $id = $params["id"];
    $category = $this->getCategoryById($id);
    if (!$category) {
      return $this->bad("要删除的表单分类不存在");
    }
    $name = $category["name"];

    // 检查是否有下级分类
    $sql = "select count(*) as cnt from t_form_category where parent_id = '%s' ";
    $data = $db->query($sql, $id);
    $cnt = $data[0]["cnt"];
    if ($cnt > 0) {
      return $this->bad("分类[{$name}]还有下级分类，不能删除");
    }

    // 检查该分类下是否有表单
    $sql = "select count(*) as cnt from t_form where category_id = '%s' ";
    $data = $db->query($sql, $id);
    $cnt = $data[0]["cnt"];
    if ($cnt > 0) {
      return $this->bad("分类[{$name}]中还有表单，不能删除");
    }

    // 执行删除操作
    $sql = "delete from t_form_category where id = '%s' ";
    $rc = $db->execute($sql, $id);
    if ($rc === false) {
      return $this->sqlError(__METHOD__, __LINE__);
    }

    // 操作成功
    $params["name"] = $name;
    return null;
  }

  /**
   * 表单分类自定义字段 - 查询数据
   */
  public function queryDataForCategory($params)
  {
    $db = $this->db;

    $queryKey = $params["queryKey"] ?? "";

    $sql = "select id, code, name
            from t_form_category
            where code like '%s' or name like '%s' ";
    $queryParams = [];
    $queryParams[] = "%{$queryKey}%";
    $queryParams[] = "%{$queryKey}%";

    $data = $db->query($sql, $queryParams);

    $result = [];
    foreach ($data as $v) {
      $result[] = [
        "id" => $v["id"],
        "code" => $v["code"],
        "name" => $v["name"]
      ];
    }

    return $result;
  }

  /**
   * 某个分类下的表单列表
   */
  public function formList($params)
  {
    $db = $this->db;

    $categoryId = $params["caregoryId"];

    $sql = "select id, code, name, sys_form, md_version, memo
            from t_form 
            where category_id = '%s'
            order by code";
    $data = $db->query($sql, $categoryId);
    $result = [];
    foreach ($data as $v) {
      $result[] = [
        "id" => $v["id"],
        "code" => $v["code"],
        "name" => $v["name"],
        "sysForm" => $v["sys_form"],
        "mdVersion" => $v["md_version"],
        "memo" => $v["memo"]
      ];
    }
    return $result;
  }

  private function checkTableName($tableName)
  {
    $tableName = strtolower($tableName);

    $len = strlen($tableName);
    if ($len < 6) {
      return $this->bad("数据库表名长度不能小于6");
    }

    $c = ord($tableName{
      0});
    $isABC = ord('a') <= $c && ord('z') >= $c;
    if (!$isABC) {
      return $this->bad("数据库表名需要以字符开头");
    }

    for ($i = 1; $i < $len; $i++) {
      $c = ord($tableName{
        $i});
      $isABC = ord('a') <= $c && ord('z') >= $c;
      $isNumber = ord('0') <= $c && ord('9') >= $c;
      $isOK = $isABC || $isNumber || ord('_') == $c;
      if (!$isOK) {
        $index = $i + 1;
        return $this->bad("数据库表名的第{$index}个字符非法");
      }
    }

    // 表单需要以t_开头
    if (!(substr($tableName, 0, 2) == "t_")) {
      return $this->bad("数据库表名需要以 <span style='color:red'>t_</span> 开头");
    }

    // 表名正确
    return null;
  }

  /**
   * 表单主表标准字段
   */
  private function getFormSysCols()
  {
    $result = [];

    // id
    $result[] = [
      "caption" => "id",
      "fieldName" => "id",
      "fieldType" => "varchar",
      "fieldLength" => 255,
      "fieldDecimal" => 0,
      "valueFrom" => 5,
      "valueFromTableName" => "",
      "valueFromColName" => "",
      "valueFromColNameDisplay" => "",
      "mustInput" => 1,
      "showOrder" => -1000,
      "sysCol" => 1,
      "isVisible" => 2,
      "editorXtype" => "displayfield",
      "colSpan" => 1
    ];

    // bill_status
    $result[] = [
      "caption" => "状态",
      "fieldName" => "bill_status",
      "fieldType" => "int",
      "fieldLength" => 11,
      "fieldDecimal" => 0,
      "valueFrom" => 5,
      "valueFromTableName" => "",
      "valueFromColName" => "",
      "valueFromColNameDisplay" => "",
      "mustInput" => 1,
      "showOrder" => -1000,
      "sysCol" => 1,
      "isVisible" => 1,
      "editorXtype" => "displayfield",
      "colSpan" => 1
    ];

    // ref
    $result[] = [
      "caption" => "单号",
      "fieldName" => "ref",
      "fieldType" => "varchar",
      "fieldLength" => 255,
      "fieldDecimal" => 0,
      "valueFrom" => 5,
      "valueFromTableName" => "",
      "valueFromColName" => "",
      "valueFromColNameDisplay" => "",
      "mustInput" => 1,
      "showOrder" => 1,
      "sysCol" => 1,
      "isVisible" => 1,
      "editorXtype" => "displayfield",
      "colSpan" => 1
    ];

    // biz_dt
    $result[] = [
      "caption" => "业务日期",
      "fieldName" => "biz_dt",
      "fieldType" => "datetime",
      "fieldLength" => 0,
      "fieldDecimal" => 0,
      "valueFrom" => 1,
      "valueFromTableName" => "",
      "valueFromColName" => "",
      "valueFromColNameDisplay" => "",
      "mustInput" => 1,
      "showOrder" => 2,
      "sysCol" => 1,
      "isVisible" => 1,
      "editorXtype" => "datefield",
      "colSpan" => 1
    ];

    // biz_user_id
    $result[] = [
      "caption" => "业务员",
      "fieldName" => "biz_user_id",
      "fieldType" => "varchar",
      "fieldLength" => 255,
      "fieldDecimal" => 0,
      "valueFrom" => 3,
      "valueFromTableName" => "t_user",
      "valueFromColName" => "id",
      "valueFromColNameDisplay" => "name",
      "mustInput" => 1,
      "showOrder" => 3,
      "sysCol" => 1,
      "isVisible" => 1,
      "editorXtype" => "psi_userfield",
      "colSpan" => 1
    ];

    // date_created
    $result[] = [
      "caption" => "制单时间",
      "fieldName" => "date_created",
      "fieldType" => "datetime",
      "fieldLength" => 0,
      "fieldDecimal" => 0,
      "valueFrom" => 5,
      "valueFromTableName" => "",
      "valueFromColName" => "",
      "valueFromColNameDisplay" => "",
      "mustInput" => 1,
      "showOrder" => -1000,
      "sysCol" => 1,
      "isVisible" => 2,
      "editorXtype" => "displayfield",
      "colSpan" => 1
    ];

    // input_user_id
    $result[] = [
      "caption" => "制单人",
      "fieldName" => "input_user_id",
      "fieldType" => "varchar",
      "fieldLength" => 255,
      "fieldDecimal" => 0,
      "valueFrom" => 5,
      "valueFromTableName" => "t_user",
      "valueFromColName" => "id",
      "valueFromColNameDisplay" => "name",
      "mustInput" => 1,
      "showOrder" => -1000,
      "sysCol" => 1,
      "isVisible" => 1,
      "editorXtype" => "displayfield",
      "colSpan" => 1
    ];

    // bill_memo
    $result[] = [
      "caption" => "备注",
      "fieldName" => "bill_memo",
      "fieldType" => "varchar",
      "fieldLength" => 1000,
      "fieldDecimal" => 0,
      "valueFrom" => 1,
      "valueFromTableName" => "",
      "valueFromColName" => "",
      "valueFromColNameDisplay" => "",
      "mustInput" => 2,
      "showOrder" => 4,
      "sysCol" => 1,
      "isVisible" => 1,
      "editorXtype" => "textfield",
      "colSpan" => 1
    ];

    // confirm_user_id
    $result[] = [
      "caption" => "审核人",
      "fieldName" => "confirm_user_id",
      "fieldType" => "varchar",
      "fieldLength" => 255,
      "fieldDecimal" => 0,
      "valueFrom" => 5,
      "valueFromTableName" => "t_user",
      "valueFromColName" => "id",
      "valueFromColNameDisplay" => "name",
      "mustInput" => 1,
      "showOrder" => -1000,
      "sysCol" => 1,
      "isVisible" => 1,
      "editorXtype" => "displayfield",
      "colSpan" => 1
    ];

    // confirm_dt
    $result[] = [
      "caption" => "审核时间",
      "fieldName" => "confirm_dt",
      "fieldType" => "datetime",
      "fieldLength" => 0,
      "fieldDecimal" => 0,
      "valueFrom" => 5,
      "valueFromTableName" => "",
      "valueFromColName" => "",
      "valueFromColNameDisplay" => "",
      "mustInput" => 1,
      "showOrder" => -1000,
      "sysCol" => 1,
      "isVisible" => 1,
      "editorXtype" => "displayfield",
      "colSpan" => 1
    ];

    // data_org
    $result[] = [
      "caption" => "数据域",
      "fieldName" => "data_org",
      "fieldType" => "varchar",
      "fieldLength" => 255,
      "fieldDecimal" => 0,
      "valueFrom" => 5,
      "valueFromTableName" => "",
      "valueFromColName" => "",
      "valueFromColNameDisplay" => "",
      "mustInput" => 1,
      "showOrder" => -1000,
      "sysCol" => 1,
      "isVisible" => 1,
      "editorXtype" => "displayfield",
      "colSpan" => 1
    ];

    // company_id
    $result[] = [
      "caption" => "公司id",
      "fieldName" => "company_id",
      "fieldType" => "varchar",
      "fieldLength" => 255,
      "fieldDecimal" => 0,
      "valueFrom" => 5,
      "valueFromTableName" => "",
      "valueFromColName" => "",
      "valueFromColNameDisplay" => "",
      "mustInput" => 1,
      "showOrder" => -1000,
      "sysCol" => 1,
      "isVisible" => 1,
      "editorXtype" => "displayfield",
      "colSpan" => 1
    ];

    return $result;
  }

  /**
   * 新增表单
   */
  public function addForm(&$params)
  {
    $db = $this->db;

    $categoryId = $params["categoryId"];
    $category = $this->getCategoryById($categoryId);
    if (!$category) {
      return $this->badParam("categoryId");
    }

    $code = $params["code"];
    $name = $params["name"];
    $tableName = strtolower($params["tableName"]);
    $memo = $params["memo"];

    // 1. 检查数据库表名是否正确
    $rc = $this->checkTableName($tableName);
    if ($rc) {
      return $rc;
    }

    // 2. 检查数据库表是否已经存在了
    $sql = "select count(*) as cnt from t_form
            where table_name = '%s' ";
    $data = $db->query($sql, $tableName);
    $cnt = $data[0]["cnt"];
    if ($cnt > 0) {
      return $this->bad("表名为[{$tableName}]的表单已经存在");
    }
    // 检查数据库中是否已经存在该表了
    $dbUtilDAO = new DBUtilDAO($db);
    if ($dbUtilDAO->tableExists($tableName)) {
      return $this->bad("表[{$tableName}]已经在数据库中存在了");
    }
    // 检查明细表是否已经存在在数据库中
    $detailTableName = $tableName . "_detail";
    if ($dbUtilDAO->tableExists($detailTableName)) {
      return $this->bad("表[{$detailTableName}]已经在数据库中存在了");
    }

    // 3. 保存元数据
    // 检查编码是否重复
    if ($code) {
      $sql = "select count(*) as cnt from t_form where code = '%s' ";
      $data = $db->query($sql, $code);
      $cnt = $data[0]["cnt"];
      if ($cnt > 0) {
        return $this->bad("编码为[$code]的表单已经存在");
      }
    }
    // 检查表单名称是否重复
    $sql = "select count(*) as cnt from t_form where name = '%s' ";
    $data = $db->query($sql, $name);
    $cnt = $data[0]["cnt"];
    if ($cnt > 0) {
      return $this->bad("表单名称为[$name]的表单已经存在");
    }

    // 3.1 主表元数据
    $id = $this->newId();
    $sql = "insert into t_form (id, code, name, category_id, sys_form, md_version, memo)
            values ('%s', '%s', '%s', '%s', 0, 1, '%s') ";
    $rc = $db->execute($sql, $id, $code, $name, $categoryId, $memo);
    if ($rc === false) {
      return $this->sqlError(__METHOD__, __LINE__);
    }

    // 3.2 主表各个标准字段的元数据
    $mdCols = $this->getFormSysCols();;
    foreach ($mdCols as $v) {
      $sql = "insert into t_form_cols(id, form_id, caption, db_field_name, db_field_type,
                db_field_length, db_field_decimal, show_order, col_span, value_from,
                value_from_table_name, value_from_col_name, value_from_col_name_display, must_input,
                sys_col, is_visible, note, editor_xtype)
              values ('%s', '%s', '%s', '%s', '%s',
                %d, %d, %d, %d, %d,
                '%s', '%s', '%s', %d,
                %d, %d, '%s', '%s'
                )";
      $rc = $db->execute(
        $sql,
        $this->newId(),
        $id,
        $v["caption"],
        $v["fieldName"],
        $v["fieldType"],
        $v["fieldLength"],
        $v["fieldDecimal"],
        $v["showOrder"],
        $v["colSpan"],
        $v["valueFrom"],
        $v["valueFromTableName"],
        $v["valueFromColName"],
        $v["valueFromColNameDisplay"],
        $v["mustInput"],
        $v["sysCol"],
        $v["isVisible"],
        "",
        $v["editorXtype"]
      );
      if ($rc === false) {
        return $this->sqlError(__METHOD__, __LINE__);
      }
    }

    // 3.3 明细表元数据
    // 3.4 明细表各个标准字段的元数据

    // 4. 创建数据库表

    $params["id"] = $id;
    return $this->todo();
  }
}

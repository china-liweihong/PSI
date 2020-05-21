<?php

namespace Home\DAO;

/**
 * 表单视图 DAO
 *
 * @author 李静波
 */
class FormViewDAO extends PSIBaseExDAO
{

  /**
   * 视图分类列表
   */
  public function categoryList($params)
  {
    $db = $this->db;

    $sql = "select id, code, name, is_system
            from t_fv_category
            order by code";
    $data = $db->query($sql);

    $result = [];
    foreach ($data as $v) {
      $result[] = [
        "id" => $v["id"],
        "code" => $v["code"],
        "name" => $v["name"],
        "isSystem" => $v["is_system"],
        "isSystemCaption" => $v["is_system"] == 1 ? "▲" : "",
      ];
    }

    return $result;
  }

  /**
   * 新增视图分类
   *
   * @param array $params
   * @return null|array
   */
  public function addViewCategory(&$params)
  {
    $db = $this->db;

    $code = $params["code"] ?? "";
    $code = strtoupper($code);
    $name = $params["name"];

    // 检查编码是否存在
    if ($code) {
      $sql = "select count(*) as cnt from t_fv_category where code = '%s' ";
      $data = $db->query($sql, $code);
      $cnt = $data[0]["cnt"];
      if ($cnt) {
        return $this->bad("视图分类编码[{$code}]已经存在");
      }
    } else {
      $code = "";
    }

    // 检查分类名称是否存在
    $sql = "select count(*) as cnt from t_fv_category where name = '%s' ";
    $data = $db->query($sql, $name);
    $cnt = $data[0]["cnt"];
    if ($cnt) {
      return $this->bad("视图分类[{$name}]已经存在");
    }

    $id = $this->newId();
    $sql = "insert into t_fv_category (id, code, name, parent_id)
            values ('%s', '%s', '%s', null)";

    $rc = $db->execute($sql, $id, $code, $name);
    if ($rc === false) {
      return $this->sqlError(__METHOD__, __LINE__);
    }

    // 操作成功
    $params["id"] = $id;
    return null;
  }

  public function getViewCategoryById($id)
  {
    $db = $this->db;

    $sql = "select code, name, is_system from t_fv_category where id = '%s' ";
    $data = $db->query($sql, $id);
    if ($data) {
      return [
        "code" => $data[0]["code"],
        "name" => $data[0]["name"],
        "isSystem" => $data[0]["is_system"],
      ];
    } else {
      return null;
    }
  }

  /**
   * 编辑视图分类
   *
   * @param array $params
   */
  public function updateViewCategory($params)
  {
    $db = $this->db;

    $id = $params["id"];
    $code = $params["code"] ?? "";
    $code = strtoupper($code);
    $name = $params["name"];

    $category = $this->getViewCategoryById($id);
    if (!$category) {
      return $this->bad("要编辑的视图分类不存在");
    }
    $isSystem = $category["isSystem"];
    if ($isSystem == 1) {
      $n = $category["name"];
      return $this->bad("分类[{$n}]是系统固有分类，不能编辑");
    }

    // 检查编码是否存在
    if ($code) {
      $sql = "select count(*) as cnt from t_fv_category 
              where code = '%s' and id <> '%s' ";
      $data = $db->query($sql, $code, $id);
      $cnt = $data[0]["cnt"];
      if ($cnt) {
        return $this->bad("视图分类编码[{$code}]已经存在");
      }
    } else {
      $code = "";
    }

    // 检查分类名称是否存在
    $sql = "select count(*) as cnt from t_fv_category 
            where name = '%s' and id <> '%s' ";
    $data = $db->query($sql, $name, $id);
    $cnt = $data[0]["cnt"];
    if ($cnt) {
      return $this->bad("视图分类[{$name}]已经存在");
    }

    $sql = "update t_fv_category
            set code = '%s', name = '%s'
            where id = '%s' ";

    $rc = $db->execute($sql, $code, $name, $id);
    if ($rc === false) {
      return $this->sqlError(__METHOD__, __LINE__);
    }

    // 操作成功
    return null;
  }

  /**
   * 删除视图分类
   */
  public function deleteViewCategory(&$params)
  {
    $db = $this->db;

    $id = $params["id"];

    $category = $this->getViewCategoryById($id);
    if (!$category) {
      return $this->bad("要删除的视图分类不存在");
    }
    $name = $category["name"];
    $isSystem = $category["isSystem"];
    if ($isSystem == 1) {
      return $this->bad("分类[{$name}]是系统固有分类，不能删除");
    }

    // 查询该分类是否被使用了
    $sql = "select count(*) as cnt from t_fv
            where category_id = '%s' ";
    $data = $db->query($sql, $id);
    $cnt = $data[0]["cnt"];
    if ($cnt > 0) {
      return $this->bad("视图分类[$name]下还有视图，不能删除");
    }

    $sql = "delete from t_fv_category where id = '%s' ";
    $rc = $db->execute($sql, $id);
    if ($rc === false) {
      return $this->sqlError(__METHOD__, __LINE__);
    }

    // 操作成功
    $params["name"] = $name;
    return null;
  }

  /**
   * 视图分类自定义字段 - 查询数据
   */
  public function queryDataForFvCategory($params)
  {
    $db = $this->db;

    $queryKey = $params["queryKey"] ?? "";

    $sql = "select id, code, name
            from t_fv_category
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

  private function getXtypeName($code)
  {
    $db = $this->db;

    $sql = "select name, memo from t_sysdict_fv_xtype where code_int = %d";
    $data = $db->query($sql, $code);
    if ($data) {
      $v = $data[0];

      return $v["name"] . " - " . $v["memo"];
    } else {
      return "";
    }
  }

  private function regionCodeToName($code)
  {
    switch ($code) {
      case "center":
        return "主体";
      case "west":
        return "左边";
      case "south":
        return "下边";
      default:
        return "";
    }
  }

  private function layoutCodeToName($code)
  {
    switch ($code) {
      case 1:
        return "填满整个区域";
      case 2:
        return "左右布局";
      case 3:
        return "上下布局";
      default:
        return "";
    }
  }

  private function dataSourceCodeToName($code)
  {
    switch ($code) {
      case 0:
        return "[无]";
      case 1:
        return "码表";
      case 2:
        return "自定义表单";
      default:
        return "";
    }
  }

  private function fvListInternal($parentId)
  {
    $db = $this->db;

    $sql = "select id, code, name, fid, xtype, region, width_or_height, layout_type,
              data_source_type, data_source_table_name, memo
            from t_fv
            where parent_id = '%s'
            order by code, name";
    $data = $db->query($sql, $parentId);
    $result = [];
    foreach ($data as $v) {
      $id = $v["id"];

      // 递归调用自己
      $children = $this->fvListInternal($id);

      $result[] = [
        "id" => $id,
        "code" => $v["code"],
        "text" => $v["name"],
        "fid" => $v["fid"],
        "children" => $children,
        "leaf" => count($children) == 0,
        "iconCls" => "PSI-FvCategory",
        "xtype" => $this->getXtypeName($v["xtype"]),
        "region" => $this->regionCodeToName($v["region"]),
        "widthOrHeight" => $v["width_or_height"],
        "layoutType" => $this->layoutCodeToName($v["layout_type"]),
        "dataSourceType" => $this->dataSourceCodeToName($v["data_source_type"]),
        "dataSourceTableName" => $v["data_source_table_name"],
        "memo" => $v["memo"],
      ];
    }
    return $result;
  }

  /**
   * 视图的列表
   */
  public function fvList($params)
  {
    $db = $this->db;

    $categoryId = $params["categoryId"];

    $sql = "select id, code, name, fid, md_version, is_fixed,
              module_name, xtype, region, width_or_height, layout_type,
              data_source_type, memo
            from t_fv
            where category_id = '%s' and parent_id is null
            order by code, name";
    $data = $db->query($sql, $categoryId);
    $result = [];
    foreach ($data as $v) {
      $id = $v["id"];
      $children = $this->fvListInternal($id);

      $result[] = [
        "id" => $id,
        "code" => $v["code"],
        "text" => $v["name"],
        "fid" => $v["fid"],
        "mdVersion" => $v["md_version"],
        "children" => $children,
        "leaf" => count($children) == 0,
        "iconCls" => "PSI-FvCategory",
        "isFixed" => $v["is_fixed"] == 1 ? "▲" : "",
        "moduleName" => $v["module_name"],
        "xtype" => $this->getXtypeName($v["xtype"]),
        "widthOrHeight" => $v["width_or_height"],
        "layoutType" => $this->layoutCodeToName($v["layout_type"]),
        "dataSourceType" => $this->dataSourceCodeToName($v["data_source_type"]),
        "memo" => $v["memo"],
      ];
    }

    return $result;
  }

  /**
   * 新增视图
   */
  public function addFv(&$params)
  {
    $db = $this->db;

    $categoryId = $params["categoryId"];
    $code = $params["code"];
    $name = $params["name"];
    $moduleName = $params["moduleName"];
    $xtype = $params["xtype"];
    $region = $params["region"];
    $widthOrHeight = $params["widthOrHeight"];
    $layout = intval($params["layout"]);
    $dataSourceType = intval($params["dataSourceType"]);
    $dataSourceTableName = $params["dataSourceTableName"];
    $memo = $params["memo"];
    $py = $params["py"];

    $category = $this->getViewCategoryById($categoryId);
    if (!$category) {
      return $this->bad("视图分类不存在");
    }

    // 检查code是否重复
    if ($code) {
      $sql = "select count(*) as cnt from t_fv where code = '%s' ";
      $data = $db->query($sql, $code);
      $cnt = $data[0]["cnt"];
      if ($cnt > 0) {
        return $this->bad("编码为[{$code}]的视图已经存在");
      }
    }

    if ($layout < 1 || $layout > 3) {
      return $this->bad("不支持当前选择的布局");
    }

    if ($dataSourceType < 0 || $dataSourceType > 2) {
      return $this->bad("不支持当前选择的数据源");
    }

    if ($dataSourceType == 0) {
      // 数据源是混合的类型的时候，不指定数据源表名
      $dataSourceTableName = "";
    } else {
      if (!$dataSourceTableName) {
        return $this->bad("没有输入数据源表名");
      }
    }

    // 检查数据源表是否存在
    if ($dataSourceType == 1) {
      // 数据源是码表
      $sql = "select count(*) as cnt from t_code_table_md where table_name = '%s' ";
      $data = $db->query($sql, $dataSourceTableName);
      $cnt = $data[0]["cnt"];
      if ($cnt != 1) {
        return $this->bad("码表[{$dataSourceTableName}]的元数据不存在");
      }
    } else if ($dataSourceType == 2) {
      // 数据源是表单
      $sql = "select count(*) as cnt from t_form where table_name = '%s' ";
      $data = $db->query($sql, $dataSourceTableName);
      $cnt = $data[0]["cnt"];
      if ($cnt != 1) {
        return $this->bad("自定义表单[{$dataSourceTableName}]的元数据不存在");
      }
    }

    $id = $this->newId();
    $fid = "fv" . date("YmdHis");

    $sql = "insert into t_fv (id, category_id, code, name, memo, py, fid,
              module_name, xtype, region, width_or_height, layout_type,
              data_source_type, data_source_table_name)
            values ('%s', '%s', '%s', '%s', '%s', '%s', '%s',
              '%s', '%s', '%s', '%s', %d,
              %d, '%s')";
    $rc = $db->execute(
      $sql,
      $id,
      $categoryId,
      $code,
      $name,
      $memo,
      $py,
      $fid,
      $moduleName,
      $xtype,
      $region,
      $widthOrHeight,
      $layout,
      $layout == 1 ? $dataSourceType : 0,
      $layout == 1 ? $dataSourceTableName : ""
    );
    if ($rc === false) {
      return $this->sqlError(__METHOD__, __LINE__);
    }

    if ($layout == 2) {
      // 左右布局
      $parentId = $id;

      // 左
      $leftId = $this->newId();
      $leftFid = $fid . "-1";
      $sql = "insert into t_fv (id, category_id, name, fid,
                module_name, xtype, region, width_or_height, layout_type, parent_id,
                data_source_type, data_source_table_name)
              values ('%s', '%s', '%s', '%s',
                '%s', '%s', '%s', '%s', %d, '%s',
                %d, '%s')";
      $rc = $db->execute(
        $sql,
        $leftId,
        $categoryId,
        $name,
        $leftFid,
        $moduleName,
        $xtype,
        "west",
        "30%",
        1,
        $parentId,
        $dataSourceType,
        $dataSourceTableName
      );
      if ($rc === false) {
        return $this->sqlError(__METHOD__, __LINE__);
      }

      // 右
      $rightId = $this->newId();
      $rightFid = $fid . "-2";
      $sql = "insert into t_fv (id, category_id, name, fid,
                module_name, xtype, region, width_or_height, layout_type, parent_id,
                data_source_type, data_source_table_name)
              values ('%s', '%s', '%s', '%s',
                '%s', '%s', '%s', '%s', %d, '%s',
                %d, '%s')";
      $rc = $db->execute(
        $sql,
        $rightId,
        $categoryId,
        $name,
        $rightFid,
        $moduleName,
        $xtype,
        "center",
        "70%",
        1,
        $parentId,
        $dataSourceType,
        $dataSourceTableName
      );
      if ($rc === false) {
        return $this->sqlError(__METHOD__, __LINE__);
      }
    }

    if ($layout == 3) {
      // 上下布局
      $parentId = $id;

      // 上
      $upId = $this->newId();
      $upFid = $fid . "-1";
      $sql = "insert into t_fv (id, category_id, name, fid,
                module_name, xtype, region, width_or_height, layout_type, parent_id,
                data_source_type, data_source_table_name)
              values ('%s', '%s', '%s', '%s',
                '%s', '%s', '%s', '%s', %d, '%s',
                %d, '%s')";
      $rc = $db->execute(
        $sql,
        $upId,
        $categoryId,
        $name,
        $upFid,
        $moduleName,
        $xtype,
        "center",
        "50%",
        1,
        $parentId,
        $dataSourceType,
        $dataSourceTableName
      );
      if ($rc === false) {
        return $this->sqlError(__METHOD__, __LINE__);
      }

      // 下 
      $downId = $this->newId();
      $downFid = $fid . "-2";
      $sql = "insert into t_fv (id, category_id, name, fid,
                module_name, xtype, region, width_or_height, layout_type, parent_id,
                data_source_type, data_source_table_name)
              values ('%s', '%s', '%s', '%s',
                '%s', '%s', '%s', '%s', %d, '%s',
                %d, '%s')";
      $rc = $db->execute(
        $sql,
        $downId,
        $categoryId,
        $name,
        $downFid,
        $moduleName,
        $xtype,
        "south",
        "50%",
        1,
        $parentId,
        $dataSourceType,
        $dataSourceTableName
      );
      if ($rc === false) {
        return $this->sqlError(__METHOD__, __LINE__);
      }
    }

    // fid
    $sql = "insert into t_fid_plus (fid, name, py, memo)
            values ('%s', '%s', '%s', '%s')";
    $rc = $db->execute($sql, $fid, $moduleName, $py, $memo);
    if ($rc === false) {
      return $this->sqlError(__METHOD__, __LINE__);
    }

    // 权限
    $sql = "insert into t_permission_plus (id, fid, name, note, category, py, show_order)
            values ('%s', '%s', '%s', '%s', '%s','%s', %d)";
    $rc = $db->execute($sql, $fid, $fid, $moduleName, "模块权限：通过菜单进入{$moduleName}模块的权限", $moduleName, "", 100);
    if ($rc === false) {
      return $this->sqlError(__METHOD__, __LINE__);
    }

    // 操作成功
    $params["id"] = $id;
    return null;
  }

  /**
   * 编辑视图
   */
  public function updateFv(&$params)
  {
    $db = $this->db;

    $id = $params["id"];
    $categoryId = $params["categoryId"];
    $code = $params["code"];
    $name = $params["name"];
    $moduleName = trim($params["moduleName"]);
    $xtype = $params["xtype"];
    $widthOrHeight = $params["widthOrHeight"];
    $dataSourceType = intval($params["dataSourceType"]);
    $dataSourceTableName = $params["dataSourceTableName"];
    $memo = $params["memo"];
    $py = $params["py"];

    $sql = "select name, parent_id, fid
            from t_fv where id = '%s' ";
    $data = $db->query($sql, $id);
    if (!$data) {
      return $this->bad("要编辑的视图不存在");
    }
    $v = $data[0];
    $parentId = $v["parent_id"];
    $oldName = $v["name"];
    $fid = $v["fid"];

    if ($dataSourceType < 0 || $dataSourceType > 2) {
      return $this->bad("不支持当前选择的数据源");
    }

    // 检查数据源表是否存在
    if ($dataSourceType == 1) {
      // 数据源是码表
      $sql = "select count(*) as cnt from t_code_table_md where table_name = '%s' ";
      $data = $db->query($sql, $dataSourceTableName);
      $cnt = $data[0]["cnt"];
      if ($cnt != 1) {
        return $this->bad("码表[{$dataSourceTableName}]的元数据不存在");
      }
    } else if ($dataSourceType == 2) {
      // 数据源是表单
      $sql = "select count(*) as cnt from t_form where table_name = '%s' ";
      $data = $db->query($sql, $dataSourceTableName);
      $cnt = $data[0]["cnt"];
      if ($cnt != 1) {
        return $this->bad("自定义表单[{$dataSourceTableName}]的元数据不存在");
      }
    }

    if (!$parentId) {
      // 顶级视图

      // 检查视图分类是否存在
      $category = $this->getViewCategoryById($categoryId);
      if (!$category) {
        return $this->bad("视图分类不存在");
      }

      // 检查code是否重复
      if ($code) {
        $sql = "select count(*) as cnt from t_fv where code = '%s' and id <> '%s' ";
        $data = $db->query($sql, $code, $id);
        $cnt = $data[0]["cnt"];
        if ($cnt > 0) {
          return $this->bad("编码为[{$code}]的视图已经存在");
        }
      }

      if (!$moduleName) {
        return $this->bad("没有输入模块名称");
      }

      $sql = "update t_fv
              set category_id = '%s', name = '%s', module_name = '%s', memo = '%s',
                py = '%s'
              where id = '%s' ";
      $rc = $db->execute($sql, $categoryId, $name, $moduleName, $memo, $py, $id);
      if ($rc === false) {
        return $this->sqlError(__METHOD__, __LINE__);
      }

      // 更新子视图的分类id
      // TODO 目前只处理了一级子视图
      $sql = "update t_fv
              set category_id = '%s'
              where parent_id = '%s' ";
      $rc = $db->execute($sql, $categoryId, $id);
      if ($rc === false) {
        return $this->sqlError(__METHOD__, __LINE__);
      }

      // fid
      $sql = "update t_fid_plus
              set name = '%s', py = '%s'
              where fid = '%s' ";
      $rc = $db->execute($sql, $moduleName, $py, $fid);
      if ($rc === false) {
        return $this->sqlError(__METHOD__, __LINE__);
      }

      // 权限
      $sql = "update t_permission_plus
              set name = '%s', note = '%s', category = '%s'
              where fid = '%s' ";
      $rc = $db->execute($sql, $moduleName, "通过菜单进入{$moduleName}模块的权限", $moduleName, $fid);
      if ($rc === false) {
        return $this->sqlError(__METHOD__, __LINE__);
      }

      // TODO 还没有处理按钮权限
    } else {
      // 子视图
      $sql = "update t_fv
              set name = '%s', xtype = '%s', data_source_type = %d,
                data_source_table_name = '%s',
                width_or_height = '%s', memo = '%s'
              where id = '%s' ";
      $rc = $db->execute(
        $sql,
        $name,
        $xtype,
        $dataSourceType,
        $dataSourceTableName,
        $widthOrHeight,
        $memo,
        $id
      );
      if ($rc === false) {
        return $this->sqlError(__METHOD__, __LINE__);
      }

      $params["name"] = $oldName;
    }

    // 操作成功
    return null;
  }

  /**
   * 某个视图的详情
   */
  public function fvInfo($params)
  {
    $db = $this->db;

    // 视图id
    $id = $params["id"];

    $sql = "select code_int, name, memo from t_sysdict_fv_xtype order by show_order";
    $data = $db->query($sql);
    $allXtype = [];
    foreach ($data as $v) {
      $allXtype[] = [
        "id" => $v["code_int"],
        "text" => $v["name"] . " - " . $v["memo"]
      ];
    }
    $result = ["allXtype" => $allXtype];

    if ($id) {
      // 编辑
      $sql = "select c.id as category_id, c.name as category_name,
                f.code, f.name, f.memo, f.module_name, f.xtype,
                f.region, f.width_or_height, f.layout_type,
                f.data_source_type, f.data_source_table_name,
                f.parent_id
              from t_fv f, t_fv_category c 
              where f.id = '%s' and f.category_id = c.id ";
      $data = $db->query($sql, $id);
      if ($data) {
        $v = $data[0];
        $result["categoryId"] = $v["category_id"];
        $result["categoryName"] = $v["category_name"];
        $result["code"] = $v["code"];
        $result["name"] = $v["name"];
        $result["memo"] = $v["memo"];
        $result["moduleName"] = $v["module_name"];
        $result["xtype"] = $v["xtype"];
        $result["region"] = $v["region"];
        $result["widthOrHeight"] = $v["width_or_height"];
        $result["layout"] = $v["layout_type"];
        $result["dataSourceType"] = $v["data_source_type"];
        $result["dataSourceTableName"] = $v["data_source_table_name"];
        $result["parentId"] = $v["parent_id"];
      }
    }

    return $result;
  }

  /**
   * 查询某个fid的元数据
   */
  public function getMetadataForRuntimeInit($params)
  {
    $db = $this->db;

    $fid = $params["fid"];

    $sql = "select module_name from t_fv where fid = '%s' ";
    $data = $db->query($sql, $fid);
    if ($data) {
      $v = $data[0];

      return ["title" => $v["module_name"]];
    } else {
      return null;
    }
  }

  /**
   * 查询某个fid的完整元数据，用于创建UI
   */
  public function fetchMetaDataForRuntime($params)
  {
    $db = $this->db;

    $fid = $params["fid"];

    $sql = "select id, module_name, layout_type 
            from t_fv where fid = '%s' ";
    $data = $db->query($sql, $fid);
    if (!$data) {
      return null;
    }
    $v = $data[0];

    $id = $v["id"];
    $layoutType = $v["layout_type"];

    $result = [
      "title" => $v["module_name"],
      "layoutType" => $layoutType
    ];

    if ($layoutType > 1) {
      // 子视图
      // TODO: 需要改成递归算法
      $sql = "select region, width_or_height, xtype
              from t_fv where parent_id = '%s' ";
      $data = $db->query($sql, $id);
      $subView = [];
      foreach ($data as $v) {
        $sql = "select name from t_sysdict_fv_xtype where code_int = %d";
        $d = $db->query($sql, $v["xtype"]);
        $xtype = $d[0]["name"] ?? "panel";

        $subView[] = [
          "region" => $v["region"],
          "widthOrHeight" => $v["width_or_height"],
          "xtype" => $xtype
        ];
      }

      $result["subView"] = $subView;
    }


    return $result;
  }

  /**
   * 删除视图
   */
  public function deleteFv(&$params)
  {
    $db = $this->db;

    $id = $params["id"];

    $sql = "select name, parent_id, fid from t_fv where id = '%s' ";
    $data = $db->query($sql, $id);
    if (!$data) {
      return $this->bad("要删除的视图不存在");
    }
    $v = $data[0];
    $name = $v["name"];
    $fid = $v["fid"];
    $parentId = $v["parent_id"];
    if ($parentId) {
      return $this->bad("请选择顶级视图来删除整个视图");
    }

    // 检查视图是否已经挂接到主菜单了
    $sql = "select count(*) as cnt from t_menu_item_plus where fid = '%s' ";
    $data = $db->query($sql, $fid);
    $cnt = $data[0]["cnt"];
    if ($cnt > 0) {
      return $this->bad("视图[$name]已经挂接到主菜单了，不能删除");
    }
    $sql = "select count(*) as cnt from t_menu_item where fid = '%s' ";
    $data = $db->query($sql, $fid);
    $cnt = $data[0]["cnt"];
    if ($cnt > 0) {
      return $this->bad("视图[$name]已经挂接到主菜单了，不能删除");
    }

    // 删除视图
    $sql = "delete from t_fv where id = '%s' ";
    $rc = $db->execute($sql, $id);
    if ($rc === false) {
      return $this->sqlError(__METHOD__, __LINE__);
    }

    // 删除子视图
    // TODO: 目前只处理了一级子视图
    $sql = "delete from t_fv where parent_id = '%s' ";
    $rc = $db->execute($sql, $id);
    if ($rc === false) {
      return $this->sqlError(__METHOD__, __LINE__);
    }

    // TODO
    // 删除列
    // 删除查询条件
    // 删除按钮

    // 删除fid
    $sql = "delete from t_fid_plus where fid = '%s' ";
    $rc = $db->execute($sql, $fid);
    if ($rc === false) {
      return $this->sqlError(__METHOD__, __LINE__);
    }

    // 删除权限
    $sql = "delete from t_permission_plus where fid = '%s' ";
    $rc = $db->execute($sql, $fid);
    if ($rc === false) {
      return $this->sqlError(__METHOD__, __LINE__);
    }
    $sql = "delete from t_permission_plus where parent_fid = '%s' ";
    $rc = $db->execute($sql, $fid);
    if ($rc === false) {
      return $this->sqlError(__METHOD__, __LINE__);
    }

    // 操作成功
    $params["name"] = $name;
    return null;
  }
}

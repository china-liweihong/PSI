<?php

namespace Home\Controller;

use Home\Common\FIdConst;
use Home\Service\UserService;
use Home\Service\FormService;

/**
 * 自定义表单Controller
 *
 * @author 李静波
 *        
 */
class FormController extends PSIBaseController
{

  /**
   * 自定义表单 - 主页面
   */
  public function index()
  {
    $us = new UserService();

    if ($us->hasPermission(FIdConst::FORM_SYSTEM)) {
      $this->initVar();

      $this->assign("title", "自定义表单");

      $this->display();
    } else {
      $this->gotoLoginPage("/Home/Form/index");
    }
  }

  /**
   * 表单分类列表
   */
  public function categoryList()
  {
    if (IS_POST) {
      $us = new UserService();
      if (!$us->hasPermission(FIdConst::FORM_SYSTEM)) {
        die("没有权限");
      }

      $service = new FormService();
      $this->ajaxReturn($service->categoryList());
    }
  }

  /**
   * 新增或编辑表单分类
   */
  public function editFormCategory()
  {
    if (IS_POST) {
      $us = new UserService();
      if (!$us->hasPermission(FIdConst::FORM_SYSTEM)) {
        die("没有权限");
      }

      $params = [
        "id" => I("post.id"),
        "code" => I("post.code"),
        "name" => I("post.name")
      ];

      $service = new FormService();
      $this->ajaxReturn($service->editFormCategory($params));
    }
  }

  /**
   * 删除表单分类
   */
  public function deleteFormCategory()
  {
    if (IS_POST) {
      $us = new UserService();
      if (!$us->hasPermission(FIdConst::FORM_SYSTEM)) {
        die("没有权限");
      }

      $params = [
        "id" => I("post.id"),
      ];

      $service = new FormService();
      $this->ajaxReturn($service->deleteFormCategory($params));
    }
  }

  /**
   * 表单分类自定义字段 - 查询数据
   */
  public function queryDataForCategory()
  {
    if (IS_POST) {
      $us = new UserService();
      if (!$us->hasPermission(FIdConst::FORM_SYSTEM)) {
        die("没有权限");
      }

      $params = [
        "queryKey" => I("post.queryKey")
      ];

      $service = new FormService();
      $this->ajaxReturn($service->queryDataForCategory($params));
    }
  }

  /**
   * 新增或编辑表单
   */
  public function editForm()
  {
    if (IS_POST) {
      $us = new UserService();
      if (!$us->hasPermission(FIdConst::FORM_SYSTEM)) {
        die("没有权限");
      }

      $params = [
        "id" => I("post.id"),
        "categoryId" => I("post.categoryId"),
        "code" => I("post.code"),
        "name" => I("post.name"),
        "tableName" => I("post.tableName"),
        "memo" => I("post.memo")
      ];

      $service = new FormService();
      $this->ajaxReturn($service->editForm($params));
    }
  }

  /**
   * 某个分类下的表单列表
   */
  public function formList()
  {
    if (IS_POST) {
      $us = new UserService();
      if (!$us->hasPermission(FIdConst::FORM_SYSTEM)) {
        die("没有权限");
      }

      $params = [
        "categoryId" => I("post.categoryId")
      ];

      $service = new FormService();
      $this->ajaxReturn($service->formList($params));
    }
  }

  /**
   * 表单主表列
   */
  public function formColList()
  {
    if (IS_POST) {
      $us = new UserService();
      if (!$us->hasPermission(FIdConst::FORM_SYSTEM)) {
        die("没有权限");
      }

      $params = [
        "id" => I("post.id")
      ];

      $service = new FormService();
      $this->ajaxReturn($service->formColList($params));
    }
  }

  /**
   * 表单明细表列表
   */
  public function formDetailList()
  {
    if (IS_POST) {
      $us = new UserService();
      if (!$us->hasPermission(FIdConst::FORM_SYSTEM)) {
        die("没有权限");
      }

      $params = [
        "id" => I("post.id")
      ];

      $service = new FormService();
      $this->ajaxReturn($service->formDetailList($params));
    }
  }

  /**
   * 表单明细表的列的列表
   */
  public function formDetailColList()
  {
    if (IS_POST) {
      $us = new UserService();
      if (!$us->hasPermission(FIdConst::FORM_SYSTEM)) {
        die("没有权限");
      }

      $params = [
        "id" => I("post.id")
      ];

      $service = new FormService();
      $this->ajaxReturn($service->formDetailColList($params));
    }
  }

  /**
   * 删除表单元数据
   */
  public function deleteForm()
  {
    if (IS_POST) {
      $us = new UserService();
      if (!$us->hasPermission(FIdConst::FORM_SYSTEM)) {
        die("没有权限");
      }

      $params = [
        "id" => I("post.id")
      ];

      $service = new FormService();
      $this->ajaxReturn($service->deleteForm($params));
    }
  }

  /**
   * 获得表单主表元数据
   */
  public function formInfo()
  {
    if (IS_POST) {
      $us = new UserService();
      if (!$us->hasPermission(FIdConst::FORM_SYSTEM)) {
        die("没有权限");
      }

      $params = [
        "id" => I("post.id")
      ];

      $service = new FormService();
      $this->ajaxReturn($service->formInfo($params));
    }
  }

  /**
   * 返回表单主表列的信息
   */
  public function formColInfo()
  {
    if (IS_POST) {
      $us = new UserService();
      if (!$us->hasPermission(FIdConst::FORM_SYSTEM)) {
        die("没有权限");
      }

      $params = [
        "id" => I("post.id"),
        "formId" => ("post.formId")
      ];

      $service = new FormService();
      $this->ajaxReturn($service->formColInfo($params));
    }
  }

  /**
   * 新增或编辑表单主表列
   */
  public function editFormCol()
  {
    if (IS_POST) {
      $us = new UserService();
      if (!$us->hasPermission(FIdConst::FORM_SYSTEM)) {
        die("没有权限");
      }

      $params = [
        "id" => I("post.id"),
        "formId" => ("post.formId")
      ];

      $service = new FormService();
      $this->ajaxReturn($service->editFormCol($params));
    }
  }
}

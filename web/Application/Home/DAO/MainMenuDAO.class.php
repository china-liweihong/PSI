<?php

namespace Home\DAO;

/**
 * 主菜单 DAO
 *
 * @author 李静波
 */
class MainMenuDAO extends PSIBaseExDAO {

	/**
	 * 查询所有的主菜单项 - 主菜单维护模块中使用
	 */
	public function allMenuItemsForMaintain() {
		$db = $this->db;
		
		return $this->emptyResult();
	}
}
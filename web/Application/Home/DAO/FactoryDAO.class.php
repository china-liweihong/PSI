<?php

namespace Home\DAO;

/**
 * 工厂 DAO
 *
 * @author 李静波
 */
class FactoryDAO extends PSIBaseExDAO {

	/**
	 * 新增工厂分类
	 *
	 * @param array $params        	
	 * @return array|null
	 */
	public function addFactoryCategory(& $params) {
		$db = $this->db;
		
		$code = trim($params["code"]);
		$name = trim($params["name"]);
		
		$dataOrg = $params["dataOrg"];
		$companyId = $params["companyId"];
		if ($this->dataOrgNotExists($dataOrg)) {
			return $this->badParam("dataOrg");
		}
		if ($this->companyIdNotExists($companyId)) {
			return $this->badParam("companyId");
		}
		
		if ($this->isEmptyStringAfterTrim($code)) {
			return $this->bad("分类编码不能为空");
		}
		if ($this->isEmptyStringAfterTrim($name)) {
			return $this->bad("分类名称不能为空");
		}
		
		// 检查分类编码是否已经存在
		$sql = "select count(*) as cnt from t_factory_category where code = '%s' ";
		$data = $db->query($sql, $code);
		$cnt = $data[0]["cnt"];
		if ($cnt > 0) {
			return $this->bad("编码为 [$code] 的分类已经存在");
		}
		
		$id = $this->newId();
		
		$sql = "insert into t_factory_category (id, code, name, data_org, company_id)
					values ('%s', '%s', '%s', '%s', '%s') ";
		$rc = $db->execute($sql, $id, $code, $name, $dataOrg, $companyId);
		if ($rc === false) {
			return $this->sqlError(__METHOD__, __LINE__);
		}
		
		// 操作成功
		$params["id"] = $id;
		return null;
	}

	/**
	 * 编辑工厂分类
	 *
	 * @param array $params        	
	 * @return array|null
	 */
	public function updateFactoryCategory(& $params) {
		return $this->todo();
	}
}
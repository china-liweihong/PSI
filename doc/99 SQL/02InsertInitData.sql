SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

TRUNCATE TABLE `t_fid`;
INSERT INTO `t_fid` (`fid`, `name`) VALUES
('-7996', '码表设置'),
('-7997', '表单视图开发助手'),
('-9999', '重新登录'),
('-9997', '首页'),
('-9996', '修改我的密码'),
('-9995', '帮助'),
('-9994', '关于'),
('-9993', '购买商业服务'),
('-8999', '用户管理'),
('-8999-01', '组织机构在业务单据中的使用权限'),
('-8999-02', '业务员在业务单据中的使用权限'),
('-8997', '业务日志'),
('-8996', '权限管理'),
('1001', '商品'),
('1001-01', '商品在业务单据中的使用权限'),
('1001-02', '商品分类'),
('1002', '商品计量单位'),
('1003', '仓库'),
('1003-01', '仓库在业务单据中的使用权限'),
('1004', '供应商档案'),
('1004-01', '供应商档案在业务单据中的使用权限'),
('1004-02', '供应商分类'),
('1007', '客户资料'),
('1007-01', '客户资料在业务单据中的使用权限'),
('1007-02', '客户分类'),
('2000', '库存建账'),
('2001', '采购入库'),
('2001-01', '采购入库-新建采购入库单'),
('2001-02', '采购入库-编辑采购入库单'),
('2001-03', '采购入库-删除采购入库单'),
('2001-04', '采购入库-提交入库'),
('2001-05', '采购入库-单据生成PDF'),
('2001-06', '采购入库-采购单价和金额可见'),
('2001-07', '采购入库-打印'),
('2002', '销售出库'),
('2002-01', '销售出库-销售出库单允许编辑销售单价'),
('2002-02', '销售出库-新建销售出库单'),
('2002-03', '销售出库-编辑销售出库单'),
('2002-04', '销售出库-删除销售出库单'),
('2002-05', '销售出库-提交出库'),
('2002-06', '销售出库-单据生成PDF'),
('2002-07', '销售出库-打印'),
('2003', '库存账查询'),
('2004', '应收账款管理'),
('2005', '应付账款管理'),
('2006', '销售退货入库'),
('2006-01', '销售退货入库-新建销售退货入库单'),
('2006-02', '销售退货入库-编辑销售退货入库单'),
('2006-03', '销售退货入库-删除销售退货入库单'),
('2006-04', '销售退货入库-提交入库'),
('2006-05', '销售退货入库-单据生成PDF'),
('2006-06', '销售退货入库-打印'),
('2007', '采购退货出库'),
('2007-01', '采购退货出库-新建采购退货出库单'),
('2007-02', '采购退货出库-编辑采购退货出库单'),
('2007-03', '采购退货出库-删除采购退货出库单'),
('2007-04', '采购退货出库-提交采购退货出库单'),
('2007-05', '采购退货出库-单据生成PDF'),
('2007-06', '采购退货出库-打印'),
('2008', '业务设置'),
('2009', '库间调拨'),
('2009-01', '库间调拨-新建调拨单'),
('2009-02', '库间调拨-编辑调拨单'),
('2009-03', '库间调拨-删除调拨单'),
('2009-04', '库间调拨-提交调拨单'),
('2009-05', '库间调拨-单据生成PDF'),
('2009-06', '库间调拨-打印'),
('2010', '库存盘点'),
('2010-01', '库存盘点-新建盘点单'),
('2010-02', '库存盘点-编辑盘点单'),
('2010-03', '库存盘点-删除盘点单'),
('2010-04', '库存盘点-提交盘点单'),
('2010-05', '库存盘点-单据生成PDF'),
('2010-06', '库存盘点-打印'),
('2011-01', '首页-销售看板'),
('2011-02', '首页-库存看板'),
('2011-03', '首页-采购看板'),
('2011-04', '首页-资金看板'),
('2012', '报表-销售日报表(按商品汇总)'),
('2013', '报表-销售日报表(按客户汇总)'),
('2014', '报表-销售日报表(按仓库汇总)'),
('2015', '报表-销售日报表(按业务员汇总)'),
('2016', '报表-销售月报表(按商品汇总)'),
('2017', '报表-销售月报表(按客户汇总)'),
('2018', '报表-销售月报表(按仓库汇总)'),
('2019', '报表-销售月报表(按业务员汇总)'),
('2020', '报表-安全库存明细表'),
('2021', '报表-应收账款账龄分析表'),
('2022', '报表-应付账款账龄分析表'),
('2023', '报表-库存超上限明细表'),
('2024', '现金收支查询'),
('2025', '预收款管理'),
('2026', '预付款管理'),
('2027', '采购订单'),
('2027-01', '采购订单-审核/取消审核'),
('2027-02', '采购订单-生成采购入库单'),
('2027-03', '采购订单-新建采购订单'),
('2027-04', '采购订单-编辑采购订单'),
('2027-05', '采购订单-删除采购订单'),
('2027-06', '采购订单-关闭订单/取消关闭订单'),
('2027-07', '采购订单-单据生成PDF'),
('2027-08', '采购订单-打印'),
('2028', '销售订单'),
('2028-01', '销售订单-审核/取消审核'),
('2028-02', '销售订单-生成销售出库单'),
('2028-03', '销售订单-新建销售订单'),
('2028-04', '销售订单-编辑销售订单'),
('2028-05', '销售订单-删除销售订单'),
('2028-06', '销售订单-单据生成PDF'),
('2028-07', '销售订单-打印'),
('2028-08', '销售订单-生成采购订单'),
('2029', '商品品牌'),
('2030-01', '商品构成-新增子商品'),
('2030-02', '商品构成-编辑子商品'),
('2030-03', '商品构成-删除子商品'),
('2031', '价格体系'),
('2031-01', '商品-设置商品价格体系'),
('2032', '销售合同'),
('2032-01', '销售合同-新建销售合同'),
('2032-02', '销售合同-编辑销售合同'),
('2032-03', '销售合同-删除销售合同'),
('2032-04', '销售合同-审核/取消审核'),
('2032-05', '销售合同-生成销售订单'),
('2032-06', '销售合同-单据生成PDF'),
('2032-07', '销售合同-打印'),
('2033', '存货拆分'),
('2033-01', '存货拆分-新建拆分单'),
('2033-02', '存货拆分-编辑拆分单'),
('2033-03', '存货拆分-删除拆分单'),
('2033-04', '存货拆分-提交拆分单'),
('2033-05', '存货拆分-单据生成PDF'),
('2033-06', '存货拆分-打印'),
('2034', '工厂'),
('2034-01', '工厂在业务单据中的使用权限'),
('2034-02', '工厂分类'),
('2034-03', '工厂-新增工厂分类'),
('2034-04', '工厂-编辑工厂分类'),
('2034-05', '工厂-删除工厂分类'),
('2034-06', '工厂-新增工厂'),
('2034-07', '工厂-编辑工厂'),
('2034-08', '工厂-删除工厂'),
('2035', '成品委托生产订单'),
('2035-01', '成品委托生产订单-新建成品委托生产订单'),
('2035-02', '成品委托生产订单-编辑成品委托生产订单'),
('2035-03', '成品委托生产订单-删除成品委托生产订单'),
('2035-04', '成品委托生产订单-提交成品委托生产订单'),
('2035-05', '成品委托生产订单-审核/取消审核成品委托生产入库单'),
('2035-06', '成品委托生产订单-关闭/取消关闭成品委托生产订单'),
('2035-07', '成品委托生产订单-单据生成PDF'),
('2035-08', '成品委托生产订单-打印'),
('2036', '成品委托生产入库'),
('2036-01', '成品委托生产入库-新建成品委托生产入库单'),
('2036-02', '成品委托生产入库-编辑成品委托生产入库单'),
('2036-03', '成品委托生产入库-删除成品委托生产入库单'),
('2036-04', '成品委托生产入库-提交入库'),
('2036-05', '成品委托生产入库-单据生成PDF'),
('2036-06', '成品委托生产入库-打印'),
('2101', '会计科目'),
('2102', '银行账户'),
('2103', '会计期间');

TRUNCATE TABLE `t_menu_item`;
INSERT INTO `t_menu_item` (`id`, `caption`, `fid`, `parent_id`, `show_order`) VALUES
('01', '文件', NULL, NULL, 1),
('0101', '首页', '-9997', '01', 1),
('0102', '重新登录', '-9999', '01', 2),
('0103', '修改我的密码', '-9996', '01', 3),
('02', '采购', NULL, NULL, 2),
('0200', '采购订单', '2027', '02', 0),
('0201', '采购入库', '2001', '02', 1),
('0202', '采购退货出库', '2007', '02', 2),
('03', '库存', NULL, NULL, 3),
('0301', '库存账查询', '2003', '03', 1),
('0302', '库存建账', '2000', '03', 2),
('0303', '库间调拨', '2009', '03', 3),
('0304', '库存盘点', '2010', '03', 4),
('12', '加工', NULL, NULL, 4),
('1201', '存货拆分', '2033', '12', 1),
('1202', '成品委托生产', NULL, '12', 2),
('120201', '成品委托生产订单', '2035', '1202', 1),
('120202', '成品委托生产入库', '2036', '1202', 2),
('04', '销售', NULL, NULL, 5),
('0401', '销售合同', '2032', '04', 1),
('0402', '销售订单', '2028', '04', 2),
('0403', '销售出库', '2002', '04', 3),
('0404', '销售退货入库', '2006', '04', 4),
('05', '客户关系', NULL, NULL, 6),
('0501', '客户资料', '1007', '05', 1),
('06', '资金', NULL, NULL, 7),
('0601', '应收账款管理', '2004', '06', 1),
('0602', '应付账款管理', '2005', '06', 2),
('0603', '现金收支查询', '2024', '06', 3),
('0604', '预收款管理', '2025', '06', 4),
('0605', '预付款管理', '2026', '06', 5),
('07', '报表', NULL, NULL, 8),
('0701', '销售日报表', NULL, '07', 1),
('070101', '销售日报表(按商品汇总)', '2012', '0701', 1),
('070102', '销售日报表(按客户汇总)', '2013', '0701', 2),
('070103', '销售日报表(按仓库汇总)', '2014', '0701', 3),
('070104', '销售日报表(按业务员汇总)', '2015', '0701', 4),
('0702', '销售月报表', NULL, '07', 2),
('070201', '销售月报表(按商品汇总)', '2016', '0702', 1),
('070202', '销售月报表(按客户汇总)', '2017', '0702', 2),
('070203', '销售月报表(按仓库汇总)', '2018', '0702', 3),
('070204', '销售月报表(按业务员汇总)', '2019', '0702', 4),
('0703', '库存报表', NULL, '07', 3),
('070301', '安全库存明细表', '2020', '0703', 1),
('070302', '库存超上限明细表', '2023', '0703', 2),
('0706', '资金报表', NULL, '07', 6),
('070601', '应收账款账龄分析表', '2021', '0706', 1),
('070602', '应付账款账龄分析表', '2022', '0706', 2),
('11', '财务总账', NULL, NULL, 9),
('1101', '基础数据', NULL, '11', 1),
('110101', '会计科目', '2101', '1101', 1),
('110102', '银行账户', '2102', '1101', 2),
('110103', '会计期间', '2103', '1101', 3),
('08', '基础数据', NULL, NULL, 10),
('0801', '商品', NULL, '08', 1),
('080101', '商品', '1001', '0801', 1),
('080102', '商品计量单位', '1002', '0801', 2),
('080103', '商品品牌', '2029', '0801', 3),
('080104', '价格体系', '2031', '0801', 4),
('0803', '仓库', '1003', '08', 3),
('0804', '供应商档案', '1004', '08', 4),
('0805', '工厂', '2034', '08', 5),
('09', '系统管理', NULL, NULL, 11),
('0901', '用户管理', '-8999', '09', 1),
('0902', '权限管理', '-8996', '09', 2),
('0903', '业务日志', '-8997', '09', 3),
('0904', '业务设置', '2008', '09', 4),
('0905', '二次开发', NULL, '09', 5),
('090501', '码表设置', '-7996', '0905', 1),
('090502', '表单视图开发助手', '-7997', '0905', 2),
('10', '帮助', NULL, NULL, 12),
('1001', '使用帮助', '-9995', '10', 1),
('1003', '关于', '-9994', '10', 3);

TRUNCATE TABLE `t_org`;
INSERT INTO `t_org` (`id`, `full_name`, `name`, `org_code`, `data_org`, `parent_id`) VALUES
('4D74E1E4-A129-11E4-9B6A-782BCBD7746B', '公司', '公司', '01', '01', NULL),
('5EBDBE11-A129-11E4-9B6A-782BCBD7746B', '公司\\信息部', '信息部', '0199', '0101', '4D74E1E4-A129-11E4-9B6A-782BCBD7746B');

TRUNCATE TABLE `t_permission`;
INSERT INTO `t_permission` (`id`, `fid`, `name`, `note`, `category`, `py`, `show_order`) VALUES
('-7996', '-7996', '码表设置', '模块权限：通过菜单进入码表设置模块的权限', '码表设置', 'MBSZ', 100),
('-8996', '-8996', '权限管理', '模块权限：通过菜单进入权限管理模块的权限', '权限管理', 'QXGL', 100),
('-8996-01', '-8996-01', '权限管理-新增角色', '按钮权限：权限管理模块[新增角色]按钮权限', '权限管理', 'QXGL_XZJS', 201),
('-8996-02', '-8996-02', '权限管理-编辑角色', '按钮权限：权限管理模块[编辑角色]按钮权限', '权限管理', 'QXGL_BJJS', 202),
('-8996-03', '-8996-03', '权限管理-删除角色', '按钮权限：权限管理模块[删除角色]按钮权限', '权限管理', 'QXGL_SCJS', 203),
('-8997', '-8997', '业务日志', '模块权限：通过菜单进入业务日志模块的权限', '系统管理', 'YWRZ', 100),
('-8999', '-8999', '用户管理', '模块权限：通过菜单进入用户管理模块的权限', '用户管理', 'YHGL', 100),
('-8999-01', '-8999-01', '组织机构在业务单据中的使用权限', '数据域权限：组织机构在业务单据中的使用权限', '用户管理', 'ZZJGZYWDJZDSYQX', 300),
('-8999-02', '-8999-02', '业务员在业务单据中的使用权限', '数据域权限：业务员在业务单据中的使用权限', '用户管理', 'YWYZYWDJZDSYQX', 301),
('-8999-03', '-8999-03', '用户管理-新增组织机构', '按钮权限：用户管理模块[新增组织机构]按钮权限', '用户管理', 'YHGL_XZZZJG', 201),
('-8999-04', '-8999-04', '用户管理-编辑组织机构', '按钮权限：用户管理模块[编辑组织机构]按钮权限', '用户管理', 'YHGL_BJZZJG', 202),
('-8999-05', '-8999-05', '用户管理-删除组织机构', '按钮权限：用户管理模块[删除组织机构]按钮权限', '用户管理', 'YHGL_SCZZJG', 203),
('-8999-06', '-8999-06', '用户管理-新增用户', '按钮权限：用户管理模块[新增用户]按钮权限', '用户管理', 'YHGL_XZYH', 204),
('-8999-07', '-8999-07', '用户管理-编辑用户', '按钮权限：用户管理模块[编辑用户]按钮权限', '用户管理', 'YHGL_BJYH', 205),
('-8999-08', '-8999-08', '用户管理-删除用户', '按钮权限：用户管理模块[删除用户]按钮权限', '用户管理', 'YHGL_SCYH', 206),
('-8999-09', '-8999-09', '用户管理-修改用户密码', '按钮权限：用户管理模块[修改用户密码]按钮权限', '用户管理', 'YHGL_XGYHMM', 207),
('1001', '1001', '商品', '模块权限：通过菜单进入商品模块的权限', '商品', 'SP', 100),
('1001-01', '1001-01', '商品在业务单据中的使用权限', '数据域权限：商品在业务单据中的使用权限', '商品', 'SPZYWDJZDSYQX', 300),
('1001-02', '1001-02', '商品分类', '数据域权限：商品模块中商品分类的数据权限', '商品', 'SPFL', 301),
('1001-03', '1001-03', '新增商品分类', '按钮权限：商品模块[新增商品分类]按钮权限', '商品', 'XZSPFL', 201),
('1001-04', '1001-04', '编辑商品分类', '按钮权限：商品模块[编辑商品分类]按钮权限', '商品', 'BJSPFL', 202),
('1001-05', '1001-05', '删除商品分类', '按钮权限：商品模块[删除商品分类]按钮权限', '商品', 'SCSPFL', 203),
('1001-06', '1001-06', '新增商品', '按钮权限：商品模块[新增商品]按钮权限', '商品', 'XZSP', 204),
('1001-07', '1001-07', '编辑商品', '按钮权限：商品模块[编辑商品]按钮权限', '商品', 'BJSP', 205),
('1001-08', '1001-08', '删除商品', '按钮权限：商品模块[删除商品]按钮权限', '商品', 'SCSP', 206),
('1001-09', '1001-09', '导入商品', '按钮权限：商品模块[导入商品]按钮权限', '商品', 'DRSP', 207),
('1001-10', '1001-10', '设置商品安全库存', '按钮权限：商品模块[设置安全库存]按钮权限', '商品', 'SZSPAQKC', 208),
('1002', '1002', '商品计量单位', '模块权限：通过菜单进入商品计量单位模块的权限', '商品', 'SPJLDW', 500),
('1003', '1003', '仓库', '模块权限：通过菜单进入仓库的权限', '仓库', 'CK', 100),
('1003-01', '1003-01', '仓库在业务单据中的使用权限', '数据域权限：仓库在业务单据中的使用权限', '仓库', 'CKZYWDJZDSYQX', 300),
('1003-02', '1003-02', '新增仓库', '按钮权限：仓库模块[新增仓库]按钮权限', '仓库', 'XZCK', 201),
('1003-03', '1003-03', '编辑仓库', '按钮权限：仓库模块[编辑仓库]按钮权限', '仓库', 'BJCK', 202),
('1003-04', '1003-04', '删除仓库', '按钮权限：仓库模块[删除仓库]按钮权限', '仓库', 'SCCK', 203),
('1003-05', '1003-05', '修改仓库数据域', '按钮权限：仓库模块[修改数据域]按钮权限', '仓库', 'XGCKSJY', 204),
('1004', '1004', '供应商档案', '模块权限：通过菜单进入供应商档案的权限', '供应商管理', 'GYSDA', 100),
('1004-01', '1004-01', '供应商档案在业务单据中的使用权限', '数据域权限：供应商档案在业务单据中的使用权限', '供应商管理', 'GYSDAZYWDJZDSYQX', 301),
('1004-02', '1004-02', '供应商分类', '数据域权限：供应商档案模块中供应商分类的数据权限', '供应商管理', 'GYSFL', 300),
('1004-03', '1004-03', '新增供应商分类', '按钮权限：供应商档案模块[新增供应商分类]按钮权限', '供应商管理', 'XZGYSFL', 201),
('1004-04', '1004-04', '编辑供应商分类', '按钮权限：供应商档案模块[编辑供应商分类]按钮权限', '供应商管理', 'BJGYSFL', 202),
('1004-05', '1004-05', '删除供应商分类', '按钮权限：供应商档案模块[删除供应商分类]按钮权限', '供应商管理', 'SCGYSFL', 203),
('1004-06', '1004-06', '新增供应商', '按钮权限：供应商档案模块[新增供应商]按钮权限', '供应商管理', 'XZGYS', 204),
('1004-07', '1004-07', '编辑供应商', '按钮权限：供应商档案模块[编辑供应商]按钮权限', '供应商管理', 'BJGYS', 205),
('1004-08', '1004-08', '删除供应商', '按钮权限：供应商档案模块[删除供应商]按钮权限', '供应商管理', 'SCGYS', 206),
('1007', '1007', '客户资料', '模块权限：通过菜单进入客户资料模块的权限', '客户管理', 'KHZL', 100),
('1007-01', '1007-01', '客户资料在业务单据中的使用权限', '数据域权限：客户资料在业务单据中的使用权限', '客户管理', 'KHZLZYWDJZDSYQX', 300),
('1007-02', '1007-02', '客户分类', '数据域权限：客户档案模块中客户分类的数据权限', '客户管理', 'KHFL', 301),
('1007-03', '1007-03', '新增客户分类', '按钮权限：客户资料模块[新增客户分类]按钮权限', '客户管理', 'XZKHFL', 201),
('1007-04', '1007-04', '编辑客户分类', '按钮权限：客户资料模块[编辑客户分类]按钮权限', '客户管理', 'BJKHFL', 202),
('1007-05', '1007-05', '删除客户分类', '按钮权限：客户资料模块[删除客户分类]按钮权限', '客户管理', 'SCKHFL', 203),
('1007-06', '1007-06', '新增客户', '按钮权限：客户资料模块[新增客户]按钮权限', '客户管理', 'XZKH', 204),
('1007-07', '1007-07', '编辑客户', '按钮权限：客户资料模块[编辑客户]按钮权限', '客户管理', 'BJKH', 205),
('1007-08', '1007-08', '删除客户', '按钮权限：客户资料模块[删除客户]按钮权限', '客户管理', 'SCKH', 206),
('1007-09', '1007-09', '导入客户', '按钮权限：客户资料模块[导入客户]按钮权限', '客户管理', 'DRKH', 207),
('2000', '2000', '库存建账', '模块权限：通过菜单进入库存建账模块的权限', '库存建账', 'KCJZ', 100),
('2001', '2001', '采购入库', '模块权限：通过菜单进入采购入库模块的权限', '采购入库', 'CGRK', 100),
('2001-01', '2001-01', '采购入库-新建采购入库单', '按钮权限：采购入库模块[新建采购入库单]按钮权限', '采购入库', 'CGRK_XJCGRKD', 201),
('2001-02', '2001-02', '采购入库-编辑采购入库单', '按钮权限：采购入库模块[编辑采购入库单]按钮权限', '采购入库', 'CGRK_BJCGRKD', 202),
('2001-03', '2001-03', '采购入库-删除采购入库单', '按钮权限：采购入库模块[删除采购入库单]按钮权限', '采购入库', 'CGRK_SCCGRKD', 203),
('2001-04', '2001-04', '采购入库-提交入库', '按钮权限：采购入库模块[提交入库]按钮权限', '采购入库', 'CGRK_TJRK', 204),
('2001-05', '2001-05', '采购入库-单据生成PDF', '按钮权限：采购入库模块[单据生成PDF]按钮权限', '采购入库', 'CGRK_DJSCPDF', 205),
('2001-06', '2001-06', '采购入库-采购单价和金额可见', '字段权限：采购入库单的采购单价和金额可以被用户查看', '采购入库', 'CGRK_CGDJHJEKJ', 206),
('2001-07', '2001-07', '采购入库-打印', '按钮权限：采购入库模块[打印预览]和[直接打印]按钮权限', '采购入库', 'CGRK_DY', 207),
('2002', '2002', '销售出库', '模块权限：通过菜单进入销售出库模块的权限', '销售出库', 'XSCK', 100),
('2002-01', '2002-01', '销售出库-销售出库单允许编辑销售单价', '功能权限：销售出库单允许编辑销售单价', '销售出库', 'XSCKDYXBJXSDJ', 101),
('2002-02', '2002-02', '销售出库-新建销售出库单', '按钮权限：销售出库模块[新建销售出库单]按钮权限', '销售出库', 'XSCK_XJXSCKD', 201),
('2002-03', '2002-03', '销售出库-编辑销售出库单', '按钮权限：销售出库模块[编辑销售出库单]按钮权限', '销售出库', 'XSCK_BJXSCKD', 202),
('2002-04', '2002-04', '销售出库-删除销售出库单', '按钮权限：销售出库模块[删除销售出库单]按钮权限', '销售出库', 'XSCK_SCXSCKD', 203),
('2002-05', '2002-05', '销售出库-提交出库', '按钮权限：销售出库模块[提交出库]按钮权限', '销售出库', 'XSCK_TJCK', 204),
('2002-06', '2002-06', '销售出库-单据生成PDF', '按钮权限：销售出库模块[单据生成PDF]按钮权限', '销售出库', 'XSCK_DJSCPDF', 205),
('2002-07', '2002-07', '销售出库-打印', '按钮权限：销售出库模块[打印预览]和[直接打印]按钮权限', '销售出库', 'XSCK_DY', 207),
('2003', '2003', '库存账查询', '模块权限：通过菜单进入库存账查询模块的权限', '库存账查询', 'KCZCX', 100),
('2004', '2004', '应收账款管理', '模块权限：通过菜单进入应收账款管理模块的权限', '应收账款管理', 'YSZKGL', 100),
('2005', '2005', '应付账款管理', '模块权限：通过菜单进入应付账款管理模块的权限', '应付账款管理', 'YFZKGL', 100),
('2006', '2006', '销售退货入库', '模块权限：通过菜单进入销售退货入库模块的权限', '销售退货入库', 'XSTHRK', 100),
('2006-01', '2006-01', '销售退货入库-新建销售退货入库单', '按钮权限：销售退货入库模块[新建销售退货入库单]按钮权限', '销售退货入库', 'XSTHRK_XJXSTHRKD', 201),
('2006-02', '2006-02', '销售退货入库-编辑销售退货入库单', '按钮权限：销售退货入库模块[编辑销售退货入库单]按钮权限', '销售退货入库', 'XSTHRK_BJXSTHRKD', 202),
('2006-03', '2006-03', '销售退货入库-删除销售退货入库单', '按钮权限：销售退货入库模块[删除销售退货入库单]按钮权限', '销售退货入库', 'XSTHRK_SCXSTHRKD', 203),
('2006-04', '2006-04', '销售退货入库-提交入库', '按钮权限：销售退货入库模块[提交入库]按钮权限', '销售退货入库', 'XSTHRK_TJRK', 204),
('2006-05', '2006-05', '销售退货入库-单据生成PDF', '按钮权限：销售退货入库模块[单据生成PDF]按钮权限', '销售退货入库', 'XSTHRK_DJSCPDF', 205),
('2006-06', '2006-06', '销售退货入库-打印', '按钮权限：销售退货入库模块[打印预览]和[直接打印]按钮权限', '销售退货入库', 'XSTHRK_DY', 206),
('2007', '2007', '采购退货出库', '模块权限：通过菜单进入采购退货出库模块的权限', '采购退货出库', 'CGTHCK', 100),
('2007-01', '2007-01', '采购退货出库-新建采购退货出库单', '按钮权限：采购退货出库模块[新建采购退货出库单]按钮权限', '采购退货出库', 'CGTHCK_XJCGTHCKD', 201),
('2007-02', '2007-02', '采购退货出库-编辑采购退货出库单', '按钮权限：采购退货出库模块[编辑采购退货出库单]按钮权限', '采购退货出库', 'CGTHCK_BJCGTHCKD', 202),
('2007-03', '2007-03', '采购退货出库-删除采购退货出库单', '按钮权限：采购退货出库模块[删除采购退货出库单]按钮权限', '采购退货出库', 'CGTHCK_SCCGTHCKD', 203),
('2007-04', '2007-04', '采购退货出库-提交采购退货出库单', '按钮权限：采购退货出库模块[提交采购退货出库单]按钮权限', '采购退货出库', 'CGTHCK_TJCGTHCKD', 204),
('2007-05', '2007-05', '采购退货出库-单据生成PDF', '按钮权限：采购退货出库模块[单据生成PDF]按钮权限', '采购退货出库', 'CGTHCK_DJSCPDF', 205),
('2007-06', '2007-06', '采购退货出库-打印', '按钮权限：采购退货出库模块[打印预览]和[直接打印]按钮权限', '采购退货出库', 'CGTHCK_DY', 206),
('2008', '2008', '业务设置', '模块权限：通过菜单进入业务设置模块的权限', '系统管理', 'YWSZ', 100),
('2009', '2009', '库间调拨', '模块权限：通过菜单进入库间调拨模块的权限', '库间调拨', 'KJDB', 100),
('2009-01', '2009-01', '库间调拨-新建调拨单', '按钮权限：库间调拨模块[新建调拨单]按钮权限', '库间调拨', 'KJDB_XJDBD', 201),
('2009-02', '2009-02', '库间调拨-编辑调拨单', '按钮权限：库间调拨模块[编辑调拨单]按钮权限', '库间调拨', 'KJDB_BJDBD', 202),
('2009-03', '2009-03', '库间调拨-删除调拨单', '按钮权限：库间调拨模块[删除调拨单]按钮权限', '库间调拨', 'KJDB_SCDBD', 203),
('2009-04', '2009-04', '库间调拨-提交调拨单', '按钮权限：库间调拨模块[提交调拨单]按钮权限', '库间调拨', 'KJDB_TJDBD', 204),
('2009-05', '2009-05', '库间调拨-单据生成PDF', '按钮权限：库间调拨模块[单据生成PDF]按钮权限', '库间调拨', 'KJDB_DJSCPDF', 205),
('2009-06', '2009-06', '库间调拨-打印', '按钮权限：库间调拨模块[打印预览]和[直接打印]按钮权限', '库间调拨', 'KJDB_DY', 206),
('2010', '2010', '库存盘点', '模块权限：通过菜单进入库存盘点模块的权限', '库存盘点', 'KCPD', 100),
('2010-01', '2010-01', '库存盘点-新建盘点单', '按钮权限：库存盘点模块[新建盘点单]按钮权限', '库存盘点', 'KCPD_XJPDD', 201),
('2010-02', '2010-02', '库存盘点-编辑盘点单', '按钮权限：库存盘点模块[编辑盘点单]按钮权限', '库存盘点', 'KCPD_BJPDD', 202),
('2010-03', '2010-03', '库存盘点-删除盘点单', '按钮权限：库存盘点模块[删除盘点单]按钮权限', '库存盘点', 'KCPD_SCPDD', 203),
('2010-04', '2010-04', '库存盘点-提交盘点单', '按钮权限：库存盘点模块[提交盘点单]按钮权限', '库存盘点', 'KCPD_TJPDD', 204),
('2010-05', '2010-05', '库存盘点-单据生成PDF', '按钮权限：库存盘点模块[单据生成PDF]按钮权限', '库存盘点', 'KCPD_DJSCPDF', 205),
('2010-06', '2010-06', '库存盘点-打印', '按钮权限：库存盘点模块[打印预览]和[直接打印]按钮权限', '库存盘点', 'KCPD_DY', 206),
('2011-01', '2011-01', '首页-销售看板', '功能权限：在首页显示销售看板', '首页看板', 'SY_XSKB', 100),
('2011-02', '2011-02', '首页-库存看板', '功能权限：在首页显示库存看板', '首页看板', 'SY_KCKB', 100),
('2011-03', '2011-03', '首页-采购看板', '功能权限：在首页显示采购看板', '首页看板', 'SY_CGKB', 100),
('2011-04', '2011-04', '首页-资金看板', '功能权限：在首页显示资金看板', '首页看板', 'SY_ZJKB', 100),
('2012', '2012', '报表-销售日报表(按商品汇总)', '模块权限：通过菜单进入销售日报表(按商品汇总)模块的权限', '销售日报表', 'BB_XSRBB_ASPHZ_', 100),
('2013', '2013', '报表-销售日报表(按客户汇总)', '模块权限：通过菜单进入销售日报表(按客户汇总)模块的权限', '销售日报表', 'BB_XSRBB_AKHHZ_', 100),
('2014', '2014', '报表-销售日报表(按仓库汇总)', '模块权限：通过菜单进入销售日报表(按仓库汇总)模块的权限', '销售日报表', 'BB_XSRBB_ACKHZ_', 100),
('2015', '2015', '报表-销售日报表(按业务员汇总)', '模块权限：通过菜单进入销售日报表(按业务员汇总)模块的权限', '销售日报表', 'BB_XSRBB_AYWYHZ_', 100),
('2016', '2016', '报表-销售月报表(按商品汇总)', '模块权限：通过菜单进入销售月报表(按商品汇总)模块的权限', '销售月报表', 'BB_XSYBB_ASPHZ_', 100),
('2017', '2017', '报表-销售月报表(按客户汇总)', '模块权限：通过菜单进入销售月报表(按客户汇总)模块的权限', '销售月报表', 'BB_XSYBB_AKHHZ_', 100),
('2018', '2018', '报表-销售月报表(按仓库汇总)', '模块权限：通过菜单进入销售月报表(按仓库汇总)模块的权限', '销售月报表', 'BB_XSYBB_ACKHZ_', 100),
('2019', '2019', '报表-销售月报表(按业务员汇总)', '模块权限：通过菜单进入销售月报表(按业务员汇总)模块的权限', '销售月报表', 'BB_XSYBB_AYWYHZ_', 100),
('2020', '2020', '报表-安全库存明细表', '模块权限：通过菜单进入安全库存明细表模块的权限', '库存报表', 'BB_AQKCMXB', 100),
('2021', '2021', '报表-应收账款账龄分析表', '模块权限：通过菜单进入应收账款账龄分析表模块的权限', '资金报表', 'BB_YSZKZLFXB', 100),
('2022', '2022', '报表-应付账款账龄分析表', '模块权限：通过菜单进入应付账款账龄分析表模块的权限', '资金报表', 'BB_YFZKZLFXB', 100),
('2023', '2023', '报表-库存超上限明细表', '模块权限：通过菜单进入库存超上限明细表模块的权限', '库存报表', 'BB_KCCSXMXB', 100),
('2024', '2024', '现金收支查询', '模块权限：通过菜单进入现金收支查询模块的权限', '现金管理', 'XJSZCX', 100),
('2025', '2025', '预收款管理', '模块权限：通过菜单进入预收款管理模块的权限', '预收款管理', 'YSKGL', 100),
('2026', '2026', '预付款管理', '模块权限：通过菜单进入预付款管理模块的权限', '预付款管理', 'YFKGL', 100),
('2027', '2027', '采购订单', '模块权限：通过菜单进入采购订单模块的权限', '采购订单', 'CGDD', 100),
('2027-01', '2027-01', '采购订单-审核/取消审核', '按钮权限：采购订单模块[审核]按钮和[取消审核]按钮的权限', '采购订单', 'CGDD _ SH_QXSH', 204),
('2027-02', '2027-02', '采购订单-生成采购入库单', '按钮权限：采购订单模块[生成采购入库单]按钮权限', '采购订单', 'CGDD _ SCCGRKD', 205),
('2027-03', '2027-03', '采购订单-新建采购订单', '按钮权限：采购订单模块[新建采购订单]按钮权限', '采购订单', 'CGDD _ XJCGDD', 201),
('2027-04', '2027-04', '采购订单-编辑采购订单', '按钮权限：采购订单模块[编辑采购订单]按钮权限', '采购订单', 'CGDD _ BJCGDD', 202),
('2027-05', '2027-05', '采购订单-删除采购订单', '按钮权限：采购订单模块[删除采购订单]按钮权限', '采购订单', 'CGDD _ SCCGDD', 203),
('2027-06', '2027-06', '采购订单-关闭订单/取消关闭订单', '按钮权限：采购订单模块[关闭采购订单]和[取消采购订单关闭状态]按钮权限', '采购订单', 'CGDD _ GBDD_QXGBDD', 206),
('2027-07', '2027-07', '采购订单-单据生成PDF', '按钮权限：采购订单模块[单据生成PDF]按钮权限', '采购订单', 'CGDD _ DJSCPDF', 207),
('2027-08', '2027-08', '采购订单-打印', '按钮权限：采购订单模块[打印预览]和[直接打印]按钮权限', '采购订单', 'CGDD_DY', 208),
('2028', '2028', '销售订单', '模块权限：通过菜单进入销售订单模块的权限', '销售订单', 'XSDD', 100),
('2028-01', '2028-01', '销售订单-审核/取消审核', '按钮权限：销售订单模块[审核]按钮和[取消审核]按钮的权限', '销售订单', 'XSDD_SH_QXSH', 204),
('2028-02', '2028-02', '销售订单-生成销售出库单', '按钮权限：销售订单模块[生成销售出库单]按钮的权限', '销售订单', 'XSDD_SCXSCKD', 206),
('2028-03', '2028-03', '销售订单-新建销售订单', '按钮权限：销售订单模块[新建销售订单]按钮的权限', '销售订单', 'XSDD_XJXSDD', 201),
('2028-04', '2028-04', '销售订单-编辑销售订单', '按钮权限：销售订单模块[编辑销售订单]按钮的权限', '销售订单', 'XSDD_BJXSDD', 202),
('2028-05', '2028-05', '销售订单-删除销售订单', '按钮权限：销售订单模块[删除销售订单]按钮的权限', '销售订单', 'XSDD_SCXSDD', 203),
('2028-06', '2028-06', '销售订单-单据生成PDF', '按钮权限：销售订单模块[单据生成PDF]按钮的权限', '销售订单', 'XSDD_DJSCPDF', 207),
('2028-07', '2028-07', '销售订单-打印', '按钮权限：销售订单模块[打印预览]和[直接打印]按钮的权限', '销售订单', 'XSDD_DY', 208),
('2028-08', '2028-08', '销售订单-生成采购订单', '按钮权限：销售订单模块[生成采购订单]按钮的权限', '销售订单', 'XSDD_SCCGDD', 205),
('2029', '2029', '商品品牌', '模块权限：通过菜单进入商品品牌模块的权限', '商品', 'SPPP', 600),
('2030-01', '2030-01', '商品构成-新增子商品', '按钮权限：商品模块[新增子商品]按钮权限', '商品', 'SPGC_XZZSP', 209),
('2030-02', '2030-02', '商品构成-编辑子商品', '按钮权限：商品模块[编辑子商品]按钮权限', '商品', 'SPGC_BJZSP', 210),
('2030-03', '2030-03', '商品构成-删除子商品', '按钮权限：商品模块[删除子商品]按钮权限', '商品', 'SPGC_SCZSP', 211),
('2031', '2031', '价格体系', '模块权限：通过菜单进入价格体系模块的权限', '商品', 'JGTX', 700),
('2031-01', '2031-01', '商品-设置商品价格体系', '按钮权限：商品模块[设置商品价格体系]按钮权限', '商品', 'JGTX', 701),
('2032', '2032', '销售合同', '模块权限：通过菜单进入销售合同模块的权限', '销售合同', 'XSHT', 100),
('2032-01', '2032-01', '销售合同-新建销售合同', '按钮权限：销售合同模块[新建销售合同]按钮的权限', '销售合同', 'XSHT_XJXSHT', 201),
('2032-02', '2032-02', '销售合同-编辑销售合同', '按钮权限：销售合同模块[编辑销售合同]按钮的权限', '销售合同', 'XSHT_BJXSHT', 202),
('2032-03', '2032-03', '销售合同-删除销售合同', '按钮权限：销售合同模块[删除销售合同]按钮的权限', '销售合同', 'XSHT_SCXSHT', 203),
('2032-04', '2032-04', '销售合同-审核/取消审核', '按钮权限：销售合同模块[审核]按钮和[取消审核]按钮的权限', '销售合同', 'XSHT_SH_QXSH', 204),
('2032-05', '2032-05', '销售合同-生成销售订单', '按钮权限：销售合同模块[生成销售订单]按钮的权限', '销售合同', 'XSHT_SCXSDD', 205),
('2032-06', '2032-06', '销售合同-单据生成PDF', '按钮权限：销售合同模块[单据生成PDF]按钮的权限', '销售合同', 'XSHT_DJSCPDF', 206),
('2032-07', '2032-07', '销售合同-打印', '按钮权限：销售合同模块[打印预览]和[直接打印]按钮的权限', '销售合同', 'XSHT_DY', 207),
('2033', '2033', '存货拆分', '模块权限：通过菜单进入存货拆分模块的权限', '存货拆分', 'CHCF', 100),
('2033-01', '2033-01', '存货拆分-新建拆分单', '按钮权限：存货拆分模块[新建拆分单]按钮的权限', '存货拆分', 'CHCFXJCFD', 201),
('2033-02', '2033-02', '存货拆分-编辑拆分单', '按钮权限：存货拆分模块[编辑拆分单]按钮的权限', '存货拆分', 'CHCFBJCFD', 202),
('2033-03', '2033-03', '存货拆分-删除拆分单', '按钮权限：存货拆分模块[删除拆分单]按钮的权限', '存货拆分', 'CHCFSCCFD', 203),
('2033-04', '2033-04', '存货拆分-提交拆分单', '按钮权限：存货拆分模块[提交拆分单]按钮的权限', '存货拆分', 'CHCFTJCFD', 204),
('2033-05', '2033-05', '存货拆分-单据生成PDF', '按钮权限：存货拆分模块[单据生成PDF]按钮的权限', '存货拆分', 'CHCFDJSCPDF', 205),
('2033-06', '2033-06', '存货拆分-打印', '按钮权限：存货拆分模块[打印预览]和[直接打印]按钮的权限', '存货拆分', 'CHCFDY', 206),
('2034', '2034', '工厂', '模块权限：通过菜单进入工厂模块的权限', '工厂', 'GC', 100),
('2034-01', '2034-01', '工厂在业务单据中的使用权限', '数据域权限：工厂在业务单据中的使用权限', '工厂', 'GCCYWDJZDSYQX', 301),
('2034-02', '2034-02', '工厂分类', '数据域权限：工厂模块中工厂分类的数据权限', '工厂', 'GCFL', 300),
('2034-03', '2034-03', '新增工厂分类', '按钮权限：工厂模块[新增工厂分类]按钮权限', '工厂', 'XZGYSFL', 201),
('2034-04', '2034-04', '编辑工厂分类', '按钮权限：工厂模块[编辑工厂分类]按钮权限', '工厂', 'BJGYSFL', 202),
('2034-05', '2034-05', '删除工厂分类', '按钮权限：工厂模块[删除工厂分类]按钮权限', '工厂', 'SCGYSFL', 203),
('2034-06', '2034-06', '新增工厂', '按钮权限：工厂模块[新增工厂]按钮权限', '工厂', 'XZGYS', 204),
('2034-07', '2034-07', '编辑工厂', '按钮权限：工厂模块[编辑工厂]按钮权限', '工厂', 'BJGYS', 205),
('2034-08', '2034-08', '删除工厂', '按钮权限：工厂模块[删除工厂]按钮权限', '工厂', 'SCGYS', 206),
('2035', '2035', '成品委托生产订单', '模块权限：通过菜单进入成品委托生产订单模块的权限', '成品委托生产订单', 'CPWTSCDD', 100),
('2035-01', '2035-01', '成品委托生产订单-新建成品委托生产订单', '按钮权限：成品委托生产订单模块[新建成品委托生产订单]按钮的权限', '成品委托生产订单', 'CPWTSCDDXJCPWTSCDD', 201),
('2035-02', '2035-02', '成品委托生产订单-编辑成品委托生产订单', '按钮权限：成品委托生产订单模块[编辑成品委托生产订单]按钮的权限', '成品委托生产订单', 'CPWTSCDDBJCPWTSCDD', 202),
('2035-03', '2035-03', '成品委托生产订单-删除成品委托生产订单', '按钮权限：成品委托生产订单模块[删除成品委托生产订单]按钮的权限', '成品委托生产订单', 'CPWTSCDDSCCPWTSCDD', 203),
('2035-04', '2035-04', '成品委托生产订单-审核/取消审核', '按钮权限：成品委托生产订单模块[审核]和[取消审核]按钮的权限', '成品委托生产订单', 'CPWTSCDDSHQXSH', 204),
('2035-05', '2035-05', '成品委托生产订单-生成成品委托生产入库单', '按钮权限：成品委托生产订单模块[生成成品委托生产入库单]按钮的权限', '成品委托生产订单', 'CPWTSCDDSCCPWTSCRKD', 205),
('2035-06', '2035-06', '成品委托生产订单-关闭/取消关闭成品委托生产订单', '按钮权限：成品委托生产订单模块[关闭成品委托生产订单]和[取消关闭成品委托生产订单]按钮的权限', '成品委托生产订单', 'CPWTSCDGBJCPWTSCDD', 206),
('2035-07', '2035-07', '成品委托生产订单-单据生成PDF', '按钮权限：成品委托生产订单模块[单据生成PDF]按钮的权限', '成品委托生产订单', 'CPWTSCDDDJSCPDF', 207),
('2035-08', '2035-08', '成品委托生产订单-打印', '按钮权限：成品委托生产订单模块[打印预览]和[直接打印]按钮的权限', '成品委托生产订单', 'CPWTSCDDDY', 208),
('2036', '2036', '成品委托生产入库', '模块权限：通过菜单进入成品委托生产入库模块的权限', '成品委托生产入库', 'CPWTSCRK', 100),
('2036-01', '2036-01', '成品委托生产入库-新建成品委托生产入库单', '按钮权限：成品委托生产入库模块[新建成品委托生产入库单]按钮的权限', '成品委托生产入库', 'CPWTSCRKXJCPWTSCRKD', 201),
('2036-02', '2036-02', '成品委托生产入库-编辑成品委托生产入库单', '按钮权限：成品委托生产入库模块[编辑成品委托生产入库单]按钮的权限', '成品委托生产入库', 'CPWTSCRKBJCPWTSCRKD', 202),
('2036-03', '2036-03', '成品委托生产入库-删除成品委托生产入库单', '按钮权限：成品委托生产入库模块[删除成品委托生产入库单]按钮的权限', '成品委托生产入库', 'CPWTSCRKSCCPWTSCRKD', 203),
('2036-04', '2036-04', '成品委托生产入库-提交入库', '按钮权限：成品委托生产入库模块[提交入库]按钮的权限', '成品委托生产入库', 'CPWTSCRKTJRK', 204),
('2036-05', '2036-05', '成品委托生产入库-单据生成PDF', '按钮权限：成品委托生产入库模块[单据生成PDF]按钮的权限', '成品委托生产入库', 'CPWTSCRKDJSCPDF', 205),
('2036-06', '2036-06', '成品委托生产入库-打印', '按钮权限：成品委托生产入库模块[打印预览]和[直接打印]按钮的权限', '成品委托生产入库', 'CPWTSCRKDY', 206),
('2101', '2101', '会计科目', '模块权限：通过菜单进入会计科目模块的权限', '会计科目', 'KJKM', 100),
('2102', '2102', '银行账户', '模块权限：通过菜单进入银行账户模块的权限', '银行账户', 'YHZH', 100),
('2103', '2103', '会计期间', '模块权限：通过菜单进入会计期间模块的权限', '会计期间', 'KJQJ', 100);

TRUNCATE TABLE `t_role`;
INSERT INTO `t_role` (`id`, `name`, `data_org`, `company_id`, `code`) VALUES
('A83F617E-A153-11E4-A9B8-782BCBD7746B', '系统管理', '01010001', '4D74E1E4-A129-11E4-9B6A-782BCBD7746B', '01');

TRUNCATE TABLE `t_role_permission`;
INSERT INTO `t_role_permission` (`role_id`, `permission_id`) VALUES
('A83F617E-A153-11E4-A9B8-782BCBD7746B', '-8999'),
('A83F617E-A153-11E4-A9B8-782BCBD7746B', '-8997'),
('A83F617E-A153-11E4-A9B8-782BCBD7746B', '-8996'),
('A83F617E-A153-11E4-A9B8-782BCBD7746B', '1001'),
('A83F617E-A153-11E4-A9B8-782BCBD7746B', '1002'),
('A83F617E-A153-11E4-A9B8-782BCBD7746B', '1003'),
('A83F617E-A153-11E4-A9B8-782BCBD7746B', '1004'),
('A83F617E-A153-11E4-A9B8-782BCBD7746B', '1007'),
('A83F617E-A153-11E4-A9B8-782BCBD7746B', '2000'),
('A83F617E-A153-11E4-A9B8-782BCBD7746B', '2001'),
('A83F617E-A153-11E4-A9B8-782BCBD7746B', '2002'),
('A83F617E-A153-11E4-A9B8-782BCBD7746B', '2002-01'),
('A83F617E-A153-11E4-A9B8-782BCBD7746B', '2003'),
('A83F617E-A153-11E4-A9B8-782BCBD7746B', '2004'),
('A83F617E-A153-11E4-A9B8-782BCBD7746B', '2005'),
('A83F617E-A153-11E4-A9B8-782BCBD7746B', '2006'),
('A83F617E-A153-11E4-A9B8-782BCBD7746B', '2007'),
('A83F617E-A153-11E4-A9B8-782BCBD7746B', '2008'),
('A83F617E-A153-11E4-A9B8-782BCBD7746B', '2009'),
('A83F617E-A153-11E4-A9B8-782BCBD7746B', '2010'),
('A83F617E-A153-11E4-A9B8-782BCBD7746B', '2011-01'),
('A83F617E-A153-11E4-A9B8-782BCBD7746B', '2011-02'),
('A83F617E-A153-11E4-A9B8-782BCBD7746B', '2011-03'),
('A83F617E-A153-11E4-A9B8-782BCBD7746B', '2011-04'),
('A83F617E-A153-11E4-A9B8-782BCBD7746B', '2012'),
('A83F617E-A153-11E4-A9B8-782BCBD7746B', '2013'),
('A83F617E-A153-11E4-A9B8-782BCBD7746B', '2014'),
('A83F617E-A153-11E4-A9B8-782BCBD7746B', '2015'),
('A83F617E-A153-11E4-A9B8-782BCBD7746B', '2016'),
('A83F617E-A153-11E4-A9B8-782BCBD7746B', '2017'),
('A83F617E-A153-11E4-A9B8-782BCBD7746B', '2018'),
('A83F617E-A153-11E4-A9B8-782BCBD7746B', '2019'),
('A83F617E-A153-11E4-A9B8-782BCBD7746B', '2020'),
('A83F617E-A153-11E4-A9B8-782BCBD7746B', '2021'),
('A83F617E-A153-11E4-A9B8-782BCBD7746B', '2022'),
('A83F617E-A153-11E4-A9B8-782BCBD7746B', '2023'),
('A83F617E-A153-11E4-A9B8-782BCBD7746B', '2024'),
('A83F617E-A153-11E4-A9B8-782BCBD7746B', '2025'),
('A83F617E-A153-11E4-A9B8-782BCBD7746B', '2026'),
('A83F617E-A153-11E4-A9B8-782BCBD7746B', '2027'),
('A83F617E-A153-11E4-A9B8-782BCBD7746B', '2027-01'),
('A83F617E-A153-11E4-A9B8-782BCBD7746B', '2027-02'),
('A83F617E-A153-11E4-A9B8-782BCBD7746B', '2028'),
('A83F617E-A153-11E4-A9B8-782BCBD7746B', '2029'),
('A83F617E-A153-11E4-A9B8-782BCBD7746B', '2030-01'),
('A83F617E-A153-11E4-A9B8-782BCBD7746B', '2030-02'),
('A83F617E-A153-11E4-A9B8-782BCBD7746B', '2030-03'),
('A83F617E-A153-11E4-A9B8-782BCBD7746B', '2031');

TRUNCATE TABLE `t_role_user`;
INSERT INTO `t_role_user` (`role_id`, `user_id`) VALUES
('A83F617E-A153-11E4-A9B8-782BCBD7746B', '6C2A09CD-A129-11E4-9B6A-782BCBD7746B');

TRUNCATE TABLE `t_user`;
INSERT INTO `t_user` (`id`, `enabled`, `login_name`, `name`, `org_id`, `org_code`, `data_org`, `password`, `py`) VALUES
('6C2A09CD-A129-11E4-9B6A-782BCBD7746B', '1', 'admin', '系统管理员', '5EBDBE11-A129-11E4-9B6A-782BCBD7746B', '019901', '01010001', '21232f297a57a5a743894a0e4a801fc3', 'XTGLY');

TRUNCATE TABLE `t_config`;
INSERT INTO `t_config` (`id`, `name`, `value`, `note`, `show_order`) VALUES
('9000-01', '公司名称', '', '', 100),
('9000-02', '公司地址', '', '', 101),
('9000-03', '公司电话', '', '', 102),
('9000-04', '公司传真', '', '', 103),
('9000-05', '公司邮编', '', '', 104),
('2001-01', '采购入库默认仓库', '', '', 200),
('2002-02', '销售出库默认仓库', '', '', 300),
('2002-01', '销售出库单允许编辑销售单价', '0', '当允许编辑的时候，还需要给用户赋予权限[销售出库单允许编辑销售单价]', 301),
('1003-02', '存货计价方法', '0', '', 401),
('9001-01', '增值税税率', '17', '', 501),
('9002-01', '产品名称', 'PSI', '', 0),
('9003-01', '采购订单单号前缀', 'PO', '', 601),
('9003-02', '采购入库单单号前缀', 'PW', '', 602),
('9003-03', '采购退货出库单单号前缀', 'PR', '', 603),
('9003-04', '销售出库单单号前缀', 'WS', '', 604),
('9003-05', '销售退货入库单单号前缀', 'SR', '', 605),
('9003-06', '调拨单单号前缀', 'IT', '', 606),
('9003-07', '盘点单单号前缀', 'IC', '', 607),
('9003-08', '销售订单单号前缀', 'SO', '', 608);

update t_config set company_id = '4D74E1E4-A129-11E4-9B6A-782BCBD7746B' ;

TRUNCATE TABLE `t_menu_item_h5`;
INSERT INTO `t_menu_item_h5` (`id`, `caption`, `fid`, `parent_id`, `show_order`) VALUES
('01', '销售', NULL, NULL, 1),
('0101', '销售订单', '2028', '01', 1),
('02', '客户关系', NULL, NULL, 2),
('0201', '客户资料', '1007', '02', 1);


TRUNCATE TABLE `t_psi_db_version`;
INSERT INTO `t_psi_db_version` (`db_version`, `update_dt`) VALUES
('20190416-01', now());

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

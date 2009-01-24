<?php
// imports
require_once(WCF_DIR.'lib/system/cache/CacheBuilder.class.php');

class CacheBuilderAdminToolsFunction implements CacheBuilder {
	/**
	 * @see CacheBuilder::getData()
	 */
	public function getData($cacheResource) {
		list($cache, $packageID) = explode('-', $cacheResource['cache']); 
		$data = array();

		// get all functions and filter functions with low priority
		$sql = "SELECT		function.*,  package.packageDir						
			FROM		wcf".WCF_N."_package_dependency package_dependency,
						wcf".WCF_N."_admin_tools_function function					
			LEFT JOIN	wcf".WCF_N."_package package
			ON			(package.packageID = function.packageID)
			WHERE 		function.packageID = package_dependency.dependency
					AND package_dependency.packageID = ".$packageID."
			ORDER BY	package_dependency.priority";
		$result = WCF::getDB()->sendQuery($sql);		
		while ($row = WCF::getDB()->fetchArray($result)) {
			$row['functionClassName'] = StringUtil::getClassName($row['classPath']);
			
			$data[$row['functionID']] = $row;
		}
		
		return $data;
	}
}
?>
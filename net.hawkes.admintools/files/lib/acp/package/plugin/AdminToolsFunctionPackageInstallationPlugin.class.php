<?php
require_once(WCF_DIR.'lib/acp/package/plugin/AbstractOptionPackageInstallationPlugin.class.php');

/**
 * This PIP installs, updates or deletes admin tools functions.
 * 
 * @author	Oliver Kliebisch
 * @copyright	2009 Oliver Kliebisch
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	de.wbb3addons.admintools
 */
class AdminToolsFunctionPackageInstallationPlugin extends AbstractOptionPackageInstallationPlugin {
	public $tagName = 'admintoolsfunction';
	public $tableName = 'admin_tools_option';
	public $functions = array();

	public function __construct(PackageInstallationQueue $installation) {
		parent::__construct($installation);
		
		WCF::getCache()->addResource('admin_tools_functions-'.PACKAGE_ID, WCF_DIR.'cache/cache.admin_tools_functions-'.PACKAGE_ID.'.php', WCF_DIR.'lib/system/cache/CacheBuilderAdminToolsFunction.class.php');
		$functions = WCF::getCache()->get('admin_tools_functions-'.PACKAGE_ID);
		foreach($functions as $ID => $function) {
			$this->functions[$function['function']] = $ID;
		}
	}
	
	/**
	 * Installs admin tools functions, categories and options.
	 */
	public function install() {
		AbstractXMLPackageInstallationPlugin::install();		
		if (!$xml = $this->getXML()) {
			return;
		}
						
		
		// create an array with the import and delete instructions from the xml file
		$optionsXML = $xml->getElementTree('data');

		// install or uninstall categories and options.
		foreach ($optionsXML['children'] as $key => $block) {
			if (count($block['children'])) {
				// handle the import instructions
				if ($block['name'] == 'import') {
					// loop through categories and options
					foreach ($block['children'] as $child) {
						// handle functions
						if ($child['name'] == 'functions') {
							// loop thorugh all functions
							foreach ($child['children'] as $function) {
								
								// check required category name
								if (!isset($function['attrs']['name'])) {
									throw new SystemException("Required 'name' attribute for option function is missing", 13023); 
								}
								
								// default values
								$functionName = $classPath = "";
								$executeAsCronjob = $saveSettings = 0;								
								
								// make xml tags-names (keys in array) to lower case
								$this->keysToLowerCase($function);
								
								// get function data from children
								foreach ($function['children'] as $data) {
									if (!isset($data['cdata'])) continue;
									$function[$data['name']] = $data['cdata'];
								}
								
								// get and secure values
								$functionName =  escapeString($function['attrs']['name']);
								if (isset($function['classpath'])) $classPath = escapeString($function['classpath']);
								if (isset($function['savesettings'])) $saveSettings = intval($function['savesettings']);
								if (isset($function['executeascronjob'])) $executeAsCronjob =  intval($function['executeascronjob']);														

								$functionData = array(
										'function' => $functionName,
										'classPath' => $classPath,
										'saveSettings' => $saveSettings,
										'executeAsCronjob' => $executeAsCronjob
								);
								
								// save function
								$this->saveFunction($functionData, $function);
							}
						}
						// handle categories					
						else if ($child['name'] == 'categories') {
							// loop through all categories
							foreach ($child['children'] as $category) {
								
								// check required category name
								if (!isset($category['attrs']['name'])) {
									throw new SystemException("Required 'name' attribute for option category is missing", 13023); 
								}
								
								// default values
								$categoryName = $parentCategoryName = $permissions = $options = $function = '';
								$showOrder = null;
								$functionID = 0;
								
								// make xml tags-names (keys in array) to lower case
								$this->keysToLowerCase($category);
								
								// get category data from children (parent, showorder, icon and menuicon)
								foreach ($category['children'] as $data) {
									if (!isset($data['cdata'])) continue;
									$category[$data['name']] = $data['cdata'];
								}
								
								// get and secure values
								$categoryName =  escapeString($category['attrs']['name']);
								if (isset($category['permissions'])) $permissions = $category['permissions'];
								if (isset($category['options'])) $options = $category['options'];
								if (isset($category['parent'])) $parentCategoryName =  escapeString($category['parent']);
								if (!empty($category['showorder'])) $showOrder = intval($category['showorder']);
								if (!empty($category['function'])) $function = $category['function'];
								if (!isset($this->functions[$function])) {
									throw new SystemException("Unable to find function with ".(empty($function) ? "empty " : "")."name '".$function."' for category with name '".$categoryName."'.", 13011);
								}
								$functionID = $this->functions[$function];
								if ($showOrder !== null || $this->installation->getAction() != 'update') {
									$showOrder = $this->getShowOrder($showOrder, $parentCategoryName, 'parentCategoryName', '_category');
								}
								
								// if a parent category was set and this parent is not in database 
								// or it is a category from a package from other package environment: don't install further.
								if ($parentCategoryName != '') {
									$sql = "SELECT	COUNT(categoryID) AS count
											FROM	wcf".WCF_N."_".$this->tableName."_category
											WHERE	categoryName = '".escapeString($parentCategoryName)."'";
											/*	AND packageID IN (
													SELECT	dependency
													FROM	wcf".WCF_N."_package_dependency
													WHERE	packageID = ".$this->installation->getPackageID()."
												)";*/
									$parentCategoryCount = WCF::getDB()->getFirstRow($sql);

									// unable to find parent category in dependency-packages: abort installation
									if ($parentCategoryCount['count'] == 0) {
										throw new SystemException("Unable to find parent 'option category' with name '".$parentCategoryName."' for category with name '".$categoryName."'.", 13011);
									}
								}
								
								// save category
								$categoryData = array(
									'categoryName' => $categoryName,
									'parentCategoryName' => $parentCategoryName,
									'showOrder' => $showOrder,
									'permissions' => $permissions,
									'options' => $options,
									'functionID' => $functionID
								);
								$this->saveCategory($categoryData, $category);
							}
						}
						// handle options
						elseif ($child['name'] == 'options') {
							// <option> 
							foreach ($child['children'] as $option) {
								// extract <category> <optiontype> <optionvalue> <visible> etc
								foreach ($option['children'] as $_child) {
									$option[$_child['name']] = $_child['cdata'];
								}
								
								// convert character encoding
								if (CHARSET != 'UTF-8') {
									if (isset($option['defaultvalue'])) {
										$option['defaultvalue'] = StringUtil::convertEncoding('UTF-8', CHARSET, $option['defaultvalue']);
									}
									if (isset($option['selectoptions'])) {
										$option['selectoptions'] = StringUtil::convertEncoding('UTF-8', CHARSET, $option['selectoptions']);
									}
								}
								
								// check required category name
								if (!isset($option['categoryname'])) {
									throw new SystemException("Required category for option is missing", 13023); 
								}
								$categoryName = escapeString($option['categoryname']);
								
								// store option name
								$option['name'] = $option['attrs']['name'];
								
								// children info already stored with name => cdata
								// shrink array 
								unset($option['children']);
								
								if (!preg_match("/^[\w-\.]+$/", $option['name'])) {
									$matches = array();
									preg_match_all("/(\W)/", $option['name'], $matches);
									throw new SystemException("The user option '".$option['name']."' has at least one non-alphanumeric character (underscore is permitted): (".implode("), ( ", $matches[1]).").", 13024); 
								}
								$this->saveOption($option, $categoryName);
							}
						}				
					}
				}
				// handle the delete instructions
				else if ($block['name'] == 'delete' && $this->installation->getAction() == 'update') {
					$functionNames = '';
					$optionNames = '';
					$categoryNames = '';
					foreach ($block['children'] as $deleteTag) {
						// check required attributes
						if (!isset($deleteTag['attrs']['name'])) {
							throw new SystemException("Required 'name' attribute for '".$deleteTag['name']."'-tag is missing", 13023);
						}
						
						if ($deleteTag['name'] == 'function') {
							// build functionnames string
							if (!empty($functionNames)) $functionNames .= ',';
							$functionNames .= "'".escapeString($deleteTag['attrs']['name'])."'";	
						}						
						elseif ($deleteTag['name'] == 'option') {
							// build optionnames string
							if (!empty($optionNames)) $optionNames .= ',';
							$optionNames .= "'".escapeString($deleteTag['attrs']['name'])."'";
						}
						elseif ($deleteTag['name'] == 'optioncategory') {
							// build categorynames string
							if (!empty($categoryNames)) $categoryNames .= ',';
							$categoryNames .= "'".escapeString($deleteTag['attrs']['name'])."'";
						}
					}
					// delete functions
					if (!empty($functionNames)) {
						$this->deleteFunctions($functionNames);	
					}					
					// delete options
					if (!empty($optionNames)) {
						$this->deleteOptions($optionNames);
					}
					// elete categories
					if (!empty($categoryNames)) {
						$this->deleteCategories($categoryNames);
					}
				}
			}
		}
	}

	public function hasUninstall() {
		$hasUninstallOptions = parent::hasUninstall();
		$sql = "SELECT 	COUNT(functionID) AS count
			FROM 	wcf".WCF_N."_admin_tools_function
			WHERE	packageID = ".$this->installation->getPackageID();
		$categoryCount = WCF::getDB()->getFirstRow($sql);
		return ($hasUninstallOptions || $categoryCount['count'] > 0);
	}
	
	public function uninstall() {
		parent::uninstall();
		
		// delete functions
		$sql = "DELETE FROM	wcf".WCF_N."_admin_tools_function
			WHERE		packageID = ".$this->installation->getpackageID();
		WCF::getDB()->sendQuery($sql);
	}
	
	/**
	 * Installs functions.
	 * 
	 * @param 	array	$function
	 * @param	XML		$functionXML
	 */
	protected function saveFunction($function, $functionXML = null) {
	// 	search existing function
		$sql = "SELECT	functionID
			FROM	wcf".WCF_N."_admin_tools_function
			WHERE	function = '".escapeString($function['function'])."'
				AND packageID = ".$this->installation->getPackageID();
		$row = WCF::getDB()->getFirstRow($sql);
		if (empty($row['functionID'])) {
			// insert new functiom
			$sql = "INSERT INTO wcf".WCF_N."_admin_tools_function
											(packageID, function, classPath, saveSettings, executeAsCronjob)
											VALUES (".$this->installation->getPackageID().",
													'".$function['function']."',
													'".$function['classPath']."',
													".$function['saveSettings'].",
													".$function['executeAsCronjob'].")";
			WCF::getDB()->sendQuery($sql);
			$this->functions[$functionName] = WCF::getDB()->getInsertID();
		}
		else {
			// update existing function
			$sql = "UPDATE 	wcf".WCF_N."_admin_tools_function
				SET	classPath = '".escapeString($function['classPath'])."',
					saveSettings = '".intval($function['saveSettings'])."',
					executeAsCronjob = '".intval($function['executeAsCronjob'])."'					
				WHERE	functionID = ".$row['functionID'];
			WCF::getDB()->sendQuery($sql);
		}
	}
	
	/**
	 * Installs option categories.
	 * 
	 * @param 	array		$category
	 * @param	XML		$categoryXML
	 */
	protected function saveCategory($category, $categoryXML = null) {
		// search existing category
		$sql = "SELECT	categoryID
			FROM	wcf".WCF_N."_".$this->tableName."_category
			WHERE	categoryName = '".escapeString($category['categoryName'])."'
				AND packageID = ".$this->installation->getPackageID();
		$row = WCF::getDB()->getFirstRow($sql);
		if (empty($row['categoryID'])) {
			// insert new category
			$sql = "INSERT INTO	wcf".WCF_N."_".$this->tableName."_category
						(packageID, functionID, categoryName, parentCategoryName, permissions, options".($category['showOrder'] !== null ? ",showOrder" : "").")
				VALUES		(".$this->installation->getPackageID().",
						".intval($category['functionID']).",
						'".escapeString($category['categoryName'])."',
						'".escapeString($category['parentCategoryName'])."',
						'".escapeString($category['permissions'])."',
						'".escapeString($category['options'])."'
						".($category['showOrder'] !== null ? ",".$category['showOrder'] : "").")";
			WCF::getDB()->sendQuery($sql);
		}
		else {
			// update existing category
			$sql = "UPDATE 	wcf".WCF_N."_".$this->tableName."_category
				SET	parentCategoryName = '".escapeString($category['parentCategoryName'])."',
					permissions = '".escapeString($category['permissions'])."',
					options = '".escapeString($category['options'])."'
					".($category['showOrder'] !== null ? ",showOrder = ".$category['showOrder'] : "")."
				WHERE	categoryID = ".$row['categoryID'];
			WCF::getDB()->sendQuery($sql);
		}
	}
	
	/**
	 * @see	 AbstractOptionPackageInstallationPlugin::saveOption()
	 */
	protected function saveOption($option, $categoryName, $existingOptionID = 0) {
		// default values
		$optionName = $optionType = $defaultValue = $validationPattern = $selectOptions = $enableOptions = $permissions = $options = '';
		$showOrder = null;
		$hidden = 0; 
		
		// make xml tags-names (keys in array) to lower case
		$this->keysToLowerCase($option);
								
		// get values
		if (isset($option['name'])) $optionName = $option['name'];
		if (isset($option['optiontype'])) $optionType = $option['optiontype'];
		if (isset($option['defaultvalue'])) $defaultValue = WCF::getLanguage()->get($option['defaultvalue']);
		if (isset($option['validationpattern'])) $validationPattern = $option['validationpattern'];
		if (isset($option['enableoptions'])) $enableOptions = $option['enableoptions'];
		if (isset($option['showorder'])) $showOrder = intval($option['showorder']);
		if (isset($option['hidden'])) $hidden = intval($option['hidden']);
		$showOrder = $this->getShowOrder($showOrder, $categoryName, 'categoryName');
		if (isset($option['selectoptions'])) $selectOptions = $option['selectoptions'];
		if (isset($option['permissions'])) $permissions = $option['permissions'];
		if (isset($option['options'])) $options = $option['options'];
		
		// insert or update option
		$sql = "INSERT INTO 			wcf".WCF_N."_".$this->tableName."
							(packageID, optionName,
							categoryName, optionType, 
							optionValue, validationPattern, 
							selectOptions, showOrder,
							enableOptions, hidden,
							permissions, options)
			VALUES				(".$this->installation->getPackageID().", 
							'".escapeString($optionName)."', 
							'".escapeString($categoryName)."', 
							'".escapeString($optionType)."', 
							'".escapeString($defaultValue)."', 
							'".escapeString($validationPattern)."',
							'".escapeString($selectOptions)."',		 
							".intval($showOrder).",
							'".escapeString($enableOptions)."',
							".intval($hidden).",
							'".escapeString($permissions)."',
							'".escapeString($options)."')
			ON DUPLICATE KEY UPDATE		categoryName = VALUES(categoryName), 
							optionType = VALUES(optionType),
							validationPattern = VALUES(validationPattern),
							selectoptions = VALUES(selectOptions),
							showOrder = VALUES(showOrder),
							enableOptions = VALUES(enableOptions),
							hidden = VALUES(hidden),
							permissions = VALUES(permissions),
							options = VALUES(options)";
		WCF::getDB()->sendQuery($sql);
	}
	
	protected function deleteFunctions($functionNames) {
		// delete functions
		$sql = "DELETE FROM	wcf".WCF_N."_admin_tools_function
			WHERE		function IN (".$functionNames.")
			AND 		packageID = ".$this->installation->getPackageID();
		WCF::getDB()->sendQuery($sql);
	}
}
?>
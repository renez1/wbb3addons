<?php
require_once(WCF_DIR.'lib/acp/form/DynamicOptionListForm.class.php');
require_once(WCF_DIR.'lib/acp/admintools/AdminToolsFunctionExecution.class.php');

class AdminToolsFunctionForm extends DynamicOptionListForm {
	public $templateName = 'adminToolsFunction';
	public $activeMenuItem = 'wcf.acp.menu.link.admintools.functions';
	public $cacheName = 'admin_tools-option-';
	public $options;
	public $functions;
	
	public $functionName = '';
	public $functionID = 0;
	public $activeTabMenuItem = '';
	
	
	/**
	 * @see Form::readFormParameters()
	 */
	public function readFormParameters() {
		parent::readFormParameters();
		
		if (isset($_POST['functionName'])) $this->functionName = StringUtil::trim($_POST['functionName']);
		if (isset($_POST['functionID'])) $this->functionID = intval($_POST['functionID']);
		if (isset($_POST['activeTabMenuItem'])) $this->activeTabMenuItem = $_POST['activeTabMenuItem'];		
	}
	
	/**
	 * @see Form::save()
	 */
	public function save() {
		parent::save();
		
		$executor = AdminToolsFunctionExecution::getInstance();
		$executor->setValues($this->values); 		
		$saveOptions = array();
		foreach($this->options as $functionCategory) {
			$function = $this->functions[$functionCategory['functionID']];
			if(!$function['saveSettings']) continue;
			$this->loadActiveOptions($functionCategory['categoryName']);
			$saveOptions = array_merge($saveOptions, $this->activeOptions);
		}				
		$inserts = '';
		foreach ($saveOptions as $option) {
			if (!empty($inserts)) $inserts .= ',';
			$inserts .= "(".$option['optionID'].", '".escapeString($option['optionValue'])."')";						
		}
						
		if (!empty($inserts)) {
			$sql = "INSERT INTO	wcf".WCF_N."_admin_tools_option
						(optionID, optionValue)
				VALUES 		".$inserts."
				ON DUPLICATE KEY UPDATE optionValue = VALUES(optionValue)";
			WCF::getDB()->sendQuery($sql);
			WCF::getCache()->clear(WCF_DIR.'cache/', 'cache.admin_tools-option*');					
		}				
		
		if($this->functionID) {			
			$executor->callFunction($this->functionID);			
		}
		
		WCF::getTPL()->assign(array(
			'success' => true,
		));
	}
	
	public function setValues($values) {
		$this->values = $values;
	}
	
	/**
	 * @see Page::readData()
	 */
	public function readData() {
		parent::readData();
		
		$this->options = $this->getOptionTree();
		if (!count($_POST)) {
			$this->activeTabMenuItem = $this->options[0]['categoryName'];
		}
	}
	
	public function submit() {		
		$this->options = $this->getOptionTree();
		WCF::getCache()->addResource('admin_tools_functions-'.PACKAGE_ID, WCF_DIR.'cache/cache.admin_tools_functions-'.PACKAGE_ID.'.php', WCF_DIR.'lib/system/cache/CacheBuilderAdminToolsFunction.class.php');
		$this->functions = WCF::getCache()->get('admin_tools_functions-'.PACKAGE_ID);
		
		parent::submit();
	}
	
	/**
	 * @see Page::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();
		
		WCF::getTPL()->assign(array(			
			'options' 		=> $this->options,			
			'activeTabMenuItem' 	=> $this->activeTabMenuItem,			
		));				
	}
		
	/**
	 * @see Form::show()
	 */
	public function show() {
		// set active menu item
		WCFACP::getMenu()->setActiveMenuItem($this->activeMenuItem);
		
		// check permission
		//WCF::getUser()->checkPermission($this->permission);
		
		// get user options and categories from cache
		$this->readCache();
		
		// show form
		parent::show();
	}
	
	/**
	 * Creates a list of all active options.
	 *
	 * @param	string		$parentCategoryName
	 */
	protected function loadActiveOptions($parentCategoryName) {
		if (isset($this->cachedOptionToCategories[$parentCategoryName])) {
			foreach ($this->cachedOptionToCategories[$parentCategoryName] as $optionName) {
				if (!$this->checkOption($optionName)) continue;
				$this->activeOptions[$optionName] =& $this->cachedOptions[$optionName];
			}
		}
		if (isset($this->cachedCategoryStructure[$parentCategoryName])) {
			foreach ($this->cachedCategoryStructure[$parentCategoryName] as $categoryName) {
				$this->loadActiveOptions($categoryName);
			}
		}
	}
	
	/**
	 * Returns the tree of options.
	 * 
	 * @param	string		$parentCategoryName
	 * @param	integer		$level
	 * @return	array
	 */
	protected function getOptionTree($parentCategoryName = '', $level = 0) {
		$options = array();
		
		if (isset($this->cachedCategoryStructure[$parentCategoryName])) {
			// get super categories
			foreach ($this->cachedCategoryStructure[$parentCategoryName] as $superCategoryName) {
				$superCategory = $this->cachedCategories[$superCategoryName];
				
				if ($level <= 1) {
					$superCategory['categories'] = $this->getOptionTree($superCategoryName, $level + 1);
				}
				if ($level > 1 || count($superCategory['categories']) == 0) {
					$superCategory['options'] = $this->getCategoryOptions($superCategoryName);
				}
				else {
					$superCategory['options'] = $this->getCategoryOptions($superCategoryName, false);
				}
				
				if ((isset($superCategory['categories']) && count($superCategory['categories']) > 0) || (isset($superCategory['options']) && count($superCategory['options']) > 0)) {
					$options[] = $superCategory;
				}
			}
		}
	
		return $options;
	}
	
	/**
	 * @see DynamicOptionListForm::getTypeObject()
	 */
	protected function getTypeObject($type) {
		if (!isset($this->typeObjects[$type])) {
			$className = 'OptionType'.ucfirst(strtolower($type));
			$classPath = WCF_DIR.'lib/acp/option/'.$className.'.class.php';
			
			// include class file
			if (!file_exists($classPath)) {
				throw new SystemException("unable to find class file '".$classPath."'", 11000);
			}
			require_once($classPath);
			
			// create instance
			if (!class_exists($className)) {
				throw new SystemException("unable to find class '".$className."'", 11001);
			}
			$this->typeObjects[$type] = new $className();
		}
		
		return $this->typeObjects[$type];
	}
}
?>
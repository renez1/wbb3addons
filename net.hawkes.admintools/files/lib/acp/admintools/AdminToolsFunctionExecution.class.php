<?php


class AdminToolsFunctionExecution {
	protected static $instance = null;

	// cache data
	public $cacheName = 'admin_tools-option-';
	public $cacheClass = 'CacheBuilderOption';

	// cache content
	public $cachedCategories = array();
	public $cachedOptions = array();
	public $cachedCategoryStructure = array();
	public $cachedOptionToCategories = array();

	//cached functions
	public $cachedFunctions = array();

	public $options;

	/**
	 * Options of the active category.
	 *
	 * @var array
	 */
	public $activeOptions = array();

	/**
	 * Type object cache.
	 *
	 * @var array
	 */
	public $typeObjects = array();



	protected function __construct() {
		$this->readCache();
		$this->options = $this->getOptionTree();		
	}

	public static function getInstance() {
		if(!self::$instance instanceof self) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function callFunction($functionID, $additionalParameters = array()) {
		$function = $this->cachedFunctions[$functionID];		
		$data = $function;
		$data['parameters'] = array();
		if(isset($this->options[$functionID])) {
			$this->loadActiveOptions($this->options[$functionID]['categoryName']);
			$options = $this->activeOptions;
			$params = array();
			foreach($options as $option) {
				if(!isset($params[$option['categoryName']])) {
					$params[$option['categoryName']] = array();					
				}
				$params[$option['categoryName']][str_ireplace($option['categoryName'].'.', '', $option['optionName'])] = $option['optionValue'];				
			}
			$data['parameters'] = $params;
		}

		$data['parameters'] = array_merge($data['parameters'], $additionalParameters);		
		// get path to class file
		if (empty($function['packageDir'])) {
			$path = WCF_DIR;
		}
		else {
			$path = FileUtil::getRealPath(WCF_DIR.$function['packageDir']);
		}

		$path .= $function['classPath'];

		// include class file of the action
		if (!class_exists($function['functionClassName'])) {
			if (!file_exists($path)) {
				throw new SystemException("Unable to find class file '".$path."'", 11000);
			}
			require_once($path);
		}
			
		// instance action object
		if (!class_exists($function['functionClassName'])) {
			throw new SystemException("Unable to find class '".$action['listenerClassName']."'", 11001);
		}

		$object = new $function['functionClassName'];
		$object->execute($data);
	}
	
	public function setValues($values) {
		$this->values = $values;
		$this->options = $this->getOptionTree();
	}
	
	/**
	 * Gets all options, option categories and functions from cache.
	 */
	protected function readCache() {
		// get cache contents
		$cacheName = $this->cacheName.PACKAGE_ID;
		WCF::getCache()->addResource($cacheName, WCF_DIR.'cache/cache.'.$cacheName.'.php', WCF_DIR.'lib/system/cache/'.$this->cacheClass.'.class.php');
		$this->cachedCategories = WCF::getCache()->get($cacheName, 'categories');
		$this->cachedOptions = WCF::getCache()->get($cacheName, 'options');
		$this->cachedCategoryStructure = WCF::getCache()->get($cacheName, 'categoryStructure');
		$this->cachedOptionToCategories = WCF::getCache()->get($cacheName, 'optionToCategories');

		WCF::getCache()->addResource('admin_tools_functions-'.PACKAGE_ID, WCF_DIR.'cache/cache.admin_tools_functions-'.PACKAGE_ID.'.php', WCF_DIR.'lib/system/cache/CacheBuilderAdminToolsFunction.class.php');
		$this->cachedFunctions = WCF::getCache()->get('admin_tools_functions-'.PACKAGE_ID);
		
		$this->loadActiveOptions('');
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
					$options[$superCategory['functionID']] = $superCategory;
				}
			}
		}

		return $options;
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
	 * Returns an object of the requested option type.
	 *
	 * @param	string			$type
	 * @return	OptionType
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

	/**
	 * Returns a list with the options of a specific option category.
	 *
	 * @param	string		$categoryName
	 * @param	boolean		$inherit
	 * @return	array
	 */
	protected function getCategoryOptions($categoryName = '', $inherit = true) {
		$children = array();

		// get sub categories
		if ($inherit && isset($this->cachedCategoryStructure[$categoryName])) {
			foreach ($this->cachedCategoryStructure[$categoryName] as $subCategoryName) {
				$children = array_merge($children, $this->getCategoryOptions($subCategoryName));
			}
		}

		// get options
		if (isset($this->cachedOptionToCategories[$categoryName])) {
			$i = 0;
			$last = count($this->cachedOptionToCategories[$categoryName]) - 1;
			foreach ($this->cachedOptionToCategories[$categoryName] as $optionName) {
				if (!$this->checkOption($optionName)) continue;

				// get option data				
				$option = $this->activeOptions[$optionName];

				// set default values
				$option['beforeLabel'] = false;

				// get form element htlm
				$option['html'] = $this->getFormElement($option['optionType'], $option);

				// add option to list
				$children[] = $option;

				$i++;
			}
		}

		return $children;
	}

	/**
	 * @see OptionType::getFormElement()
	 */
	protected function getFormElement($type, &$optionData) {
		return $this->getTypeObject($type)->getFormElement($optionData);
	}

	/**
	 * Filters displayed options by specific parameters.
	 *
	 * @param	string		$optionName
	 * @return	boolean
	 */
	protected function checkOption($optionName) {
		return true;
	}
}
?>
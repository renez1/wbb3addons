<?php
require_once(WCF_DIR.'lib/acp/admintools/function/AbstractAdminToolsFunction.class.php');
require_once(WCF_DIR.'lib/acp/option/Options.class.php');
require_once(WCF_DIR.'lib/system/language/LanguageEditor.class.php');


class ClearCacheAdminToolsFunction extends AbstractAdminToolsFunction {

	/**
	 * @see AdminToolsFunction::execute($data)
	 */
	public function execute($data) {
		parent::execute($data);
		
		$parameters = $data['parameters']['clearCache'];				
		if ($parameters['clearWCFCache']) {
			WCF::getCache()->clear(WCF_DIR.'cache', '*.php', true);
		}
		
		if ($parameters['clearStandaloneCache']) {
			$sql = "SELECT packageDir FROM wcf".WCF_N."_package WHERE packageID = ".PACKAGE_ID;
			$row = WCF::getDB()->getFirstRow($sql);
			WCF::getCache()->clear($row['packageDir'].'cache', '*.php', true);
		}
		
		if ($parameters['clearTemplateCache']) {
			require_once(WCF_DIR.'lib/system/template/ACPTemplate.class.php');
			ACPTemplate::deleteCompiledACPTemplates();
			Template::deleteCompiledTemplates();
		}
		
		if ($parameters['clearLanguageCache']) {
			LanguageEditor::deleteLanguageFiles('*', '*', '*');
		}
		
		if ($parameters['clearStandaloneOptions']) {
			Options::resetCache();
			Options::resetFile();
		}				
		
		$this->executed();		
	}
}
?>
<?php
require_once(WCF_DIR.'lib/acp/option/OptionTypeSelect.class.php');
require_once(WCF_DIR.'lib/system/language/LanguageEditor.class.php');

/**
 * OptionTypeLanguageselect is an implementation of OptionType for 'select' tags with languages as dynamic content.
 * 
 * This file is part of Admin Tools 2.
 *
 * Admin Tools 2 is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Admin Tools 2 is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Admin Tools 2.  If not, see <http://www.gnu.org/licenses/>.
 *
 *
 * @author	Oliver Kliebisch
 * @copyright	2009 Oliver Kliebisch
 * @license	GNU General Public License <http://www.gnu.org/licenses/>
 * @package	net.hawkes.optiontype.languageselect
 * @subpackage acp.option
 * @category WCF
 */
class OptionTypeLanguageselect extends OptionTypeSelect {
	/**
	 * @see OptionType::getFormElement()
	 */
	public function getFormElement(&$optionData) {
		if (!isset($optionData['optionValue'])) {
			if (isset($optionData['defaultValue'])) $optionData['optionValue'] = $optionData['defaultValue'];
			else $optionData['optionValue'] = false;
		}
		 
		// get options		
		$options = LanguageEditor::getLanguages();		
		WCF::getTPL()->assign(array(
			'optionData' => $optionData,
			'options' => $options
		));
		return WCF::getTPL()->fetch('optionTypeSelect');
	}
	
}
?>
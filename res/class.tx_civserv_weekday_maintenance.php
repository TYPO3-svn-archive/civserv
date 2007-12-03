<?php 
/***************************************************************
* Copyright notice
*
* (c) 2004 ProService (osiris@ercis.de)
* All rights reserved
*
* This script is part of the TYPO3 project. The TYPO3 project is
* free software; you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation; either version 2 of the License, or
* (at your option) any later version.
*
* The GNU General Public License can be found at
* http://www.gnu.org/copyleft/gpl.html.
* A copy is found in the textfile GPL.txt and important notices to the license
* from the author is found in LICENSE.txt distributed with these scripts.
*
*
* This script is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details.
*
* This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/
/**
* This class provides functionality to updates the label of the given officehour-entry to guarantee the I18N within the weekdays
*
* Some scripts that use this class: ?
* Depends on: ?
*
* $Id$
*
* @author Georg Niemeyer (niemeyer@uni-muenster.de)
* @package TYPO3
* @subpackage tx_civserv
* @version 1.0
*
* Changes:
*/
/**
* [CLASS/FUNCTION INDEX of SCRIPT]
*/

class tx_civserv_weekday_maintenance {

	/**
	* Updates the label of the given officehour-entry to guarantee the I18N within the weekdays
	*
	* @param	string	$params are parameters sent along to alt_doc.php. This requires a much more details description which you must seek in Inside TYPO3s documentation API
	* @return	void
	*/
	function update_labels($params){
		global $LANG;
		$LANG->includeLLFile(t3lib_extMgm::extPath('civserv')."locallang_db.php");
		
#		debug($LANG);
		
#		$GLOBALS['TYPO3_DB']->debugOutput = TRUE;
		if ($params['table']=='tx_civserv_officehours'  && substr($params['uid'],0,3)!='NEW')	{
			$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('oh_weekday','tx_civserv_officehours','uid = '.$params['uid'],'','','');
			$row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
			$GLOBALS['TYPO3_DB']->exec_UPDATEquery('tx_civserv_officehours','uid = '.$params['uid'],array("oh_descr"=>$LANG->getLL('tx_civserv_weekday_'.$row['oh_weekday'])));
		}
	}
	
}
if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/civserv/res/class.tx_civserv_weekday_maintenance.php']) {
	include_once ($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/civserv/res/class.tx_civserv_weekday_maintenance.php']);
}
?>
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
* This class holds some functions used by the TYPO3 backend....
*
* Some scripts that use this class: tca.php (Invocation), ext_tables.php (Definition), ext_localconf.php (Definition)
* Depends on: ?
*
* $Id$
*
* @author Georg Niemeyer (niemeyer@uni-muenster.de)
* @package TYPO3
* @subpackage tx_civserv
* @version 1.0
*
* Changes:	06.08.04, CR - Anpassung an Konventionen
* 			13.08.04, GN - PID wird jetzt ganz sauber und _verst�ndlich_ extrahiert ;-)
*/
/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   63: class tx_civserv_floorbuild
 *   72:     function main(& $params, & $pObj)
 *  100:     function update_pid($params)
 *
 * TOTAL FUNCTIONS: 2
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */
/**
 * This class holds functionality to ensure a consistency within the building-floor-mm-table
 *
 * @author	Georg Niemeyer <niemeyer@uni-muenster.de>
 * @package Extension
 * @subpackage civserv
 */
class tx_civserv_floorbuild {

	/**
	 * Gets valid Building-Floor-combinations and write them back in the $params-Array as items which are shown in the selectorbox under the uid of the mm-entry
	 *
	 * @param	string		$params are parameters sent along to alt_doc.php. This requires a much more details description which you must seek in Inside TYPO3s documentation API
	 * @param	string		$pObj is a reference to the calling object
	 * @return	void
	 */
	function main(& $params, & $pObj) {
		//The Pid ist now extracted from the cachedTSconfig. This seems to be the best way!
		$pid = intval($pObj->cachedTSconfig[$params['table'].':'.$params['row']['uid']]['_CURRENT_PID']);

		// now the EXECUTING-QUERIES-Method is used to get the valid floor-building-combinations for this mandant
		$res = $GLOBALS['TYPO3_DB']->exec_SELECT_mm_query(
			'bl_name, 
			 fl_number, 
			 fl_descr, 
			 tx_civserv_building_bl_floor_mm.uid uid', 		// Field list for SELECT
			'tx_civserv_building', 						// Tablename, local table
			'tx_civserv_building_bl_floor_mm', 				// Tablename, relation table
			'tx_civserv_floor', 							// Tablename, foreign table
			'AND tx_civserv_building_bl_floor_mm.pid='.$pid.' 
			 AND tx_civserv_building.deleted=0 
			 AND tx_civserv_building.hidden=0 
			 AND tx_civserv_floor.deleted=0 
			 AND tx_civserv_floor.hidden=0', 				// Optional additional WHERE clauses
			'', 										// Optional GROUP BY field(s), if none, supply blank string.
			'bl_name, fl_number', 						// Optional ORDER BY field(s), if none, supply blank string.
			'' 										// Optional LIMIT value ([begin,]max), if none, supply blank string.
		);

		//write the building-floor-combinations back in the params-Array (which is shown in the selectorbox)
		while ($data = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
			$params['items'][++ $i] = Array ($data['bl_name'].', '.$data['fl_descr'], $data['uid']);
		}
	}

	/**
	 * Initializes an update of the PID to show it right in the tree structure.
	 * Currently it sets the same PID for all pages
	 *
	 * @param	string		$params are parameters sent along to alt_doc.php. This requires a much more details description which you must seek in Inside TYPO3s documentation API
	 * @return	void
	 */
	function update_pid($params){
		if (is_array($params) && $params['table']== 'tx_civserv_building') {
			$res = $GLOBALS['TYPO3_DB']->exec_SELECT_mm_query(
					'tx_civserv_building_bl_floor_mm.uid, 
					 tx_civserv_building.pid', 
					'tx_civserv_building', 
					'tx_civserv_building_bl_floor_mm', 
					'', 
					'', 
					'', 
					'', 
					'');
			while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
				$GLOBALS['TYPO3_DB']->exec_UPDATEquery('tx_civserv_building_bl_floor_mm', 'uid = '.$GLOBALS['TYPO3_DB']->quoteStr($row['uid'], 'tx_civserv_building_bl_floor_mm'), array ("pid" => $row['pid']));
			}
		}
	}
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/civserv/res/class.tx_civserv_floorbuild.php']) {
	include_once ($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/civserv/res/class.tx_civserv_floorbuild.php']);
}
?>
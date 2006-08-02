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
* Updates some content for Employee-Position-Relationship which have to be made as a workaround for non-existing Typo3 functionality.
*
* Some scripts that use this class: tca.php (Invocation), ext_tables.php (Definition), ext_localconf.php (Definition)
* Depends on: ?
*
* $Id$
*
* @author Christoph Rosenkranz (rosenkra@uni-muenster.de)
* @package TYPO3
* @subpackage tx_civserv
* @version 1.0
*
* Changes: 23.08.04, CR - Initial Build
*		   02.08.04, GN - the main-method isn't needed any longer
*/
/**
* [CLASS/FUNCTION INDEX of SCRIPT]
*
*/

require_once ($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/civserv/res/class.tx_civserv_mandant.php']);

/**
* Updates some content for Employee-Position-Relationship which have to be made as a workaround for non-existing Typo3 functionality.
*/
class tx_civserv_oepupdate {
	
	/**
	* Initializes an update of the PID to show it right in the tree structure.
	* All mm-entries are updated to the corresponding employee's pid
	*
	* @param	string		$params are parameters sent along to alt_doc.php. This requires a much more details description which you must seek in Inside TYPO3s documentation API
	* @return	void
	*/
	function update_pid($params){
		//debug($params, 'jetzt update_pid!' );
		
		if (is_array($params) && ($params['table']== 'tx_civserv_employee' || $params['table']=='tx_civserv_employee_em_position_mm')) {		
			$res = $GLOBALS['TYPO3_DB']->exec_SELECT_mm_query(
			'tx_civserv_employee_em_position_mm.uid, tx_civserv_employee.pid',	//SELECT
			'tx_civserv_employee', 												//LOCAL_TABLE
			'tx_civserv_employee_em_position_mm', 								//MM_TABLE
			'', 																//foreign_table
			'', 																//whereClause
			'', 																//groupBy
			'', 																//orderBy
			'');																//limit
			
			$liste="";
			while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
				$GLOBALS['TYPO3_DB']->exec_UPDATEquery('tx_civserv_employee_em_position_mm', 'uid = '.$GLOBALS['TYPO3_DB']->quoteStr($row['uid'], 'tx_civserv_employee_em_position_mm'), array ("pid" => $row['pid']));
				$liste.=$row['uid'].", ";
			}
		}
	}
	
	/**
	* Updates the label of all employee-position-relations to get a speaking name for mm-relation-entries
	* This is a workaround which is needed in Typo3 at the moment because the labels of a record (defined in ext_tables.php)
	* can only consist of attributes in the same record out of the same table and can't be resolved out of foreign-relations
	*
	* @param	string		$params are parameters sent along to alt_doc.php. This requires a much more details description which you must seek in Inside TYPO3s documentation API
	* @return	void
	*/
function update_label($params){
		// experimental: make function faster by including $params['uid'] in where-clause - if available i.e. uid != 'NEW12345'
		if (is_array($params) && ($params['table']== 'tx_civserv_employee' || $params['table']=='tx_civserv_employee_em_position_mm')) {	
			$res = $GLOBALS['TYPO3_DB']->exec_SELECT_mm_query(
				'tx_civserv_employee_em_position_mm.uid, 
				 tx_civserv_employee.em_name, 
				 tx_civserv_employee.em_firstname, 
				 tx_civserv_position.po_name ',		// SELECT
				'tx_civserv_employee', 				// local table
				'tx_civserv_employee_em_position_mm',	// mm table
				'tx_civserv_position', 				// foreign table
				(substr($params['uid'],0,3)!='NEW')?
					($params['table']=='tx_civserv_employee_em_position_mm')?
						'AND tx_civserv_employee_em_position_mm.uid='.$GLOBALS['TYPO3_DB']->quoteStr($params['uid'], 'tx_civserv_employee_em_position_mm') :
						'AND tx_civserv_employee.uid='.$GLOBALS['TYPO3_DB']->quoteStr($params['uid'], 'tx_civserv_employee')			// where
				: '',	
				'', 
				'', 
				'');
			while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
				$GLOBALS['TYPO3_DB']->exec_UPDATEquery(
					'tx_civserv_employee_em_position_mm', 	// update table
					'uid = '.$GLOBALS['TYPO3_DB']->quoteStr($row['uid'], 'tx_civserv_employee_em_position_mm'),  // where
					array ("ep_label" => $row['em_name'].', '.$row['em_firstname'].' ('.$row['po_name'].')')	// set... (array)
				);
			}
		}
		#if (is_array($params) && ($params['table']== 'tx_civserv_room' || $params['table']=='tx_civserv_employee_em_position_mm')) {	
		if (is_array($params) && ($params['table']== 'tx_civserv_room')) {	
			$where= 'tx_civserv_room.rbf_building_bl_floor = tx_civserv_building_bl_floor_mm.uid 
				 AND tx_civserv_building_bl_floor_mm.uid_local = tx_civserv_building.uid 
				 AND tx_civserv_building_bl_floor_mm.uid_foreign = tx_civserv_floor.uid 
				 AND tx_civserv_building_bl_floor_mm.deleted=0 
				 AND tx_civserv_building_bl_floor_mm.hidden=0 ';
			//make it faster by changing just the room in question: uncomment following line:	 
			#$where.= (substr($params['uid'],0,3)!='NEW') ? 'AND tx_civserv_room.uid = '.$GLOBALS['TYPO3_DB']->quoteStr($params['uid'], 'tx_civserv_room') : '';
		
			$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
				'tx_civserv_room.pid, 
				 tx_civserv_room.uid uid, 
				 tx_civserv_room.ro_name ro_name, 
				 tx_civserv_building.bl_name bl_name, 
				 tx_civserv_floor.fl_descr fl_descr',			// SELECT
				'tx_civserv_room, 
				 tx_civserv_building, 
				 tx_civserv_floor, 
				 tx_civserv_building_bl_floor_mm',				// FROM
				 $where,										// WHERE
				'',												// GROUP_BY
				'bl_name, fl_descr, ro_name', 					// ORDER BY
				'' 												// LIMIT
			);
			
			while ($data = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
				$GLOBALS['TYPO3_DB']->exec_UPDATEquery(		
					'tx_civserv_room',
					'uid = '.$GLOBALS['TYPO3_DB']->quoteStr($data['uid'], 'tx_civserv_room'),  // where
					array ("rbf_label" => $data['ro_name'].' ('.$data['bl_name'].', '.$data['fl_descr'].')')	// set... (array)
				);
			}
		}
	}
	
	/**
	* Shows building and floor in the selectorbox for each room in the Employee-Position-Relationship (this version of
	* Employee-Position-Relationship is a real entity and thus a faked MM-Relation.) Only rooms which have an associated Building-Floor-Relationship
	* are shown.  (column 'ep_room' in TCA)
	*
	* @param	string		$params are parameters sent along to alt_doc.php. This requires a much more details description which you must seek in Inside TYPO3s documentation API
	* @param	string		$pObj is a reference to the calling object
	* @return	void
	*/
	function ep_room(&$params, &$pObj) {
		
		//$GLOBALS['TYPO3_DB']->debugOutput=true;
	
		//The Pid ist now extracted from the cachedTSconfig. This seems to be the best way!
		$pid = intval($pObj->cachedTSconfig[$params['table'].':'.$params['row']['uid']]['_CURRENT_PID']);

		$admin = t3lib_div::makeInstance('tx_civserv_mandant');
		
		$uidAdministration = $admin->get_path($pid,0);
		
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'tx_civserv_room.pid, tx_civserv_room.uid uid, tx_civserv_room.ro_name ro_name, tx_civserv_building.bl_name bl_name, tx_civserv_floor.fl_descr fl_descr',	// SELECT
			'tx_civserv_room, tx_civserv_building, tx_civserv_floor, tx_civserv_building_bl_floor_mm',	// FROM
			'tx_civserv_room.rbf_building_bl_floor = tx_civserv_building_bl_floor_mm.uid AND tx_civserv_building_bl_floor_mm.uid_local = tx_civserv_building.uid AND tx_civserv_building_bl_floor_mm.uid_foreign = tx_civserv_floor.uid AND tx_civserv_building_bl_floor_mm.deleted=0 AND !tx_civserv_building_bl_floor_mm.hidden',	// WHERE
			'',	// GROUP_BY
			'', // ORDER BY
			'' // LIMIT
		);
		
		while ($data = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
			if ($admin->get_path($data['pid'],0)==$uidAdministration)		
				$params['items'][++ $i] = Array ($data['ro_name'].' ('.$data['bl_name'].', '.$data['fl_descr'].')', $data['uid']);
		
		}
	}
	
	/**
	* Shows building and floor in the selectorbox for each room in the Employee-Position-Relationship (this version of
	* Employee-Position-Relationship is a real MM-Relation and thus a faked entity.) Only rooms which have an associated Building-Floor-Relationship
	* are shown. (column 'ep_room' in TCA)
	*
	* @param	string		$params are parameters sent along to alt_doc.php. This requires a much more details description which you must seek in Inside TYPO3s documentation API
	* @param	string		$pObj is a reference to the calling object
	* @return	void
	*/
	function ep_room2(&$params, &$pObj) {
		//The Pid ist now extracted from the cachedTSconfig. This seems to be the best way!
		$pid = intval($pObj->cachedTSconfig[$params['table'].':'.$params['row']['uid']]['_CURRENT_PID']);

		$admin = t3lib_div::makeInstance('tx_civserv_mandant');
		
		if ($pid > 0) $uidAdministration = $admin->get_mandant($pid);
		
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'tx_civserv_room.pid, tx_civserv_room.uid uid, tx_civserv_room.ro_name ro_name, tx_civserv_building.bl_name bl_name, tx_civserv_floor.fl_descr fl_descr',	// SELECT
			'tx_civserv_room, tx_civserv_building, tx_civserv_floor, tx_civserv_building_bl_floor_mm',	// FROM
			'tx_civserv_room.rbf_building_bl_floor = tx_civserv_building_bl_floor_mm.uid AND tx_civserv_building_bl_floor_mm.uid_local = tx_civserv_building.uid AND tx_civserv_building_bl_floor_mm.uid_foreign = tx_civserv_floor.uid AND tx_civserv_building_bl_floor_mm.deleted=0 AND tx_civserv_building_bl_floor_mm.hidden=0',	// WHERE
			'',	// GROUP_BY
			'bl_name, fl_descr, ro_name', // ORDER BY
			'' // LIMIT
		);
		
		while ($data = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
			if ($admin->get_mandant($data['pid'])==$uidAdministration)		
				$params['items'][++ $i] = Array ($data['ro_name'].' ('.$data['bl_name'].', '.$data['fl_descr'].')', $data['uid']);
		
		}
	}
}

/**
* Definition of class (needed for extension)
*/
if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/civserv/res/class.tx_civserv_oepupdate.php']) {
	include_once ($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/civserv/res/class.tx_civserv_oepupdate.php']);
}
?>
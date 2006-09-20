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
* This class maintains the external services passed on by other communities
* and creates speaking labels for the service-position-relation
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
class tx_civserv_service_maintenance {
		
	/**
	* This function maintains the external services for a community which are passed on to it by an other community.
	* Therefore it checks, whether a service configured for pass on is new and should be inserted as a new external service
	* or the a service formally configured for pass on is no longer passed on and should be deleted
	*
	* @param	string		$params are parameters sent along to alt_doc.php. This requires a much more details description which you must seek in Inside TYPO3s documentation API
	* @return	void
	*/
	function transfer_services($params){
		$GLOBALS['TYPO3_DB']->debugOutput = TRUE;
		debug($params, 'tx_civserv_service_maintenance->transfer_services, $params');
		$separator = '### ###';
		if ($params['table']=='tx_civserv_service' && substr($params['uid'],0,3)!='NEW')	{
			//get _all_ services configured to be passed on to other communities
			//why all???
			//test: check only if this service is configured to be passed on!!!
			$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
						'mandant.cm_external_service_folder_uid, 
						 service.uid AS service, 
						 service.pid,
						 service.sv_name', 								// Field list for SELECT
						'tx_civserv_service service, 
						 tx_civserv_service_sv_region_mm sr, 
						 tx_civserv_region region, 
						 tx_civserv_conf_mandant_cm_region_mm mandant_region, 
						 tx_civserv_conf_mandant mandant', 				// from
						'sr.uid_local = service.uid AND 
						 sr.uid_foreign = region.uid AND  
						 service.deleted=0 AND  
						 !service.hidden AND 
						 mandant_region.uid_foreign = region.uid AND 
						 mandant_region.uid_local = mandant.uid AND 
						 mandant.cm_external_service_folder_uid > 0 AND
						 service.uid='.$params['uid'],	// WHERE clauses
						'', 											// Optional GROUP BY field(s), if none, supply blank string.
						'', 											// Optional ORDER BY field(s), if none, supply blank string.
						'' 												// Optional LIMIT value ([begin,]max), if none, supply blank string.
				);
			$new_services = array();
			// test: highlight_external! show community for external services!
			$mandant = t3lib_div::makeInstanceClassName('tx_civserv_mandant');
			$mandantInst = new $mandant();
			while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
				$service_community_name = $mandantInst->get_mandant_name($row['pid']);
				#$service_community_name = "Rumpelstiltskin";
				$new_services[]=	$row['cm_external_service_folder_uid'].
									$separator.
									$row['service'].
									$separator.
									$row['sv_name']." (".$service_community_name.")";
			}			
			//get all already existing external services for all mandants
			$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('pid, es_external_service, es_name',	// Field list for SELECT
				'tx_civserv_external_service  service', 										// Tablename, local table
				'service.deleted=0', 															// Optional additional WHERE clauses
				'', 																			// Optional GROUP BY field(s), if none, supply blank string.
				'', 																			// Optional ORDER BY field(s), if none, supply blank string.
				'' 																				// Optional LIMIT value ([begin,]max), if none, supply blank string.
			);
			$old_services = array();		
			while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
				$old_services[]=	$row['pid'].
									$separator.
									$row['es_external_service'].
									$separator.
									$row['es_name'];
			}
			// insert the service as external service if the new service isn't available yet
			$new_ones = array_diff($new_services, $old_services);
			$row = array();
			foreach($new_ones as $value){
				$new = explode($separator,$value);
				$row['hidden']=1;
				$row['es_external_service']=$new[1];
				$row['es_name']=$new[2];
				$row['pid']=$new[0];
				// new_services which are not in old_services are inserted
				$GLOBALS['TYPO3_DB']->exec_INSERTquery('tx_civserv_external_service',$row);
			}
			
			// delete an external service if an old service isn't any longer configurated for pass on
			// old_services which are not in new_services
			$old_ones = array_diff($old_services, $new_services);
			foreach($old_ones as $value){
				$old = explode($separator,$value);
				$GLOBALS['TYPO3_DB']->exec_DELETEquery('tx_civserv_external_service','pid = '.$old[0].' AND es_external_service='.$old[1]); 
			}
		}
	}
	
	/**
	* Updates the label of all service-position-relations to get a speaking name for mm-relation-entries
	* This is a workaround which is needed in Typo3 at the moment because the labels of a record (defined in ext_tables.php)
	* can only consist of attributes in the same record out of the same table and can't be resolved out of foreign-relations
	*
	* @param	string		$params are parameters sent along to alt_doc.php. This requires a much more details description which you must seek in Inside TYPO3s documentation API
	* @return	void
	*/
	function update_position(&$params){
		debug($params, 'civserv/res/tx_civserv_service_maintenance->update_position: params');
		$labels=array();
		$i=0;
		#if (is_array($params) && ($params['table']=='tx_civserv_service' || $params['table']=='tx_civserv_service_sv_position_mm')) {	
		if (is_array($params) && ($params['table']=='tx_civserv_service' && substr($params['uid'],0,3)!='NEW')) {	
			$res = $GLOBALS['TYPO3_DB']->exec_SELECT_mm_query(
				'tx_civserv_service_sv_position_mm.uid as svpos_uid, 	
				 tx_civserv_position.uid as pos_uid, 
				 sv_name, 
				 po_name, 
				 tx_civserv_service.pid',				// select
				'tx_civserv_service', 					// local
				'tx_civserv_service_sv_position_mm',	// mm
				'tx_civserv_position', 					// foreign
				#'',									// where --> all records
				' AND uid_local='.$params['uid'],		// where --> only this record!
				'', 
				'', 
				'');
			while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
				#$GLOBALS['TYPO3_DB']->exec_UPDATEquery('tx_civserv_service_sv_position_mm', 'uid = '.intval($row['svpos_uid']), array ("sp_label" => $row['sv_name'].', '.$row['po_name'], "pid" => $row['pid']));
				$labels[$i]['svpos_uid']=$row['svpos_uid'];
				$labels[$i]['pos_uid']=$row['pos_uid'];
				$labels[$i]['sv_name']=$row['sv_name'];
				$labels[$i]['po_name']=$row['po_name'];
				$labels[$i]['pid']=$row['pid'];
				$labels[$i]['names']=array('unbekannt');
				$em_count=0;
				$res2 = $GLOBALS['TYPO3_DB']->exec_SELECT_mm_query(
						'tx_civserv_employee.em_name',			// select
						'tx_civserv_employee', 					// local
						'tx_civserv_employee_em_position_mm',	// mm
						'tx_civserv_position', 					// foreign	
						' AND tx_civserv_position.uid='.$row['pos_uid'], 	// where
						'', 
						'', 
						'');
				while ($row2 = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res2)) {
					$labels[$i]['names'][$em_count]=$row2['em_name'];
					$em_count++;
				}
				$i++;
			}
			foreach($labels as $label){
				$GLOBALS['TYPO3_DB']->exec_UPDATEquery('tx_civserv_service_sv_position_mm', 'uid = '.intval($label['svpos_uid']), array ("sp_label" => $label['sv_name'].', '.$label['po_name'].' ('.implode(',',$label['names']).')', "pid" => $label['pid']));
			}
		}
	}
}
if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/civserv/res/class.tx_civserv_service_maintenance.php']) {
	include_once ($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/civserv/res/class.tx_civserv_service_maintenance.php']);
}
?>
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

class tx_civserv_service_maintenance{
		
	/**
	* This function maintains the external services for a community which are passed on to it by an other community.
	* Therefore it checks, whether a service configured for pass on is new and should be inserted as a new external service
	* or the a service formally configured for pass on is no longer passed on and should be deleted
	*
	* @param	string		$params are parameters sent along to alt_doc.php. This requires a much more details description which you must seek in Inside TYPO3s documentation API
	* @return	void
	*/
	function transfer_services($params){
		global $LANG;
		$GLOBALS['TYPO3_DB']->debugOutput = TRUE;
		$LANG->includeLLFile("EXT:civserv/res/locallang_region_workflow.php");
		$separator = '### ###';
		if ($params['table']=='tx_civserv_service' && substr($params['uid'],0,3)!='NEW')	{
			//ercis: get _all_ services configured to be passed on to other communities
			//why all???
			//test citeq: check only if _this_ service is configured to be passed on!!!
			
			// the query below collects the uids of the external-Service-Folders of all the communities 
			// that have been selected in the region-field of the service
			$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
						'mandant.cm_external_service_folder_uid, 
						 mandant.cm_community_name,
						 mandant.cm_target_email,
						 service.uid AS service, 
						 service.pid,
						 service.sv_name,
						 pages.title as es_folder_name',
						'tx_civserv_service service, 
						 tx_civserv_service_sv_region_mm sr, 
						 tx_civserv_region region, 
						 tx_civserv_conf_mandant_cm_region_mm mandant_region, 
						 tx_civserv_conf_mandant mandant,
						 pages',
						'sr.uid_local = service.uid AND 	
						 sr.uid_foreign = region.uid AND  
						 service.deleted=0 AND  
						 !service.hidden AND 
						 mandant_region.uid_foreign = region.uid AND 
						 mandant_region.uid_local = mandant.uid AND 
						 mandant.cm_external_service_folder_uid > 0 AND 
						 pages.uid = mandant.cm_external_service_folder_uid', 	//all services!
						 # AND service.uid='.$params['uid'],					//only the one service beeing saved at this moment
						'', 											// Optional GROUP BY field(s), if none, supply blank string.
						'', 											// Optional ORDER BY field(s), if none, supply blank string.
						'' 												// Optional LIMIT value ([begin,]max), if none, supply blank string.
				);
			$new_services = array();
			// test: highlight_external! show community for external services!
			$mandant = t3lib_div::makeInstanceClassName('tx_civserv_mandant');
			$mandantInst = new $mandant();
			
			// in the end I want to compare the array key of new_services and old_services.
			// to make the unique I make a combination of service_uid and external_service_folder
			$separator="_";
			
			while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
				$service_community=array();
				$service_community['cm_uid'] = $mandantInst->get_mandant($row['pid']); //pid of the services where it originally resides
				$res_service_community = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
					'*',
					'tx_civserv_conf_mandant',
					'cm_community_id='.$service_community['cm_uid'],
					'',
					'',
					''
				);
				if($row_service_community = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res_service_community)){
					$service_community['name']=$row_service_community['cm_community_name'];
					$service_community['previewpage']=$row_service_community['cm_page_uid'];
					$service_community['cm_community_id']=$row_service_community['cm_community_id'];
				}
				$key=$row['service'].$separator.$row['cm_external_service_folder_uid'];
				$new_services[$key]['es_folder_uid']=				$row['cm_external_service_folder_uid'];	
				$new_services[$key]['es_original_uid']=				$row['service'];
				$new_services[$key]['es_label']=					$row['sv_name']." (".$service_community['name'].")";
				$new_services[$key]['es_name']=						$row['sv_name'];
				$new_services[$key]['receiving_mandant_name']=		$row['cm_community_name'];
				$new_services[$key]['receiving_mandant_email']=		$row['cm_target_email'];
				$new_services[$key]['es_folder_name']=				$row['es_folder_name'];
				$new_services[$key]['service_community_gkz']=		$service_community['cm_community_id'];
				$new_services[$key]['service_community_name']=		$service_community['name'];
				$new_services[$key]['service_community_previewpage']=$service_community['previewpage'];
				$new_services[$key]['service_community_cm_uid']=$service_community['cm_uid'];
			}// end while
			//ercis: get _all_ already existing external services for _all_ mandants
			$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
				'service.pid, 
				 service.es_external_service, 
				 service.es_name,
				 mandant.cm_community_name,
				 mandant.cm_target_email,
				 pages.title as folder_name',
				'tx_civserv_external_service  service,
				 tx_civserv_conf_mandant mandant,
				 pages', 										
				'service.deleted=0 AND
				 mandant.cm_external_service_folder_uid = service.pid AND 
				 pages.uid = service.pid', 															// Optional additional WHERE clauses
				'', 																			// Optional GROUP BY field(s), if none, supply blank string.
				'', 																			// Optional ORDER BY field(s), if none, supply blank string.
				'' 																				// Optional LIMIT value ([begin,]max), if none, supply blank string.
			);
			$old_services = array();
			while ($row_old_sv = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
				$key=$row_old_sv['es_external_service'].$separator.$row_old_sv['pid'];
				$old_services[$key]['es_folder_uid']=	$row_old_sv['pid'];
				$old_services[$key]['es_original_uid']=	$row_old_sv['es_external_service'];
				$old_services[$key]['es_label']=		$row_old_sv['es_name'];
				$old_services[$key]['es_name']=						substr($row_old_sv['es_name'],0,strpos($row_old_sv['es_name'],"("));
				$old_services[$key]['receiving_mandant_name']=		$row_old_sv['cm_community_name'];
				$old_services[$key]['receiving_mandant_email']=		$row_old_sv['cm_target_email'];
				$old_services[$key]['es_folder_name']=				$row_old_sv['folder_name'];
				$old_services[$key]['service_community_name']=		substr($row_old_sv['es_name'],strpos($row_old_sv['es_name'],"(")+1, strlen($row_old_sv['es_name'])-2);
			}
			// now I have two arrays which I can compare!
			// new_ones are the ones which are in new_services but not in old_services
			// old_ones are the ones which are in old_services but not in new_services
			
			$new_ones=array();
			foreach($new_services as $key => $values){
				if (array_key_exists($key, $old_services)) {
					//
				}else{
					$new_ones[$key]=$values;
				}
			}
			#debug($new_ones, 'new_ones (are going to be inserted)');	
			
			$row_new_sv = array();
			foreach($new_ones as $new_one){
				$new = explode($separator,$value);
				$row_new_sv['hidden']=1;
				$row_new_sv['es_external_service']=$new_one['es_original_uid'];
				$row_new_sv['es_name']=$new_one['es_label'];
				$row_new_sv['pid']=$new_one['es_folder_uid'];
				// new_services which are not in old_services are inserted
				$GLOBALS['TYPO3_DB']->exec_INSERTquery('tx_civserv_external_service',$row_new_sv);
				
				
				//emails
				$text = str_replace("###mandant_name###", $new_one['receiving_mandant_name'], $LANG->getLL("xyz.emailmessage.header"));
				
				$search = array(	"sv_name" => "###service_name###",	"sv_community" => "###service_community###",			"es_folder" => "###external_service_folder###",);
				$replace = array(	"sv_name" => $new_one['es_name'],	"sv_community" => $new_one['service_community_name'],	"es_folder" => $new_one['es_folder_name']);
				$text .= str_replace($search, $replace, $LANG->getLL("xyz.emailmessage.service_new"));
				
				$service_link='http://typo3vm.citeq.de/osiris_svnb/index.php?id='.$new_one['service_community_previewpage'].'&tx_civserv_pi1[community_id]='.$new_one['service_community_cm_uid'].'&tx_civserv_pi1[mode]=service&tx_civserv_pi1[id]='.$params['uid'].'&no_cache=1';
				$text .= str_replace('###service_link###', $service_link, $LANG->getLL("xyz.emailmessage.service_link"));

				$search = array(	"es_folder" => "###external_service_folder###",	"sv_community" => "###service_community###");
				$replace = array(	"es_folder" => $new_one['es_folder_name'],		"sv_community" => $new_one['service_community_name']);
				$text .= str_replace($search, $replace, $LANG->getLL("xyz.emailmessage.order"));

				$fr_res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('cf_value','tx_civserv_configuration','cf_key = "email_from"','','','');
				$from_res = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($fr_res);
				$from = "From: ".$from_res['cf_value'];
				
				$subject = str_replace("###service_name###", $new_one['es_name'], $LANG->getLL("xyz.emailmessage.subject"));
				$subject .= " (".$new_one['receiving_mandant_name'].")";		
				if (t3lib_div::validEmail($to=$new_one['receiving_mandant_email'])){
					#debug($text);
					t3lib_div::plainMailEncoded($to,$subject,$text,$from);	
				}
			}
			
			// delete an external service if an old service isn't any longer configurated for pass on
			// old_services which are not in new_services

			$old_ones=array();
			foreach($old_services as $key => $values){
				if (array_key_exists($key, $new_services)) {
					//
				}else{
					$old_ones[$key]=$values;
				}
			}
			#debug($old_ones, 'old_ones (are going to be deleted)');
			foreach($old_ones as $old_one){
				$GLOBALS['TYPO3_DB']->exec_DELETEquery('tx_civserv_external_service','pid = '.$old_one['es_folder_uid'].' AND es_external_service='.$old_one['es_original_uid']); 
				
				//emails
				$text = str_replace("###mandant_name###", $old_one['receiving_mandant_name'], $LANG->getLL("xyz.emailmessage.header"));
				
				$search = array(	"sv_name" => "###service_name###",	"sv_community" => "###service_community###",			"es_folder" => "###external_service_folder###");
				$replace = array(	"sv_name" => $old_one['es_name'],	"sv_community" => $old_one['service_community_name'],	"es_folder" => $old_one['es_folder_name']);
				$text .= str_replace($search, $replace, $LANG->getLL("xyz.emailmessage.service_deleted"));
		
				$fr_res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('cf_value','tx_civserv_configuration','cf_key = "email_from"','','','');
				$from_res = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($fr_res);
				$from = "From: ".$from_res['cf_value'];
				
				$subject = str_replace("###service_name###", $old_one['es_name'], $LANG->getLL("xyz.emailmessage.subject"));
				$subject .= " (".$old_one['receiving_mandant_name'].")";		
				if (t3lib_div::validEmail($to=$old_one['receiving_mandant_email'])){
					#debug($text);
					t3lib_div::plainMailEncoded($to,$subject,$text,$from);	
				}
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
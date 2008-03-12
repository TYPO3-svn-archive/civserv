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
	echo "<script type=\"text/javascript\">alert('du eimer!');</script>";
		global $LANG;
		$GLOBALS['TYPO3_DB']->debugOutput = TRUE;
		$LANG->includeLLFile("EXT:civserv/res/locallang_region_workflow.php");
		$actual_site = str_replace(t3lib_div::getIndpEnv('TYPO3_REQUEST_HOST'), '', t3lib_div::getIndpEnv('TYPO3_SITE_URL'));
		$actual_site = str_replace('/', '\'', $actual_site);
		//will divide the uid-vals in $old_services and in $new_services
		$separator="_";

		
		//we'll need the mandant_obj later on
		$mandant_obj = t3lib_div::makeInstance('tx_civserv_mandant');
		
		
		if ($params['table']=='tx_civserv_service' && substr($params['uid'],0,3)!='NEW')	{
		
			// first get the (potentially) new services!
			// the query below collects the uids of the external-Service-Folders of all 
			// the communities that have been selected in the region-fields of all the services
			$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
						'mandant.cm_external_service_folder_uid,
						 service.uid AS service_uid, 
						 service.pid AS service_pid', 		  // select
						'tx_civserv_service service, 
						 tx_civserv_service_sv_region_mm sr, 
						 tx_civserv_region region, 
						 tx_civserv_conf_mandant_cm_region_mm mandant_region, 
						 tx_civserv_conf_mandant mandant,
						 pages',								// from
						'sr.uid_local = service.uid AND 	
						 sr.uid_foreign = region.uid AND  
						 service.deleted=0 AND  
						 service.hidden=0 AND 
						 mandant_region.uid_foreign = region.uid AND 
						 mandant_region.uid_local = mandant.uid AND 
						 mandant.cm_external_service_folder_uid > 0 AND 
						 pages.uid = mandant.cm_external_service_folder_uid',	// where: all services! very time consuming!, 										// where-clause, see above
						'', 											// Optional GROUP BY field(s), if none, supply blank string.
						'', 											// Optional ORDER BY field(s), if none, supply blank string.
						'' 												// Optional LIMIT value ([begin,]max), if none, supply blank string.
				);
			$new_services = array();
			while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
				if($row['service_pid'] > 0){ // or else its a versioned service record
#					debug($row, 'new service row'); //don't! there's too many of them!
					$new_services[] = $row['service_uid'].$separator.$row['cm_external_service_folder_uid'];
				}// end if
			}// end while
			
			
			
			// now get all the old services (they reside in table tx_civserv_external_service)!
			// what it does is --> get _all_ already existing external services for _all_ mandants!
			// field es_external_service stores the uid of the service from table tx_civserv_service
			$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
				'tx_civserv_external_service.pid, 
				 tx_civserv_external_service.es_external_service',													// select
				'tx_civserv_external_service,
				 tx_civserv_conf_mandant,
				 pages', 																		// from			
				'tx_civserv_external_service.deleted=0 
				 AND tx_civserv_conf_mandant.cm_external_service_folder_uid = tx_civserv_external_service.pid 
				 AND pages.uid = tx_civserv_external_service.pid', 														// WHERE clauses
				'', 																			// Optional GROUP BY field(s), if none, supply blank string.
				'', 																			// Optional ORDER BY field(s), if none, supply blank string.
				'' 																				// Optional LIMIT value ([begin,]max), if none, supply blank string.
			);
			$old_services = array();
			while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
				$old_services[] = $row['es_external_service'].$separator.$row['pid'];
			}
			// now we have two arrays which we may compare!
			// new_ones are the ones which are in new_services but not in old_services
			// old_ones are the ones which are in old_services but not in new_services
			debug(count($old_services), 'old_services');
			debug(count($new_services), 'new_services');
			
			
			/*
			//we already did that, see above
			
			// prep for retreieving service_community
			$mandant = t3lib_div::makeInstanceClassName('tx_civserv_mandant');
			$mandantInst = new $mandant();
			
			*/
			
			
			$new_ones = array();
			$new_ones = array_diff($new_services, $old_services);
			debug(count($new_ones), 'new_ones');
			
			foreach($new_ones as $value){
				$new_one = array();
				$insert_row =array();
				$new = explode($separator,$value);
				
				$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
						'receiver.cm_community_name as receiver_name,
						 receiver.cm_community_id as receiver_community_id,
						 receiver.cm_target_email as receiver_email,
						 service.sv_name,
						 service.pid,
						 pages.title as es_folder_name', // select (this is the receiving mandant)
						'tx_civserv_service service, 
						 tx_civserv_conf_mandant receiver,
						 pages',
						'service.uid = '.$new[0].' AND
						 service.deleted=0 AND  
						 service.hidden=0 AND 
						 receiver.cm_external_service_folder_uid = '.$new[1].' AND 
						 pages.uid = '.$new[1],	// where: only one!
						'', 					// Optional GROUP BY field(s), if none, supply blank string.
						'', 					// Optional ORDER BY field(s), if none, supply blank string.
						'' 						// Optional LIMIT value ([begin,]max), if none, supply blank string.
				);
				while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) { // darf nur 1 bei rauskommen
					debug('HURRAY');
					$new_one['es_folder_uid'] =	$new[1];
					$new_one['es_original_uid'] = $new[0];
					$new_one['sv_name'] = $row['sv_name'];
					$new_one['receiver_name'] =	$row['receiver_name'];
					$new_one['receiver_community_id'] = $row['receiver_community_id'];
					$new_one['receiver_email'] = $row['receiver_email'];
					$new_one['es_folder_name'] = $row['es_folder_name'];
					$new_one['es_original_pid'] = $row['pid'];
				
				    debug($new_one, '$new_one SO FAR');
				
					$new_one['donator_community_id'] = $mandant_obj->get_mandant($new_one['es_original_pid']); //pid of the services where it originally resides
					$new_one['donator_name'] = $mandant_obj->get_mandant_name($new_one['es_original_pid']);
					$new_one['donator_previewpage'] = $mandant_obj->get_mandant_preview_page($new_one['es_original_pid']);
					$new_one['es_name'] = $new_one['sv_name']." (".$new_one['donator_name'].")";
					
					debug($new_one, '$new_one');
					
					// we only need some of the above data for our insert (the rest goes into the emails)
					$insert_row['hidden'] = 1;
					$insert_row['es_external_service'] = $new[0];
					$insert_row['pid'] = $new[1];
					$insert_row['es_name'] = $new_one['es_name'];
					
					// new_services which are not in old_services are inserted
					$GLOBALS['TYPO3_DB']->exec_INSERTquery('tx_civserv_external_service',$insert_row);
					
					
					// write emails
					// header
					$search = array(	"site" => "###sitename###",		"mandant" => "###mandant_name###");
					$replace = array(	"site" => $actual_site,			"mandant" => $new_one['receiver_name']);
					$text = str_replace($search, $replace, $LANG->getLL("xyz.emailmessage.header"));
	
					//servic_new
					$search = array(	"sv_name" => "###service_name###",	"sv_community" => "###service_community###",			"es_folder" => "###external_service_folder###",);
					$replace = array(	"sv_name" => $new_one['es_name'],	"sv_community" => $new_one['donator_name'],				"es_folder" => $new_one['es_folder_name']);
					$text .= str_replace($search, $replace, $LANG->getLL("xyz.emailmessage.service_new"));
					
					// service_link
					// to do: point this link to the receiving mandant's previewpage alternatively
					$service_link=t3lib_div::getIndpEnv('TYPO3_SITE_URL').'/index.php?id='.$new_one['donator_previewpage'].'&tx_civserv_pi1[community_id]='.$new_one['donator_community_id'].'&tx_civserv_pi1[mode]=service&tx_civserv_pi1[id]='.$params['uid'].'&no_cache=1';
					$text .= str_replace('###service_link###', $service_link, $LANG->getLL("xyz.emailmessage.service_link"));
	
	
					// service order
					$search = array(	"es_folder" => "###external_service_folder###",	"sv_community" => "###service_community###");
					$replace = array(	"es_folder" => $new_one['es_folder_name'],		"sv_community" => $new_one['donator_name']);
					$text .= str_replace($search, $replace, $LANG->getLL("xyz.emailmessage.order"));
	
					// email from
					$fr_res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('cf_value','tx_civserv_configuration','cf_key = "email_from"','','','');
					$from_res = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($fr_res);
					$from = "From: ".$from_res['cf_value'];
					
					// email subject
					$search = array(	"sv_name" => "###service_name###",	"es_community" => "###external_service_community###");
					$replace = array(	"sv_name" => $new_one['es_name'],	"es_community" => $new_one['receiver_name']);
					$subject = str_replace($search, $replace, $LANG->getLL("xyz.emailmessage.subject"));
					
					// send it
					if (t3lib_div::validEmail($to=$new_one['receiver_email'])){
						t3lib_div::plainMailEncoded($to,$subject,$text,$from);	
					}
				}// row new_one
			}// foreach 
			
			
			
			// delete an external service if an old service isn't any longer configurated for pass on
			// old_services which are not in new_services
			$old_ones = array();
			// $old_ones[0] contains the original uid of the service!!
			// $old_ones[1] contains the pid of the external service
			$old_ones = array_diff($old_services, $new_services);
			debug(count($old_ones), 'old_ones');


			foreach($old_ones as $value){
				$old_one=array();
				$old = explode($separator,$value);
				// $old[0] contains the original uid of the service
				// $old[1] contains the pid of the external service
				$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
					'tx_civserv_external_service.uid as es_uid,
					 tx_civserv_external_service.es_name,
					 tx_civserv_service.sv_name,
					 receiver.cm_community_id as receiver_community_id,
					 receiver.cm_community_name as receiver_name,
					 receiver.cm_target_email as receiver_email,
					 pagetree_donator.uid as donator_sv_pid,
					 pagetree_receiver.title as es_folder_name',
					'tx_civserv_external_service,
					 tx_civserv_conf_mandant receiver,
					 tx_civserv_service,
					 pages pagetree_receiver,
					 pages pagetree_donator', 										
					'tx_civserv_external_service.es_external_service = '.$old[0].'
					 AND tx_civserv_external_service.pid = '.$old[1].' 
					 AND tx_civserv_external_service.deleted = 0 
					 AND receiver.cm_external_service_folder_uid = '.$old[1].' 
					 AND pagetree_receiver.uid = tx_civserv_external_service.pid
					 AND tx_civserv_service.uid = '.$old[0].'
					 AND tx_civserv_service.pid = pagetree_donator.uid', 														// Optional additional WHERE clauses
					'', 																			// Optional GROUP BY field(s), if none, supply blank string.
					'', 																			// Optional ORDER BY field(s), if none, supply blank string.
					'' 																				// Optional LIMIT value ([begin,]max), if none, supply blank string.
				);
				while($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) { //this should happen only once
					$old_one['es_pid'] = $old[1];
					$old_one['es_original_uid'] = $old[0];
					$old_one['es_uid'] = $row['es_uid'];
					$old_one['es_name'] = $row['es_name'];
					$old_one['es_folder_name'] = $row['es_folder_name'];  //not very interesting, they're all alike
					
					$old_one['sv_name'] = $row['sv_name'];
					
					$old_one['receiver_community_id'] = $row['receiver_community_id'];
					$old_one['receiver_name'] =	$row['receiver_name'];
					$old_one['receiver_email'] = $row['receiver_email'];
					
					$old_one['donator_name'] = $mandant_obj->get_mandant_name($row['donator_sv_pid']);		
					$old_one['donator_community_id'] = $mandant_obj->get_mandant($row['donator_sv_pid']);					
					
					debug($old_one, 'Old external service to be deleted');
					$GLOBALS['TYPO3_DB']->exec_DELETEquery(	'tx_civserv_external_service', 'pid = '.$old[1].' AND es_external_service='.$old[0]); 
					
					// write emails
					// email_header
					$search = array(	"site" => "###sitename###",		"mandant" => "###mandant_name###");
					$replace = array(	"site" => $actual_site,			"mandant" => $old_one['receiver_name']);
					$text = str_replace($search, $replace, $LANG->getLL("xyz.emailmessage.header"));
					
					// service_deleted
					$search = array(	"sv_name" => "###service_name###",	"sv_community" => "###service_community###",		"es_folder" => "###external_service_folder###");
					$replace = array(	"sv_name" => $old_one['sv_name'],	"sv_community" => $old_one['donator_name'],			"es_folder" => $old_one['es_folder_name']);
					$text .= str_replace($search, $replace, $LANG->getLL("xyz.emailmessage.service_deleted"));
			
					// email from
					$fr_res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('cf_value','tx_civserv_configuration','cf_key = "email_from"','','','');
					$from_res = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($fr_res);
					$from = "From: ".$from_res['cf_value'];
					
					// email subject
					$search = array(	"sv_name" => "###service_name###",	"es_community" => "###external_service_community###");
					$replace = array(	"sv_name" => $old_one['es_name'],	"es_community" => $old_one['receiver_name']);
					$subject = str_replace($search, $replace, $LANG->getLL("xyz.emailmessage.subject"));
					
					// send it!
					if (t3lib_div::validEmail($to=$old_one['receiver_email'])){
						t3lib_div::plainMailEncoded($to,$subject,$text,$from);	
					}
				}//row old_one
			}// for each
		}// if ($params['table']=='tx_civserv_service'
	}// end function
	
	
	
	
	
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
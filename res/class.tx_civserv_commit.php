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
* This class holds the central function to ensure the consistency within the db
*
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
 *
 *
 *
 *   60: class tx_civserv_commit
 *   72:     function update_postAction(&$params, &$pObj)
 *   83:     function updateDB($params)
 *
 * TOTAL FUNCTIONS: 2
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */
/**
 * This class holds the central function to ensure the consistency within the db
 *
 * @author	Georg Niemeyer <niemeyer@uni-muenster.de>
 * @package Extension
 * @subpackage civserv
 */
 
 
#require_once (PATH_t3lib."class.t3lib_tcemain.php");
 
class tx_civserv_commit {

	var $tables = array(
					'tx_civserv_service'=>'tx_civserv_service_sv_position_mm', 
					'tx_civserv_employee'=>'tx_civserv_employee_em_position_mm', 
					'tx_civserv_building'=>'tx_civserv_building_bl_floor_mm');
					
	var $attributes = array(
					'tx_civserv_service_sv_position_mm'=>'sv_position', 
					'tx_civserv_employee_em_position_mm'=>'em_position', 
					'tx_civserv_building_bl_floor_mm'=>'bl_floor');
					
	var $service_mm_tables =array(	'tx_civserv_service_sv_similar_services_mm',
									'tx_civserv_service_sv_form_mm',
									'tx_civserv_service_sv_searchword_mm',
									'tx_civserv_service_sv_position_mm',
									'tx_civserv_service_sv_organisation_mm',
									'tx_civserv_service_sv_navigation_mm',
									'tx_civserv_service_sv_region_mm');
	

	/**
	 * This function is central to guarantee the consistency within the DB.
	 * It is called through a hook within the class t3lib/class.t3lib_tcemain.php
	 * and is set in the class ext_localconf.php per ['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['clearCachePostProc'][]
	 *
	 *
	 * ATTENTION: this hook is called in a reliable way only in typo3 version <= 3.8.1 !!!
	 * in typo3 4.x. the class.t3lib_tcemain.php has been refactured and the hook will _not_ be called 
	 * under _one_ condition:
	 * If exactly the same number of service_position_relations is substracted from and added to a service in one 
	 * step (take one away and add another in the selection field in BE) the data_storing process via the Typo3 core ends with 
	 * t3lib_tcemain->updateDB and t3lib_tcemain->clear_cache (which carries the 'clearCachePostProc'-hook, 
	 * i.e. the function update_postAction) is not being entered again (it usually is).
	 * Consequently neither $this->renewMMentries nor $this->updateDB are executed and the database gets filled with
	 * redundant tx_civserv_service_sv_position_mm entries which have been saved to the end of the table by $this->saveMMentries.
	 * Instead of the saved entries beeing renewed, the Typo3-core writes new entries for each of the service_position_relations selected in BE.
	 * The result is chaos in tx_civserv_service_sv_position_mm. To avoid this a different hook is used for typo3 version >= 4.0
	 * @see processDatamap_afterDatabaseOperations
	 *
	 * @param	array		$params are parameters sent along to alt_doc.php. This requires a much more details description which you must seek in Inside TYPO3s documentation API
	 * @param	string		$pObj is a reference to the calling object
	 * @return	void
	 * @see t3lib/class.t3lib_tcemain.php
	 */
	function update_postAction(&$params, &$pObj){
		// concerned tables: 
		// tx_civserv_service_sv_position_mm
		// tx_civserv_employee_em_position_mm (update-db generates the records!?)
		// tx_civserv_officehours (update-db provides correct labels)
		if (t3lib_div::int_from_ver(TYPO3_version) < 4000000) { 
			//call the function renewMMentries($params), which writes back MM-entries that have been backuped through saveMMentries
			if (isset($params['table'])){
				if (array_key_exists($params['table'],$this->tables)){
					$this->renewMMentries($params);
				}	
			}
		}
		// do this anyway (typo3_src < 4.0 AND typo3_src > 4.0:
		// office-hour-labels depend on it
		// tx_civserv_employee_em_position records depend on it
		// tx_civserv_employee_em_position labels depend on it!
		// background info: it does make a difference whether updateDB is called 
		// - through $this->update_postAction (t3lib_tcemain->clear_cache, clearCachePostProc-HOOK) or 
		// - through $this->processDatamap_afterDatabaseOperations (t3lib_tcemain->process_datamap, processDatamap_afterDatabaseOperations-HOOK):
		// if it is the typo3-core cache-function calling this hook, the uids are numerical - as they are written into the db
		// if it is the typo3-core process-datamap-function calling the hook below the uids may be temporay strings like 'NEW123456'
		// with the temporary string-uids the labels for new records cannot be updated, with the numerical uids they can of course!		
		$this->updateDB($params, 'update_postAction');
	}
	
	
	/**
	 * This function allows to customize the userrights.
	 * It is called through a hook within the class t3lib/class.t3lib_tcemain.php
	 * and is set in the class ext_localconf.php per ['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['checkModifyAccessList'][]
	 *
	 * @table	string		$table is the tablename, where the modification will be performed
	 * @cmdmap	string		$cmdmap is an array in which the modification-command is listet
	 * @pObj	string		$pObj is the calling class
	 * @res		string		$res is the result of the modification-right calculated so far
	 * @return	void
	 * @see t3lib/class.t3lib_tcemain.php
	 */
	function recheckModifyAccessList($table, $cmdmap, $pObj, &$res){
		if (isset($cmdmap) && isset($cmdmap['tx_civserv_model_service_temp'])){
			foreach($cmdmap['tx_civserv_model_service_temp'] as $id => $incomingCmdArray)	{
				if (is_array($incomingCmdArray))	{
					reset($incomingCmdArray);
					$command = key($incomingCmdArray);
					if (!$pObj->admin && $command == 'delete') $res = 0;
				}
			}
		}
	}
	
	
	
	/**
	 * Hook function, called through a hook within the class typo3/class.db_list.inc set in the class ext_localconf.php...
	 * The Hook is for VERSIONING mainly, it is not part of typo3 4.0.x (or typo3 < 4.0.x)! it has to be introduced MANUALLY into the sources!!!
	 * This function manipulates the list of tx_civserv_service_sv_position_mm records displayed in BE below the list of service_records
	 * Background-information: the tx_civserv_service_sv_position_mm are made persistent in O.S.I.R.I.S. and they carry special 
	 * attributes which can be edited (if they relate to online services, see displaycond in tca.php for that issue)
	 *
	 * @table		string		$table is the tablename, where the modification will be performed
	 * @pid			string		$pid gets manipulated! 'pid=888' gets turned into 'pid in (-1,888)'
	 * @fieldList
	 * @addWhere	string		$addWhere gets manipulated! from the offline sv-pos-records we want only those belonging to the actual servicefolder
	 * @orderby		string		$orderby gets manipulated! the sv-pos-records must be odered by sp_label!
	 * @return	void
	 * @see t3lib/class.t3lib_tcemain.php
	 */
	function remakeQueryArray($table, $id, &$pid, $fieldList, &$addWhere, &$orderby){
		//all workspace: LIVE and CUSTOM
		if($table == 'tx_civserv_service_sv_position_mm'){
			$orderby=' tx_civserv_service_sv_position_mm.sp_label'; 
			
			$addWhere=' AND deleted=0'; //have to add this explicitely for mm-tables!!! 
			//standard typo3: mm_tables have no 'deleted'-field and are never displayed at all
		}	
		// in CUSTOM workspaces we want to see the records relating to the newest service-version as well as the
		// records relating to the actual online services.
		if($table == 'tx_civserv_service_sv_position_mm' && $GLOBALS['BE_USER']->workspace > 0){ //Custom workspace!!!
			//list services available in the given folder (pid = 123)
			$display_service_list=array();
			$display_count=0;
			$pidexploded=explode('=',$pid);
			$onlinepid=$pidexploded[1]; // 123
			// select the uids of the online-services (pid > 0) and add them to the service-list
			$result = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*','tx_civserv_service','deleted=0 AND '.$pid,'','','',''); 
			while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($result)){
				$display_service_list[$display_count]['uid']=$row['uid'];
				$display_service_list[$display_count]['t3ver_id']=$row['t3ver_id'];
				// select the uids of offline-versions of the same service
				$res2 = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*','tx_civserv_service','deleted=0 AND t3ver_oid='.$row['uid'],'','','',''); 
				while ($row2 = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res2)){
					//which one of the service-records is newer????
					if(intval($row2['t3ver_id'])>intval($display_service_list[$display_count]['t3ver_id'])){
						//overwrite the older ones in the display-list
						$display_service_list[$display_count]['uid']=$row2['uid'];
						$display_service_list[$display_count]['t3ver_id']=$row2['t3ver_id'];
					}
				}
				$display_count++;
			}
			
			
			if (count($display_service_list)>0){
				$sv_uids=array();
				foreach($display_service_list as $pair){
					$sv_uids[]=$pair['uid'];
				}
				// we need the records associated with online records as well! (0 shouldn't be in the pid-list)
				$pid='pid in (0, -1, '.$onlinepid.')'; 
				$addWhere='AND uid_local in ('.implode(',',$sv_uids).')';
				
				// make civserv-updates:
				// without call to update_position, the records appear as "no title" in BE!!
				// would rather have to call it with table tx_civserv_service and according uid then!!!
				$params=array('table' =>'tx_civserv_service_sv_position_mm');
				//baustelle!!!!!
				//$this->updateDB($params, 'remakeQueryArray');
			}
		}// CUSTOM WORKSPACE
	}
	

	/**
	 * This function writes back all MM-entries from the end of the table, which have been backuped through saveMMentries().
	 * Actual entries in the front of the table get deleted and replaced by the ones from the end. See
	 * Concerned tables: tx_civserv_service_sv_position_mm', tx_civserv_employee_em_position_mm, tx_civserv_building_bl_floor_mm
	 *
	 * @see rocessDatamap_preProcessFieldArray
	 * @param	array		$params are parameters sent along to alt_doc.php. This requires a much more details description which you must seek in Inside TYPO3s documentation API
	 * @return	void
	 * @see t3lib/class.t3lib_tcemain.php
	 */
	function renewMMentries($params){
			//get all MM tables, which could be concerned by the typo3 MM-entrie problem
		$mmTables = explode(',',$this->tables[$params['table']]);
			//fetch all backuped entries from the end of the table
		foreach($mmTables as $mmTable){
			$result = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*',$mmTable,'uid_local = '.($params['uid']+1000000000));
			$oldEntries = array();
				//for each entry, delete the one from the beginning of the table
			while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($result)){
				$row['uid_local']=$row['uid_local']-1000000000;
				$oldEntries[]=$row;
				$del_result = $GLOBALS['TYPO3_DB']->exec_DELETEquery($mmTable,'uid_local = '.$row['uid_local'].' AND uid_foreign = '.$row['uid_foreign']);
			}
				// get the entries from the back and set their uid to the one deleted above, 
				// so the backup is completet and all extra attributes from our MM-tables are saved!
			foreach($oldEntries as $row){
				$row['uid']=$row['uid_temp'];
				$uid_temp=$row['uid_temp'];
				unset($row['uid_temp']);
				$sub_result = $GLOBALS['TYPO3_DB']->exec_UPDATEquery($mmTable,'uid_temp = '.$uid_temp,$row);
			}
		}
	}

	/**
	 * ATTENTION ATTENTION ATTENTION
	 * Hook function, called through a hook within the class t3lib/class.t3lib_tcemain.php
	 * Typo3 Bug, this function has to be defined, but is of no use! Maybe a change in a later version will be made!
	 * ATTENTION ATTENTION ATTENTION
	 */
	function processDatamap_preProcessIncomingFieldArray($incomingFieldArray, $table, $id, $pObj){
		//dummy-Hook
		#if (array_key_exists($table,$this->tables) && substr($id,0,3)!='NEW')
		if (array_key_exists($table,$this->tables))
			$this->saveMMentries($incomingFieldArray, $table, $id);
	}

	/**
	 * Hook function, called through a hook within the class t3lib/class.t3lib_tcemain.php
	 * Typo3 always drops all entries in a MM-table with the uid_foreign from the contenttype that
	 * is actually worked on and saved. After this, it writes
	 * back the entries, that still selected in the contenttype. Problem at this procedure is, that some MM-tables in this
	 * extension have extra-attributes, that are not saved by Typo3 and so get lost. Typo3 only copies the uid_local, uid_foreign and sorting attributes.
	 * Concerned tables: tx_civserv_service_sv_position_mm', tx_civserv_employee_em_position_mm, tx_civserv_building_bl_floor_mm
	 *
	 * This hook calls a function, that saves all concerned MM-entries, before the Typo3 logic starts
	 *
	 * @see saveMMentries
	 * @param	array		$incomingFieldArray are parameters sent along to alt_doc.php from the contenttype, that is worked on. This requires a much more details description which you must seek in Inside TYPO3s documentation API
	 * @param	string		&table is the actuell table that belongs to the contenttype, that is worked on
	 * @param   integer		&id is the uid of the contenttype, that is worked on
	 * @param   string		$pObj is a reference to the calling object
	 * @return	void
	 * @see t3lib/class.t3lib_tcemain.php
	 */
	function processDatamap_preProcessFieldArray($incomingFieldArray, $table, $id, $pObj){
		//saves the current MM-entries
		if (array_key_exists($table,$this->tables) && (substr($id,0,3)!='NEW'))
			$this->saveMMentries($incomingFieldArray, $table, $id);
	}
	
	
	/**
	 * Hook function, called through a hook within the class t3lib/class.t3lib_tcemain.php
	 * This hook is for VERSIONING only!!
	 * It's meant to close gap between versioned and non-versioned records in tx_civserv: tx_civserv_service is the only table with versioning!
	 * the hook takes care that database relations are transmitted from workspace version to the live version in the event of publishing of
	 * any tx_civserv_service record from the workspace!
	 * so far only implemented for the event of simple publishing (swap), not for 'swap_into_workspace'.....
	 *
	 * @see saveMMentries
	 * @command		array		$command contains the parameters sent along to alt_doc.php from the contenttype, that is worked on. This requires a much more details description which you must seek in Inside TYPO3s documentation API
	 * @table		string		&table is the actual table that belongs to the contenttype that is worked on
	 * @id   		integer		&id is the uid of the contenttype that is worked on 
	 * @value		array		$value contains the different action types that can be performed on the actual contentype (in case of versioning)
	 * @pObj   		array		$pObj is a reference to the calling object, do not try to debug it! will cause endless loop. why ever.
	 * @return	void
	 * @see t3lib/class.t3lib_tcemain.php
	 */
	function processCmdmap_preProcess($command, &$table, $id, $value, &$pObj){
		// $id contains uid of actual online service in LIVE version
		// $value['swap_with'] contains uid of the offline-service in custom workspace, the one that ist beeing published
		$GLOBALS['TYPO3_DB']->debugOutput=TRUE;
		if (array_key_exists($table,$this->tables) && $table =='tx_civserv_service' && $command == 'delete'){
			// apparently 'deleted = 1' does not suffice to eleminate service-position records from the BE?
			// the remake-query-array-hook below is needed to make sure they are not listed in BE!
			$update_row=array('deleted' => '1');
			$sub_result = $GLOBALS['TYPO3_DB']->exec_UPDATEquery(' tx_civserv_service_sv_position_mm','uid_local = '.$id,$update_row);
		}
		if (array_key_exists($table,$this->tables) && $table =='tx_civserv_service' && $command == 'version'){
			switch($value['action']){
				case 'setStage':
				break;
				case 'swap':
					$pid=0;
					$res_pid=$GLOBALS['TYPO3_DB']->exec_SELECTquery('pid',$table,'uid = '.$id);
					if($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res_pid)){
						// first of all get the pid of the service to be published because you want to update the pid field 
						// in the _persistent_ relation-records - just as you update the uid_local to point to the LIVE 
						// service-record
						$pid=$row['pid'];
					}
					$update_fields = array("uid_local" => $id);
					foreach($this->service_mm_tables as $sv_mm_table){
						// second check if there are any mm-records relating to the offline version which is about to be published?
						// fehler eingebaut!!! baustelle
						$result = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*',$sv_mm_table,'uid_local = '.$value['swapWith']);
						if($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($result)){ //count might have been nicer here...
							if($sv_mm_table=='tx_civserv_service_sv_position_mm'){
								// Special case: Service-Position-Relations which have been made persistent in O.S.I.R.I.S. and 
								// they can carry additional information as i.e. Descriptions.
								// For every change on a offline-version of a service-record the system creates new relation-records (which then 
								// consist of just uid_local and uid_foreign)
								// Therefore we collect the additional information carried by records relating to the actual online 
								// services and add that additional information to the records relating to the offline service-version 
								// which is beeing published 
								// Then we swap the uids of the service versions and delete the records relating to the 'old' online-records.
								$res_positions = $GLOBALS['TYPO3_DB']->exec_SELECTquery('uid_foreign,sp_descr',$sv_mm_table,'uid_local = '.$id);
								while($row_positions = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res_positions)){
									$update_position_array = array("sp_descr" => $row_positions['sp_descr']);
									$update_positions = $GLOBALS['TYPO3_DB']->exec_UPDATEquery($sv_mm_table, 'uid_local = '.$value['swapWith'].' AND uid_foreign = '.$row_positions['uid_foreign'], $update_position_array);
								}
								//fix the pid for all services_posistion_records relating to the service being puplished.
								$update_positions = $GLOBALS['TYPO3_DB']->exec_UPDATEquery($sv_mm_table, 'uid_local = '.$value['swapWith'], array('pid' => $pid));
							}
							// delete the records relating to the actual online version of the service 
							// (they might be more or less in number than the records relating to the offline version which is being published)
							
							// ATTENTION: this is very necessary for service-position-relations?! but does it collide with renewMMentries?
							$del_result = $GLOBALS['TYPO3_DB']->exec_DELETEquery($sv_mm_table,'uid_local = '.$id);
																	
							// update tx_civserv_service_sv_position_mm set uid_local = $id (online!) where uid_local = $value['swap_with'] (offline!)
							$sub_result = $GLOBALS['TYPO3_DB']->exec_UPDATEquery($sv_mm_table, 'uid_local = '.$value['swapWith'], $update_fields);
						}
					}
				break;
			}
		}
	}
	
	/**
	 * Hook function, called through a hook within the class t3lib/class.t3lib_tcemain.php
	 * This hook is for VERSIONING only!!
	 * Does the cleaning up after publishing service through processCmdmap, kills all records from mm_table relating to versions of newly published service...
	 *
	 * @command		array		$command contains the parameters sent along to alt_doc.php from the contenttype, that is worked on. This requires a much more details description which you must seek in Inside TYPO3s documentation API
	 * @table		string		&table is the actual table that belongs to the contenttype that is worked on
	 * @id   		integer		&id is the uid of the contenttype that is worked on 
	 * @value		array		$value contains the different action types that can be performed on the actual contentype (in case of versioning)
	 * @pObj   		array		$pObj is a reference to the calling object, do not try to debug it! will cause endless loop. why ever.
	 * @return	void
	 * @see t3lib/class.t3lib_tcemain.php
	 */
	function processCmdmap_postProcess($command, &$table, $id, $value, &$pObj){
		if (array_key_exists($table,$this->tables) && $table =='tx_civserv_service'){
			//experimental:
			if($value['action']=='swap'){
				$del_versions = $GLOBALS['TYPO3_DB']->exec_DELETEquery($table,'pid=-1 AND t3ver_oid = '.$id);
			}
		}
	}
	
	
	/**
	 * Hook function, called through a hook within the class t3lib/class.t3lib_tcemain.php
	 * This hook was introduced because with typo3 4.0. and VERSIONING $this->update_postAction (called 
	 * through 'clearCachePostProc'-hook in t3lib_tcemain->clear_cache) is not always executed when the 
	 * service-postion relationsships have changed!!! For further explanation 
	 * @see update_postAction
	 *
	 * ATTENTION: this will only work if the displayconds are set accordingly in tca.php!
	 * ...
	 *
	 * @see saveMMentries
	 * @command		array		$command contains the parameters sent along to alt_doc.php from the contenttype, that is worked on. This requires a much more details description which you must seek in Inside TYPO3s documentation API
	 * @table		string		&table is the actual table that belongs to the contenttype that is worked on
	 * @id   		integer		&id is the uid of the contenttype that is worked on 
	 * @value		array		$value contains the different action types that can be performed on the actual contentype (in case of versioning)
	 * @pObj   		array		$pObj is a reference to the calling object, do not try to debug it! will cause endless loop. why ever.
	 * @return	void
	 * @see t3lib/class.t3lib_tcemain.php
	 */
	function processDatamap_afterDatabaseOperations($status, $table, $id, $fieldArray, &$pObj){
		if (t3lib_div::int_from_ver(TYPO3_version) >= 4000000) {
			$params=array('table' => $table, 'uid' => $id);
			if (isset($params['table'])){
				if (array_key_exists($params['table'],$this->tables)){
					$this->renewMMentries($params);
				}
			}
			// do we need to call this function again??? yes we do! 
			// we need to do it for exactly the case when the hook calling $this->update_postAction is not executed!!!
			// can we check if $this->update_postAction has run before - so we avoid double execution of same functions?
			$this->updateDB($params, 'processDatamap_afterDatabaseOperations');
		}
	}



	/**
	 * This function saves all concerned MM-entries that would be deleted through Typo3.
	 * Concerned entries are copied to the end of the MM-table under a uid_local, which is the old uid_local + 1000000000.
	 * The old uid is saved in the attribute uid_temp, which is needed, to write the entrie back to its origin position in the MM-table.
	 * This is done by another function (renewMMentries).
	 * Concerned tables: tx_civserv_service_sv_position_mm', tx_civserv_employee_em_position_mm, tx_civserv_building_bl_floor_mm
	 *
	 * @see renewMMentries
	 * @param	array		$incomingFieldArray are parameters sent along to alt_doc.php from the contenttype, that is worked on. This requires a much more details description which you must seek in Inside TYPO3s documentation API
	 * @param	string		&table is the actuell table that belongs to the contenttype, that is worked on
	 * @param   integer		&id is the uid of the contenttype, that is worked on
	 * @return	void
	 * @see t3lib/class.t3lib_tcemain.php
	 */
	function saveMMentries($incomingFieldArray, $table, $id){
		// versioningg-problem on tx_civserv_service_sv_position: when saving service in custom workspace (version_service_uid) 
		// this function generates a new and superfluous saved_entry for the live_service_uid which 
		// leads to double entries in the table.
		if ($GLOBALS['BE_USER']->workspace==0){ //only do this in the live workspace!!!
			// get all MM tables, which could be concerned by the typo3 MM-entrie problem
			$mmTables = explode(',',$this->tables[$table]);
			$GLOBALS['TYPO3_DB']->debugOutput=TRUE;
			// for all concerned MM-tables save the concerned entries at the end of the MM-tables and backup the origin uid in the field uid_temp
			foreach($mmTables as $mmTable){
				//only get the entries, which are selected in the actual backendmask for the contenttype "service". So deleted ones are not listed here
				$entries = explode(',',$incomingFieldArray[$this->attributes[$mmTable]]);
				$foreign_uids=array();
				foreach ($entries as $entry){
					if (strlen($entry)>0){
						$pos=strrpos($entry,'_')+1;
						if ($pos>1)	$foreign_uids[]=substr($entry,$pos);
						else $foreign_uids[]=$entry;
					}
				}
				
				$result = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*',$mmTable,'!deleted AND !hidden AND uid_local = '.$id); 
					//copy each concerned entrie to the end of table
				while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($result)){
					if (in_array($row['uid_foreign'],$foreign_uids)){
						$row['uid_temp']=$row['uid'];
						$row['uid_local'] = $row['uid_local']+1000000000;
						unset($row['uid']);
						$sub_result = $GLOBALS['TYPO3_DB']->exec_INSERTquery($mmTable,$row);
					}				
				}
			}
		} //only in LIVE workspace
	}



	/**
	 * As a result of the given table specified by $params updateDB decides which table has to be updated
	 * an calls the the corresponding method.
	 *
	 * @param	string		$params are parameters sent along to alt_doc.php. This requires a much more details description which you must seek in Inside TYPO3s documentation API
	 * @return	void
	 */
	function updateDB($params, $who) {
		global $GLOBALS, $BE_USER;
		if (TYPO3_DLOG)  t3lib_div::devLog('function updateDB called by '.$who, 'civserv');
		if ($params['table']=='tx_civserv_building')	{
			if ($GLOBALS['GLOBALS']['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/civserv/res/class.tx_civserv_floorbuild.php']){
				$update_obj = t3lib_div::makeInstance('tx_civserv_floorbuild');
				$update_obj->update_pid($params);
			}
		}
		if ($params['table']=='tx_civserv_room')	{
			if ($GLOBALS['GLOBALS']['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/civserv/res/class.tx_civserv_oepupdate.php']){
				$update_obj = t3lib_div::makeInstance('tx_civserv_oepupdate');
				$update_obj->update_label($params);
			}
		}
		if ($params['table']=='tx_civserv_employee' && substr($params['uid'],0,3)!='NEW')	{
			if ($GLOBALS['GLOBALS']['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/civserv/res/class.tx_civserv_oepupdate.php']){
				$update_obj = t3lib_div::makeInstance('tx_civserv_oepupdate');
				$update_obj->update_pid($params);
				//This could be much faster if only the current employee would be updated
				$update_obj->update_label($params);
			}
		}
		if ($params['table']=='tx_civserv_employee_em_position_mm')	{
			if ($GLOBALS['GLOBALS']['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/civserv/res/class.tx_civserv_oepupdate.php']){
				$update_obj = t3lib_div::makeInstance('tx_civserv_oepupdate');
				$update_obj->update_pid($params);
				//This could be much faster if only the current employee would be updated
				$update_obj->update_label($params);
			}
		}
		if ($params['table']=='tx_civserv_service')	{
			if ($GLOBALS['GLOBALS']['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/civserv/res/class.tx_civserv_service_maintenance.php']){
				$update_obj = t3lib_div::makeInstance('tx_civserv_service_maintenance');
				//fix me! 
				if($who != "processDatamap_afterDatabaseOperations") $update_obj->transfer_services($params);
				$update_obj->update_position($params);
			}
		}
		if ($params['table']=='tx_civserv_model_service_temp')	{
			if ($GLOBALS['GLOBALS']['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/civserv/res/class.tx_civserv_ms_maintenance.php']){
				$update_obj = t3lib_div::makeInstance('tx_civserv_ms_maintenance');
				$update_obj->check_changes($params);
				$GLOBALS['TYPO3_DB']->exec_UPDATEquery('tx_civserv_model_service_temp', 'uid = '.$params['uid'], array ("ms_uid_editor" => $BE_USER->user["uid"],"ms_revised_approver_one" => 0, "ms_revised_approver_two" => 0));
			}
		}
		if ($params['table']=='tx_civserv_model_service' && substr($params['uid'],0,3)!='NEW')	{
			if ($GLOBALS['GLOBALS']['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/civserv/res/class.tx_civserv_ms_maintenance.php']){
				$update_obj = t3lib_div::makeInstance('tx_civserv_ms_maintenance');
				$update_obj->check_ms_name_changed($params);
				$update_obj->transfer_ms($params);
			}
		}
		if($params['table']=='tx_civserv_officehours'){
			if ($GLOBALS['GLOBALS']['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/civserv/res/class.tx_civserv_weekday_maintenance.php']){
				$update_obj = t3lib_div::makeInstance('tx_civserv_weekday_maintenance');
				$update_obj->update_labels($params);
			}
		}
		if($params['table']=='tx_civserv_conf_mandant'){
			$this->makeDirs($params);
		}
		if($params['table']=='tx_civserv_navigation'){
			$result = $GLOBALS['TYPO3_DB']->exec_SELECTquery('uid_local','tx_civserv_navigation_nv_structure_mm','uid_local = uid_foreign');
			while($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($result)){
				$del_result = $GLOBALS['TYPO3_DB']->exec_DELETEquery('tx_civserv_navigation_nv_structure_mm','uid_local = '.$row['uid_local'].' AND uid_foreign = '.$row['uid_local']);				
			}
		}
		if($params['table']=='tx_civserv_organisation'){
			$result = $GLOBALS['TYPO3_DB']->exec_SELECTquery('uid_local','tx_civserv_organisation_or_structure_mm','uid_local = uid_foreign');
			while($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($result)){
				$del_result = $GLOBALS['TYPO3_DB']->exec_DELETEquery('tx_civserv_organisation_or_structure_mm','uid_local = '.$row['uid_local'].' AND uid_foreign = '.$row['uid_local']);				
			}
		}
	}

	/**
	 * This function makes the missing directories on the file server
	 *
	 * @param	string		$params are parameters sent along to alt_doc.php. This requires a much more details description which you must seek in Inside TYPO3s documentation API
	 * @return	void
	 */
	function makeDirs($params){
		global $BACK_PATH;
		$result = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*','tx_civserv_conf_mandant','uid = '.$params['uid'].' AND !deleted');
		$row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($result);
		$community = $row['cm_community_id'];
		$base=dirname($_SERVER['SCRIPT_FILENAME']);
		//eliminate /typo3
		$base=substr($base,0,strrpos($base,'/'));
		if (!file_exists($base.'/fileadmin/civserv/'.$community)){
			mkdir($base.'/fileadmin/civserv/'.$community, 0775);
		}
		if (!file_exists($base.'/fileadmin/civserv/'.$community.'/images')){
			mkdir($base.'/fileadmin/civserv/'.$community.'/images', 0775);
		}
		if (! file_exists($base.'/fileadmin/civserv/'.$community.'/forms')){
			mkdir($base.'/fileadmin/civserv/'.$community.'/forms', 0775);
		}

		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'cf_value',			 							// SELECT ...
			'tx_civserv_configuration',						// FROM ...
			'cf_key = "model_service_image_folder"',		// AND title LIKE "%blabla%"', // WHERE...
			'', 											// GROUP BY...
			'',   											// ORDER BY...
			'' 												// LIMIT to 10 rows, starting with number 5 (MySQL compat.)
		);

		$model_service_image_folder = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
		$model_service_folder = $model_service_image_folder['cf_value'];
		if (!file_exists($base.'/'.$model_service_folder)){
			mkdir($base.'/'.$model_service_folder, 0775);
		}
	}
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/civserv/res/class.tx_civserv_commit.php']) {
	include_once ($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/civserv/res/class.tx_civserv_commit.php']);
}
?>
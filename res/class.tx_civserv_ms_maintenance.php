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
* This class holds some functions used by the TYPO3 backend to guarantee the consistency within the pids
*
* Some scripts that use this class: ?
* Depends on: ?
*
* $Id$
*
* @author Georg Niemeyer (niemeyer@uni-muenster.de)
* @author Maurits Hinzen (mhinzen@uni-muenster.de)
* @author Tobias Müller (mullerto@uni-muenster.de)
* @package TYPO3
* @subpackage tx_civserv
* @version 1.0
*
* Changes: Datum, Initialen - vorgenommene Änderungen
*/
/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   61: class tx_civserv_ms_maintenance
 *   68:     function main()
 *   78:     function check_changes($params)
 *  108:     function compute_checksum($model_service_temp)
 *  143:     function write_checksum_and_flags($model_service_temp, $new_hash)
 *  157:     function transfer_ms($params)
 *  233:     function show_mandants(&$params, &$pObj)
 *
 * TOTAL FUNCTIONS: 6
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */
class tx_civserv_ms_maintenance {

	/**
	 * Checks, if a currently saved dataset has actually been saved (changed?!) by computing a checksum of the data fields and
	 * comparing it with the previously saved checksum.
	 *
	 * @param	string		$params [array]: the parameters of the saved dataset
	 * @return	void
	 */
	function check_changes($params) {
		if ($params['table']=='tx_civserv_model_service_temp')	{

				// Gets all data from the currently saved record...
			$this->res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
				'*',			 					// SELECT ...
				'tx_civserv_model_service_temp',	// FROM ...
				'uid='.$params['uid'],				// AND title LIKE "%blabla%"', // WHERE...
				'', 								// GROUP BY...
				'',   								// ORDER BY...
				'' 									// LIMIT to 10 rows, starting with number 5 (MySQL compat.)
			);

				// ...and stores it in $model_service_temp.
			$model_service_temp = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($this->res);

			$new_hash = $this->compute_checksum($model_service_temp);
				// Compares the old with the new checksum to find out, if the data actually has been changed.
			if ($new_hash!=$model_service_temp['ms_checksum'])	{
				$this->write_checksum_and_flags($model_service_temp, $new_hash);
			}
		}
	}


	/**
	 * Checks, if the name of a currently saved model_service-dataset has actually been changed by
	 * comparing it with the previously saved ms_stored_name.
	 *
	 * @param	string		$params [array]: the parameters of the saved dataset
	 * @return	void
	 */
	function check_ms_name_changed($params) {
		if ($params['table']=='tx_civserv_model_service')	{


			// Gets all data from the currently saved record...
			// by the way: ms_stored_name is not editable from the BE
			$this->res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
				'*',								// SELECT ...
				'tx_civserv_model_service',			// FROM ...
				'uid='.$params['uid'],				// AND title LIKE "%blabla%"', // WHERE...
				'', 								// GROUP BY...
				'',   								// ORDER BY...
				'' 									// LIMIT to 10 rows, starting with number 5 (MySQL compat.)
			);

			// ...and stores it in $model_service_names.
			$model_service_names = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($this->res);

			//...check the value of the field ms_stored_name: if it is an empty string it is a newly inserted dataset and the value of the field ms_stored_name should be equated with the value of ms_name
			if ($model_service_names['ms_stored_name'] == ''){
				$model_service_names['ms_stored_name'] == $model_service_names['ms_name'];
				$name_to_be_stored=array('ms_stored_name'=>$model_service_names['ms_name']);
				$GLOBALS['TYPO3_DB']->exec_UPDATEquery('tx_civserv_model_service', 'uid = '.$model_service_names['uid'], $name_to_be_stored);
			}

			//...has the internal name of the model service been changed?
			if ($model_service_names['ms_name']!=$model_service_names['ms_stored_name'])	{
				//update the name-fields in tx_model_service and tx_model_service_temp

				//update tx_civserv_model_services
				$new_name=array('ms_stored_name'=>$model_service_names['ms_name']);
				$GLOBALS['TYPO3_DB']->exec_UPDATEquery('tx_civserv_model_service', 'uid = '.$model_service_names['uid'], $new_name);

				//update tx_civserv_model_services_temp - we go by the old name, as the field ms_name in tx_civserv_model_service_temp is not editable in the BE anyhow, so this should always work....
				//could also go by the uid-field since the copies in tx_civserv_model_service_temp have the same (!!!) uids as the originals in tx_civserv_model_service
				$new_name=array('ms_name'=>$model_service_names['ms_name']);
				$GLOBALS['TYPO3_DB']->exec_UPDATEquery('tx_civserv_model_service_temp', 'ms_name = "'.$model_service_names['ms_stored_name'].'"', $new_name);
			}
		}
	}

	/**
	 * Computes a checksum of all fields from model service, which can be edited by a mandant, by using the MD5-Algorithm.
	 *
	 * @param	string	$model_service_temp [array]: a model service dataset, which can be edited and therefore is stored as model service temp
	 * @return	string	md5-checksum, which is computed over all given fields excepted the once listed with unset
	 */
	function compute_checksum($model_service_temp)	{
			// Gets all field names except uid,pid,tstamp,crdate,cruser_id,deleted,hidden,fe_group,ms_mandant,
			// ms_approver_one and ms_approver_two out of the model service table.
		$field_names = $GLOBALS['TYPO3_DB']->admin_get_fields('tx_civserv_model_service');
		unset($field_names['pid']);
		unset($field_names['tstamp']);
		unset($field_names['crdate']);
		unset($field_names['cruser_id']);
		unset($field_names['deleted']);
		unset($field_names['hidden']);
		unset($field_names['fe_group']);
		unset($field_names['ms_mandant']);
		unset($field_names['ms_approver_one']);
		unset($field_names['ms_approver_two']);
		$field_names = array_keys($field_names);

			// Puts all data out of those fields, which are the same in model_service and model_service_temp,
			// together into one string.
		for ($i=0; $i<=count($field_names); $i++) {
			$data_as_string.=(string)$model_service_temp[$field_names[$i]];
		}

			// Computes a hashsum of the string above.
		$new_hash=md5($data_as_string);

		return $new_hash;
	}

	/**
	 * Stores a Checksum in a model service dataset and sets a flag, which indicates, that the dataset has been changed.
	 * Also sets approver-flags to null, because both approvers have to commit when any data has been changed.
	 * At the end there will be sent an email to the approver to inform them, that there were changes
	 *
	 * @param	string	$model_service_temp [array]: a model service dataset of the model service temp - table
	 * @param	string	$new_hash [string]: checksum
	 * @return	void
	 */
	function write_checksum_and_flags($model_service_temp, $new_hash)	{
		global $LANG;
		$LANG->includeLLFile(t3lib_extMgm::extPath('civserv')."modmsworkflow/locallang.php");
			// Sets the ms_has_changed field to 1 so that it indicates, that data has been changed,
			// stores the new checksum in the dataset and finally sets both commit checkboxes to null.
		$GLOBALS['TYPO3_DB']->exec_UPDATEquery('tx_civserv_model_service_temp', 'uid = '.$model_service_temp['uid'], array ("ms_additional_label" => $LANG->getLL('modmsworkflow.label_monitoring'), "ms_checksum" => $new_hash, "ms_has_changed" => 1, "ms_commit_approver_one" => 0, "ms_commit_approver_two" => 0));

		$row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc(
			$GLOBALS['TYPO3_DB']->exec_SELECTquery('ms_approver_one, ms_approver_two','tx_civserv_model_service','uid = '.$model_service_temp['uid'])
		);
		$ms_approver_one = $row[ms_approver_one];
		$ms_approver_two = ($row[ms_approver_two]==$ms_approver_one? false:$row[ms_approver_two]);

		$eMailOne = $GLOBALS['TYPO3_DB']->sql_fetch_assoc(
			$GLOBALS['TYPO3_DB']->exec_SELECTquery('cm_target_email','tx_civserv_conf_mandant','cm_community_id = '.$ms_approver_one)
		);

		if ($ms_approver_two) $eMailTwo = $GLOBALS['TYPO3_DB']->sql_fetch_assoc(
				$GLOBALS['TYPO3_DB']->exec_SELECTquery('cm_target_email','tx_civserv_conf_mandant','cm_community_id = '.$ms_approver_two)
			);

		$fr_res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('cf_value','tx_civserv_configuration','cf_key = "email_from"','','','');
		$from_res = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($fr_res);
		$from = "From: ".$from_res['cf_value'];
		$subject = str_replace("###model_service_name###", $model_service_temp['ms_name'], $LANG->getLL("modmsworkflow.email_subject_inititate"));
		$search = array("model_service_name" => "###model_service_name###");
		$replace = array("model_service_name" => $model_service_temp['ms_name']);
		$text = str_replace($search, $replace, $LANG->getLL("modmsworkflow.email_text_initiate"));

		if (t3lib_div::validEmail($to=$eMailOne['cm_target_email'])){
			t3lib_div::plainMailEncoded($to,$subject,$text,$from);
		}
		if (t3lib_div::validEmail($to=$eMailTwo['cm_target_email'])){
			t3lib_div::plainMailEncoded($to,$subject,$text,$from);
		}
	}

	/**
	 * This function is called from the tx_civserv_commit-class to initiate the modelservice-workflow. Depending on the made decision concerning the
	 * maintainer of a modelservice and its controllinstances the modelservice is temporally copied in a table to achieve a kind of versioning in maintaining.
	 * And the pid of the copied record is set to the configured cm_model_service_temp_uid in the mandant-configuration-table to let the maintainer work on
	 * the modelservice in his treestructur.
	 *
	 * @param	string	$params are parameters sent along to alt_doc.php. This requires a much more details description which you must seek in Inside TYPO3s documentation API
	 * @return	void
	 */
	function transfer_ms($params){
		global $LANG;
		$LANG->includeLLFile(t3lib_extMgm::extPath('civserv')."modmsworkflow/locallang.php");
		//get all data from the original table, including datasets which carry the deleted flag, because we want to eleminate them from the copies also!
		if ($params['table']=='tx_civserv_model_service')	{
			$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
				'*',									// Field list for SELECT
				'tx_civserv_model_service',				// Tablename, local table
				'ms_approver_one != 0 AND ms_approver_two != 0 AND uid='.$params['uid'], // Optional additional WHERE clauses
				'',										// Optional GROUP BY field(s), if none, supply blank string.
				'',										// Optional ORDER BY field(s), if none, supply blank string.
				'' 										// Optional LIMIT value ([begin,]max), if none, supply blank string.
			);
			while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
				//get rid of the extra-field for name-logging in tx_civserv_model_service (or else insert will fail because tx_civserv_model_service_temp has no such field)
				unset($row['ms_stored_name']);

				//does a copy of this dataset from tx_civserv_model_service already exist in tx_civserv_model_service_temp? (remember: same uids!!)
				$exists_res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
					'*',											// Field list for SELECT
					'tx_civserv_model_service_temp',				// Tablename, local table
					'uid = '.$row['uid'],							// Optional additional WHERE clauses
					'',												// Optional GROUP BY field(s), if none, supply blank string.
					'',												// Optional ORDER BY field(s), if none, supply blank string.
					''												// Optional LIMIT value ([begin,]max), if none, supply blank string.
				);
				$exists = $GLOBALS['TYPO3_DB']->sql_num_rows($exists_res);
				$current_temp = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($exists_res);


				if($row['ms_mandant'] != 0){
					//get the target-folder for the responsible modelservices from extension configuration table
					$ms_res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
						'cm_model_service_temp_uid', 					// Field list for SELECT
						'tx_civserv_conf_mandant', 						// Tablename, local table
						'cm_community_id = '.$row['ms_mandant'],		// Optional additional WHERE clauses
						'',												// Optional GROUP BY field(s), if none, supply blank string.
						'',												// Optional ORDER BY field(s), if none, supply blank string.
						'' 												// Optional LIMIT value ([begin,]max), if none, supply blank string.
					);
					$ms_folder_res = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($ms_res);
					$ms_folder_pid = $ms_folder_res['cm_model_service_temp_uid'];


					if ($exists && !$row['deleted']){  //exists in tx_civserv_model_service_temp and is not deleted in tx_civserv_model_service!!!
						// check if the modelservice already is in the responsibility of the community
						$mandant_obj = t3lib_div::makeInstance('tx_civserv_mandant');
						$community_id = $mandant_obj->get_mandant($current_temp['pid']);
						if ($community_id != $row['ms_mandant']) {
							$community_changed = 1;
						} else $community_changed = 0;
					} else $community_changed = 0;

					if ($exists && $community_changed) {  //which implies 'not deleted', see above
						//delete existing modelservice_temp and insert modelservice for the new community
						$GLOBALS['TYPO3_DB']->exec_DELETEquery('tx_civserv_model_service_temp','uid = '.$row['uid']);
						unset($row['ms_mandant']);
						unset($row['ms_approver_one']);
						unset($row['ms_approver_two']);
						$row['pid'] = $ms_folder_pid;
						$row['ms_checksum'] = $this->compute_checksum($row);
						$row['ms_additional_label'] = $LANG->getLL('modmsworkflow.label_approved');
						$GLOBALS['TYPO3_DB']->exec_INSERTquery('tx_civserv_model_service_temp',$row);
					} elseif ($exists && !$community_changed){ //
						if($row['deleted'] == 1){
							$GLOBALS['TYPO3_DB']->exec_DELETEquery('tx_civserv_model_service_temp', 'uid = '.$row['uid']);
						}
					} elseif (!$exists && !$row['deleted']){
						unset($row['ms_mandant']);
						unset($row['ms_approver_one']);
						unset($row['ms_approver_two']);
						$row['pid'] = $ms_folder_pid;
						$row['ms_checksum'] = $this->compute_checksum($row);
						$row['ms_additional_label'] = $LANG->getLL('modmsworkflow.label_approved');
						$GLOBALS['TYPO3_DB']->exec_INSERTquery('tx_civserv_model_service_temp',$row);
					}
				} elseif($row['ms_mandant'] == 0) {
					//if nobody is in charge then there's no need for the copy to exist
					$GLOBALS['TYPO3_DB']->exec_DELETEquery('tx_civserv_model_service_temp','uid = '.$row['uid']);
				}
			}
		}
	}

	/**
	 * writes all existing and configured mandants back into the $params-Array as items which can be chosen in a selectorbox
	 *
	 * @param	string		$params are parameters sent along to alt_doc.php. This requires a much more details description which you must seek in Inside TYPO3s documentation API
	 * @param	string		$pObj is a reference to the calling object
	 * @return	void
	 */
	function show_mandants(&$params, &$pObj) {
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'cm_community_id, cm_community_name',				// Field list for SELECT
			'tx_civserv_conf_mandant ',							// Tablename, local table
			'!deleted AND !hidden',								// Optional additional WHERE clauses
			'',													// Optional GROUP BY field(s), if none, supply blank string.
			'cm_community_name',								// Optional ORDER BY field(s), if none, supply blank string.
			'' 													// Optional LIMIT value ([begin,]max), if none, supply blank string.
		);

		//write the community_name-community_id back in the params-Array (which is shown in the selectorbox)
		$i=0;
		while ($data = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
			$params['items'][++ $i] = Array ($data['cm_community_name'], $data['cm_community_id']);
		}
	}


}
if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/civserv/res/class.tx_civserv_ms_maintenance.php']) {
	include_once ($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/civserv/res/class.tx_civserv_ms_maintenance.php']);
}
?>
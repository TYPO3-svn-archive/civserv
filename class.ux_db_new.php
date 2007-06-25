<?php
/** 
* User-Extension of allowed_new_tables in class SC_db_new.
*
* @author    Britta Kohorst <kohorst@citeq.de>
*
* this class extends typo3 core class with layout/output function for TYPO3 Backend Scripts
* 
*
*
*/
class ux_sc_db_new extends SC_db_new{
	function isTableAllowedForThisPage($pid_row, $checkTable)	{
		global $TCA, $PAGES_TYPES;
		//citeq begin
		global $LANG;
		$LANG->includeLLFile("EXT:civserv/res/locallang_user_be_msg.php");
		//citeq end
		if (!is_array($pid_row))	{
			if ($GLOBALS['BE_USER']->user['admin'])	{
				return true;
			} else {
				return false;
			}
		}
			// be_users and be_groups may not be created anywhere but in the root.
		if ($checkTable=='be_users' || $checkTable=='be_groups')	{
			return false;
		}
			// Checking doktype:
		$doktype = intval($pid_row['doktype']);
		if (!$allowedTableList = $PAGES_TYPES[$doktype]['allowedTables'])	{
			$allowedTableList = $PAGES_TYPES['default']['allowedTables'];
		}
		if (strstr($allowedTableList,'*') || t3lib_div::inList($allowedTableList,$checkTable))	{		// If all tables or the table is listed as a allowed type, return true
			//citeq beginn
			debug($allowedTableList, '$allowedTableList');
			debug($checkTable, '$checkTable');
			if($checkTable == 'tx_civserv_model_service'){
				debug($pid_row, '$pid_row');
				if($pid_row['doktype'] == 242 && ($pid_row['tx_civserv_ms_mandant'] <= '' || $pid_row['tx_civserv_ms_approver_one'] <= '' || $pid_row['tx_civserv_ms_approver_two'] <= '')){
					$ms_container_name = $pid_row['title'];
					$ms_container_uid  = $pid_row['uid'];
					$info_img = '<img'.t3lib_iconWorks::skinImg($this->PH_backPath,'gfx/icon_note.gif','width="18" height="16"').' title="'.$GLOBALS['LANG']->sL('LLL:EXT:lang/locallang_core.php:labels.close',1).'" alt="" />';
					$msg .= '<p>'.$info_img.'&nbsp;&nbsp;'.$LANG->getLL("tx_civserv_user_be_msg.tx_civserv_model_service.new_record_no_mandant_roles").'</p>';
					$msg = str_replace('###ms_container_name###', $ms_container_name, $msg);
					$msg = str_replace('###ms_container_uid###', $ms_container_uid, $msg);


					$this->content .= '<br /><div style="
											border: 2px dashed #666666;
											width : 90%;
											line-height:1.9em;
											margin: 5px 5px 5px 5px;
											padding: 5px 5px 5px 5px;"
											>
											<h3>'.$msg.'</h3>
										</div><br />';
					return false;
				}
			}
			//citeq end
			return true;
		}
	}
}
?>
<?php
/***************************************************************
* Copyright notice
*
* (c) 2006 citeq (osiris@citeq.de)
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
* This class has been introduced with typo3 4.x because a problem arose with mm-relations
* this class could possibly be made redundant by IRRE
* - function is called form tca.php in case of totally new records (tca.php takes care that relations to other records can only be added when the base-record has been saved once i.e. has a proper id)
* - function generates msg to BE-user, informing him, that he must save base record (tx_civserv_service) before he can make any relations to other records
* look at typo3 CORE API chapter 4 -> userFunc for further information
*
* Some scripts that use this class: ?
* Depends on: ?
*
*
* @author Britta Kohorst (kohorst@citeq.de)
* @package TYPO3
* @subpackage tx_civserv
* @version 1.0
*
* Changes: Datum, Initialen - vorgenommene Änderungen
*/



class tx_civserv_user_be_msg {
	function user_TCAform_test($PA=array(), $fobj=array()) { //adding '=array()' to signature enabels calls to this function with no parameters (happens through "displayCond" => "REC:NEW:true")
		global $LANG;
		$LANG->includeLLFile("EXT:civserv/res/locallang_user_be_msg.php");
		
		$table=$PA['table'];
		$field=$PA['fieldConf']['dbField']; //dbField is a custom variable introduced in TCA to make the ll-fetching more flexible
		
		$pageid=0;
		//debugging $GLOBALS told me that I best get the actual page-id out of the returnURL:
		$returl=parse_url(t3lib_div::_GET('returnUrl'));
		$query=explode('&', $returl['query']);
		foreach($query as $item){ //not sure if 'id' always comes first in the query-string
			$query_parts=explode('=',$item);
			if($query_parts[0]=='id'){
				$pageid=$query_parts[1];
			}
		}
		
		$info_img= '<img'.t3lib_iconWorks::skinImg($this->PH_backPath,'gfx/icon_note.gif','width="18" height="16"').' title="'.$GLOBALS['LANG']->sL('LLL:EXT:lang/locallang_core.php:labels.close',1).'" alt="" />';
		
		
		
		if($PA['table']=='tx_civserv_service_sv_position_mm'){
			// identify the service in question:
			$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
					'uid_local',			 			// SELECT ...
					$PA['table'],			// FROM ...
					'uid = '.$PA['row']['uid'],	// AND title LIKE "%blabla%"', // WHERE...
					'', 						// GROUP BY...
					'',   						// ORDER BY...
					'' 							// LIMIT to 10 rows, starting with number 5 (MySQL compat.)
				);
			$sv_pos_row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
			$sv_uid = $sv_pos_row['uid_local'];
			
			// get the name of the service in question
			$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
					'uid, sv_name',			 			// SELECT ...
					'tx_civserv_service',			// FROM ...
					'uid = '.$sv_uid,	// AND title LIKE "%blabla%"', // WHERE...
					'', 						// GROUP BY...
					'',   						// ORDER BY...
					'' 							// LIMIT to 10 rows, starting with number 5 (MySQL compat.)
				);
			$sv_row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
			$sv_uid = $sv_row['uid'];
			$sv_name = $sv_row['sv_name'];
			
			$info_img= '<img'.t3lib_iconWorks::skinImg($this->PH_backPath,'gfx/icon_warning.gif','width="18" height="16"').' title="'.$GLOBALS['LANG']->sL('LLL:EXT:lang/locallang_core.php:labels.close',1).'" alt="" />';
			
			// offer sufficient possibilities to return to the editor
			$backurl = t3lib_div::getIndpEnv('TYPO3_REQUEST_DIR').'db_list.php?id='.$pageid;
			$closeicon = '<img'.t3lib_iconWorks::skinImg($this->PH_backPath,'gfx/closedok.gif','width="21" height="16"').' title="'.$GLOBALS['LANG']->sL('LLL:EXT:lang/locallang_core.php:labels.close',1).'" alt="" />';
			$backurl_closeicon = '<a href="'.$backurl.'"><img'.t3lib_iconWorks::skinImg($this->PH_backPath,'gfx/closedok.gif','width="21" height="16"').' title="'.$GLOBALS['LANG']->sL('LLL:EXT:lang/locallang_core.php:labels.close',1).'" alt="" /></a>';
		}
		$div_open= '
			<div style="
				 border: 2px dashed #666666;
				 width : 90%;
				 margin: 5px 5px 5px 5px;
				 padding: 5px 5px 5px 5px;"
				 >';
		$msg= $info_img;
		$msg.='<p>'.$LANG->getLL("tx_civserv_user_be_msg.".$table.".".$field).'</p>';
		$msg=str_replace('###sv_name###', $sv_name, $msg);
		$msg=str_replace('###icon###', $backurl_closeicon, $msg);
		$returnURL=$PA['table']=='tx_civserv_service_sv_position_mm' ? '<a href="'.$backurl.'">hier geht\'s zurück</a>' : '';
		$div_close='</div>';
		return $div_open.$msg.$returnURL.$div_close;
	}
	
//	would prefer more speaking function-name but couldn't get typo3 to find the function then, see tca.php
	function user_TCAform_test2(&$PA, &$fobj) {
		global $LANG;
		$LANG->includeLLFile("EXT:civserv/res/locallang_user_be_msg.php");
		$ms_container_name='unbekannt'; //dummy 
		$ms_container_pid = $PA['row']['pid'];
		if(preg_match('/NEW/', $PA['row']['uid'])){
			// select mandant-roles from table pages where the doktype is 'Model Service Container'
			$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
				'title, tx_civserv_ms_mandant, tx_civserv_ms_approver_one, tx_civserv_ms_approver_two',				// Field list for SELECT
				'pages ',							// Tablename, local table
				'deleted=0 AND hidden=0 AND doktype= \'242\' AND uid='.$PA['row']['pid'],								// Optional additional WHERE clauses
				'',													// Optional GROUP BY field(s), if none, supply blank string.
				'',								// Optional ORDER BY field(s), if none, supply blank string.
				'' 													// Optional LIMIT value ([begin,]max), if none, supply blank string.
			);
			$value=0;
			$text='';
			while ($data = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) { //precisely once......
				$ms_container_name = $data['title'];
				switch ($PA['field']){
					case 'ms_mandant':
						$value = $data['tx_civserv_ms_mandant'];
						break;
					case 'ms_approver_one':
						$value = $data['tx_civserv_ms_approver_one'];
						break;
					case 'ms_approver_two':
						$value = $data['tx_civserv_ms_approver_two'];
						break;
				}
			}
			if($value == 0){ // mandant roles not set in modelservice container? return!
				$div_open= '
					<div style="
						 border: 2px dashed #666666;
						 width : 90%;
						 margin: 5px 5px 5px 5px;
						 padding: 5px 5px 5px 5px;"
						 >';
				$info_img = '<img'.t3lib_iconWorks::skinImg($this->PH_backPath,'gfx/required_h.gif','width="18" height="16"').' title="'.$GLOBALS['LANG']->sL('LLL:EXT:lang/locallang_core.php:labels.close',1).'" alt="" />';
				$msg .= '<p>'.$info_img.'&nbsp;&nbsp;'.$LANG->getLL("tx_civserv_user_be_msg.tx_civserv_model_service.no_mandant_roles").'</p>';
				$msg = str_replace('###ms_container_name###', $ms_container_name, $msg);
				$msg = str_replace('###ms_container_uid###', $ms_container_pid, $msg);
				return $div_open.$msg.$div_close;
			}else{
				//get human readable names for the mandant-roles
				$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
					'cm_community_name',			 			// SELECT ...
					'tx_civserv_conf_mandant',			// FROM ...
					'cm_community_id  = '.$value,	// AND title LIKE "%blabla%"', // WHERE...
					'', 						// GROUP BY...
					'',   						// ORDER BY...
					'' 							// LIMIT to 10 rows, starting with number 5 (MySQL compat.)
				);
				$row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
				$text = $row['cm_community_name'];			
				$div_open='<div style="width : 90%; margin: 5px 5px 5px 5px; padding: 5px 5px 5px 5px; border:0;">';
				$html_field='<input type="hidden"	name="'.$PA['itemFormElName'].'"
								value="'.$value.'"
								'.$PA['onFocus'].'/>
							<span>'.$text.' ('.$LANG->getLL("tx_civserv_user_be_msg.tx_civserv_model_service.community_code").': '.$value.')</span>';
				$div_close='</div>';
			} //mandant roles not set correctly!!!
		}else{	// old record	
			$value='';
			$text='';
			switch ($PA['field']){
				case 'ms_mandant':
					$value = $PA['row']['ms_mandant'];
					break;
				case 'ms_approver_one':
					$value = $PA['row']['ms_approver_one'];
					break;
				case 'ms_approver_two':
					$value = $PA['row']['ms_approver_two'];
					break;
			}
			//get human readable names for the mandant-roles
			$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
				'cm_community_name',			 			// SELECT ...
				'tx_civserv_conf_mandant',			// FROM ...
				'cm_community_id  = '.$value,	// AND title LIKE "%blabla%"', // WHERE...
				'', 						// GROUP BY...
				'',   						// ORDER BY...
				'' 							// LIMIT to 10 rows, starting with number 5 (MySQL compat.)
			);
			$row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
			$text = $row['cm_community_name'];			
			$div_open = '<div style="width : 90%; margin: 5px 5px 5px 5px; padding: 5px 5px 5px 5px;">';
			$html_field = $text." (Gemeindekennziffer: ".$value.")";
			$div_close = '</div>';
		}
		return $div_open.$html_field.$div_close;
	}
}


/*

   1: class user_class {
   2:     function user_TCAform_test($PA, $fobj)    {
   3:         return '
   4:             <div style="
   5:                     border: 2px dashed #666666;
   6:                     width : 90%;
   7:                     margin: 5px 5px 5px 5px;
   8:                     padding: 5px 5px 5px 5px;"
   9:                     >
  10:                 <h2>My Own Form Field:</h2>
  11:                 <input
  12:                     name="'.$PA['itemFormElName'].'"
  13:                     value="'.htmlspecialchars($PA['itemFormElValue']).'"
  14:                     onchange="'.htmlspecialchars(implode('',$PA['fieldChangeFunc'])).'"
  15:                     '.$PA['onFocus'].'
  16:                     />
  17:             </div>';
  18:     }
  19: }

*/


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/civserv/res/class.tx_civserv_user_be_msg.php']) {
	include_once ($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/civserv/res/class.tx_civserv_user_be_msg.php']);
}

?>
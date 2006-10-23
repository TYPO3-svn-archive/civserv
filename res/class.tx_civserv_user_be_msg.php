<?php
class tx_civserv_user_be_msg {
	function user_TCAform_test($PA=array(), $fobj=array()) { //add array() bits to signature to enable calls to this function with no parameters (happens through "displayCond" => "REC:NEW:true")
		global $LANG;
		$GLOBALS['TYPO3_DB']->debugOutput = TRUE;
		$LANG->includeLLFile("EXT:civserv/res/locallang_user_be_msg.php");
		
		$table=$PA['table'];
		$field=$PA['fieldConf']['dbField']; //dbField is a custom variable introduced in TCA to make the ll-fetching more flexible
		
		$pageid=0;
		//debugging $GLOBALS told me, that I best get the actual page-id out of the returnURL:
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
}


//<a href="http://typo3vm.citeq.de/osiris_svn_ms/typo3/db_list.php?table=''&id=36">back</a>
//<h2 style="color:red">'.$LANG->getLL("tx_civserv_user_be_msg.tx_civserv_employee.em_position").'</h2>

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
if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/civserv/res/class.tx_civserv_user_class.php']) {
	include_once ($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/civserv/res/class.tx_civserv_user_class.php']);
}

?>
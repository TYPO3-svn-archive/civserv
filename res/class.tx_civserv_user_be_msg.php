<?
class tx_civserv_user_be_msg {
	function user_TCAform_test($PA=array(), $fobj=array()) { //add array() bits to signator to enable calling this function with no parameters (happens through "displayCond" => "REC:NEW:true")
		global $LANG;
		$GLOBALS['TYPO3_DB']->debugOutput = TRUE;
		$LANG->includeLLFile("EXT:civserv/res/locallang_user_be_msg.php");
		
		$table=$PA['table'];
		$field=$PA['fieldConf']['dbField']; //dbField is a custom variable introduce in TCA to make the ll-fetching more flexible
		debug($table, 'table');
		debug($field, 'field');
		debug($PA, '$PA');
		debug($fobj, '$fobj'); 
			 return '
            <div style="
                     border: 2px dashed #666666;
                     width : 90%;
                     margin: 5px 5px 5px 5px;
                     padding: 5px 5px 5px 5px;"
                     >
				 <h2>'.$LANG->getLL("tx_civserv_user_be_msg.".$table.".".$field).'</h2>
             </div>';
	}
}

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
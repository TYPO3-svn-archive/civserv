<?php

// obsolete!! is based on old hook introduced by uni münster in 2004, now integrated into Core
// but the new hook to checkModifyAccess is not being called when clicking any of the shortcut-Icons - so is useless for our purposes
// see for hook-implementation in class.tx_civserv_localRecordList_actionsHook.php instead!!!

require_once(PATH_t3lib.'interfaces/interface.t3lib_tcemain_checkmodifyaccesslisthook.php');

class user_checkModifyAccessList implements t3lib_TCEmain_checkModifyAccessListHook {
	
	
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
	public function checkModifyAccessList(&$accessAllowed, $table, t3lib_TCEmain $parent){
		
		echo('<script text="javascript"> alert("miststück"); </script>');
		
		$gpvars = t3lib_div::_GET();
		#debug($gpvars, '$gpvars');
		
		#debug($accessAllowed, '$accessAllowed');//res
		#debug($table, '$table');
		#debug($parent, '$parent');

		if($table == 'tx_civserv_model_service_temp'){
			//da kommt leider nix mehr bei cmdmap :-(
			//wie kann ich also das command abfangen???
			//der schlüssel liegt bei tcemain->process_cmdmap(), die enthält auch einen hook
			//aber wann greift der???
			if(isset($parent->cmdmap) && is_array($parent->cmdmap) && count($parent->cmdmap)>0){
				#debug('lets go!!!');
				foreach($parent->cmdmap['tx_civserv_model_service_temp'] as $id => $incomingCmdArray)	{
					if (is_array($incomingCmdArray))	{
						reset($incomingCmdArray);
						$command = key($incomingCmdArray);
						if ($command == 'delete') $res = 0;
					}
				}
			}
			
		}

		/*
		if (isset($cmdmap) && isset($cmdmap['tx_civserv_model_service_temp'])){
			foreach($cmdmap['tx_civserv_model_service_temp'] as $id => $incomingCmdArray)	{
				if (is_array($incomingCmdArray))	{
					reset($incomingCmdArray);
					$command = key($incomingCmdArray);
					if (!$pObj->admin && $command == 'delete') $res = 0;
				}
			}
		}
		*/
	}
	
	
}


?>
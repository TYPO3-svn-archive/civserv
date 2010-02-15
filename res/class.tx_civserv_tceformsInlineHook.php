<?php

require_once(PATH_t3lib.'interfaces/interface.t3lib_tceformsInlineHook.php');

// obsolete!!!
// only for IRRE (might be used in a later version of civserv
// not yet implemented anyhow!!!


class user_tceformsInlineHook implements t3lib_tceformsInlineHook{
	//see: http://lists.typo3.org/pipermail/typo3-project-irre/2009-July/000352.html
	
		/**
	 * Initializes this hook object.
	 *
	 * @param	t3lib_TCEforms_inline		$parentObject: The calling t3lib_TCEforms_inline object.
	 * @return	void
	 */
	public function init(&$parentObject){
		//???
	}

	/**
	 * Pre-processing to define which control items are enabled or disabled.
	 *
	 * @param	string		$parentUid: The uid of the parent (embedding) record (uid or NEW...)
	 * @param	string		$foreignTable: The table (foreign_table) we create control-icons for
	 * @param	array		$childRecord: The current record of that foreign_table
	 * @param	array		$childConfig: TCA configuration of the current field of the child record
	 * @param	boolean		$isVirtual: Defines whether the current records is only virtually shown and not physically part of the parent record
	 * @param	array		&$enabledControls: (reference) Associative array with the enabled control items
	 * @return	void
	 */
	public function renderForeignRecordHeaderControl_preProcess($parentUid, $foreignTable, $childRecord, $childConfig, $isVirtual, &$enabledControls){
		#debug($parentUid, '$parentUid');
		#debug($foreign_table, '$foreign_table');
		#debug($rec, '$rec');
		#debug($config, '$config');
		#debug($isVirtual, '$isVirtual');
		#debug($enabledControls, '$enabledControls');
	}

	/**
	 * Post-processing to define which control items to show. Possibly own icons can be added here.
	 *
	 * @param	string		$parentUid: The uid of the parent (embedding) record (uid or NEW...)
	 * @param	string		$foreignTable: The table (foreign_table) we create control-icons for
	 * @param	array		$childRecord: The current record of that foreign_table
	 * @param	array		$childConfig: TCA configuration of the current field of the child record
	 * @param	boolean		$isVirtual: Defines whether the current records is only virtually shown and not physically part of the parent record
	 * @param	array		&$controlItems: (reference) Associative array with the currently available control items
	 * @return	void
	 */
	public function renderForeignRecordHeaderControl_postProcess($parentUid, $foreignTable, $childRecord, $childConfig, $isVirtual, &$controlItems){
		//???
	}
	
	

}



?>
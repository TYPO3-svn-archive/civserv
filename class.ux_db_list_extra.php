<?php
/** 
* User-Extension of localRecordList in class db_list_extra.
*
* @author    Karina Niehüser <niehueser@citeq.de>
*
* this class extends typo3 core class with layout/output function for TYPO3 Backend Scripts
* overrides the standard max title length in BackEnd
*
*
*/

class ux_localRecordList extends localRecordList {
	var $fixedL = 100;
}	
?>
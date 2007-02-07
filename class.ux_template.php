<?php

/** 
* User-Extension of template class.
*
* @author    Britta Kohorst <kohorst@citeq.de>
*
* this class extends typo3 core class with layout/output function for TYPO3 Backend Scripts
*
* aim: suppress "new version of page" - button for all be_users!
* for the virtual townhall osiris, its all about non-pages records 
* and no matter what rights the be_user has, we don't allow new versions of pages (which would 
* turn out to be new versions of sysfolders storing civserv-records. that would make no sense)
*
* svn still testin damg
*/

class ux_template extends template {    

	function getVersionSelector($id,$noAction=FALSE){            
		return '<br />';    
	}
}
?>

<?php
/** 
* User-Extension of browse_links class.
*
* @author    Karina Niehüser <niehueser@citeq.de>
*
* this class extends typo3 core class with layout/output function for TYPO3 Backend Scripts
* overrides the standard adress of external urls in Rich Text Editor
*
*
*/

class ux_browse_links extends browse_links {
	function main_rte($wiz=0){
		if(htmlspecialchars($this->curUrlInfo['act']=='url')){
			$this->curUrlInfo['act']='nix';
		}
		return parent:: main_rte($wiz);
	}
}	
?>
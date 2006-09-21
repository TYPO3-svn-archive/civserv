<?php
class ux_browse_links extends browse_links {
	function main_rte($wiz=0){
		if(htmlspecialchars($this->curUrlInfo['act']=='url')){
			$this->curUrlInfo['act']='nix';
		}
		return parent:: main_rte($wiz);
	}
}	
?>
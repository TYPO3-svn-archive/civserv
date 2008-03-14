<?php
/***************************************************************
* Copyright notice
*
* (c) 2004 ProService (osiris@ercis.de)
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
* Updates some content for Employee-Position-Relationship which have to be made as a workaround for non-existing Typo3 functionality.
*
* Some scripts that use this class: tca.php (Invocation), ext_tables.php (Definition), ext_localconf.php (Definition)
* Depends on: ?
*
* $Id: class.tx_civserv_miscupdate.php 7359 2007-12-03 10:27:16Z bkohorst $
*
* @author Christoph Rosenkranz (rosenkra@uni-muenster.de)
* @package TYPO3
* @subpackage tx_civserv
* @version 1.0
*
* Changes: 23.08.04, CR - Initial Build
*		   02.08.04, GN - the main-method isn't needed any longer
*/
/**
* [CLASS/FUNCTION INDEX of SCRIPT]
*
*/

require_once ($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/civserv/res/class.tx_civserv_mandant.php']);

/**
* Updates some content for Employee-Position-Relationship which have to be made as a workaround for non-existing Typo3 functionality.
*/
class tx_civserv_miscupdate {
	/**
	* FUNKTIONIRT NICHT!!!!
	* Updates the label of all employee-position-relations to get a speaking name for mm-relation-entries
	* This is a workaround which is needed in Typo3 at the moment because the labels of a record (defined in ext_tables.php)
	* can only consist of attributes in the same record out of the same table and can't be resolved out of foreign-relations
	*
	* @param	string		$params are parameters sent along to alt_doc.php. This requires a much more details description which you must seek in Inside TYPO3s documentation API
	* @return	void
	*/
	function update_orcode($params){
		// experimental: make function faster by including $params['uid'] in where-clause - if available i.e. uid != 'NEW12345'
		// ATTENTION: this only makes sense in combination with a displaycond on tx_civserv_organisation in TCA!!!
		if (is_array($params) && ($params['table']== 'tx_civserv_organisation')) {	
			$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
				'tx_civserv_organisation.uid,
				 tx_civserv_organisation.or_code',		// SELECT
				'tx_civserv_organisation', 				// FROM
				'',										// WHERE
				'', 									// Group by
				'', 									// Order by
				''										// limit
				);
			while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
				$GLOBALS['TYPO3_DB']->exec_UPDATEquery(
					'tx_civserv_organisation', 	// update table
					'uid = '.$GLOBALS['TYPO3_DB']->quoteStr($row['uid'], 'tx_civserv_organisation'),  // where
					array ("or_code" => $this->replace_umlauts($row['or_code']))	// set... (array)
				);
			}
		}
	}//end function update_orcode
	
	 /******************************
	 *
	 * Functions for Custom-Links, borrowed from Frontend-Classes....
	 *
	 *******************************/
	function replace_umlauts($string){
		// remove all kinds of umlauts
#		debug($string);
		$umlaute = Array("/ä/", "/ö/", "/ü/", "/Ä/", "/Ö/", "/Ü/", "/ß/", "/é/"); //should use hexadecimal-code for é à etc????
		$replace = Array("ae", "oe", "ue", "Ae", "Oe", "Ue", "ss", "e");
		$string = preg_replace($umlaute, $replace, $string);
		
		//eliminate:
		$string=str_replace(".", "", $string);			// 'Bücherei Zweigstelle Wolbecker Str.'				--> buecherei_zweigstelle_wolbecker_str.html
		$string=str_replace(" - ", "-", $string);		// 'La Vie - Begegnungszentrum Gievenbeck'				--> la_vie-begegnungszentrum_gievenbeck.html
		$string=str_replace("- ", "-", $string);		// 'Veterinär- und Lebensmittel...'						--> veterinaer-und_lebensmittel.html
		$string=str_replace("-, ", " ", $string);		// 'Amt für Stadt-, Verkehrs- und Parkplatzplanung'		--> amt_fuer_stadt_verkehrs_und_parkplatzplanung.html
		$string=str_replace(",", "", $string);			// 'Ich, du, Müllers's Kuh'								--> ich_du_muellers_kuh.html
		$string=str_replace(": ", " ", $string);		// 'Gesundheitsamt: Therapie und Hilfe sofort'			--> gesundheitsamt_therapie_und_hilfe_sofort.html

		//make blanks:
		$string=str_replace("+", " ", $string);			// 'Wohn + Stadtbau'
		$string=str_replace("&", " ", $string);			// 'Ich & Ich'
		$string=str_replace("/", " ", $string);			// 'Feuerwehr/Rettungsdienst'
		$string=str_replace("\\", " ", $string);		// 'Eins\Zwei\Drei'
		return $string;
	}//end function replace_umlauts
}//end class	

/**
* Definition of class (needed for extension)
*/
if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/civserv/res/class.tx_civserv_miscupdate.php']) {
	include_once ($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/civserv/res/class.tx_civserv_miscupdate.php']);
}
?>
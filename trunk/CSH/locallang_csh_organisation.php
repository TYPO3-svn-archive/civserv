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
* the following lines implement the CONTEXT SENSITIVE HELP.
*
* Default  TCA_DESCR for "tx_civserv_organisation" table
*
*
* @@author Sabine Robert (srobert@uni-muenster.de),
*/


$LOCAL_LANG = Array (
    "default" => Array (
		"or_image.details" => "1st field: The image can be loaded from a local folder of Typo3. \n 2nd field: An external stored image can be integrated.",
		"or_infopage.details" => "To the organizational unit can be assigned to an existing information side.",
	    "or_structure.details" => "The organizational unit can be subordinated to another, already existing unit."  
    ),
    "de" => Array (    
		"or_image.details" => "1. Feld: Das Bild kann aus einem lokalen Ordner von Typo3 geladen werden. \n 2. Feld: Es kann ein extern gespeichertes Bild eingebunden werden.",
		"or_infopage.details" =>" Der Organisationseinheit kann eine existierende Informationsseite zugeordnet werden. Dafür muss die URL dieser Seite in dem erscheinenden Popup-Fenster angegeben werden.",
	    "or_structure.details" => "Die Organisationseinheit kann einer anderen, schon existierenden untergeordnet werden."  
	),
    
);
?>
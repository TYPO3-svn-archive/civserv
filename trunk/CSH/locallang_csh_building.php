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
* Default  TCA_DESCR for "tx_civserv_building" table
*
*
* @@author Sabine Robert (srobert@uni-muenster.de),
*/

$LOCAL_LANG = Array (
    "default" => Array (
		"bl_pubtrans_stop.details" => "Fill in the name of the  nearest busstop",
		"bl_pubtrans_url.details" => "An InterNet side of the OEPNV can be assigned to the building. Therefore the URL must be filled in the Popup-window.",
		"bl_image.details" => "1st field: The image can be loaded from the local folder of Typo3. \n 2nd field: An external stored image can be integrated."
    ),
	"de" => Array (
		"bl_pubtrans_stop.details" => "Es kann der Name der nächstliegenden Haltestelle eingegeben werden.",
		"bl_pubtrans_url.details" => "Es kann eine Internetseite des ÖPNV dem Gebäude zugeordnet werden. Dazu muss die URL in das erscheinende Feld des Popup-Fenstern eingegeben werden.",
		"bl_image.details" => "1. Feld: Das Bild kann aus einem lokalen Ordner von Typo3 geladen werden.\n 2. Feld: Es kann ein extern gespeichertes Bild eingebunden werden."
    ),
);
?>
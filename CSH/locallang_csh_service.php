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
* Default  TCA_DESCR for "tx_civserv_service" table
*
*
* @@author Sabine Robert (srobert@uni-muenster.de),
*/


$LOCAL_LANG = Array (
    "default" => Array (
        "sv_model_service.details" => "The service can base on an existing model service. The fields, that are thereby filled out, cannot be changed.",
		"sv_synonym1.details" => "The synonym will be integrated in the navigation and leads to this model service. ",
		"sv_3rdparty_checkbox.details" => "With activation of this checkbox an external service can be integrated with a link. An external service does not belong to the offer of the administration.",
		"sv_image_text.details" => "For granting accessibility statement, a description of the image has to be added. It can for example be read to visually handicapped persons by reading machines.",
		"sv_descr_long.details" => "Long description for the service. If service is based on a model service, this description is an extra description, which is shown in the frontend under the description from the model service!",
		"sv_image.details" => "1st field: The image can be loaded from a local folder of Typo3. \n 2nd field: An external stored image can be integrated."
    ),
	"de" => Array (
		"sv_model_service.details" => "Es kann ein existierendes Musteranliegen zugrunde gelegt werden. Die dadurch ausgefüllten Felder stehen für die Bearbeitung nicht mehr zur Verfügung.",
		"sv_synonym1.details" => "Das eingegebene Synonym wird in die Navigation mit aufgenommen und führt zu dieser Dienstleistung.",
		"sv_3rdparty_checkbox.details" => "Durch Aktivieren dieser Checkbox kann eine externe Dienstleistung über einen Link eingebunden werden. Eine externe Dienstleistung gehört nicht zu dem Angebot der Verwaltung.",
		"sv_image_text.details" => "Um Barrierefreiheit zu gewährleisten, sollte eine Bildbeschreibung eingefügt werden. Somit kann für Sehbehinderte der Text durch ein Vorleseprogramm ausgegeben werden.",
		"sv_descr_long.details" => "Hier wird die allgemeine Beschreibung der Dienstleistung eingepflegt. \nSollte die Dienstleistung auf einem Musteranliegen basieren, so wird im Frontend zunächst die Beschreibung des Musteranliegens und darunter die hier eingepflegte Beschreibung angezeigt. In diesem Fall stellt diese Beschreibung also eine Zusatzbeschreibung dar.",
		"sv_image.details" => "1. Feld: Das Bild kann aus einem lokalen Ordner von Typo3 geladen werden.\n 2. Feld: Es kann ein extern gespeichertes Bild eingebunden werden."
    ),
);
?>
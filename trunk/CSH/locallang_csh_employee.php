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
* Default  TCA_DESCR for "tx_civserv_employee" table
*
*
* @@author Sabine Robert (srobert@uni-muenster.de),
*/


$LOCAL_LANG = Array (
    "default" => Array (
		"em_image.details" => "1st field: The image can be loaded from a local folder of Typo3. \n 2nd field: An external stored image can be integrated.",
		"em_datasec.details" =>"Only with Activating of this checkbox the dates of the employee will be released for the internet. The dates are salutation, title, first name, name, telephone number, fax number, e-mail, image and office hours." ,
		"em_number.details" => "The ID helps with the unequivocal identification of the employee.",
		"em_mobile.details" => "The mobile phone number of the employee is just used for internal needs. It is not published in the internet."
    ),
	"de" => Array (
		"em_image.details" => "1. Feld: Das Bild kann aus einem lokalen Ordner von Typo3 geladen werden. \n 2. Feld: Es kann ein extern gespeichertes Bild eingebunden werden.",
		"em_datasec.details" =>"Nur durch Aktivieren dieser Checkbox werden die Daten des Mitarbeiters für das Internet freigeschaltet. Dazu gehören Anrede, Titel, Vor- und Nachname, Telefonnummer, Faxnummer, E-Mail, Bild und öffentliche Erreichbarkeitszeiten." ,
		"em_number.details" => "Die ID hilft bei der eindeutigen Identifizierung des Mitarbeiters.",
		"em_mobile.details" => "Die Dienstnummer des Mitarbeiters wird nur für interne Zwecke verwendet und nicht im Internet veröffentlicht."
    ),
);
?>
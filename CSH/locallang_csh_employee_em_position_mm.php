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
* Default  TCA_DESCR for "tx_civserv_employee_em_position_mm" table
*
*
* @@author Sabine Robert (srobert@uni-muenster.de),
*/


$LOCAL_LANG = Array (
    "default" => Array (
		"ep_telephone.details" => "If there is not filled out the phone number field, it is published the phone number of the employee in the internet.",
		"ep_fax.details" => "If there is not filled out the fax number field, it is published the fax number of the employee in the internet.",
		"ep_email.details" => "If there is not filled out the email-adress field, it is published the email-adress of the employee in the internet.",
		"ep_mobile.details" =>"If there is not filled out the mobile phone number field, it is published the mobile phone number of the employee in the internet.",
	    # "ep_room.details" => "If it is not chosen a room, it is published the assigned room of the employee in the internet.",
		"ep_officehours.details" => "If it is not chosen office hours, there are published the assigned office hours of the employee in the internet."
    ),
	"de" => Array (
		"ep_telephone.details" => "Wenn an dieser Stelle keine Telefonnummer eingegeben wird, erscheint im Internet die Telefonnummer des Mitarbeiters.",
		"ep_fax.details" => "Wenn an dieser Stelle keine Faxnummer eingegeben wird, erscheint im Internet die Faxnummer des Mitarbeiters. ",
		"ep_email.details" => "Wenn an dieser Stelle keine E-Mail-Adresse eingegeben wird, erscheint im Internet die E-Mail-Adresse des Mitarbeiters.",
		"ep_mobile.details" =>"Wenn an dieser Stelle keine Mobilnummer eingegeben wird, wird für interne Zwecke die Mobilnummer des Mitarbeiters verwendet.",
	     # "ep_room.details" => "Wenn an dieser Stelle kein Raum ausgewählt wird, erscheint im Internet der zugeordnete Raum des Mitarbeiters.",
		"ep_officehours.details" => "Wenn an dieser Stelle keine Öffnungszeiten ausgewählt werden, erscheinen im Internet die Öffnungszeiten des Mitarbeiters."
    ),
);
?>
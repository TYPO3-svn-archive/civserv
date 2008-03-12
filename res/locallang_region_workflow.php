<?php
/***************************************************************
*  Copyright notice - www4.citeq.de: osiris_muenster special edition
*
*  (c) 2004 ProService (osiris@ercis.de)
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/
/**
 * Language labels for plugin 'civserv_pi1'
 *
 * This file is detected by the translation tool.
 *
 **/



$LOCAL_LANG = Array (
	"default" => Array (
		"xyz.emailmessage.subject" => "External Service: '###service_name###' at ###external_service_community###",
		"xyz.emailmessage.header" => "This E-Mail is automatically created by the O.S.I.R.I.S.-External-Service-Workflow of ###sitename###! Please do NOT reply to this E-Mail!\n***********************************\n\nHello ###mandant_name###,\n",	
		"xyz.emailmessage.service_new" => "The external service -- ###service_name### -- (donated by ###service_community###) has been edited.\n",	
		"xyz.emailmessage.service_deleted" => "The external service -- ###service_name### -- (donated by ###service_community###) has been eleminated from your folder '###external_service_folder###'. This means the donator has either deleted the service or is no longer forwarding it to you.\n",	
		"xyz.emailmessage.service_link" => "To view the service at the donator's klick the following link: ###service_link###\n\n",
		"xyz.emailmessage.order" => "Please check in your folder '###external_service_folder###' that the external service is connected to a navigation element."
	),
	"de" => Array (
		"xyz.emailmessage.subject" => "Ext. Dienstl. '###service_name###' in ###external_service_community###",
		"xyz.emailmessage.header" => "Diese E-Mail wurde automatisch durch den O.S.I.R.I.S.-Externe-Dienstleistungen-Workflow von ###sitename### generiert!\nBitte antworten Sie NICHT auf diese E-Mail!\n***********************************\n\nHallo ###mandant_name###,\n",	
		"xyz.emailmessage.service_new" => "Die externe Dienstleistung -- ###service_name### -- (erstellt durch ###service_community###) wurde bearbeitet.\n",	
		"xyz.emailmessage.service_deleted" => "Die externe Dienstleistung -- ###service_name### -- (erstellt durch ###service_community###) wurde aus Ihrem Ordner '###external_service_folder###' entfernt. Der Ersteller hat die Dienstleistung entweder gelöscht, oder er leitet sie nicht mehr an Ihre Kommune weiter.\n",	
		"xyz.emailmessage.service_link" => "Um die Dienstleistung im Auftritt des Erstellers zu sehen, klicken Sie auf nachfolgenden Link: ###service_link###\n\n",
		"xyz.emailmessage.order" => "Bitte öffnen Sie Ihren Ordner '###external_service_folder###' und stellen sicher, dass die externe Dienstleistung mit einem Navigationselement verknüpft wurde."
	),
);
?>
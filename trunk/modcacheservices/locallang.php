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
* Language labels for module "tools_txcivservmodcacheservices"
* 
* This file is detected by the translation tool.
*
* @@author Stefan Meesters <meesters@uni-muenster.de>
*/

$LOCAL_LANG = Array (
	'default' => Array (
		'title' => 'Cache services',
		'overview' => 'Overview',
		'caching' => 'Now caching the services, please wait ...',
		'done' => 'Done.',
		'elapsedTime' => 'Elapsed time',
		'seconds' => 'second(s)',
		'processed' => 'Services processed',
		'returnToMainMenu' => 'Return to main menu',
		'cancel' => 'Cancel',
		'note' => 'Note',
		'note_text' => 'Depending on the count of services and the server hardware this process might take quite a while! The load of your system will be very high durig this time!',
		'error' => 'An error occured during an database query. Please check the configuration.',
		'local_services' => 'At the selected community located (local) services',
		'external_services' => 'At the another community located externel services',		
		'services' => 'Services to cache total',
		'startCaching' => 'Start caching',
		'page_id' => 'Page id',	
		'description' => 'This module is for caching pages containing the plugin "Virtual Civil Services (civserv)" in order to build the indizes for the fulltext search of the extension "Indexed Search Engine (indexed_search)". Therefore the given page is loaded by wget several times with differend url parameters, so that every service is indexed.<br>You have to ensure, that the page really contains the plugin.',
		'caching_note' => 'Remember that the site can not be cached in following cases',
		'no_cache1' => 'The page contains a login',
		'no_cache2' => 'The page is using the no_cache attribut',
		'no_cache3' => 'The page contains an extension which uses no_cache',
	),
	'de' => Array (
		'title' => 'Anliegen cachen',
		'overview' => 'Überblick',
		'caching' => 'Cache nun die Anliegen. Bitte warten ...',
		'done' => 'Fetig.',
		'elapsedTime' => 'Verstrichene Zeit',		
		'seconds' => 'Sekunde(n)',
		'processed' => 'Anliegen gecached',
		'returnToMainMenu' => 'Zurück zum Hauptmenü',
		'cancel' => 'Abbrechen',
		'note' => 'Hinweis',
		'note_text' => 'Abhängig von der Anzahl an Dienstleistungen und der Ausstattung des Servers kann dieser Prozess sehr lange dauern! Die Auslastung des Systems wird während dieser Zeit sehr hoch sein!',
		'error' => 'Während einer Datenbankabfrage ist ein Fehler aufgetreten. Bitte überprüfen Sie die Konfiguration.',
		'local_services' => 'Bei dieser Kommune beheimatete (lokale) Anliegen',
		'external_services' => 'Bei einer anderen Kommune beheimatete externe Anliegen',		
		'services' => 'Anliegen insgesamt zu cachen',		
		'startCaching' => 'Anliegen cachen',		
		'page_id' => 'Seiten-ID',		
		'description' => 'Dieses Modul ist zum Cachen von Seiten, welche das Plugin "Virtual Civil Services (civserv)" enthalten, um den Index für die Volltextsuche der Extension "Indexierungs-Engine (indexed_search)" aufzubauen. Dazu wird die Seite mit Hilfe von wget mehrfach mit verschiedenen URL-Parametern aufgerufen, so dass jedes Anliegen indiziert wird.<br>Sie haben sicherzustellen, dass die Seite tatsächlich das Plugin enthält. Andernfalls wird die Volltextsuche fehlerhafte Ergebnisse liefern!',
		'caching_note' => 'Beachten Sie, daß eine Seite in folgenden Fällen nicht gecacht werden kann',
		'no_cache1' => 'Die Seite enthält ein Login.',
		'no_cache2' => 'Die Seite benutzt das no_cache-Attribut.',
		'no_cache3' => 'Die Seite enthält eine andere Extension, die das no_cache-Attribut nutzt.',
	),
);
?>
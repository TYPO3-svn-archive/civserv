README (ENGLISCH)
The complete documentation, containing a technical documentation and a user handbook, can be found in german language at 
http://www4.citeq.de/osiris/doc

MANUAL SETUP: 
After you have installed the extension via the extension manager, please follow the instructions in the manual in order to achieve a fully configured virtual town hall with a pagetree and a frontend output.

DEMO:
You can have an example site with dummy-data: 
use the 'ext_tables_static+adt.sql.ATTENTION' file if there is one in civserv/doc/ (import via command-line with the command 'mysql')
This db-dump will kill any existing site: It will completey replace all civserv-tables, the pages-, be_groups and be_users-tables in your database!
The resources (DB-DUMP, fileadmin) for the demo_site are available in 
http://www4.citeq.de/osiris/doc/demo_site/1110000.tar.gz. 
You have to un-tar the file in your Site to the path: fileadmin/civserv/1110000
After you have run the db-dump, the site will have the follwing be_users:
adminn ('password')
c00.chefeditor ('chef')
c00.editor ('editor')


ANYHOW:
After you have installed the extension 'civserv' via the extension manager you have to
put a .htaccess into the base-folder of your site. The .htaccess must contain directions for simulating static documents (default configuration of civserv in typo3conf/ext/civserv/pi1/static/setup.txt)
If you run several sites in your webroot you need to indicate a rewrite base in the .htaccess file.
For further information see: 
http://jweiland.net/typo3cms/howto/statische-seiten-simulieren/




LIES MICH (DEUTSCH)
Die komplette Dokumentation, bestehend aus der technischen Dokumentation und einem Benutzerhandbuch, kann unter http://www4.citeq.de/osiris/doc heruntergeladen werden.
Unter www.regio-komm.de sind auch die zugrunde liegende umfassende Anforderungsanalyse an Virtuelle Rathäuser und das Fachkonzept als Arbeitsberichte des Instituts für Wirtschaftsinformatik zu finden. 

MANUELLES SETUP:
Nach der Installation der Extension über den Extension Manager folgen Sie bitte der Setup-Anleitung im Manual um das Grundgerüst für ein virtuelles Rathaus mit Seitenbaum und Frontend-Ausgabe aufzubauen.

DEMO:
Sie können eine Demo-Site mit Testdaten installieren: 
benutzen Sie dazu die Datei 'ext_tables_static+adt.sql.ATTENTION' wenn sie im Pfad civserv/doc vorhanden ist (Der SQL-Dump muss per Kommandozeile über den MySQL-Befehl 'mysql' eingespielt werden)
Dieser Datenbank Dump überschreibt eine ggfs existierende Site: er ersetzt alle tx_civserv* Tabellen, sowie die Tabellen pages, be_groups und be_users aus Ihrer Datenbank!!!
Die Ressourcen für die Demo_Site befinden sich in 
http://www4.citeq.de/osiris/doc/demo_site/1110000.tar.gz. 
Entpacken Sie diese Archiv in Ihrer Site im Pfad fileadmin/civserv/1110000
Nachdem Sie den Datenbank-Dump eingspielt haben, stehen Ihnen folgende BE_user zur Verfügung:
adminn ('password')
c00.chefeditor ('chef')
c00.editor ('editor')
 

IN JEDEM FALL:
Nachdem Sie die 'civserv' Extension über den Extension Manager installiert haben, müssen Sie
eine .htaccess Datei im Basis-Verzeichnis ihrer Site ablegen. Die wird gebraucht, weil civserv standardmäßig mit simulate_static_documents konfiguriert ist (Siehe typo3conf/ext/civserv/pi1/static/setup.txt)
Wenn Sie mehrer Sites in Ihrem Webroot-Verzeichnis haben, müssen Sie in der .htaccess auch eine Rewrite-Base angeben.
Weitere Informationen dazu finden Sie auf der Homepage von Jochen Weiland: 
http://jweiland.net/typo3cms/howto/statische-seiten-simulieren/

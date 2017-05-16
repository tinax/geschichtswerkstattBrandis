# Geschichtswerkstatt Brandis
Plattform-Applikation um geschichtliche Beiträge auf einer Karten und Zeitleisten-Ansicht darzustellen.

Demo bzw. Orginal Applikation unter: [Geschichtswerkstatt Brandis](http://geschichtswerkstatt.brandis.eu/)

Weitere Beispielseiten: [Geschichtslab-Plattform](http://geschichtslab.community-infrastructuring.org/)

Benutzt als Backend Wordpress (V 4.6.1)! 

   
   Das Tool funktioniert einwandfrei mit dieser Worpress-Version, andere Wordpress-Versionen wurden nicht getestet, von daher  sollte für den vollen Funktionsumfang WordPress NICHT geupdatet werden!

Beiträge werden zum Einen auf einer Leaflet Map + Zeitleiste dargestellt.

Ausserdem gibt es eine Listenansicht.

## Installation der Plattform-Applikation

Um mit der Plattform-Applikation zu arbeiten:

1. Wordpress installieren
    - Download der [Wordpress Version 4.6.1](https://wordpress.org/download/release-archive/)
    - Installation nach folgender Anleitung:
      [Wordpress in 5 Minuten istallieren](https://www.blogaufbau.de/wordpress-installieren-in-5-minuten/)
    
2. "Brandis"-Theme über ein FTP-Programm (z.B. [Filezilla](https://filezilla-project.org/) oder [CyberDuck](https://cyberduck.io/)) in das entsprechende Wordpress-Verzeichnis (../wp-content/themes) laden 

3. Alle Plugins über FTP in das entsprechende Wordpress-Verzeichnis (../wp-content/plugins) laden

![](ScreenshotFTP.jpg)

(Screenshot FTP Wordpress-Verzeichnis)


4. Plugins in WordPress installieren (FTP-Programm, wie Theme-Installation) & aktivieren (Wordpress-Dashboard -> Plugins -> aktivieren) 

  Zu installierende Plugins: Advanced Custom Fields Pro, Custom Post Type UI, Mobble, WP-Geo

![](WordpressPlugins.JPG)

(So sollte die Pluginübersicht anschließend aussehen (OHNE Address Geocoder und JSON API) )

  
5. Theme "Brandis" in Wordpress aktivieren

![](WordpressTheme.JPG)

(Aktiviertes Brandis-Theme)


6. Beispiel-Daten (geschichtswerkstattbrandis.wordpress.xml) über WordPress-Dashboard -> Werkzeuge/Daten importieren einspielen

![](WordpressDatenImport.jpg)

(Wordpress Datenimport, dazu zunächst WordPress Datenimport installieren)

ODER

6. Beispiel DB-Dump (geschichtswerkstatt.sql) z.B. über [PHPMyAdmin](http://migratetowp.com/faqs/importing-a-sql-file-with-your-wordpress-data/) einspielen

Nun müsste es laufen...
Dann kann man Beiträge löschen und eigene Beitäge einspielen. (Wordpress-Dashboard -> Beiträge -> Aktion wählen -> In Papierkorb legen)

![](PostDelete.jpg)


## Anlegen neuer Posts

Die folgenden Screenshots zeigen, wie neue Beiträge angelegt werden. Dafür Im Worpress-Dashboard auf "Beiträge" und anschließend "Erstellen" klicken:

![](TestBeitrag1.jpg) ![](TestBeitrag2.jpg) ![](TestBeitrag3.jpg)

So sollte der Beitrag dann auf der Webseite aussehen:

![](msp.png) ![](lidt_view.png)





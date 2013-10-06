SEO42 AddOn für REDAXO 4.5+
===========================

Ein intergalaktischer Fork des original RexSEO AddOns für REDAXO mit alternativer Benutzerführung und noch ein paar weiteren SEO-Goodies...

Features
--------

* Generierung von suchmaschinenfreundlichen URLs (Webserver benötigt Modul `mod_rewrite`)
* Automatische Umschreibung der Startseite der Website in `/` (für alle Sprachen möglich)
* Verschiedene URL-Schemas verfügbar (z.B. `.html`)
* Automatische Titel-Generierung, mitgeliefertes Titel-Schema inspiriert von Google-Empfehlung
* Extra SEO-Page für jeden Artikel inkl. Titel-Vorschau und Zeichen/Wörter Zähler
* Extra URL-Page für jeden Artikel inkl 10 URL-Typen zur Manipulierung der generierten URL
* Automatische `sitemap.xml` und `robots.txt` Generierung
* Neue vereinfachte Setup-Routine, benötigt keine MetaInfos mehr
* Option um vollständige URLs inkl. Domainname wie bei WordPress zu erzeugen
* One Page Mode für Websites die nur über eine Seite verfügen (z.B. Parallax-Websites etc.)
* Suchmaschinenfreundliche Image Manager Urls durch Verwendung der verfügbaren PHP-Methoden
* SEO Tools inkl. Live PageRank Checker sowie Anzeige des Google Index der aktuellen Website
* Klasse `rex_navigation42` zum Bauen von Navigationen. Alle URL-Typen werden berücksichtigt.
* Einrichtung von 301 Umleitungen über das Redirects-Plugin
* Spezielle sprachabhängige sowie sprachunabhängige Sonderzeichen-Umschreibungen einstellbar
* Lang Slugs (de, en) können unabhängig von den REDAXO Sprachnamen gesetzt werden
* Automatische `rel="alternate"` Tags für mehrsprachige Websites
* Option um die Indizierung von Seiten durch Suchmaschinen zu verhindern
* Automatische sowie individuelle Canonical URLs
* Nicht-WWW zu WWW Umleitung (und umgekehrt). Lässt sich auch über das Setup aktivieren.
* Weitere Einstellungen (vorerst) in der `settings.advanced.inc.php` und `settings.lang.inc.php`
* Kompatibel zum Website Manager AddOn
* Enthält die Antwort auf die eine Frage ;)

Verfügbare Plugins
------------------

* `redirects` von RexDude (ist beigelegt)
* `url_control` von tbaddade: <https://github.com/tbaddade/redaxo_plugin_url_control>

Alle URL-Typen aktivieren
-------------------------

* Einige Url-Typen greifen erst, wenn bei der Ausgabe der Navigation auf diese reagiert wird.
* Die Klasse `rex_navigation42` (ehemals `rex_navigation_ex`) unterstützt diese Typen bereits (ab Version 2.1.0 dem AddOn beigelegt).
* Über die Option `all_url_types` können diese bei Bedarf aber auch deaktiviert werden.

Anpassungen für das Community Addon
-----------------------------------

Diese Anpassungen sind nur nötig, wenn man die `rex_navigation42` Klasse verwenden will:

* [Diese Zeile](https://github.com/dergel/redaxo4_community/blob/master/plugins/auth/config.inc.php#L19) auskommentieren
* Und [diese Zeile](https://github.com/RexDude/seo42/blob/master/classes/class.rex_navigation42.inc.php#L3) umschreiben in `class rex_navigation42 extends rex_com_navigation`

Update von REXSEO42 1.1/1.2 auf SEO42 2.x
-----------------------------------------

Ein Update wird nur empfohlen, wenn die neuen Features von der 2er Version benötigt werden. Bitte gleich auf die neuste 2.1.2 aktualisieren.

* In der `settings.advanced.inc.php` von REXSEO42 die Option `drop_dbfields_on_uninstall` auf `false` setzen.
* REXSEO42 deinstallieren und AddOn-Ordner löschen.
* SEO42 installieren.
* In allen Templates den Klassennamen von `rexseo42` nach `seo42` umbenennen.
* AddOn-Einstellungen von Hand nachprüfen und ggf. korrigieren.
* Ggf. Cache löschen.

Hinweise
--------

* Läuft nur mit REDAXO 4.5+
* AddOn-Ordner lautet: `seo42`
* AddOn wurde seit Version 2.0.0 von REXSEO42 in SEO42 umbenannt.
* Wenn der Webserver einen 500 Server Error meldet, die Zeile `Options -Indexes` in der `.htaccess` auskommentieren.
* Geändertes Verhalten für REDAXO Unterordner-Installationen. Bitte FAQ in der Hilfe des AddOns anschauen für weitere Infos.
* Implementiert man sein eigenes Titel-Schema, ist es vielleicht sinnvoll die Optionen `title_preview` und `no_prefix_checkbox` auf `false` zu setzen.
* `$REX["MOD_REWRITE"]` braucht nicht mehr auf `true` gesetzt werden (z.B. über die System-Page von REDAXO). Wenn SEO42 aktiv, wird es automatisch gesetzt.
* Eine hilfreiche Sprach-Sonderzeichen-Tabelle für die Ermittlung der Sonderzeichen-Umschreibungen für die `settings.lang.inc.php` findet man hier: <http://unicode.e-workers.de/>
* Vorläufige Sammlung der Lang-Presets hier: <https://github.com/RexDude/seo42/issues/61>
* Momentan muss man noch von Hand benötigte Einstellungen in den Dateien `settings.advanced.inc.php` und `settings.lang.inc.php` vornehmen. Danach sollte der Cache gelöscht werden. Ab Version 3.0.0 sollten diese Dateien dann der Vergangenheit angehören ;)

Hinweise zum Redirects Plugin
-----------------------------

* Bitte Urls immer mit einem Splash beginnen, die Ziel Url kann aber auch mit `http://` beginnen. Plugin ist Website Manager kompatibel.

FAQ
---

siehe [FAQ.md](FAQ.md)

Changelog
---------

siehe [CHANGELOG.md](CHANGELOG.md)

Lizenz
------

siehe [LICENSE.md](LICENSE.md)

Credits
-------

* [GN2](https://github.com/gn2netwerk) und [jdlx](https://github.com/jdlx) für das original RexSEO AddOn
* [Markus Staab](https://github.com/staabm) für das zugrundeliegende url_rewrite AddOn
* [Jan Kristinus](http://github.com/dergel) für REDAXO und den neuen EP in REDAXO 4.5
* [Gregor Harlan](https://github.com/gharlan) und [Thomas Blum](https://github.com/tbaddade) für Hilfe, Code und Bugmeldungen :)
* Danke ausserdem an alle die sich mit Ideen, Tests und Bugmeldungen eingebracht haben :)
* Google PageRank Checker Class by David Walsh and Jamie Scott
* PHP Markdown Lib by Michel Fortin
* Macht’s gut und danke für den Fisch ;)


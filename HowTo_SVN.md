## Einleitung ##
Subversion (SVN) ist eine Versionsverwaltung für Dateien und Verzeichnisse, also ein System, das in der Softwareentwicklung zur Versionierung und für den gemeinsamen Zugriff auf den Quellcode eingesetzt wird.
Alle Änderungen werden von dem System erfasst und archiviert. Hierbei entsteht mit jedem Einchecken ein neuer Versionsstand und es ist jederzeit möglich auf vorige Versionen zurückzugreifen.

Wenn Sie sich an unseren Projekten beteiligen möchten, egal ob programmiertechnisch oder als Übersetzer, sind Sie jederzeit willkommen. Hierfür benötigen Sie allerdings einen [Google-Account](https://www.google.com/accounts/NewAccount).
Falls Sie also Interesse zur Mitarbeit haben, dann erstellen Sie einfach einen [Google-Account](https://www.google.com/accounts/NewAccount) und wenden Sie sich anschließend an wbb3addons@gmail.com ([HowTo Google-Account erstellen](http://code.google.com/p/wbb3addons/wiki/HowTo_Setup_a_Google_account)).


## SVN-Client ##
SVN-Clients gibt es für alle gängigen Betriebssysteme und in den unterschiedlichsten Formen. Standalone, ins Betriebssystem integriert oder einfach Shell basierend.
Für ein Windows-System empfiehlt sich TortoiseSVN, der sich in den Windows-Explorer integriert.


## Arbeitskopie unter Windows auschecken ##
Nachdem Sie TortoiseSVN heruntergeladen und installiert haben (Neustart ist erforderlich) kann es los gehen. TortoiseSVN installiert nur die englische Sprache. Wenn Sie eine andere Sprache bevorzugen, können Sie diese auch herunterladen und installieren.

Erstellen Sie z.B. das Verzeichnis "C:\svn\_wbb3addons" und klicken Sie anschließend mit der rechten Maustaste auf oder in das leere Verzeichnis. Im Kontext-Menü klicken Sie dann auf "TortoiseSVN" => "auschecken".
Im sich öffnenden Dialog müssen Sie nur folgenden Wert anpassen:
  * URL des Projektarchivs => http://wbb3addons.googlecode.com/svn
TortoiseSVN legt direkt los und nach ein paar Minuten haben Sie alle unsere Sourcen auf Ihrem PC.


## Arbeitskopie unter Linux auschecken ##
Syntax: `svn checkout [SWITCHES] URL... [PATH]`
In diesem Beispiel liegt die Arbeitskopie in "/var/shares/svn\_checkout/wbb3addons".
Wenn Sie nur auschecken möchten, können Sie auf "--username DeinUsername" und "--password DeinPasswort" verzichten, **müssen** aber "**https**" durch "**http**" ersetzen!

Erstmaliges Checkout:
`svn checkout --username "DeinUsername" --password "DeinPasswort" https://wbb3addons.googlecode.com/svn/ /var/shares/svn_checkout/wbb3addons`

Danach sind nur noch "Updates" notwendig.
`svn update --username "DeinUsername" --password "DeinPasswort" https://wbb3addons.googlecode.com/svn/ /var/shares/svn_checkout/wbb3addons`

**Tipp:** Um regelmäßig auf dem Laufenden zu bleiben, können Sie das "svn update" auch als Cron-Job einrichten.


## Arbeitskopie unter Windows einchecken ##
Hierzu benötigt es bei TortoiseSVN nur einen rechten Mausklick ins Verzeichnis und danach "Übertragen" aus dem TortoiseSVN-Kontext-Menü auswählen.


## Arbeitskopie unter Linux einchecken ##
Ihre Änderungen erledigen Sie per "svn add DateinameOderVerzeichnis".
Wenn Sie z.B. die Datei "/var/shares/svn\_checkout/wbb3addons/trunk/sandbox/test.txt" geändert oder erstellt haben lautet die Syntax:
`svn add /var/shares/svn_checkout/wbb3addons/trunk/sandbox/test.txt`
Danach muss die Änderung noch übertragen werden. Dieses erfolgt per "svn commit", z.B.:
`svn commit --message "LogInformationen"`


## **Regeln** zum Einchecken Ihrer Arbeitskopie ##
  * Dateiformate
    1. Alle Ascii-Dateien sind im Linux-Format zu erstellen/bearbeiten/übertragen.
    1. Unten angegebene "Config" sollte als Referenz verwendet werden.

  * SVN-Keywords
    1. In allen Ascii-Dateien, in SQL-Dateien nicht zwingend erforderlich, sollte das SVN-Keyword $"Id"$ (ohne Anführungszeichen!) eingesetzt werden.

  * Kommentare
    1. Beim Einchecken nie den Kommentar vergessen.
    1. Vergessene Kommentare werden mit der Ausgabe eines Kasten Bier geahndet.
    1. Der Kommentar sollte mit dem betroffenen Paketnamen beginnen, z.B. de.mailman.wbb.portal.box.buddies:
    1. Der Kommentar sollte die Änderungen so detailliert wie möglich beinhalten.


---

## Weiterführende Links ##
  * [Subversion Home](http://subversion.tigris.org/)
  * [Subversion Book](http://svnbook.red-bean.com/nightly/de/index.html)
  * [TortoiseSVN](http://tortoisesvn.tigris.org/)
  * [Subversion Wikipedia](http://de.wikipedia.org/wiki/Subversion_(Software))



---

## Config ##
```
### This file configures various client-side behaviors.
###
### The commented-out examples below are intended to demonstrate
### how to use this file.

### Section for authentication and authorization customizations.
[auth]
### Set store-passwords to 'no' to avoid storing passwords in the
### auth/ area of your config directory.  It defaults to 'yes'.
### Note that this option only prevents saving of *new* passwords;
### it doesn't invalidate existing passwords.  (To do that, remove
### the cache files by hand as described in the Subversion book.)
# store-passwords = no
### Set store-auth-creds to 'no' to avoid storing any subversion
### credentials in the auth/ area of your config directory.
### It defaults to 'yes'.  Note that this option only prevents
### saving of *new* credentials;  it doesn't invalidate existing
### caches.  (To do that, remove the cache files by hand.)
# store-auth-creds = no

### Section for configuring external helper applications.
[helpers]
### Set editor to the command used to invoke your text editor.
###   This will override the environment variables that Subversion
###   examines by default to find this information ($EDITOR, 
###   et al).
# editor-cmd = editor (vi, emacs, notepad, etc.)
### Set diff-cmd to the absolute path of your 'diff' program.
###   This will override the compile-time default, which is to use
###   Subversion's internal diff implementation.
# diff-cmd = diff_program (diff, gdiff, etc.)
### Set diff3-cmd to the absolute path of your 'diff3' program.
###   This will override the compile-time default, which is to use
###   Subversion's internal diff3 implementation.
# diff3-cmd = diff3_program (diff3, gdiff3, etc.)
### Set diff3-has-program-arg to 'true' or 'yes' if your 'diff3'
###   program accepts the '--diff-program' option.
# diff3-has-program-arg = [true | false]

### Section for configuring tunnel agents.
[tunnels]
### Configure svn protocol tunnel schemes here.  By default, only
### the 'ssh' scheme is defined.  You can define other schemes to
### be used with 'svn+scheme://hostname/path' URLs.  A scheme
### definition is simply a command, optionally prefixed by an
### environment variable name which can override the command if it
### is defined.  The command (or environment variable) may contain
### arguments, using standard shell quoting for arguments with
### spaces.  The command will be invoked as:
###   <command> <hostname> svnserve -t
### (If the URL includes a username, then the hostname will be
### passed to the tunnel agent as <user>@<hostname>.)  If the
### built-in ssh scheme were not predefined, it could be defined
### as:
# ssh = $SVN_SSH ssh
### If you wanted to define a new 'rsh' scheme, to be used with
### 'svn+rsh:' URLs, you could do so as follows:
# rsh = rsh
### Or, if you wanted to specify a full path and arguments:
# rsh = /path/to/rsh -l myusername
### On Windows, if you are specifying a full path to a command,
### use a forward slash (/) or a paired backslash (\\) as the
### path separator.  A single backslash will be treated as an
### escape for the following character.

### Section for configuring miscelleneous Subversion options.
[miscellany]
### Set global-ignores to a set of whitespace-delimited globs
### which Subversion will ignore in its 'status' output, and
### while importing or adding files and directories.
global-ignores = *.o *.lo *.la #*# .*.rej *.rej .*~ *~ .#* .DS_Store build dist target
### Set log-encoding to the default encoding for log messages
# log-encoding = latin1
### Set use-commit-times to make checkout/update/switch/revert
### put last-committed timestamps on every file touched.
# use-commit-times = yes
### Set no-unlock to prevent 'svn commit' from automatically
### releasing locks on files.
# no-unlock = yes
### Set enable-auto-props to 'yes' to enable automatic properties
### for 'svn add' and 'svn import', it defaults to 'no'.
### Automatic properties are defined in the section 'auto-props'.
enable-auto-props = yes

### Section for configuring automatic properties.
[auto-props]
### The format of the entries is:
###   file-name-pattern = propname[=value][;propname[=value]...]
### The file-name-pattern can contain wildcards (such as '*' and
### '?').  All entries which match will be applied to the file.
### Note that auto-props functionality must be enabled, which
### is typically done by setting the 'enable-auto-props' option.
# *.c = svn:eol-style=native
# *.cpp = svn:eol-style=native
# *.h = svn:eol-style=native
# *.dsp = svn:eol-style=CRLF
# *.dsw = svn:eol-style=CRLF
# *.sh = svn:eol-style=native;svn:executable
# *.txt = svn:eol-style=native
# *.png = svn:mime-type=image/png
# *.jpg = svn:mime-type=image/jpeg
# Makefile = svn:eol-style=native
*.7z = svn:mime-type=application/7z
*.bat = svn:mime-type=text/plain;svn:eol-style=CRLF
*.bin = svn:mime-type=application/octet-stream
*.bmp = svn:mime-type=image/bmp
*.c = svn:eol-style=native;svn:keywords=Date Author Id Revision HeadURL
*.class = svn:mime-type=application/java
*.cmd = svn:mime-type=text/plain;svn:eol-style=CRLF
*.css = svn:mime-type=text/css;svn:eol-style=LF;svn:keywords=Id
*.cpp = svn:eol-style=native;svn:keywords=Date Author Id Revision HeadURL
*.h = svn:eol-style=native;svn:keywords=Date Author Id Revision HeadURL
*.doc = svn:mime-type=application/msword
*.dsp = svn:eol-style=CRLF
*.dsw = svn:eol-style=CRLF
*.dtd = svn:mime-type=text/plain;svn:eol-style=native;svn:keywords=Date Revision
*.ent = svn:mime-type=text/plain;svn:eol-style=native;svn:keywords=Date Revision
*.exe = svn:mime-type=application/octet-stream
*.gif = svn:mime-type=image/gif
*.gz = svn:mime-type=application/x-gzip
*.htm = svn:mime-type=text/html;svn:eol-style=LF
*.html = svn:mime-type=text/html;svn:eol-style=LF
*.jar = svn:mime-type=application/java-archive
*.java = svn:mime-type=text/plain;svn:eol-style=native;svn:keywords=Date Revision
*.jelly = svn:mime-type=text/plain;svn:eol-style=native;svn:keywords=Date Revision
*.jpg = svn:mime-type=image/jpeg
*.jpeg = svn:mime-type=image/jpeg
*.js = svn:mime-type=text/plain;svn:eol-style=LF
*.jsp = svn:mime-type=text/plain;svn:eol-style=native;svn:keywords=Date Revision
*.obj = svn:mime-type=application/octet-stream
*.pdf = svn:mime-type=application/pdf
*.php = svn:mime-type=text/php;svn:eol-style=LF;svn:keywords=Id
*.png = svn:mime-type=image/png
*.properties = svn:mime-type=text/plain;svn:eol-style=native;svn:keywords=Date Revision
*.sh = svn:executable;svn:eol-style=LF;svn:keywords=Id
*.sql = svn:mime-type=text/sql;svn:eol-style=LF;svn:keywords=Id
*.tgz = svn:mime-type=application/octet-stream
*.tif = svn:mime-type=image/tiff
*.tiff = svn:mime-type=image/tiff
*.tpl = svn:mime-type=text/plain;svn:eol-style=LF;svn:keywords=Id
*.txt = svn:mime-type=text/plain;svn:eol-style=native
*.vsl = svn:mime-type=text/plain;svn:eol-style=native;svn:keywords=Date Revision
*.wiki = svn:mime-type=text/plain;svn:eol-style=LF
*.wsdl = svn:mime-type=text/xml;svn:eol-style=native;svn:keywords=Date Revision
*.xml = svn:mime-type=text/xml;svn:eol-style=LF;svn:keywords=Id
*.xsd = svn:mime-type=text/xml;svn:eol-style=native;svn:keywords=Date Revision
*.xsl = svn:mime-type=text/xml;svn:eol-style=native;svn:keywords=Date Revision
*.zip = svn:mime-type=application/zip
Makefile = svn:eol-style=native;svn:keywords=Date Author Id Revision HeadURL

```
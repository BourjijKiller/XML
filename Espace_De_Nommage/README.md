# ESPACE DE NOMMAGE XML

------------------------------------------------
## DESCRIPTION

### Pourquoi les "Espaces de nommage ?"
En XML, il est possible, grâce aux **entités générales (&;)** et **paramètres (%;)**, d'importer des fragments de code, respectivement XML et DTD.
Cependant, l'import de ces fragments de code peut-être source de conflits.

En effet, si l'on importe une _entité paramètre_ dans un fichier DTD, que cette dernière contient un élément de même nom qu'un élément déjà présent dans le fichier, mais avec une composition différente,
il y aura donc conflit puisque la définition de l'élément importée sera différente de la définition de l'élément local.

Un exemple assez simple qui illustre cette situation :

```dtd
<?xml version="1.0" encoding="utf-8"?>
<!ELEMENT lieu (réseauTransport)
<!ELEMENT réseauTransport (bus, tramway, vélolib)
<!ELEMENT bus (#PCDATA)>
<!ELEMENT tramway (#PCDATA)>
<!ELEMENT vélolib (#PCDATA)>
<!ENTITY % import SYSTEM "import.dtd">
%import;
```
Soit la DTD ci-dessus illustrant qu'un lien possède éventuellement 1 réseau de transport constitué de :
* Bus
* Tramway
* Vélolib

Cependant, pour un lieu différent, on peut avoir un réseau de transport totalement différent, et on utilise ici une entité paramètre `import` contenant :

```dtd
<?xml version="1.0" encoding="utf-8"?>
<!ELEMENT réseauTransport (bus, BHNS)
<!ELEMENT BHNS (#PCDATA)>
```
On remarque donc bien que la DTD initiale n'est pas valide : **la définition de l'élément réseauTransport importée est en conflit avec la définition locale de l'élément RéseauTransport.**

Pour pallier à ce problème, il faudrait ainsi distinguer les définitions locales des définitions importées, ce qui nous amène au **Espaces de nommage**.

### Un espace de nommage XML, c'est quoi ?

DANS LA VALLEE HAN HAN

------------------------------------------------
## UTILISATION

**[En cours de rédaction]**

### 1°) Sous XML
**[En cours de rédaction]**

### 2°) Sous DTD
**[En cours de rédaction]**

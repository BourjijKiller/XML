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

Pour pallier à ce problème, il faudrait ainsi distinguer les définitions locales des définitions importées, ce qui nous amène aux **Espaces de nommage**.

### Un espace de nommage XML, c'est quoi ?

Dans le monde XML, un **espace de nommage** désigne un **ensemble, sans "réalité physique", de données (éléments, attributs, types, etc.) sémantiquement cohérentes qui lui sont associées**.

Le principal usage des espaces de nommage est l'évitement de conflits de nommage, comme vu au-dessus. En XML, un espace de nommage est identifié par une _[URI](https://fr.wikipedia.org/wiki/Uniform_Resource_Identifier)_
et n'a pas de "réalité physique" (ni sur disque, ni en mémoire). On peut donc mettre ce que l'on veut.

L'URI se construit de la manière suivante : `protocole://instruction/projet/sousProjet/...`

Pour notre exemple précédent, on peut définir 2 espaces de nommage, en fonction du lieu par exemple :

**Rajout dans la première DTD**
```dtd
<!ATTLIST réseauTransport
    xmlns:ville1
    CDATA
    #FIXED "http://www.transport.fr/lieu/Montpellier">
```

**Rajout dans la DTD externe**
```dtd
<!ATTLIST réseauTransport
    xmlns:ville2
    CDATA
    #FIXED "http://www.transport.fr/lieu/Aix-en-Provence">
```
 **Pour chaque élément, il faudra préfixé le nom par ville1 pour la DTD initiale, et ville2 pour la DTD externe.**
 
Avec ces changements, le fichier XML basé sur cette DTD ne produira plus de conflits, puisque chaque élément est différencié par un espace de nommage.

```xml
<?xml version="1.0" encoding="UTF-8" ?>
<!-- Chargement du modèle de document associé -->
<!DOCTYPE lieu SYSTEM "./transport.dtd">
<!-- Pour la première définition de l'élément lieu, on associe l'espace de nommage ville1 qui contiendra une URI -->
<!-- Il n'est pas nécessaire de remettre à chaque fois l'URI, une fois suffit afin d'accrocher cette dernière à au préfixe ville1 -->
<ville1:lieu xmlns:ville1="http://www.transport.fr/lieu/Montpellier">
    <ville1:réseauTransport>
        <ville1:bus>TAMBUS</ville1:bus>
        <ville1:tramway>TAMTRAM</ville1:tramway>
        <ville1:vélolib>TAMVELO</ville1:vélolib>
    </ville1:réseauTransport>
</ville1:lieu>
<ville2:lieu xmlns:ville2="http://www.transport.fr/lieu/Aix-en-Provence">
    <ville2:bus>Aix-en-Bus</ville2:bus>
    <ville2:BHNS>AixPress</ville2:BHNS>
</ville2:lieu>
```
* Tout les éléments préfixés par **_ville1_** seront identifiés par l'espace de nom http://www.transport.fr/lieu/Montpellier
* Tout les éléments préfixés par **_ville2_** seront identifiés par l'espace de nom http://www.transport.fr/lieu/Aix-en-Provence

------------------------------------------------
## UTILISATION

**[En cours de rédaction]**

### 1°) Préfixes d'espaces de nommage
**[En cours de rédaction]**

### 2°) Espace de nommage par défaut
**[En cours de rédaction]**

------------------------------------------------
## RÈGLES DE VISIBILITÉS

**[En cours de rédaction]**
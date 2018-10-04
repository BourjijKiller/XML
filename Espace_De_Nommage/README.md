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
<!DOCTYPE ville1:lieu SYSTEM "./transport.dtd">
<!-- Pour la première définition de l'élément lieu, on associe l'espace de nommage ville1 qui contiendra une URI -->
<!-- Il n'est pas nécessaire de remettre à chaque fois l'URI, une fois suffit afin d'associer cette dernière au préfixe ville1 -->
<ville1:lieu xmlns:ville1="http://www.transport.fr/lieu/Montpellier">
    <ville1:réseauTransport>
        <ville1:bus>TAMBUS</ville1:bus>
        <ville1:tramway>TAMTRAM</ville1:tramway>
        <ville1:vélolib>TAMVELO</ville1:vélolib>
    </ville1:réseauTransport>
</ville1:lieu>
<!-- On déclare un deuxième préfixe qui contiendra une autre URI afin de différencier les deux définitions de l'élément réseauTransport -->
<ville2:lieu xmlns:ville2="http://www.transport.fr/lieu/Aix-en-Provence">
    <ville2:réseauTransport>
        <ville2:bus>Aix-en-Bus</ville2:bus>
        <ville2:BHNS>AixPress</ville2:BHNS>
    </ville2:réseauTransport>
</ville2:lieu>
```
* Tout les éléments préfixés par **_ville1_** seront identifiés par l'espace de nom http://www.transport.fr/lieu/Montpellier
* Tout les éléments préfixés par **_ville2_** seront identifiés par l'espace de nom http://www.transport.fr/lieu/Aix-en-Provence

_Avec cette méthode, il n'y aura plus de conflits entre l'élément réseauTransport de la première DTD et de la deuxième DTD. Ils sont bien différenciés avec les espaces de nommage._

------------------------------------------------
## UTILISATION

L'utilisation des **espaces de nommage** est équivalent à l'utilisation des packages en Java, à la seule différence que :
1. En _Java_, les packages ont une réalité phydique (représenté par des simples dossiers) tandis qu'en XML, les URI n'en ont pas, ce qui explique le fait qu'on ne vérifie pas l'existence de l'URI
2. En _Java_, **l'opérateur de qualification est le "." alors qu'en XML, c'est le ":"**
 
Indiquer dans des documents XML des noms étendus d’éléments, d’attributs, …, de la forme **URI:nom**
peut vite devenir contraignant, même si cela résout les problèmes de conflits de noms. C’est pourquoi
on n’utilise en fait pas ces noms étendus mais deux techniques d'espaces de nommage.

### 1°) Préfixes d'espace de nommage

Un **préfixe d'espace de nommage est un mécanisme d'alias : le préfixe est donc l'alias d'une URI identifiant un espace de nommage**.

Le préfixe fera donc référence à l'espace de nommage (représenté par l'URI) et il suffira ainsi de préfixer les éléments avec ce dernier pour dire
"Je veux inclure cet élément dans l'espace de nommage XX".

#####Déclaration et utilisation d'un espace préfixe d'espace de nommage
```xml
<?xml version="1.0" encoding="UTF-8"?>
<element xmlns:pre1 = "http://ww.example.fr/XML/DTD"
         xmlns:pre2 = "http://www.juju.fr/Pourquoi"
         xmlns:pre3 = "http://worldoftanks.fr/Malinovka">
         <!-- Contenu de l'élément -->
         <!-- Il suffira ensuite de préfixer les éléments pour lui associer un espace de nommage -->
</element>
```

### 2°) Espace de nommage par défaut

Un **espace de nommage par défaut est un mécanisme d'association implicite d'éléments à un espace de nommage, à condition que ces éléments ne soient pas explicitement qualifiés.**

Lors de la déclaration d'un espace de nommage par défaut, tout les éléments suivants seront automatiquement associé à ce dernier, sauf si :
* L'espace de nommage par défaut est **annhilé** (annulé / vidé)
* L'élément porte **un préfixe** qui fait référence à un autre espace de nommage par défaut

#####Déclaration et utilisation d'un espace de nommage par défaut

```xml
<?xml version="1.0" encoding="UTF-8"?>
<!-- Déclaration de deux préfixes d'espaces de nommage suivi de l'espace de nommage par défaut -->
<element xmlns:wot = "http://worldoftanks.fr/Malinovka"
         xmlns:flyff = "http://www.flyff.fr/Rartesia"
         xmlns = "http://espacedenommagepardefaut.fr/General">
         <!-- Les éléments n'étant pas préfixés, ils sont associés à l'espace de nommage par défaut -->
         <Autoroutes>
            <A1> Je suis associé à l'espace de nommage par défaut http://espacedenommagepardefaut.fr/General </A1>
            <A2> Je suis associé à l'espace de nommage par défaut http://espacedenommagepardefaut.fr/General </A2>
            <!-- L'élément suivant est préfixé par flyff, donc elle prendra l'espace de nommage associé à ce préfixe -->
            <!-- Ainsi que ses enfants, si ils sont préfixés -->
            <flyff:Autoroute-flyff>
                <flyff:texte>
                    Je suis associé à l'espace de nommage http://www.flyff.fr/Rartesia
                </flyff:texte>
            </flyff:Autoroute-flyff>
            <!-- L'élément suivant est préfixé par wot, donc elle prendra l'espace de nommage associé à ce préfixe -->
            <wot:Autoroute-wot>
                <wot:texte>
                    Je suis associé à l'espace de nommage http://worldoftanks.fr/Malinovka
                </wot:texte>
            </wot:Autoroute-wot>
            <!-- Ici, on annhile l'espace de nommage par défaut, donc par conséquent, depuis la balise ouvrante incluse Routes jusqu'à la balise fermante incluse Routes, il n'existe plus d'espace de nommage par défaut -->
            <Routes xmlns = "">
                <D456>
                    Elément associé à aucun espace de nommage
                </D456>
            </Routes>
         </Autoroutes>
</element>
```

------------------------------------------------
## RÈGLES DE VISIBILITÉS

Un **préfixe d’espace de nommage** est visible :

* Dans l’élément dans lequel il est déclaré (il peut être utilisé y compris dans le nom de l’élément
figurant dans la balise ouvrante dans laquelle il est défini).

* Dans tous les descendants de cet élément à moins :
    * Qu’un nouvel espace de même préfixe ne soit déclaré dans certains de ces descendants (il recouvre alors temporairement le préfixe déclaré dans son ancêtre).
    * Que sa déclaration ne soit annihilée localement dans certains de ces descendants.
    
    
Un **espace de nommage par défaut** est visible :

* Dans l’élément dans lequel il est déclaré (il peut être utilisé y compris dans le nom de l’élément figurant dans la balise ouvrante dans laquelle il est défini).

* Dans tous les descendants de cet élément à moins :
    * Qu’un nouvel espace de nommage par défaut ne soit déclaré dans certains de ces descendants (il recouvre alors temporairement celui déclaré dans son ancêtre).
    * Que sa déclaration ne soit annihilée localement dans certains de ces descendants.


_Ces règles de portée et de visibilité sont classiques : ce sont les mêmes qui régissent en général la
portée et la visibilité de déclarations de variables dans des langages de programmation classiques._

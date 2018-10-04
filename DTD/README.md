# DTD (**Data Type Definition**)
---------------------------------------------------
## DESCRIPTION

Une DTD décrit la structure des documents XML qui la réfèrent, c’est donc un modèle de documents. Elle permet principalement de définir les **éléments** et les **attributs** autorisés dans les documents XML la respectant ainsi que leur composition.

Le contenu d'une DTD permet notamment de décrire :
1. _Des éléments_ : Un élément se définit au moyen de l’instruction `ELEMENT` par deux caractéristiques obligatoires :

    * Un nom : celui qui figurera dans les balises ouvrante et fermante le délimitant
    * Un modèle de contenu : il indique ce qui pourra/devra se trouver entre les balises ouvrante et fermante le délimitant.
    
    Exemple :
    ```dtd
    <!ELEMENT carnet (personne*, rdv*)>
    <!ELEMENT personne (nom, prénom, société)>
    <!ELEMENT nom (#PCDATA)>
    <!ELEMENT prénom (#PCDATA)>
    <!ELEMENT société (#PCDATA)>
    <!ELEMENT rdv (date, heure, durée?, nom+)
    <!ELEMENT date (#PCDATA)>
    <!ELEMENT heure (#PCDATA)>
    <!ELEMENT durée (#PCDATA)>
    ```
    
    Dans le code ci-dessus, on peut déjà imaginer le document XML qui va être généré par le modèle de la DTD.
    * On créé un élément **CARNET** qui peut contenir **éventuellement** une ou plusieurs personnes et **éventuellement** un ou plusieurs rendez-vous.
    
    * L'élément **Personne** est constitué d'un nom, d'un prénom et d'une société
        * L'élément **nom** est une chaîne de caractères
        * L'élément **prénom** est une chaîne de caractères
        * L'élément **société** est une chaîne de caractères
        * L'élément **rdv** est constitué d'une date, d'une heure, **éventuellement** d'une durée et d'au moins un nom minimum
            * L'élément **date** est une chaîne de caractères
            * L'élément **heure** est une chaîne de caractères
            * L'élément **durée** est une chaîne de caractères
            
    Ici, nous pouvons en déduire que l'élément **Carnet** sera l'élément racine du document XML. Cependant, la machine ne peut pas le savoir. C'est l'instruction suivante qui va permettre de dire au document XML "Prend moi cet élément comme racine" :
    
    ```xml
    <?xml version="1.0" encoding="utf-8" ?>
    <!DOCTYPE carnet SYSTEM "chemin_absolue_vers_fichier_dtd/fichier.dtd">
    ```
    
2. _Des attributs_ : Il est possible d’associer à chaque élément définit dans une DTD une **liste d’attributs** au moyen de l’instruction `ATTLIST` suivie :
    * Du nom de l’élément auquel on souhaite associer cette liste d’attributs : ces attributs pourront/devront apparaître dans la balise ouvrante de l’élément indiqué
    * D’un ensemble de définitions d’attributs (au moins une)
    * Dans cet ensemble de définitions, chaque attribut est défini par trois caractéristiques obligatoires :
        * Un nom
        * Un type : le type de sa valeur
        * Un comportement : la présence de l’attribut est-elle optionnelle ou obligatoire, a-t-il une valeur par défaut ou une valeur fixe ?
        
        Dans l'exemple ci-dessus, on peut dire qu'un carnet est référencé par un identifiant unique. Pour ce faire, on va déclarer un **attribut** dans le carnet :
        
        ```dtd
         <!ATTLIST carnet id ID #REQUIRED>
        ```
        
        Ici, on définit donc un attribut de nom _id_ sur l'élément carnet, qui est de type **ID** et qui est obligatoire grâce à l'instruction `#REQUIRED`.
        
3. _Des entités_ : Une entité est un fragment de document (fragment de document XML, caractère, objet non XML, fragment de DTD, …) éventuellement nommé qui peut largement varié d’un simple caractère à tout un document.
Il existe plusieurs types d'entités, dont :
    *  **Les entités générales** qui permettent de fragmenter un document ou de « factoriser » des portions de documents XML. Les entités générales doivent être déclarées dans une DTD et référencées depuis le corps d’un document XML basé sur cette DTD.
    
        **Déclaration**
        ```dtd
        <!ENTITY Test "Ceci est un test">
        <!ELEMENT paragraphe EMPTY>
        <!-- Déclaration de l'attribut contenant l'entité -->
        <!ATTLIST paragraphe refEntity ENTITY #REQUIRED>
        ```
        
        **Utilisation dans le document XML**
        ```xml
        <?xml version="1.0" encoding="utf-8" ?>
        <paragraphe refEntity="Test"></paragraphe>
        ```
        
    * **Les entités paramètres** qui permettent de fragmenter une DTD ou de « factoriser » des portions de DTD. Elles ne peuvent donc être référencées que dans une DTD.
    
    **Déclaration d'une DTD commune `commun.dtd`**
    
    ```dtd
    <!-- Déclaration d'une entité personne -->
    <!ENTITY % personne "prénom, nom">
    <!ELEMENT prénom (#PCDATA)>
    <!ELEMENT nom (#PCDATA)>
    <!ELEMENT adresse (numéro?, typeRue?, nomRue, codePostal, bureauDistributeur?, ville, pays?)>
    <!-- Autres définitions communes -->
    ```
    
    **Réutilisation de l'entité personne dans une autre DTD**
    
    ```dtd
    <!-- Déclaration de l’entité paramètre -->
    <!ENTITY % commun SYSTEM "./commun.dtd">
    <!-- Import des définitions communes -->
    %commun;
    <!-- Utilisation de définitions communes -->
    <!ELEMENT auteur (%personne;)>
    <!-- Autres définitions -->
    ```

---------------------------------------------------
## UTILISATION

Les fichiers **.dtd** permettent donc de générer des documents XML grâce à des logiciels (IDE, propriétaires) ainsi que de pouvoir égalemment valider un document XML en fonction de la DTD de **référence**.
Des logiciels comme **XMLBLUEPRINT** ou des IDE comme **Eclipse** ou encore **Visual Studio**, avec des plugins XML, permettent de faire ces opérations.

Liens utiles :

* [XMLBluePrint](https://www.xmlblueprint.com/)
* [XML tools for VS](https://marketplace.visualstudio.com/items?itemName=DotJoshJohnson.xml)
* [Plugin Eclipse for XML DTD](https://www.eclipse.org/downloads/packages/release/juno/sr1/eclipse-modeling-tools)

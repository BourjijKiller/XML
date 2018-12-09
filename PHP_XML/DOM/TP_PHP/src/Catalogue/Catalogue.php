<?php
/**
 * Created by IntelliJ IDEA.
 * User: Entezam Samuel
 * Date: 03/12/2018
 * Time: 11:39
 */

namespace TP_PHP\src\Catalogue;

use TP_PHP\core\Controller;
use TP_PHP\Utils\Collection;
use TP_PHP\Utils\Produit;

/**
 * Class Catalogue
 * @package TP_PHP\src\Catalogue
 */
class Catalogue extends Controller
{
    private $document = null;
    private $xPath = null;
    private $nomFichier = null;

    /**
     * Catalogue constructor.
     * @throws \Exception File Not Found
     */
    public function __construct()
    {
        // Initialisation de la session au début
        $this->getSession();
        $catalogueXML = $_SERVER['DOCUMENT_ROOT']."/xml/CatalogueXML/catalogue.xml";
        if(file_exists($catalogueXML)) {
            $this->document = new \DOMDocument();
            $this->document->load($catalogueXML);
            $this->xPath = new \DOMXPath($this->document);
            $this->nomFichier = substr($this->document->baseURI, strrpos($this->document->baseURI, '/')+1, strlen($this->document->baseURI));
            $_SESSION['flashBagMsgSuccess']['fileLoad'] = "<div class='text-center'> Le fichier $this->nomFichier a été chargé avec succès ! </div>";
        }
        else {
            throw new \Exception("File Not Found");
        }
    }

    /**
     * Appel la vue associée au catalogue
     * @throws \Exception XML Document is NULL
     */
    public function view() : void
    {
        $flashBag = $this->getFlashBag();
        $CAEuro = $this->getChiffreAffaires("EURO");
        $CADollar = $this->getChiffreAffaires("DOLLAR");
        $CALivre = $this->getChiffreAffaires("LIVRE");
        $catalogueWithProd = $this->getCatalogue();

        $this->render(
            'CatalogueView',
            [
                "CAEuro" => $CAEuro,
                "CADollar" => $CADollar,
                "CALivre" => $CALivre,
                "catalogue" => $catalogueWithProd,
                "success" => count($flashBag['success']) > 0 ? $flashBag['success'] : null,
                "errors" => count($flashBag['errors']) > 0 ? $flashBag['errors'] : null
            ],
            'Catalogue'
        );
    }

    /**
     * Récupère le chiffre d'affaires en fonction des produits et des quantités de produits
     * Utilise la méthode @see CAWithConvertion pour le calcul avec taux de change entre monnaie
     * @param string $typeMonnaie Calcul du chiffre d'affaires en fonction du type de monnaie désiré
     * @return string chiffre d'affaires
     * @throws \Exception XML Document is NULL
     */
    public function getChiffreAffaires(string $typeMonnaie) : string
    {
        $collection = null;
        $CA = 0.0;
        if($this->document != null) {
            $listProduit = $this->document->getElementsByTagNameNS("http://www.univ-amu.fr/XML/catalogue", "produit");
            if($listProduit->count() > 0) {
                $collection = $this->getCollectionMontants($listProduit);
                switch ($typeMonnaie)
                {
                    case "EURO":
                        $this->CAWithConvertion($collection, $CA, "EURO");
                        break;
                    case "DOLLAR":
                        $this->CAWithConvertion($collection, $CA, "DOLLAR");
                        break;
                    case "LIVRE":
                        $this->CAWithConvertion($collection, $CA, "LIVRE");
                        break;
                }
            }
            else {
                $_SESSION['flashBagMsgErrors']['missingMontant'] = "<div class='text-center'>Le fichier $this->nomFichier ne contient pas de produit...</div>";
            }
        }
        else {
            throw new \Exception("XML Document is NULL");
        }
        return number_format($CA, 2, ',', '.');
    }

    /**
     * Calcul le chiffre d'affaires en tenant compte du taux de change entre les devises
     * @param Collection $collection tableau associatif contenant pour chaque devises, les montants et quantités
     * @param float $CA Chiffre d'affaires (passage par référence)
     * @param string $devise Type de monnaie pour la conversion
     * @throws \Exception
     */
    public function CAWithConvertion(Collection $collection, float &$CA, string $devise) : void
    {
        foreach ($collection->keys() as $key) {
            foreach ($collection->getItem($key) as $item) {
                $quantite = substr($item, 0, strpos($item, ';'));
                $montant = substr($item, strrpos($item, ';')+1);
                $CA += $key == $devise ? intval($quantite) * floatval($montant) : intval($quantite) * $this->convert($key, $devise, floatval($montant));
            }
        }
    }

    /**
     * Converti un montant en Dollar, Euro ou Livre Sterling
     * @param string $convertFrom Monnaie de référence
     * @param string $devise EURO | DOLLAR | LIVRE
     * @param float $montant montant à convertir
     * @return float montant après conversion
     * @throws \Exception Montant NULL
     */
    public function convert(string $convertFrom, string $devise, float $montant) : float
    {
        $montantApresConversion = null;
        if($montant != null) {
            switch ($devise) {
                case "DOLLAR":
                    switch ($convertFrom) {
                        // EURO --> DOLLAR
                        case "EURO":
                            $montantApresConversion = $montant * 1.2;
                            break;
                        // LIVRE --> DOLLAR
                        case "LIVRE":
                            $montantApresConversion = $montant * 0.19073;
                            break;
                    }
                    break;
                case "EURO":
                    switch ($convertFrom) {
                        // DOLLAR --> EURO
                        case "DOLLAR":
                            $montantApresConversion = $montant * 0.87885;
                            break;
                        // LIVRE --> EURO
                        case "LIVRE":
                            $montantApresConversion = $montant * 1.11885;
                            break;
                    }
                    break;
                case "LIVRE":
                    switch ($convertFrom) {
                        // DOLLAR --> LIVRE STERLING
                        case "DOLLAR":
                            $montantApresConversion = $montant * 0.78536;
                            break;
                        // EURO --> LIVRE STERLING
                        case "EURO":
                            $montantApresConversion = $montant * 0.89375;
                            break;
                    }
                    break;
            }
            return $montantApresConversion;
        }
        else {
            throw new \Exception("Montant NULL");
        }
    }

    /**
     * Récupère et ajout les montants dans une nouvelle collection, avec la devise comme clé de chaque index
     * @param \DOMNodeList $listProduit montants
     * @return Collection [devise] => array{quantite;montant}
     */
    public function getCollectionMontants(\DOMNodeList $listProduit) : Collection
    {
        $collection = new Collection();
        foreach ($listProduit as $produit) {
            $quantite = $produit->getElementsByTagNameNS("http://www.univ-amu.fr/XML/catalogue", "quantitéStock")->item(0)->nodeValue;
            $montant = $produit->getElementsByTagNameNS("http://www.univ-amu.fr/XML/catalogue", "montant")->item(0);
            if($montant->parentNode->HasAttributes()) {
                $monnaie = null;
                $montant->parentNode->getAttributeNode("devise")->value === "EURO" ? $monnaie = "EURO" : $monnaie = "DOLLAR";
                $collection->addItem($quantite, $monnaie, $montant->nodeValue);
            }
            else {
                $nodeName = $this->xPath->evaluate('name(.//cat:prix)', $produit);
                $nodeValue = $this->xPath->evaluate('string(.//cat:prix)', $produit);
                $_SESSION['flashBagMsgErrors']['missingDevise'] = "<div class='text-center'>Type de monnaie non-spécifié (EURO | DOLLAR US | LIVRE STERLING). <br />
                                                  Le noeud ".$nodeName."(".$nodeValue.") du fichier XML doit contenir une devise en attribut.</div>";
            }
        }
        return $collection;
    }

    /**
     * Parcours le catalogue avec des expressions XPath et stock chaque produits dans une collection d'objets
     * La collection stock des objets de types @uses Produit
     * @return Collection
     */
    public function getCatalogue() : Collection
    {
        $results = new Collection();
        foreach ($this->xPath->evaluate('.//cat:produit') as $item) {
            $results->addItem(new Produit(
                $this->xPath->evaluate('string(.//cat:désignation/text())', $item),
                $this->xPath->evaluate('string(.//@référence)', $item),
                intval($this->xPath->evaluate('string(.//cat:quantitéStock/text())', $item)),
                number_format(floatval($this->xPath->evaluate('string(.//cat:montant/text())', $item)), 2, ',', '.'),
                $this->xPath->evaluate('string(.//cat:prix/@devise)', $item)
            ), $this->xPath->evaluate('string(../cat:nom/text())', $item));
        }

        if($results->length() > 0) {
            $_SESSION['flashBagMsgSuccess']['catalogueOk'] = "<div class='text-center'>Affichage du catalogue </div>";
        }
        else {
            $_SESSION['flashBagMsgErrors']['catalogueVide'] = "<div class='text-center'>Le catalogue est vide</div>";
            $results = null;
        }
        return $results;
    }
}
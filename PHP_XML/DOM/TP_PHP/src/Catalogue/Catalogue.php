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

/**
 * Class Catalogue
 * @package TP_PHP\src\Catalogue
 */
class Catalogue extends Controller
{
    private $document = null;

    /**
     * Catalogue constructor.
     * @throws \Exception File Not Found
     */
    public function __construct()
    {
        $catalogueXML = $_SERVER['DOCUMENT_ROOT']."/xml/CatalogueXML/catalogue.xml";
        if(file_exists($catalogueXML)) {
            $this->document = new \DOMDocument();
            $this->document->load($catalogueXML);
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
        $this->getSession();
        $flashBag = $this->getFlashBag();
        $CAEuro = $this->getChiffreAffaires("EURO");
        // TODO Remplacer
        $CADollar = null;
        $CALivre = null;

        $this->render(
            'CatalogueView',
            [
                "CAEuro" => $CAEuro,
                "CADollar" => $CADollar,
                "CALivre" => $CALivre,
                "success" => !empty($flashBag['success']) ? $flashBag['success'] : null,
                "errors" => !empty($flashBag['errors']) ? $flashBag['errors'] : null
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
                $nomFichier = substr($this->document->baseURI, strrpos($this->document->baseURI, '/')+1, strlen($this->document->baseURI));
                $_SESSION['flashBagMsgErrors'] = "<div class='row justify-content-start'>Le fichier $nomFichier ne contient pas de montant...</div>";
            }
        }
        else {
            throw new \Exception("XML Document is NULL");
        }
        return number_format($CA, 2, ',', '.');
    }

    public function CAWithConvertion(Collection $collection, float &$CA, string $devise) : void
    {
        foreach ($collection->keys() as $key) {
            echo "<pre>", var_dump($collection->getItem($key)), "</pre>";
            // TODO Calcul du CA en fonction du taux de change en fonction des monnaies
            //$quantite = substr($item, 0, strpos($item, ';'));
            //$montant = substr($item, strrpos($item, ';')+1);
            //$CA += $collection->getKeyFromValue($item) == $devise ? intval($quantite) * floatval($montant) : intval($quantite) * $this->convert($collection->getKeyFromValue($item), $devise, floatval($montant));
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
                $collection->addItem($quantite, $montant->nodeValue, $monnaie);
            }
            else {
                $_SESSION['flashBagMsgErrors'] = "<div class='row justify-content-start'>Type de monnaie non-spécifié (EURO | DOLLAR US | LIVRE STERLING)</div>";
            }
        }
        return $collection;
    }
}
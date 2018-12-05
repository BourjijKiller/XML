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
    // TODO transformer en variable session
    private $message = null;
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
        $CAEuro = $this->getChiffreAffaire("EURO");
        $CADollar = $this->getChiffreAffaire("DOLLAR");
        $CALivre = $this->getChiffreAffaire("LIVRE");

        $this->render(
            'CatalogueView',
            array(
                "CAEuro" => $CAEuro,
                "CADollar" => $CADollar,
                "CALivre" => $CALivre,
                "message" => $this->message
            ),
            'Catalogue'
        );
    }

    public function getChiffreAffaire(string $typeMonnaie) : float
    {
        $message = null;
        $collection = null;
        if($this->document != null) {
            $listProduit = $this->document->getElementsByTagNameNS("http://www.univ-amu.fr/XML/catalogue", "produit");
            if($listProduit->count() > 0) {
                $collection = $this->getCollectionMontants($listProduit);
                echo "<pre>", var_dump($collection), "</pre>";
            }
            else {
                $this->message = "Pas de montant dans le document XML ".$this->document->baseURI;
            }
        }
        else {
            throw new \Exception("XML Document is NULL");
        }

        // TODO Calcul chiffre d'affaires
        return 0.0;
    }

    /**
     * Converti un montant dollar | livre en euro
     * @param string $convertTo Monnaie de base
     * @param string $devise EURO | DOLLAR US
     * @param float $montant montant à convertir
     * @return float montant après conversion
     * @throws \Exception
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
            throw new \Exception("Montant null");
        }
    }

    /**
     * Récupère et ajout les montans dans la collection, en fonction de la devise
     * @param \DOMNodeList $listProduit montants
     * @return Collection [devise] => {montants}
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
                $collection->addItem($montant->nodeValue, $monnaie);
                echo "MONTANT : $montant->nodeValue avec quantité de $quantite <br />";
            }
            else {
                $this->message = "Type de monnaie non-spécifié [EURO | DOLLAR US | LIVRE]";
            }
        }
        return $collection;
    }
}
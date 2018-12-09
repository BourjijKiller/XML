<?php
/**
 * Created by IntelliJ IDEA.
 * User: Entezam Samuel
 * Date: 09/12/2018
 * Time: 10:45
 */

namespace TP_PHP\Utils;

/**
 * Class Produit
 * @package TP_PHP\Utils
 */
class Produit
{
    private $designation = null;
    private $reference = null;
    private $quantite = null;
    private $prixUnitaire = null;
    private $devise = null;

    /**
     * Produit constructor.
     * @param string $designation désignation du produit
     * @param string $reference référence du produit
     * @param int $quantite quantité de produits
     * @param string $prixUnitaire prix pour 1 produit
     * @param string $devise devise du prix du produit
     */
    public function __construct(string $designation, string $reference, int $quantite, string $prixUnitaire, string $devise)
    {
        $this->designation = $designation;
        $this->reference = $reference;
        $this->quantite = $quantite;
        $this->prixUnitaire = $prixUnitaire;
        $this->devise = $devise;
    }

    /**
     * Renvoie la désignation du produit
     * @return string|null
     */
    public function getDesignation() : ?string
    {
        return $this->designation;
    }

    /**
     * Renvoie la référence du produit
     * @return string|null
     */
    public function getReference() : ?string
    {
        return $this->reference;
    }

    /**
     * Renvoie la quantité de produits
     * @return int|null
     */
    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    /**
     * Renvoie le prix unitaire du produit
     * @return null|string
     */
    public function getPrixUnitaire(): ?string
    {
        return $this->prixUnitaire;
    }

    /**
     * Renvoie la devise du prix du produit
     * @return null|string
     */
    public function getDevise(): ?string
    {
        return $this->devise;
    }

    /**
     * Converti la devise en symbole associé
     * @param null|string $devise
     */
    public function setDevise(?string $devise): void
    {
        switch ($devise) {
            case "DOLLAR US":
                $this->devise = '$';
                break;
            case "LIVRE":
                $this->devise = '£';
                break;
            default:
                $this->devise = '€';
        }
    }
}
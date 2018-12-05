<?php
/**
 * Created by IntelliJ IDEA.
 * User: Entezam Samuel
 * Date: 04/12/2018
 * Time: 09:29
 */

namespace TP_PHP\Utils;

/**
 * Collection sans contraintes de clés uniques
 * Class Collection
 * @package TP_PHP\Utils
 */
class Collection
{
    private $collection;

    /**
     * Collection constructor.
     */
    public function __construct()
    {
        $this->collection= array();
    }

    /**
     * Ajoute un objet à la collection
     * @param $obj1 Quantité de produits
     * @param $obj2 Prix unitaire
     * @param $key valeur de la clé
     */
    public function addItem($obj1, $obj2, $key) : void
    {
        $this->collection[$key][] = $obj1.";".$obj2;
    }

    /**
     * Supprime un objet à la collection
     * @param $key valeur de la clé
     * @throws \Exception
     */
    public function removeItem($key) : void
    {
        if(isset($this->collection[$key])) {
            unset($this->collection[$key]);
        }
        else {
            throw new \Exception("Invalid key $key");
        }
    }

    /**
     * Récupère un item dans la collection en fonction de la clé spécifiée
     * @param $key clé de l'objet
     * @throws \Exception
     */
    public function getItem($key)
    {
        if(isset($this->collection[$key])) {
            return $this->collection[$key];
        }
        else {
            throw new \Exception("Invalid key $key");
        }
    }

    /**
     * Renvoie la taille de la collection
     * @return int
     */
    public function length() : int
    {
        return count($this->collection);
    }

    /**
     * Retourne l'ensemble des clés de la collection
     * @return array
     */
    public function keys() : array
    {
        return array_keys($this->collection);
    }
}
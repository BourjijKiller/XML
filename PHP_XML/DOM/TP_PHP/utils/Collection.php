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
        $this->collection = array();
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
     * @throws \Exception Si la clé est inexistante
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
     * @return mixed l'objet correspondant à la clé si cette dernière existe
     * @throws \Exception Si la clé n'existe pas
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
     * Calcul la taille de la collection
     * @return int taille
     */
    public function length() : int
    {
        return count($this->collection);
    }

    /**
     * Récupère la clé de la collection associée à la valeur courante
     * @param $value valeur
     * @return int|null|string key
     * @throws \Exception Si la valeur n'existe pas dans la collection
     */
    public function getKeyFromValue($value)
    {
        if(in_array($value, current($this->collection))) {
            return key($this->collection);
        }
        else {
            throw new \Exception("Value $value not exist in array");
        }
    }

    /**
     * Vérifie si la clé spécifiée existe dans la collection
     * @param $key clé
     * @return bool VRAI ou FAUX
     */
    public function keyExist($key) : bool
    {
        return array_key_exists($key, $this->collection);
    }

    /**
     * Récupère tout les clés de la collection
     * @return array clés
     */
    public function keys() : array
    {
        return array_keys($this->collection);
    }
}
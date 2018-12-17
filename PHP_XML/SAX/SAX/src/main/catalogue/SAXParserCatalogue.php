<?php
/**
 * Created by IntelliJ IDEA.
 * User: Samuel Entezam
 * Date: 16/12/2018
 * Time: 23:08
 */

namespace App\main\catalogue;

/**
 * Class SAXParserCatalogue
 * @package App\main\catalogue
 */
class SAXParserCatalogue
{
    private $parser = null;
    private $XMLFile = null;
    private static $path = null;
    private $products = [];
    private $currentFamilyName = null;
    private $currentProduct = 0;
    private $fin = false;
    private $data = null;

    /**
     * Initialise et configure le parser SAX
     * SAXParserCatalogue constructor.
     * @param $parser moteur SAX
     * @param string $XMLFile nom du fichier XML
     * @param string $FilePATH chemin absolu vers le fichier XML
     * @throws \Exception File << $XMLFile >> Not Found
     */
    public function __construct($parser, string $XMLFile, string $FilePATH)
    {
        if (file_exists($FilePATH . $XMLFile)) {
            $this->XMLFile = file_get_contents($FilePATH . $XMLFile);
        } else {
            throw new \Exception("File << $XMLFile >> Not Found");
        }
        $this->parser = $parser;
        // Ignore les caractères d'espacements
        xml_parser_set_option($this->parser, XML_OPTION_SKIP_WHITE, TRUE);
        // Conservation de la casse des noms d'éléments
        xml_parser_set_option($this->parser, XML_OPTION_CASE_FOLDING, FALSE);
        xml_set_object($this->parser, $this);
        xml_set_character_data_handler($this->parser, "_getCharacterData");
        xml_set_element_handler($this->parser, "_beginningElement", "_endElement");
        xml_parse($this->parser, $this->XMLFile);
        xml_parser_free($this->parser);

        var_dump($this->products);
    }

    /**
     * Extrait la fin d'une chaîne de caractère (taille de la chaîne jusqu'à /)
     * @param string $value valeur textuelle
     * @return string fin de la chaîne extraite
     * @throws \Exception Bad string given << $value >>
     */
    private function _extractLastInPath(string $value): string
    {
        $value = trim($value);
        $exploded = explode('/', $value);
        if (empty($exploded)) {
            throw new \Exception("Bad string given << $value >>");
        }
        return mb_strtolower(end($exploded));
    }

    /** Méthode de classe s'éxécutant lorsque le parser SAX rencontre un noeud textuelle
     * @param $parser moteur SAX
     * @param string $data valeur du noeud textuelle
     * @throws \Exception Bad string given << $value >>
     */
    private function _getCharacterData($parser, string $data): void
    {
        if(!$this->fin) {
            $this->data .= ltrim($data);
        }
        else {
           $this->data = '';
        }
        if (!empty($this->data)) {
            switch ($this->_extractLastInPath(self::$path)) {
                case 'cat:montant':
                    $this->_addProductInformation('montant', $this->data, true);
                    break;
                case 'cat:nom':
                    $this->currentFamilyName = $this->data;
                    break;
                case 'cat:désignation':
                    $this->_addProductInformation('désignation', $this->data, false);
                    break;
                case 'cat:produit':
                    break;
                default:
                    break;
            }
        }
    }

    /**
     * Ajoute dans le tableau associatif un produit en fonction du couple key/value
     * @param string $key clé
     * @param string $value valeur
     * @param bool $isFloat
     */
    private function _addProductInformation(string $key, string $value, $isFloat = false) : void
    {
        $currentProduct = $this->products[$this->currentProduct] ?? [];
        if (empty($currentProduct)) {
            $currentProduct = [
                'nom' => null,
                'montant' => null,
                'catégorie' => $this->currentFamilyName,
                'désignation' => null
            ];
        }
        if ($isFloat) {
            $value = (float)$value;
        } else {
            $value = (string) $value;
        }
        var_dump($value);
        $currentProduct[$key] = $value;
        $this->products[$this->currentProduct] = $currentProduct;
    }

    /**
     * Méthode de classe s'éxécutant lorsque le parser SAX rencontre une balise ouvrante
     * @param $parser moteur SAX
     * @param $nameElement nom de l'élément courant
     * @param $attributes attribut(s) possible(s) que contient l'élément
     * @throws \Exception Bad string given << $value >>
     */
    private function _beginningElement($parser, $nameElement, $attributes): void
    {
        $this->fin = false;
        self::$path .= "/$nameElement";
        if ($this->_extractLastInPath(self::$path) === "cat:produit") {
            $this->currentProduct++;
        }
    }

    /**
     * Méthode de classe s'exécutant lorsque le parser SAX rencontre une balise fermante
     * @param $parser moteur SAX
     * @param $nameElement nom de l'élément courant
     */
    private function _endElement($parser, $nameElement): void
    {
        $this->fin = true;
        self::$path = substr(self::$path, 0, strrpos(self::$path, '/'));
    }
}
<?php
/**
 * Created by IntelliJ IDEA.
 * User: Samuel Entezam
 * Date: 10/12/2018
 * Time: 08:55
 */

namespace TP_PHP\src\MIAGE;

use TP_PHP\core\Controller;
use TP_PHP\Utils\Formation;
use TP_PHP\Utils\Module;
use TP_PHP\Utils\Semestre;
use TP_PHP\Utils\UE;

/**
 * Class MIAGE
 * @package TP_PHP\src\MIAGE
 */
class MIAGE extends Controller
{
    private $document = null;
    private $xPath = null;
    private $fileName = null;

    /**
     * MIAGE constructor.
     * @throws \Exception File Not Found on this server
     */
    public function __construct()
    {
        // Initialisation de la session
        $this->getSession();
        $FILEPATH = $_SERVER['DOCUMENT_ROOT'].'/xml/MIAGEXML/MIAGE.xml';
        if(file_exists($FILEPATH)) {
            $this->document = new \DOMDocument();
            $this->document->load($FILEPATH);
            $this->xPath = new \DOMXPath($this->document);
            $this->fileName = substr($this->document->baseURI, strrpos($this->document->baseURI, '/')+1, strlen($this->document->baseURI));
            $_SESSION['flashBagMsgSuccess']['fileLoad'] = "<div class='text-center'> Le fichier $this->fileName a été chargé avec succès ! </div>";
        }
        else {
            throw new \Exception("File Not Found on this server");
        }
    }

    /**
     * Retourne la vue pour le fichier XML
     * @throws \Exception
     */
    public function renderView() : void
    {
        $flashBag = $this->getFlashBag();
        $formations = $this->getFormations($this->document);
        $this->render(
            'MIAGEView',
            array (
                'success' => count($flashBag['success']) > 0 ? $flashBag['success'] : null,
                'errors' => count($flashBag['errors']) > 0 ? $flashBag['errors'] : null,
                'formations' => $formations
            ),
            "MIAGE"
        );
    }

    /**
     * Récupère dans un tableau les données pour chaque formations existantes dans le fichier XML
     * @param \DOMDocument $document Document XML
     * @return array Tableau des formations et de leurs contenus
     * @throws \Exception XML Document is NULL
     */
    private function getFormations(\DOMDocument $document) : array
    {
        $data = array();
        if($document != null) {
            $formations = $document->getElementsByTagNameNS("http://www.univ-amu.fr/XML/MIAGE", "formation");
            if($formations->count() > 0) {
                foreach ($formations as $value) {
                    $value->hasAttributes() ? $niveau = $value->getAttribute("niveau") :
                        $_SESSION['flashBagMsgErrors']['missingNiveau'] = "<div class='text-center'>Le niveau de la formation est absent...</div>";
                    $intitule = $value->getElementsByTagNameNS("http://www.univ-amu.fr/XML/MIAGE", "intitulé")->item(0)->nodeValue;
                    $formation = new Formation(
                        isset($niveau) ? $niveau : null,
                        $intitule,
                        $this->getSemestres($value)
                   );
                    $data[] = $formation;
                }
            }
            else {
                $_SESSION['flashBagMsgErrors']['emptyFormation'] = "<div class='text-center'>Aucune formation(s) trouvée(s) dans le fichier $this->fileName XML.</div>";
            }
        }
        else {
            throw new \Exception("XML Document is NULL");
        }
        return $data;
    }

    /**
     * Ajoute dans le tableau global de la méthode @see getFormations les semestres pour chaque formations
     * @param \DOMElement $currentFormation Noeud courant représentant la formation
     * @return array Tableau contenant les semestres et leurs contenus
     * @throws \Exception Formation Node is NULL
     */
    private function getSemestres(\DOMElement $currentFormation) : array
    {
        $data = array();
        if($currentFormation != null) {
            $semestres = $currentFormation->getElementsByTagNameNS("http://www.univ-amu.fr/XML/MIAGE", "semestre");
            if($semestres->count() > 0) {
                foreach ($semestres as $value) {
                    $value->hasAttributes() ? $numero = $value->getAttribute("numéro") :
                        $_SESSION['flashBagMsgErrors']['missingNumberSemestre'] = "<div class='text-center'>Le numéro de semestre est absent de l'élément $value->nodeName</div>";
                    $semestre = new Semestre(
                        isset($numero) ? $numero : null,
                        $this->getUE($value)
                    );
                    $data[] = $semestre;
                }
            }
            else {
                $_SESSION['flashBagMsgErrors']['emptySemestre'] = "<div class='text-center'>Aucun semestre(s) trouvé(s) dans le fichier $this->fileName XML</div>";
            }
        }
        else {
            throw new \Exception("Formation Node is NULL" );
        }
        return $data;
    }

    /**
     * Ajoute dans le tableau global de la méthode @see getFormations les UE pour chaque semestres
     * @param \DOMElement $currentSemestre Semestre concerné
     * @return array Tableau contenant les UE et leurs contenus
     * @throws \Exception Semestre Node is NULL
     */
    private function getUE(\DOMElement $currentSemestre) : array
    {
        $data = array();
        if($currentSemestre != null) {
            $listUE = $currentSemestre->getElementsByTagNameNS("http://www.univ-amu.fr/XML/MIAGE", "UE");
            if($listUE->count() > 0) {
                foreach ($listUE as $value) {
                    $value->hasAttributes() ? $type = $value->getAttribute("type") :
                        $_SESSION['flashBagMsgErrors']['missingTypeUE'] = "<div class='text-center'>Le type de l'UE est absent de l'élément $value->nodeName</div>";
                    $UE = new UE(
                      isset($type) ? $type : null,
                      $value->getElementsByTagNameNS("http://www.univ-amu.fr/XML/MIAGE", "intitulé")->item(0)->nodeValue,
                      $value->getElementsByTagNameNS("http://www.univ-amu.fr/XML/MIAGE", "ECTS")->item(0)->nodeValue,
                      $this->getModules($value)
                    );
                    $data[] = $UE;
                }
            }
            else {
                $_SESSION['flashBagMsgErrors']['emptyUE'] = "<div class='text-center'>Aucune UE trouvées dans le fichier $this->fileName XML</div>";
            }
        }
        else {
            throw new \Exception("Semestre Node is NULL");
        }
        return $data;
    }

    /**
     * Ajoute dans le tableau global de la méthode @see getFormations les modules pour chaque UE
     * @param \DOMElement $currentUE UE concernée
     * @return array Tableau contenant les modules et leurs contenus
     * @throws \Exception Module Node is NULL
     */
    private function getModules(\DOMElement $currentUE) : array
    {
        $data = array();
        if($currentUE != null) {
            $modules = $currentUE->getElementsByTagNameNS("http://www.univ-amu.fr/XML/MIAGE", "module");
            if($modules->count() > 0) {
                foreach ($modules as $value) {
                    $module = new Module(
                        $value->getElementsByTagNameNS("http://www.univ-amu.fr/XML/MIAGE", "intitulé")->item(0)->nodeValue,
                        $value->getElementsByTagNameNS("http://www.univ-amu.fr/XML/MIAGE", "CM")->item(0)->nodeValue,
                        $value->getElementsByTagNameNS("http://www.univ-amu.fr/XML/MIAGE", "TDTP")->item(0)->nodeValue
                    );
                    $data[] = $module;
                }
            }
            else {
                $_SESSION['flashBagMsgErrors']['emptyModule'] = "<div class='text-center'>Aucun module(s) trouvé(s) dans le fichier $this->fileName XML</div>";
            }
        }
        else {
            throw new \Exception("Module Node is NULL");
        }
        return $data;
    }
}
<?php
/**
 * Created by IntelliJ IDEA.
 * User: Entezam Samuel
 * Date: 03/12/2018
 * Time: 11:41
 */

namespace TP_PHP\core;

/**
 * Class Controller
 * @package TP_PHP\core
 */
class Controller
{
    /** Renvoie la vue précisée en paramètre
     * @param $view vue
     * @param $array paramètre(s) à passer à la vue
     * @param $folderView nom du dossier racine
     */
    public function render($view, $array, $folderView) : void
    {
        ob_start();
        extract($array);
        include($_SERVER['DOCUMENT_ROOT'].'/src/'.$folderView.'/'.$view.'.php');
        echo ob_get_clean();
    }

    /**
     * Initialise ou récupère la session en cours
     */
    public function getSession() : void
    {
        session_status() === PHP_SESSION_NONE ? session_start() : $_SESSION[session_id()];
    }

    public function getFlashBag() : array
    {
        return array();
    }
}
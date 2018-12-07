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

    /**
     * Récupère et met en forme tout les messages stockés en session
     * @return array tableau des messages success / errors
     */
    public function getFlashBag() : array
    {
        $flashSuccess = array();
        $flashError = array();

        if(isset($_SESSION['flashBagMsgSuccess']) && !empty($_SESSION['flashBagMsgSuccess']))
        {
            foreach ($_SESSION['flashBagMsgSuccess'] as $message) {
                array_push($flashSuccess, "<div class='col-md-6 alert alert-success'>".$message."</div>");
            }
            unset($_SESSION['flashBagMsgSuccess']);
        }

        if(isset($_SESSION['flashBagMsgErrors']) && !empty($_SESSION['flashBagMsgErrors']))
        {
            foreach ($_SESSION['flashBagMsgErrors'] as $message) {
                array_push($flashError, "<div class='col-md-6 alert alert-danger'>".$message."</div>");
            }
            unset($_SESSION['flashBagMsgErrors']);
        }

        $sessionFlash = array(
            'success' => $flashSuccess,
            'errors' => $flashError
        );
        return $sessionFlash;
    }
}
<?php
/**
 * Created by IntelliJ IDEA.
 * User: Samuel Entezam
 * Date: 10/12/2018
 * Time: 10:42
 */

namespace TP_PHP\Utils;


class Module
{
    /**
     * @var null|string
     */
    private $intitule = null;
    /**
     * @var int|null
     */
    private $heuresCM = null;
    /**
     * @var int|null
     */
    private $heuresTDTP = null;

    /**
     * Module constructor.
     * @param string $intitule Intitulé du module
     * @param int $heuresCM Nombre d'heures de CM du module
     * @param int $heuresTDTP Nombre d'heures de TD/TP du module
     */
    public function __construct(string $intitule, int $heuresCM, int $heuresTDTP)
    {
        $this->intitule = $intitule;
        $this->heuresCM = $heuresCM;
        $this->heuresTDTP = $heuresTDTP;
    }

    /**
     * @return null|string
     */
    public function getIntitule(): ?string
    {
        return $this->intitule;
    }

    /**
     * @param null|string $intitule
     */
    public function setIntitule(?string $intitule): void
    {
        $this->intitule = $intitule;
    }

    /**
     * @return int|null
     */
    public function getHeuresCM(): ?int
    {
        return $this->heuresCM;
    }

    /**
     * @param int|null $heuresCM
     */
    public function setHeuresCM(?int $heuresCM): void
    {
        $this->heuresCM = $heuresCM;
    }

    /**
     * @return int|null
     */
    public function getHeuresTDTP(): ?int
    {
        return $this->heuresTDTP;
    }

    /**
     * @param int|null $heuresTDTP
     */
    public function setHeuresTDTP(?int $heuresTDTP): void
    {
        $this->heuresTDTP = $heuresTDTP;
    }
}
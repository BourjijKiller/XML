<?php
/**
 * Created by IntelliJ IDEA.
 * User: Samuel Entezam
 * Date: 10/12/2018
 * Time: 10:17
 */

namespace TP_PHP\Utils;

/**
 * Class Formation
 * @package TP_PHP\Utils
 */
class Formation
{
    /**
     * @var null|string
     */
    private $niveau = null;
    /**
     * @var null|string
     */
    private $intitule = null;
    /**
     * @var array|null
     */
    private $semestre = null;

    /**
     * Formation constructor.
     * @param string|null $niveau niveau de la formation
     * @param string|null $intitule intitulé de la formation
     * @param array|null $semestre tableau de @semestre associé à la formation
     */
    public function __construct(?string $niveau, ?string $intitule, ?array $semestre)
    {
        $this->niveau = $niveau;
        $this->intitule = $intitule;
        $this->semestre = $semestre;
    }

    /**
     * @return null|string
     */
    public function getNiveau(): ?string
    {
        return $this->niveau;
    }

    /**
     * @param null|string $niveau
     */
    public function setNiveau(?string $niveau): void
    {
        $this->niveau = $niveau;
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
     * @return null|array
     */
    public function getSemestre(): ?array
    {
        return $this->semestre;
    }

    /**
     * @param null|array $semestre
     */
    public function setSemestre(array $semestre): void
    {
        $this->semestre = $semestre;
    }
}
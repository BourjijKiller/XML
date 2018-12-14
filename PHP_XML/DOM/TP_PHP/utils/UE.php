<?php
/**
 * Created by IntelliJ IDEA.
 * User: Samuel Entezam
 * Date: 10/12/2018
 * Time: 10:29
 */

namespace TP_PHP\Utils;


class UE
{
    /**
     * @var null|string
     */
    private $type = null;
    /**
     * @var null|string
     */
    private $intitule = null;
    /**
     * @var int|null
     */
    private $ECTS = null;
    /**
     * @var array|null
     */
    private $module = null;

    /**
     * UE constructor.
     * @param string $type optionnelle | obligatoire
     * @param string $intitule Intitulé de l'UE
     * @param int $ECTS Nombre d'ECTS que rapporte l'UE
     * @param array $module module(s) contenu dans l'UE
     */
    public function __construct(string $type, string $intitule, int $ECTS, array $module)
    {
        $this->type = $type;
        $this->intitule = $intitule;
        $this->ECTS = $ECTS;
        $this->module = $module;
    }

    /**
     * @return null|string
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @param null|string $type
     */
    public function setType(?string $type): void
    {
        $this->type = $type;
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
    public function getECTS(): ?int
    {
        return $this->ECTS;
    }

    /**
     * @param int|null $ECTS
     */
    public function setECTS(?int $ECTS): void
    {
        $this->ECTS = $ECTS;
    }

    /**
     * @return array|null
     */
    public function getModule(): ?array
    {
        return $this->module;
    }

    /**
     * @param array|null $module
     */
    public function setModule(?array $module): void
    {
        $this->module = $module;
    }
}
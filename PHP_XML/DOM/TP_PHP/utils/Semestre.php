<?php
/**
 * Created by IntelliJ IDEA.
 * User: Samuel Entezam
 * Date: 10/12/2018
 * Time: 10:19
 */

namespace TP_PHP\Utils;


class Semestre
{
    /**
     * @var null|string
     */
    private $numero = null;
    /**
     * @var array|null
     */
    private $UE = null;

    /**
     * Semestre constructor.
     * @param null|string $numero Numéro du semestre
     * @param array|null $UE Tbaleau contenant les UE pour chaque semestre
     */
    public function __construct(?string $numero, ?array $UE)
    {
        $this->numero = $numero;
        $this->UE = $UE;
    }

    /**
     * @return null|string
     */
    public function getNumero(): ?string
    {
        return $this->numero;
    }

    /**
     * @return array|null
     */
    public function getUE(): ?array
    {
        return $this->UE;
    }
}
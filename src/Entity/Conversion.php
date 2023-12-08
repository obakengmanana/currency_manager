<?php
// src/Entity/Conversion.php

namespace App\Entity;

class Conversion
{


    private $zarName;
    private $zarCode;
    private $inZar;
    private $compName;
    private $compCode;
    private $inComp;
    private $currMultiByzar;


    public function getZarName(): string
    {
        return $this->zarName;
    }

    public function setZarName(string $zarName): void
    {
        $this->zarName = $zarName;
    }

    public function getZarCode(): string
    {
        return $this->zarCode;
    }

    public function setZarCode(string $zarCode): void
    {
        $this->zarCode = $zarCode;
    }

    public function getInZar(): float
    {
        return $this->inZar;
    }

    public function setInZar(float $inZar): void
    {
        $this->inZar = $inZar;
    }

    public function getCompName(): string
    {
        return $this->compName;
    }

    public function setCompName(string $compName): void
    {
        $this->compName = $compName;
    }

    public function getCompCode(): string
    {
        return $this->compCode;
    }

    public function setCompCode(string $compCode): void
    {
        $this->compCode = $compCode;
    }

    public function getInComp(): float
    {
        return $this->inComp;
    }

    public function setInComp(float $inComp): void
    {
        $this->inComp = $inComp;
    }

    public function getcurrMultiByzar(): float
    {
        return $this->currMultiByzar;
    }

    public function setcurrMultiByzar(float $currMultiByzar): void
    {
        $this->currMultiByzar = $currMultiByzar;
    }
}

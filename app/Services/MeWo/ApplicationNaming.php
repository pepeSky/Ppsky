<?php
namespace App\Services\MeWo;
class ApplicationNaming
{
    public function name(string $input): string
    {
        $base = trim(preg_replace('/(?:[.-](?:Application|MeWo))+$/iu', '', trim($input)));
        return $base === '' ? '' : $base.'.MeWo';
    }
    public function description(string $input, string $systemName): string
    {
        $name = $this->name($input);
        if ($name === '') { return ''; }
        if (mb_strtolower($name) === 'pepesky.mewo') {
            return 'Solución informática al problema de comunicación del usuario PepeSky, creada bajo las normas del mecanismo de trabajo MeWo en el sistema JFRV.';
        }
        return 'Solución informática al problema de comunicación del usuario '.$systemName.', creada bajo las normas del mecanismo de trabajo MeWo en el sistema PepeSky –Execution Quantum–.';
    }
}

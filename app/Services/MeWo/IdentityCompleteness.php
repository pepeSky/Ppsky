<?php
namespace App\Services\MeWo;
use App\Models\Identity;
class IdentityCompleteness
{
    public function percentages(Identity $identity): array
    {
        return [
            'contact'=>$this->percentage(config('identity_information.contact',[]),fn($key)=>$identity->getAttribute($key)),
            'personal'=>$this->percentage(config('identity_information.personal',[]),fn($key)=>($identity->personal_information ?? [])[$key] ?? null),
        ];
    }
    private function percentage(array $fields, callable $value): ?float
    {
        if(!$fields) { return null; }
        $complete=count(array_filter(array_keys($fields),fn($key)=>filled($value($key))));
        return round(100*$complete/count($fields),1);
    }
}

<?php
namespace App\Services\MeWo;
use App\Models\Identity;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
class IdentityDetails
{
    public function validate(Request $request, ?Identity $identity=null): array
    {
        $normalized=[];
        foreach(['identifier_number','identifier_country','identifier_kind'] as $field) {
            $value=$request->input($field);
            if(is_string($value)) { $normalized[$field]=strtoupper($field==='identifier_number' ? preg_replace('/[.\\s-]+/u','',trim($value)) : trim($value)); }
        }
        $request->merge($normalized);
        $rules=['identification'=>['nullable','string','max:255'], 'person_type'=>['nullable',Rule::in(['natural','legal','other'])],
            'email'=>['nullable','email','max:255'],'birth_date'=>['nullable','date_format:Y-m-d','before_or_equal:today'],
            'identifier_country'=>['nullable','required_with:identifier_number','string','size:2'],
            'identifier_kind'=>['nullable','required_with:identifier_number','string','max:50'],
            'identifier_number'=>['nullable','required_with:identifier_country,identifier_kind','string','max:100',
                Rule::unique('identities','identifier_number')->where('identifier_country',$request->input('identifier_country'))->where('identifier_kind',$request->input('identifier_kind'))->ignore($identity?->id)],
        ];
        foreach(['first_name','second_name','third_name','paternal_surname','maternal_surname','legal_name','nationality','phone','address','city','region','country'] as $field) { $rules[$field]=['nullable','string','max:255']; }
        $rules['personal_information']=['sometimes','nullable','array:'.implode(',',array_keys(config('identity_information.personal',[])))];
        foreach(array_keys(config('identity_information.personal',[])) as $field) { $rules['personal_information.'.$field]=['nullable','string','max:20000']; }
        $data=$request->validate($rules);
        if(is_array($data['personal_information'] ?? null)) { $data['personal_information']=array_replace($identity?->personal_information ?? [],$data['personal_information']); }
        return $data;
    }
}

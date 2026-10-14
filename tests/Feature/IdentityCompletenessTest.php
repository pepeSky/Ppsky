<?php
namespace Tests\Feature;
use App\Models\{Entity,Identity,Nature,System,User};
use App\Services\MeWo\IdentityCompleteness;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
class IdentityCompletenessTest extends TestCase
{
    use RefreshDatabase;
    public function test_percentages_are_linear_and_whitespace_is_not_information(): void
    {
        $service=app(IdentityCompleteness::class);
        $identity=new Identity(['email'=>'persona@example.com','phone'=>'  ','personal_information'=>['mission'=>'Una misión','vision'=>'Una visión','values'=>' ','propositional_logic'=>null]]);
        $this->assertSame(['contact'=>16.7,'personal'=>50.0],$service->percentages($identity));
        $identity->fill(['phone'=>'123','address'=>'Calle 1','city'=>'Santiago','region'=>'Metropolitana','country'=>'Chile','personal_information'=>['mission'=>'M','vision'=>'V','values'=>'Valores','propositional_logic'=>'LP']]);
        $this->assertSame(['contact'=>100.0,'personal'=>100.0],$service->percentages($identity));
        $this->assertSame(['contact'=>0.0,'personal'=>0.0],$service->percentages(new Identity()));
    }
    public function test_unconfigured_criteria_do_not_claim_zero_completeness(): void
    {
        config(['identity_information.contact'=>[],'identity_information.personal'=>[]]);
        $this->assertSame(['contact'=>null,'personal'=>null],app(IdentityCompleteness::class)->percentages(new Identity()));
    }
    public function test_personal_information_is_saved_and_table_only_shows_bars(): void
    {
        $owner=User::factory()->create(); $nature=Nature::create(['name'=>'Humano','slug'=>'humano']);
        $entity=Entity::create(['name'=>'JFRV','code'=>'JFRV','nature_id'=>$nature->id]);
        $identity=Identity::create(['user_id'=>$owner->id,'entity_id'=>$entity->id]);
        $system=System::create(['identity_id'=>$identity->id]); $system->users()->attach($owner);
        $this->actingAs($owner)->withSession(['active_system_id'=>$system->id]);
        $this->put(route('admin.identities.update',$identity),['email'=>'pepe@example.com','personal_information'=>['mission'=>'Texto privado de misión','vision'=>'Texto privado de visión']])->assertSessionHasNoErrors()->assertRedirect();
        $this->get(route('admin.identities.index'))->assertOk()->assertSee('Datos de contacto')->assertSee('Información personal')->assertSee('16,7 %')->assertSee('50 %')->assertSee('role="progressbar"',false)->assertSee('Integración')->assertDontSee('Ver datos')->assertDontSee('Texto privado de misión');
        $this->put(route('admin.identities.update',$identity),['personal_information'=>['values'=>'Valores privados']])->assertRedirect();
        $this->assertSame('Texto privado de misión',$identity->fresh()->personal_information['mission']);
        $this->assertSame(75.0,app(IdentityCompleteness::class)->percentages($identity->fresh())['personal']);
        $this->get(route('admin.identities.show',$identity))->assertOk()->assertSee('Texto privado de misión');
        $this->put(route('admin.identities.update',$identity),['personal_information'=>['inventado'=>'Valor']])->assertSessionHasErrors('personal_information');
    }
}

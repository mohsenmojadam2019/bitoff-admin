<?php

namespace Bitoff\Mantis\Tests\Feature\Http\Controllers;

use Bitoff\Mantis\Application\Models\Setting;
use Bitoff\Mantis\Tests\TestCase;
use Illuminate\Http\Response;

class SettingControllerTest extends TestCase
{
    /** @test */
    public function authenticate_user_can_see_all_settings()
    {
        $setting = Setting::factory()->create(['key'=>Setting::OFFERER_LEVEL]);
        $response = $this->actingAsUser()->get(route('mantis.settings.index'));

        $response->assertStatus(Response::HTTP_OK);
        $response->assertViewIs('Mantis::settings.index');
        $response->assertViewHas('settings');
        $response->assertSee($setting->value[0]->max);
        $response->assertSee($setting->value[1]->max_percent);
        $response->assertSee($setting->value[2]->max_offer);
    }

    /** @test */
    public function unauthenticated_user_can_not_see_all_settings()
    {
        $response = $this->get(route('mantis.settings.index'));

        $response->assertRedirect('login');
    }

    /** @test */
    public function authenticate_user_can_update_a_settings()
    {
        $setting = Setting::factory()->create(['key'=>Setting::OFFERER_LEVEL]);
        $attributes = [
            'min'=>[0,30,151],
            'max'=>[29,150,50000],
            'level'=>[1,2,3],
            'max_offer'=>[50,100,150],
            'max_percent'=>[80,20,30],
        ];
        
        $response = $this->actingAsUser()->patch(route('mantis.settings.update',$setting->key),$attributes);

        $updatedSetting = Setting::first()->get()->toArray();
        $response->assertOk();
        $this->assertSame(80,$updatedSetting[0]['value'][0]->max_percent);
        $this->assertSame(20,$updatedSetting[0]['value'][1]->max_percent);
        $this->assertSame(150,$updatedSetting[0]['value'][2]->max_offer);
        $this->assertSame(1,$updatedSetting[0]['value'][0]->level);
    }
}

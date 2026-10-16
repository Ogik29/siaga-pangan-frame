<?php

use App\Models\Commodity;
use App\Models\Region;

test('risk analysis works when no price record exists', function () {
    $region = Region::create([
        'code' => 'TEST',
        'name' => 'Test Region',
        'province' => 'Test Province',
        'is_active' => true,
    ]);

    $commodity = Commodity::create([
        'code' => 'TEST',
        'name' => 'Test Commodity',
        'unit' => 'ton',
        'is_active' => true,
    ]);

    $response = $this->post(route('analysis.run'), [
        'region_id' => $region->id,
        'commodity_id' => $commodity->id,
        'horizon_days' => 7,
    ]);

    $response
        ->assertRedirect(route('analysis', [
            'region_id' => $region->id,
            'commodity_id' => $commodity->id,
            'horizon_days' => 7,
        ]))
        ->assertSessionHas(
            'success',
            'Risk analysis completed successfully.'
        );

    $this->assertDatabaseHas('risk_predictions', [
        'region_id' => $region->id,
        'commodity_id' => $commodity->id,
        'horizon_days' => 7,
        'risk_level' => 'Low',
    ]);
});

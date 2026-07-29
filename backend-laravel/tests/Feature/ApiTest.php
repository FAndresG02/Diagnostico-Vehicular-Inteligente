<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_vehicle_endpoints(): void
    {
        $response = $this->getJson('/api/vehicle');
        $response->assertJson(['exists' => false]);

        $response = $this->postJson('/api/vehicle', [
            'marca' => 'Chevrolet',
            'modelo' => 'Spark GT',
            'anio' => '2020',
            'vin' => 'LSG1234567890XYZ',
        ]);
        $response->assertStatus(200);

        $response = $this->getJson('/api/vehicle');
        $response->assertJson(['exists' => true]);
    }

    public function test_obd_endpoints(): void
    {
        $response = $this->postJson('/api/obd', [
            'dtc' => ['P0301', 'P0420', 'C1234', 'invalid!'],
        ]);
        $response->assertStatus(200)
            ->assertJsonStructure(['status', 'saved' => ['dtc', 'timestamp']]);
        $this->assertEquals(['C1234', 'P0301', 'P0420'], $response->json('saved.dtc'));

        $response = $this->getJson('/api/data');
        $response->assertStatus(200)
            ->assertJsonStructure(['dtc_registros', 'count']);
    }

    public function test_simulate_endpoints(): void
    {
        $response = $this->getJson('/api/simulate');
        $response->assertStatus(200)
            ->assertJsonStructure(['status', 'generated_raw', 'generated_cleaned']);

        $response = $this->getJson('/api/create_dtc/P0999');
        $response->assertStatus(200)
            ->assertJsonStructure(['status', 'received_raw', 'generated_cleaned']);
    }

    public function test_delete_dtc(): void
    {
        $this->postJson('/api/obd', ['dtc' => ['P0301', 'P0420']]);

        $response = $this->deleteJson('/api/delete_dtc/P0301');
        $response->assertStatus(200)
            ->assertJson(['status' => 'ok', 'deleted_code' => 'P0301']);

        $response = $this->getJson('/api/data');
        $this->assertEquals(1, $response->json('count'));
    }

    public function test_destroy_all_obd(): void
    {
        $this->postJson('/api/obd', ['dtc' => ['P0301']]);
        $this->postJson('/api/obd', ['dtc' => ['P0420']]);

        $response = $this->postJson('/api/borrar_dtc_todos');
        $response->assertStatus(200);

        $response = $this->getJson('/api/data');
        $this->assertEquals(0, $response->json('count'));
    }

    public function test_ecu_commands(): void
    {
        $response = $this->getJson('/api/commands/status');
        $response->assertJson(['exists' => false]);

        $response = $this->postJson('/api/commands/clear_dtc');
        $response->assertStatus(200);

        $response = $this->getJson('/api/commands/status');
        $response->assertJson(['exists' => true]);

        $response = $this->postJson('/api/commands/confirm', ['status' => 'success']);
        $response->assertStatus(200);
    }

    public function test_ia_reports_endpoints(): void
    {
        $response = $this->getJson('/api/ia_reports');
        $response->assertStatus(200)
            ->assertJson(['count' => 0]);

        $response = $this->deleteJson('/api/ia_reports');
        $response->assertStatus(200);
    }

    public function test_obd_validation(): void
    {
        $response = $this->postJson('/api/obd', []);
        $response->assertStatus(422);

        $response = $this->postJson('/api/obd', ['dtc' => []]);
        $response->assertStatus(422);

        $response = $this->postJson('/api/obd', ['dtc' => ['!!!']]);
        $response->assertStatus(400);
    }

    public function test_vehicle_validation(): void
    {
        $response = $this->postJson('/api/vehicle', []);
        $response->assertStatus(422);
    }
}

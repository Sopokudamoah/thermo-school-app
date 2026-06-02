<?php

namespace Tests\Feature\Finance;

use App\Models\User;
use App\Models\Finance\FeeType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class FeeTypeTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['role' => 'admin']);
        Permission::create(['name' => 'finance.view']);
        Permission::create(['name' => 'finance.fee-type.create']);
        Permission::create(['name' => 'finance.fee-type.edit']);
        $this->admin->givePermissionTo(['finance.view', 'finance.fee-type.create', 'finance.fee-type.edit']);
    }

    public function test_admin_can_view_fee_types()
    {
        FeeType::create([
            'name' => 'Tuition',
            'code' => 'TUI',
            'recurring' => true,
            'active' => true
        ]);

        $response = $this->actingAs($this->admin)->get(route('finance.fee-types.index'));

        $response->assertStatus(200);
        $response->assertSee('Tuition');
        $response->assertSee('TUI');
    }

    public function test_admin_can_create_fee_type()
    {
        $data = [
            'name' => 'Laboratory',
            'code' => 'LAB',
            'description' => 'Lab fees',
            'recurring' => 0,
            'active' => 1
        ];

        $response = $this->actingAs($this->admin)->post(route('finance.fee-types.store'), $data);

        $response->assertStatus(302);
        $this->assertDatabaseHas('finance_fee_types', [
            'name' => 'Laboratory',
            'code' => 'LAB'
        ]);
    }

    public function test_admin_can_update_fee_type()
    {
        $feeType = FeeType::create([
            'name' => 'Original Name',
            'code' => 'ORIG',
            'recurring' => true,
            'active' => true
        ]);

        $data = [
            'name' => 'Updated Name',
            'code' => 'UPDT',
            'recurring' => 0,
            'active' => 1
        ];

        $response = $this->actingAs($this->admin)->put(route('finance.fee-types.update', $feeType->id), $data);

        $response->assertStatus(302);
        $this->assertDatabaseHas('finance_fee_types', [
            'id' => $feeType->id,
            'name' => 'Updated Name'
        ]);
    }
}

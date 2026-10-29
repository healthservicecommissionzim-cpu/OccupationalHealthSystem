<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use App\Models\StaffRoster;
use App\Models\RosterAssignment;
use App\Models\Facility;
use App\Models\District;
use App\Policies\RosterPolicy;
use App\Services\Roster\RosterAccessService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RosterPolicyTest extends TestCase
{
    use RefreshDatabase;

    protected $policy;
    protected $rosterAccessService;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->rosterAccessService = app(RosterAccessService::class);
        $this->policy = new RosterPolicy($this->rosterAccessService);
    }

    /** @test */
    public function super_admin_can_view_any_rosters()
    {
        $superAdmin = User::factory()->create();
        $role = Role::firstOrCreate(['name' => 'super_admin']);
        $superAdmin->roles()->attach($role);

        $this->assertTrue($this->policy->viewAny($superAdmin));
    }

    /** @test */
    public function central_hr_can_view_any_rosters()
    {
        $centralHr = User::factory()->create();
        $role = Role::firstOrCreate(['name' => 'central_hr']);
        $centralHr->roles()->attach($role);

        $this->assertTrue($this->policy->viewAny($centralHr));
    }

    /** @test */
    public function district_hr_can_view_any_rosters()
    {
        $district = District::factory()->create();
        $districtHr = User::factory()->create(['district_id' => $district->id]);
        $role = Role::firstOrCreate(['name' => 'district_hr']);
        $districtHr->roles()->attach($role);

        $this->assertTrue($this->policy->viewAny($districtHr));
    }

    /** @test */
    public function hospital_hr_can_view_any_rosters()
    {
        $facility = Facility::factory()->create();
        $hospitalHr = User::factory()->create(['facility_id' => $facility->id]);
        $role = Role::firstOrCreate(['name' => 'hospital_hr']);
        $hospitalHr->roles()->attach($role);

        $this->assertTrue($this->policy->viewAny($hospitalHr));
    }

    /** @test */
    public function hsc_conditions_can_view_any_rosters()
    {
        $hscConditions = User::factory()->create();
        $role = Role::firstOrCreate(['name' => 'hsc_conditions']);
        $hscConditions->roles()->attach($role);

        $this->assertTrue($this->policy->viewAny($hscConditions));
    }

    /** @test */
    public function regular_user_cannot_view_any_rosters()
    {
        $user = User::factory()->create();
        $role = Role::firstOrCreate(['name' => 'worker']);
        $user->roles()->attach($role);

        $this->assertFalse($this->policy->viewAny($user));
    }

    /** @test */
    public function only_super_admin_can_create_rosters()
    {
        $superAdmin = User::factory()->create();
        $role = Role::firstOrCreate(['name' => 'super_admin']);
        $superAdmin->roles()->attach($role);

        $centralHr = User::factory()->create();
        $centralHrRole = Role::firstOrCreate(['name' => 'central_hr']);
        $centralHr->roles()->attach($centralHrRole);

        $this->assertTrue($this->policy->create($superAdmin));
        $this->assertFalse($this->policy->create($centralHr));
    }

    /** @test */
    public function only_super_admin_can_update_rosters()
    {
        $facility = Facility::factory()->create();
        $roster = StaffRoster::factory()->create(['primary_facility_id' => $facility->id]);

        $superAdmin = User::factory()->create();
        $role = Role::firstOrCreate(['name' => 'super_admin']);
        $superAdmin->roles()->attach($role);

        $centralHr = User::factory()->create();
        $centralHrRole = Role::firstOrCreate(['name' => 'central_hr']);
        $centralHr->roles()->attach($centralHrRole);

        $this->assertTrue($this->policy->update($superAdmin, $roster));
        $this->assertFalse($this->policy->update($centralHr, $roster));
    }

    /** @test */
    public function only_super_admin_can_delete_rosters()
    {
        $facility = Facility::factory()->create();
        $roster = StaffRoster::factory()->create(['primary_facility_id' => $facility->id]);

        $superAdmin = User::factory()->create();
        $role = Role::firstOrCreate(['name' => 'super_admin']);
        $superAdmin->roles()->attach($role);

        $centralHr = User::factory()->create();
        $centralHrRole = Role::firstOrCreate(['name' => 'central_hr']);
        $centralHr->roles()->attach($centralHrRole);

        $this->assertTrue($this->policy->delete($superAdmin, $roster));
        $this->assertFalse($this->policy->delete($centralHr, $roster));
    }

    /** @test */
    public function only_super_admin_can_create_assignments()
    {
        $superAdmin = User::factory()->create();
        $role = Role::firstOrCreate(['name' => 'super_admin']);
        $superAdmin->roles()->attach($role);

        $districtHr = User::factory()->create();
        $districtHrRole = Role::firstOrCreate(['name' => 'district_hr']);
        $districtHr->roles()->attach($districtHrRole);

        $this->assertTrue($this->policy->createAssignment($superAdmin));
        $this->assertFalse($this->policy->createAssignment($districtHr));
    }

    /** @test */
    public function only_super_admin_can_update_assignments()
    {
        $facility = Facility::factory()->create();
        $roster = StaffRoster::factory()->create(['primary_facility_id' => $facility->id]);
        $assignment = RosterAssignment::factory()->create([
            'staff_roster_id' => $roster->id,
            'facility_id' => $facility->id,
            'cost_center_facility_id' => $facility->id,
        ]);

        $superAdmin = User::factory()->create();
        $role = Role::firstOrCreate(['name' => 'super_admin']);
        $superAdmin->roles()->attach($role);

        $hospitalHr = User::factory()->create();
        $hospitalHrRole = Role::firstOrCreate(['name' => 'hospital_hr']);
        $hospitalHr->roles()->attach($hospitalHrRole);

        $this->assertTrue($this->policy->updateAssignment($superAdmin, $assignment));
        $this->assertFalse($this->policy->updateAssignment($hospitalHr, $assignment));
    }

    /** @test */
    public function only_super_admin_can_delete_assignments()
    {
        $facility = Facility::factory()->create();
        $roster = StaffRoster::factory()->create(['primary_facility_id' => $facility->id]);
        $assignment = RosterAssignment::factory()->create([
            'staff_roster_id' => $roster->id,
            'facility_id' => $facility->id,
            'cost_center_facility_id' => $facility->id,
        ]);

        $superAdmin = User::factory()->create();
        $role = Role::firstOrCreate(['name' => 'super_admin']);
        $superAdmin->roles()->attach($role);

        $hscConditions = User::factory()->create();
        $hscConditionsRole = Role::firstOrCreate(['name' => 'hsc_conditions']);
        $hscConditions->roles()->attach($hscConditionsRole);

        $this->assertTrue($this->policy->deleteAssignment($superAdmin, $assignment));
        $this->assertFalse($this->policy->deleteAssignment($hscConditions, $assignment));
    }

    /** @test */
    public function super_admin_can_view_specific_roster()
    {
        $facility = Facility::factory()->create();
        $roster = StaffRoster::factory()->create(['primary_facility_id' => $facility->id]);

        $superAdmin = User::factory()->create();
        $role = Role::firstOrCreate(['name' => 'super_admin']);
        $superAdmin->roles()->attach($role);

        $this->assertTrue($this->policy->view($superAdmin, $roster));
    }

    /** @test */
    public function central_hr_can_view_any_specific_roster()
    {
        $facility = Facility::factory()->create();
        $roster = StaffRoster::factory()->create(['primary_facility_id' => $facility->id]);

        $centralHr = User::factory()->create();
        $role = Role::firstOrCreate(['name' => 'central_hr']);
        $centralHr->roles()->attach($role);

        $this->assertTrue($this->policy->view($centralHr, $roster));
    }

    /** @test */
    public function district_hr_can_view_roster_in_their_district()
    {
        $district = District::factory()->create();
        $facility = Facility::factory()->create(['district_id' => $district->id]);
        $roster = StaffRoster::factory()->create(['primary_facility_id' => $facility->id]);

        $districtHr = User::factory()->create(['district_id' => $district->id]);
        $role = Role::firstOrCreate(['name' => 'district_hr']);
        $districtHr->roles()->attach($role);

        $this->assertTrue($this->policy->view($districtHr, $roster));
    }

    /** @test */
    public function district_hr_cannot_view_roster_outside_their_district()
    {
        $district1 = District::factory()->create();
        $district2 = District::factory()->create();
        $facility = Facility::factory()->create(['district_id' => $district2->id]);
        $roster = StaffRoster::factory()->create(['primary_facility_id' => $facility->id]);

        $districtHr = User::factory()->create(['district_id' => $district1->id]);
        $role = Role::firstOrCreate(['name' => 'district_hr']);
        $districtHr->roles()->attach($role);

        $this->assertFalse($this->policy->view($districtHr, $roster));
    }

    /** @test */
    public function hospital_hr_can_view_roster_at_their_facility()
    {
        $facility = Facility::factory()->create();
        $roster = StaffRoster::factory()->create(['primary_facility_id' => $facility->id]);

        $hospitalHr = User::factory()->create(['facility_id' => $facility->id]);
        $role = Role::firstOrCreate(['name' => 'hospital_hr']);
        $hospitalHr->roles()->attach($role);

        $this->assertTrue($this->policy->view($hospitalHr, $roster));
    }

    /** @test */
    public function hospital_hr_cannot_view_roster_at_different_facility()
    {
        $facility1 = Facility::factory()->create();
        $facility2 = Facility::factory()->create();
        $roster = StaffRoster::factory()->create(['primary_facility_id' => $facility2->id]);

        $hospitalHr = User::factory()->create(['facility_id' => $facility1->id]);
        $role = Role::firstOrCreate(['name' => 'hospital_hr']);
        $hospitalHr->roles()->attach($role);

        $this->assertFalse($this->policy->view($hospitalHr, $roster));
    }
}

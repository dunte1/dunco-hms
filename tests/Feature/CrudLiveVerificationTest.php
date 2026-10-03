<?php

namespace Tests\Feature;

use App\Models\DoctorDepartment;
use App\Models\EmployeeDepartment;
use App\Models\HospitalDepartment;
use App\Models\NurseDepartment;
use App\Models\Role;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Spatie\Permission\Models\Permission;

/**
 * Live CRUD verification against the configured MySQL database.
 * Does NOT wipe the database — creates temp records and cleans them up.
 */
class CrudLiveVerificationTest extends BaseTestCase
{
    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        foreach ([
            'manage roles', 'manage permissions', 'manage user accounts',
            'manage price lists', 'manage services listing', 'manage packages',
            'view billing', 'create invoices', 'manage charges',
            'manage nurses', 'manage staff profiles', 'view doctors',
            'manage employees', 'view dashboard analytics',
        ] as $perm) {
            Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'web']);
        }

        $this->admin = User::where('email', 'crud.verify@test.local')->first()
            ?? User::create([
                'name' => 'CRUD Verify',
                'email' => 'crud.verify@test.local',
                'password' => 'password',
                'email_verified_at' => now(),
            ]);

        $this->admin->syncRoles([
            Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']),
        ]);
        $this->admin->syncPermissions([
            'manage roles', 'manage permissions', 'manage user accounts',
            'manage price lists', 'manage services listing', 'manage packages',
            'view billing', 'create invoices', 'manage charges',
            'manage nurses', 'manage staff profiles', 'view doctors',
            'manage employees',
        ]);

        $this->actingAs($this->admin);
    }

    public function test_services_crud_works(): void
    {
        $code = 'LIVE-SVC-' . uniqid();

        $this->post(route('hms.pricing.services.store'), [
            'name' => 'Live Verify Service',
            'code' => $code,
            'category' => 'consultation',
            'default_price' => 1200,
            'currency' => 'KES',
            'is_active' => 1,
        ])->assertRedirect(route('hms.pricing.services.index'));

        $service = Service::where('code', $code)->first();
        $this->assertNotNull($service, 'Service was created');
        $this->assertSame('Live Verify Service', $service->name);

        $this->get(route('hms.pricing.services.index'))
            ->assertOk()
            ->assertSee('Live Verify Service');

        $this->put(route('hms.pricing.services.update', $service), [
            'name' => 'Live Verify Service Updated',
            'code' => $code,
            'category' => 'consultation',
            'default_price' => 1800,
            'currency' => 'KES',
            'is_active' => 1,
        ])->assertRedirect(route('hms.pricing.services.index'));

        $this->assertSame('Live Verify Service Updated', $service->fresh()->name);
        $this->assertEquals(1800, $service->fresh()->default_price);

        $this->delete(route('hms.pricing.services.destroy', $service))
            ->assertRedirect(route('hms.pricing.services.index'));

        $this->assertSoftDeleted('services', ['id' => $service->id]);
    }

    public function test_hospital_departments_crud_works(): void
    {
        $name = 'Live HD ' . uniqid();

        $this->post(route('hms.hospital-departments.store'), [
            'name' => $name,
            'code' => 'LHD',
            'description' => 'Live hospital dept',
            'is_active' => 1,
        ])->assertRedirect(route('hms.hospital-departments.index'));

        $dept = HospitalDepartment::where('name', $name)->first();
        $this->assertNotNull($dept);

        $this->get(route('hms.hospital-departments.index'))
            ->assertOk()
            ->assertSee($name);

        $this->put(route('hms.hospital-departments.update', $dept), [
            'name' => $name . ' U',
            'code' => 'LHD',
            'description' => 'Updated',
            'is_active' => 1,
        ])->assertRedirect(route('hms.hospital-departments.index'));

        $this->assertSame($name . ' U', $dept->fresh()->name);

        $this->delete(route('hms.hospital-departments.destroy', $dept))
            ->assertRedirect(route('hms.hospital-departments.index'));

        $this->assertDatabaseMissing('hospital_departments', ['name' => $name . ' U']);
    }

    public function test_roles_grouped_by_department_and_role_crud(): void
    {
        // Ensure departments exist
        $opd = HospitalDepartment::firstOrCreate(['name' => 'OPD'], ['code' => 'OPD']);
        $maternity = HospitalDepartment::firstOrCreate(['name' => 'Maternity'], ['code' => 'MAT']);
        $triage = HospitalDepartment::firstOrCreate(['name' => 'Triage'], ['code' => 'TRI']);

        $roleName = 'Live Role ' . uniqid();
        $role = Role::create(['name' => $roleName]);
        $role->department_id = $opd->id;
        $role->save();

        $matRoleName = 'Live Mat Role ' . uniqid();
        $matRole = Role::create(['name' => $matRoleName]);
        $matRole->department_id = $maternity->id;
        $matRole->save();

        $this->get(route('admin.roles.index'))
            ->assertOk()
            ->assertSee('OPD')
            ->assertSee('Maternity')
            ->assertSee($roleName)
            ->assertSee($matRoleName);

        // Create role with department via form
        $newRoleName = 'Live Created ' . uniqid();
        $this->post(route('admin.roles.store'), [
            'name' => $newRoleName,
            'department_id' => $triage->id,
            'permissions' => [],
        ])->assertRedirect(route('admin.roles.index'));

        $created = Role::where('name', $newRoleName)->first();
        $this->assertNotNull($created);
        $this->assertEquals($triage->id, $created->department_id);

        // Update role department
        $this->put(route('admin.roles.update', $created), [
            'name' => $newRoleName,
            'department_id' => $opd->id,
            'permissions' => [],
        ])->assertRedirect(route('admin.roles.index'));

        $this->assertEquals($opd->id, $created->fresh()->department_id);

        // Delete
        $this->delete(route('admin.roles.destroy', $created))
            ->assertRedirect(route('admin.roles.index'));

        $this->assertDatabaseMissing('roles', ['name' => $newRoleName]);

        // Cleanup
        $role->delete();
        $matRole->delete();
    }

    public function test_nurse_departments_crud_works(): void
    {
        $name = 'Live Nurse Dept ' . uniqid();

        $this->post(route('hms.nurses.departments.store'), [
            'name' => $name,
            'description' => 'Live nurse dept',
        ])->assertRedirect(route('hms.nurses.departments'));

        $dept = NurseDepartment::where('name', $name)->first();
        $this->assertNotNull($dept);

        $this->get(route('hms.nurses.departments'))
            ->assertOk()
            ->assertSee($name);

        $this->put(route('hms.nurses.departments.update', $dept), [
            'name' => $name . ' U',
            'description' => 'Updated',
        ])->assertRedirect(route('hms.nurses.departments'));

        $this->assertSame($name . ' U', $dept->fresh()->name);

        $this->delete(route('hms.nurses.departments.destroy', $dept))
            ->assertRedirect(route('hms.nurses.departments'));

        $this->assertDatabaseMissing('nurse_departments', ['name' => $name . ' U']);
    }

    public function test_doctor_departments_crud_works(): void
    {
        $name = 'Live Doctor Dept ' . uniqid();

        $this->post(route('hms.doctors.departments.store'), [
            'name' => $name,
            'description' => 'Live doctor dept',
        ])->assertSessionHas('status');

        $dept = DoctorDepartment::where('name', $name)->first();
        $this->assertNotNull($dept);

        $this->get(route('hms.doctors.departments.index'))
            ->assertOk()
            ->assertSee($name);

        $this->put(route('hms.doctors.departments.update', $dept), [
            'name' => $name . ' U',
            'description' => 'Updated',
        ])->assertSessionHas('status');

        $this->assertSame($name . ' U', $dept->fresh()->name);

        $this->delete(route('hms.doctors.departments.destroy', $dept))
            ->assertSessionHas('status');

        $this->assertDatabaseMissing('doctor_departments', ['name' => $name . ' U']);
    }

    public function test_employee_departments_crud_works(): void
    {
        $name = 'Live Emp Dept ' . uniqid();

        $this->post(route('hms.hr.departments.store'), [
            'name' => $name,
            'description' => 'Live emp dept',
        ])->assertSessionHas('success');

        $dept = EmployeeDepartment::where('name', $name)->first();
        $this->assertNotNull($dept);

        $this->get(route('hms.hr.departments.index'))
            ->assertOk()
            ->assertSee($name);

        $this->put(route('hms.hr.departments.update', $dept), [
            'name' => $name . ' U',
            'description' => 'Updated',
        ])->assertSessionHas('success');

        $this->assertSame($name . ' U', $dept->fresh()->name);

        $this->delete(route('hms.hr.departments.destroy', $dept))
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('employee_departments', ['name' => $name . ' U']);
    }

    public function test_sidebar_and_pages_render_for_admin(): void
    {
        $this->get(route('hms.pricing.services.index'))->assertOk();
        $this->get(route('hms.pricing.price-lists.index'))->assertOk();
        $this->get(route('hms.hospital-departments.index'))->assertOk();
        $this->get(route('admin.roles.index'))->assertOk();
        $this->get(route('hms.nurses.departments'))->assertOk();
        $this->get(route('hms.doctors.departments.index'))->assertOk();
    }
}

<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\EmployeeDepartment;
use App\Models\TraineeRecord;
use App\Models\TraineeAssessment;
use App\Models\ResearchProject;
use App\Models\EthicsApproval;
use App\Models\Publication;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class G049ResearchTrainingTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private EmployeeDepartment $department;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->department = EmployeeDepartment::create([
            'name' => 'Clinical Medicine',
            'code' => 'CM-01',
        ]);
    }

    public function test_trainee_creation(): void
    {
        $response = $this->actingAs($this->user)->post(route('hms.training.trainees.store'), [
            'name' => 'Jane Doe',
            'trainee_type' => 'student',
            'institution' => 'University of Nairobi',
            'department_id' => $this->department->id,
            'program_name' => 'MBChB',
            'start_date' => now()->toDateString(),
            'supervisor_id' => $this->user->id,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('trainee_records', [
            'name' => 'Jane Doe',
            'trainee_type' => 'student',
            'institution' => 'University of Nairobi',
            'department_id' => $this->department->id,
            'program_name' => 'MBChB',
            'supervisor_id' => $this->user->id,
            'status' => 'active',
        ]);
    }

    public function test_trainee_index(): void
    {
        TraineeRecord::create([
            'name' => 'John Student',
            'trainee_type' => 'intern',
            'department_id' => $this->department->id,
            'start_date' => now(),
            'status' => 'active',
        ]);

        $response = $this->actingAs($this->user)->get(route('hms.training.trainees.index'));

        $response->assertOk();
    }

    public function test_trainee_assessment_recording(): void
    {
        $trainee = TraineeRecord::create([
            'name' => 'Jane Doe',
            'trainee_type' => 'resident',
            'department_id' => $this->department->id,
            'start_date' => now(),
            'status' => 'active',
        ]);

        $response = $this->actingAs($this->user)->post(route('hms.training.trainees.assessments.store', $trainee), [
            'assessment_date' => now()->toDateString(),
            'assessment_type' => 'midterm',
            'score' => 85.50,
            'grade' => 'B+',
            'strengths' => 'Excellent clinical skills',
            'areas_for_improvement' => 'Documentation',
            'comments' => 'Good overall performance',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('trainee_assessments', [
            'trainee_id' => $trainee->id,
            'assessment_type' => 'midterm',
            'score' => 85.50,
            'grade' => 'B+',
            'assessed_by' => $this->user->id,
        ]);
    }

    public function test_research_project_lifecycle(): void
    {
        $response = $this->actingAs($this->user)->post(route('hms.research.projects.store'), [
            'title' => 'Malaria Treatment Efficacy Study',
            'objectives' => 'Evaluate effectiveness of new antimalarial combination',
            'budget' => 500000.00,
            'start_date' => now()->toDateString(),
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('research_projects', [
            'title' => 'Malaria Treatment Efficacy Study',
            'principal_investigator_id' => $this->user->id,
            'status' => 'proposal',
            'budget' => 500000.00,
        ]);

        $project = ResearchProject::latest()->first();

        $response = $this->actingAs($this->user)->put(route('hms.research.projects.update', $project), [
            'title' => 'Malaria Treatment Efficacy Study',
            'status' => 'active',
            'objectives' => 'Evaluate effectiveness of new antimalarial combination',
            'methodology' => 'Randomized controlled trial',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('research_projects', [
            'id' => $project->id,
            'status' => 'active',
        ]);
    }

    public function test_research_project_index(): void
    {
        ResearchProject::create([
            'title' => 'Test Project',
            'principal_investigator_id' => $this->user->id,
            'objectives' => 'Test objectives',
            'status' => 'active',
        ]);

        $response = $this->actingAs($this->user)->get(route('hms.research.projects.index'));

        $response->assertOk();
    }

    public function test_ethics_approval_workflow(): void
    {
        $project = ResearchProject::create([
            'title' => 'Ethics Test Project',
            'principal_investigator_id' => $this->user->id,
            'objectives' => 'Test',
            'status' => 'proposal',
        ]);

        $response = $this->actingAs($this->user)->post(route('hms.research.projects.ethics.store', $project), [
            'submission_date' => now()->toDateString(),
            'committee_name' => 'Nairobi Hospital Ethics Committee',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('ethics_approvals', [
            'research_project_id' => $project->id,
            'committee_name' => 'Nairobi Hospital Ethics Committee',
            'status' => 'pending',
        ]);

        $this->assertDatabaseHas('research_projects', [
            'id' => $project->id,
            'status' => 'ethics_review',
        ]);

        $approval = EthicsApproval::latest()->first();

        $response = $this->actingAs($this->user)->put(route('hms.research.ethics.update', $approval), [
            'status' => 'approved',
            'approval_number' => 'NEC-2026-001',
            'approval_date' => now()->toDateString(),
            'expiry_date' => now()->addYear()->toDateString(),
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('ethics_approvals', [
            'id' => $approval->id,
            'status' => 'approved',
            'approval_number' => 'NEC-2026-001',
        ]);

        $this->assertDatabaseHas('research_projects', [
            'id' => $project->id,
            'status' => 'approved',
        ]);
    }

    public function test_publication_recording(): void
    {
        $project = ResearchProject::create([
            'title' => 'Publication Test Project',
            'principal_investigator_id' => $this->user->id,
            'objectives' => 'Test',
            'status' => 'completed',
        ]);

        $response = $this->actingAs($this->user)->post(route('hms.research.publications.store'), [
            'research_project_id' => $project->id,
            'title' => 'Efficacy of Artemether-Lumefantrine in Western Kenya',
            'authors' => 'Doe J, Smith A, Ochieng B',
            'journal_name' => 'East African Medical Journal',
            'publication_date' => now()->toDateString(),
            'doi' => '10.1234/eamj.2026.001',
            'publication_type' => 'journal_article',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('publications', [
            'research_project_id' => $project->id,
            'title' => 'Efficacy of Artemether-Lumefantrine in Western Kenya',
            'journal_name' => 'East African Medical Journal',
            'publication_type' => 'journal_article',
            'status' => 'submitted',
        ]);
    }

    public function test_publication_index(): void
    {
        Publication::create([
            'title' => 'Test Publication',
            'authors' => 'Test Author',
            'journal_name' => 'Test Journal',
            'publication_type' => 'journal_article',
            'status' => 'published',
        ]);

        $response = $this->actingAs($this->user)->get(route('hms.research.publications.index'));

        $response->assertOk();
    }
}

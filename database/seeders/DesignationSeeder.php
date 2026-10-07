<?php

namespace Database\Seeders;

use App\Models\Designation;
use Illuminate\Database\Seeder;

class DesignationSeeder extends Seeder
{
    public function run(): void
    {
        $designations = [
            // Medical & Surgical Designations
            ['name' => 'Chief Medical Officer (CMO)', 'category' => 'Medical & Surgical', 'is_clinical' => true, 'salary_grade' => 'Grade 1'],
            ['name' => 'Medical Superintendent', 'category' => 'Medical & Surgical', 'is_clinical' => true, 'salary_grade' => 'Grade 1'],
            ['name' => 'District Medical Officer (DMO)', 'category' => 'Medical & Surgical', 'is_clinical' => true, 'salary_grade' => 'Grade 2'],
            ['name' => 'Consultant Specialist', 'category' => 'Medical & Surgical', 'is_clinical' => true, 'salary_grade' => 'Grade 2'],
            ['name' => 'Senior Registrar', 'category' => 'Medical & Surgical', 'is_clinical' => true, 'salary_grade' => 'Grade 3'],
            ['name' => 'Registrar', 'category' => 'Medical & Surgical', 'is_clinical' => true, 'salary_grade' => 'Grade 4'],
            ['name' => 'Senior Hospital Medical Officer (SHMO)', 'category' => 'Medical & Surgical', 'is_clinical' => true, 'salary_grade' => 'Grade 4'],
            ['name' => 'Government Medical Officer (GMO)', 'category' => 'Medical & Surgical', 'is_clinical' => true, 'salary_grade' => 'Grade 5'],
            ['name' => 'Junior Resident Medical Officer (JRMO) / Intern', 'category' => 'Medical & Surgical', 'is_clinical' => true, 'salary_grade' => 'Grade 6'],

            // Nursing & Midwifery Designations
            ['name' => 'Director of Nursing Services (DNS)', 'category' => 'Nursing & Midwifery', 'is_clinical' => true, 'salary_grade' => 'Grade 2'],
            ['name' => 'Principal Nursing Officer (PNO)', 'category' => 'Nursing & Midwifery', 'is_clinical' => true, 'salary_grade' => 'Grade 3'],
            ['name' => 'Matron', 'category' => 'Nursing & Midwifery', 'is_clinical' => true, 'salary_grade' => 'Grade 4'],
            ['name' => 'Senior Nursing Officer', 'category' => 'Nursing & Midwifery', 'is_clinical' => true, 'salary_grade' => 'Grade 4'],
            ['name' => 'Sister-in-Charge', 'category' => 'Nursing & Midwifery', 'is_clinical' => true, 'salary_grade' => 'Grade 5'],
            ['name' => 'Male Nurse-in-Charge', 'category' => 'Nursing & Midwifery', 'is_clinical' => true, 'salary_grade' => 'Grade 5'],
            ['name' => 'Senior Sister', 'category' => 'Nursing & Midwifery', 'is_clinical' => true, 'salary_grade' => 'Grade 5'],
            ['name' => 'Senior Staff Nurse', 'category' => 'Nursing & Midwifery', 'is_clinical' => true, 'salary_grade' => 'Grade 6'],
            ['name' => 'Registered General Nurse (RGN)', 'category' => 'Nursing & Midwifery', 'is_clinical' => true, 'salary_grade' => 'Grade 6'],
            ['name' => 'State Certified Nurse (SCN)', 'category' => 'Nursing & Midwifery', 'is_clinical' => true, 'salary_grade' => 'Grade 7'],
            ['name' => 'Primary Care Nurse (PCN)', 'category' => 'Nursing & Midwifery', 'is_clinical' => true, 'salary_grade' => 'Grade 7'],
            ['name' => 'Operating Theatre Nurse', 'category' => 'Nursing & Midwifery', 'is_clinical' => true, 'salary_grade' => 'Grade 6'],
            ['name' => 'Intensive Care Nurse', 'category' => 'Nursing & Midwifery', 'is_clinical' => true, 'salary_grade' => 'Grade 6'],
            ['name' => 'Anaesthetic Nurse', 'category' => 'Nursing & Midwifery', 'is_clinical' => true, 'salary_grade' => 'Grade 6'],
            ['name' => 'Ophthalmic Nurse', 'category' => 'Nursing & Midwifery', 'is_clinical' => true, 'salary_grade' => 'Grade 6'],
            ['name' => 'Psychiatric Nurse', 'category' => 'Nursing & Midwifery', 'is_clinical' => true, 'salary_grade' => 'Grade 6'],
            ['name' => 'State Certified Midwife (SCM)', 'category' => 'Nursing & Midwifery', 'is_clinical' => true, 'salary_grade' => 'Grade 7'],
            ['name' => 'Nurse Aide', 'category' => 'Nursing & Midwifery', 'is_clinical' => true, 'salary_grade' => 'Grade 8'],

            // Diagnostic, Laboratory & Allied Health Designations
            ['name' => 'Chief Medical Laboratory Scientist', 'category' => 'Diagnostic, Laboratory & Allied Health', 'is_clinical' => true, 'salary_grade' => 'Grade 3'],
            ['name' => 'Medical Laboratory Scientist (MLS)', 'category' => 'Diagnostic, Laboratory & Allied Health', 'is_clinical' => true, 'salary_grade' => 'Grade 5'],
            ['name' => 'Medical Laboratory Technician', 'category' => 'Diagnostic, Laboratory & Allied Health', 'is_clinical' => true, 'salary_grade' => 'Grade 6'],
            ['name' => 'Microscopist', 'category' => 'Diagnostic, Laboratory & Allied Health', 'is_clinical' => true, 'salary_grade' => 'Grade 7'],
            ['name' => 'Chief Pharmacist', 'category' => 'Diagnostic, Laboratory & Allied Health', 'is_clinical' => true, 'salary_grade' => 'Grade 3'],
            ['name' => 'Pharmacy Manager', 'category' => 'Diagnostic, Laboratory & Allied Health', 'is_clinical' => true, 'salary_grade' => 'Grade 4'],
            ['name' => 'Pharmacist', 'category' => 'Diagnostic, Laboratory & Allied Health', 'is_clinical' => true, 'salary_grade' => 'Grade 5'],
            ['name' => 'Pharmacy Technician', 'category' => 'Diagnostic, Laboratory & Allied Health', 'is_clinical' => true, 'salary_grade' => 'Grade 6'],
            ['name' => 'Dispensary Assistant', 'category' => 'Diagnostic, Laboratory & Allied Health', 'is_clinical' => true, 'salary_grade' => 'Grade 7'],
            ['name' => 'Chief Radiographer', 'category' => 'Diagnostic, Laboratory & Allied Health', 'is_clinical' => true, 'salary_grade' => 'Grade 3'],
            ['name' => 'Radiographer', 'category' => 'Diagnostic, Laboratory & Allied Health', 'is_clinical' => true, 'salary_grade' => 'Grade 5'],
            ['name' => 'Sonographer', 'category' => 'Diagnostic, Laboratory & Allied Health', 'is_clinical' => true, 'salary_grade' => 'Grade 5'],
            ['name' => 'X-Ray Assistant', 'category' => 'Diagnostic, Laboratory & Allied Health', 'is_clinical' => true, 'salary_grade' => 'Grade 6'],
            ['name' => 'Physiotherapist', 'category' => 'Diagnostic, Laboratory & Allied Health', 'is_clinical' => true, 'salary_grade' => 'Grade 5'],
            ['name' => 'Occupational Therapist', 'category' => 'Diagnostic, Laboratory & Allied Health', 'is_clinical' => true, 'salary_grade' => 'Grade 5'],
            ['name' => 'Speech and Language Therapist', 'category' => 'Diagnostic, Laboratory & Allied Health', 'is_clinical' => true, 'salary_grade' => 'Grade 5'],
            ['name' => 'Audiologist', 'category' => 'Diagnostic, Laboratory & Allied Health', 'is_clinical' => true, 'salary_grade' => 'Grade 5'],
            ['name' => 'Podiatry Specialist', 'category' => 'Diagnostic, Laboratory & Allied Health', 'is_clinical' => true, 'salary_grade' => 'Grade 5'],
            ['name' => 'Rehabilitation Technician (RT)', 'category' => 'Diagnostic, Laboratory & Allied Health', 'is_clinical' => true, 'salary_grade' => 'Grade 6'],
            ['name' => 'Medical Social Worker', 'category' => 'Diagnostic, Laboratory & Allied Health', 'is_clinical' => true, 'salary_grade' => 'Grade 5'],
            ['name' => 'Mortuary Curator', 'category' => 'Diagnostic, Laboratory & Allied Health', 'is_clinical' => false, 'salary_grade' => 'Grade 6'],
            ['name' => 'Pathologist Assistant', 'category' => 'Diagnostic, Laboratory & Allied Health', 'is_clinical' => true, 'salary_grade' => 'Grade 6'],

            // Public Health & Preventative Designations
            ['name' => 'Provincial Medical Director (PMD)', 'category' => 'Public Health & Preventative', 'is_clinical' => true, 'salary_grade' => 'Grade 1'],
            ['name' => 'District Environmental Health Officer (DEHO)', 'category' => 'Public Health & Preventative', 'is_clinical' => true, 'salary_grade' => 'Grade 4'],
            ['name' => 'Environmental Health Officer (EHO)', 'category' => 'Public Health & Preventative', 'is_clinical' => false, 'salary_grade' => 'Grade 5'],
            ['name' => 'Environmental Health Technician (EHT)', 'category' => 'Public Health & Preventative', 'is_clinical' => false, 'salary_grade' => 'Grade 6'],
            ['name' => 'Epidemiologist', 'category' => 'Public Health & Preventative', 'is_clinical' => true, 'salary_grade' => 'Grade 4'],
            ['name' => 'Health Promotion Officer', 'category' => 'Public Health & Preventative', 'is_clinical' => false, 'salary_grade' => 'Grade 5'],
            ['name' => 'Nutritionist', 'category' => 'Public Health & Preventative', 'is_clinical' => true, 'salary_grade' => 'Grade 5'],
            ['name' => 'Dietitian', 'category' => 'Public Health & Preventative', 'is_clinical' => true, 'salary_grade' => 'Grade 5'],
            ['name' => 'Village Health Worker (VHW)', 'category' => 'Public Health & Preventative', 'is_clinical' => false, 'salary_grade' => 'Grade 8'],
            ['name' => 'Community Health Worker (CHW)', 'category' => 'Public Health & Preventative', 'is_clinical' => false, 'salary_grade' => 'Grade 8'],

            // Administrative, Operations & Support Designations
            ['name' => 'Hospital Chief Executive Officer (CEO)', 'category' => 'Administrative, Operations & Support', 'is_clinical' => false, 'salary_grade' => 'Grade 1'],
            ['name' => 'Hospital Administrator', 'category' => 'Administrative, Operations & Support', 'is_clinical' => false, 'salary_grade' => 'Grade 2'],
            ['name' => 'Human Resources Manager', 'category' => 'Administrative, Operations & Support', 'is_clinical' => false, 'salary_grade' => 'Grade 3'],
            ['name' => 'Human Resources Officer', 'category' => 'Administrative, Operations & Support', 'is_clinical' => false, 'salary_grade' => 'Grade 5'],
            ['name' => 'Human Resources (Province)', 'category' => 'Administrative, Operations & Support', 'is_clinical' => false, 'salary_grade' => 'Grade 3'],
            ['name' => 'Human Resources (District)', 'category' => 'Administrative, Operations & Support', 'is_clinical' => false, 'salary_grade' => 'Grade 4'],
            ['name' => 'Human Resources (Hospital)', 'category' => 'Administrative, Operations & Support', 'is_clinical' => false, 'salary_grade' => 'Grade 4'],
            ['name' => 'Finance Director', 'category' => 'Administrative, Operations & Support', 'is_clinical' => false, 'salary_grade' => 'Grade 2'],
            ['name' => 'Accountant', 'category' => 'Administrative, Operations & Support', 'is_clinical' => false, 'salary_grade' => 'Grade 4'],
            ['name' => 'Revenue Clerk', 'category' => 'Administrative, Operations & Support', 'is_clinical' => false, 'salary_grade' => 'Grade 6'],
            ['name' => 'Cashier', 'category' => 'Administrative, Operations & Support', 'is_clinical' => false, 'salary_grade' => 'Grade 6'],
            ['name' => 'Health Information Officer (HIO)', 'category' => 'Administrative, Operations & Support', 'is_clinical' => false, 'salary_grade' => 'Grade 5'],
            ['name' => 'Medical Records Clerk', 'category' => 'Administrative, Operations & Support', 'is_clinical' => false, 'salary_grade' => 'Grade 6'],
            ['name' => 'Biomedical Engineer', 'category' => 'Administrative, Operations & Support', 'is_clinical' => false, 'salary_grade' => 'Grade 4'],
            ['name' => 'Biomedical Technician', 'category' => 'Administrative, Operations & Support', 'is_clinical' => false, 'salary_grade' => 'Grade 6'],
            ['name' => 'Procurement Officer', 'category' => 'Administrative, Operations & Support', 'is_clinical' => false, 'salary_grade' => 'Grade 4'],
            ['name' => 'Stores Clerk', 'category' => 'Administrative, Operations & Support', 'is_clinical' => false, 'salary_grade' => 'Grade 6'],
            ['name' => 'Public Relations Officer', 'category' => 'Administrative, Operations & Support', 'is_clinical' => false, 'salary_grade' => 'Grade 4'],
            ['name' => 'IT Support Specialist', 'category' => 'Administrative, Operations & Support', 'is_clinical' => false, 'salary_grade' => 'Grade 5'],
            ['name' => 'Maintenance Artisan (Plumber, Electrician, Carpenter)', 'category' => 'Administrative, Operations & Support', 'is_clinical' => false, 'salary_grade' => 'Grade 7'],
            ['name' => 'Catering Supervisor', 'category' => 'Administrative, Operations & Support', 'is_clinical' => false, 'salary_grade' => 'Grade 5'],
            ['name' => 'Hospital Cook', 'category' => 'Administrative, Operations & Support', 'is_clinical' => false, 'salary_grade' => 'Grade 6'],
            ['name' => 'Laundry Supervisor', 'category' => 'Administrative, Operations & Support', 'is_clinical' => false, 'salary_grade' => 'Grade 5'],
            ['name' => 'Laundry Operator', 'category' => 'Administrative, Operations & Support', 'is_clinical' => false, 'salary_grade' => 'Grade 7'],
            ['name' => 'General Hand', 'category' => 'Administrative, Operations & Support', 'is_clinical' => false, 'salary_grade' => 'Grade 8'],
            ['name' => 'Ward Cleaner', 'category' => 'Administrative, Operations & Support', 'is_clinical' => false, 'salary_grade' => 'Grade 8'],
            ['name' => 'Security Officer', 'category' => 'Administrative, Operations & Support', 'is_clinical' => false, 'salary_grade' => 'Grade 6'],
            ['name' => 'Security Guard', 'category' => 'Administrative, Operations & Support', 'is_clinical' => false, 'salary_grade' => 'Grade 7'],
            ['name' => 'Ambulance Driver', 'category' => 'Administrative, Operations & Support', 'is_clinical' => false, 'salary_grade' => 'Grade 7'],
            ['name' => 'Institutional Driver', 'category' => 'Administrative, Operations & Support', 'is_clinical' => false, 'salary_grade' => 'Grade 7'],
        ];

        foreach ($designations as $designation) {
            Designation::firstOrCreate(['name' => $designation['name']], $designation);
        }
    }
}

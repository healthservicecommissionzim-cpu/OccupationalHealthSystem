<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $departments = [
            'Accident & Emergency (A&E) / Casualty',
            'Administration & Public Relations',
            'Anaesthetics',
            'Antenatal Care (ANC) / Prenatal Unit',
            'Audiology',
            'Biochemistry Laboratory',
            'Biomedical Engineering (Equipment Maintenance)',
            'Blood Transfusion Services / Blood Bank',
            'Cardiology',
            'Cardiothoracic Surgery',
            'Catering & Food Services',
            'Central Sterile Services Department (CSSD)',
            'Dermatology',
            'Dentistry & Oral Health',
            'Dialysis / Renal Unit',
            'Environmental Health (Sanitation & Malaria Control)',
            'Epidemiology & Disease Control',
            'Family Health & Reproductive Health (Immunisations & Family Planning)',
            'Finance & Patient Accounts',
            'Forensic Pathology',
            'General Surgery',
            'Growth Monitoring & Well-Baby Unit',
            'Health Information & Medical Records',
            'Health Promotion & Education',
            'Hematology Laboratory',
            'High Dependency Unit (HDU)',
            'HIV/AIDS & TB Directorate (OI / ART Clinic)',
            'Human Resources',
            'Information Technology (IT) Support',
            'Institutional Cleaning & Laundry',
            'Intensive Care Unit (ICU)',
            'Internal Medicine',
            'Labour & Delivery Ward',
            'Maintenance & Infrastructure (Plumbing, Electrical, Carpentry)',
            'Maternity & Midwifery Unit',
            'Maxillofacial Surgery',
            'Microbiology Laboratory',
            'Mortuary & Pathology Services',
            'Neonatal Intensive Care Unit (NICU)',
            'Neuromedicine & Neurosurgery',
            'Nutrition & Dietetics',
            'Obstetrics & Gynaecology',
            'Oncology & Radiotherapy',
            'Ophthalmology (Eye Unit)',
            'Occupational Therapy',
            'Orthopaedics & Fracture Clinic',
            'Otolaryngology (Ear, Nose, and Throat / ENT)',
            'Outpatient Department (OPD)',
            'Paediatrics & Child Health',
            'Pharmacy & Dispensing',
            'Physiotherapy',
            'Plastic & Reconstructive Surgery',
            'Podiatry',
            'Postnatal Ward',
            'Procurement & Stores',
            'Psychiatry & Mental Health',
            'Radiology & Medical Imaging (X-ray, Ultrasound, CT, MRI)',
            'Security & Transport Services',
            'Social Work & Counselling',
            'Speech & Language Therapy',
            'Urology',
        ];

        foreach ($departments as $name) {
            Department::firstOrCreate(['name' => $name]);
        }
    }
}

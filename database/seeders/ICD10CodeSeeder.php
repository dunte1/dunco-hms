<?php

namespace Database\Seeders;

use App\Models\ICD10Code;
use Illuminate\Database\Seeder;

class ICD10CodeSeeder extends Seeder
{
    public function run(): void
    {
        $codes = [
            ['A00', 'Cholera', 'Certain infectious and parasitic diseases'],
            ['A09', 'Infectious gastroenteritis and colitis, unspecified', 'Certain infectious and parasitic diseases'],
            ['B34', 'Viral infection of unspecified site', 'Certain infectious and parasitic diseases'],
            ['C50', 'Malignant neoplasm of breast', 'Neoplasms'],
            ['C61', 'Malignant neoplasm of prostate', 'Neoplasms'],
            ['C80', 'Malignant neoplasm without specification of site', 'Neoplasms'],
            ['D50', 'Iron deficiency anaemia', 'Diseases of the blood and blood-forming organs'],
            ['E10', 'Type 1 diabetes mellitus', 'Endocrine, nutritional and metabolic diseases'],
            ['E11', 'Type 2 diabetes mellitus', 'Endocrine, nutritional and metabolic diseases'],
            ['E11.9', 'Type 2 diabetes mellitus without complications', 'Endocrine, nutritional and metabolic diseases'],
            ['E66', 'Obesity', 'Endocrine, nutritional and metabolic diseases'],
            ['E78', 'Disorders of lipoprotein metabolism and other lipidaemias', 'Endocrine, nutritional and metabolic diseases'],
            ['F32', 'Depressive episode', 'Mental and behavioural disorders'],
            ['F41', 'Other anxiety disorders', 'Mental and behavioural disorders'],
            ['G40', 'Epilepsy', 'Diseases of the nervous system'],
            ['G43', 'Migraine', 'Diseases of the nervous system'],
            ['H10', 'Conjunctivitis', 'Diseases of the eye and adnexa'],
            ['H52', 'Disorders of refraction and accommodation', 'Diseases of the eye and adnexa'],
            ['I10', 'Essential (primary) hypertension', 'Diseases of the circulatory system'],
            ['I20', 'Angina pectoris', 'Diseases of the circulatory system'],
            ['I21', 'Acute myocardial infarction', 'Diseases of the circulatory system'],
            ['I25', 'Chronic ischaemic heart disease', 'Diseases of the circulatory system'],
            ['I48', 'Atrial fibrillation and flutter', 'Diseases of the circulatory system'],
            ['I50', 'Heart failure', 'Diseases of the circulatory system'],
            ['I63', 'Cerebral infarction', 'Diseases of the circulatory system'],
            ['J00', 'Acute nasopharyngitis [common cold]', 'Diseases of the respiratory system'],
            ['J02', 'Acute pharyngitis', 'Diseases of the respiratory system'],
            ['J03', 'Acute tonsillitis', 'Diseases of the respiratory system'],
            ['J06', 'Acute upper respiratory infections of multiple and unspecified sites', 'Diseases of the respiratory system'],
            ['J10', 'Influenza due to other identified influenza virus', 'Diseases of the respiratory system'],
            ['J15', 'Bacterial pneumonia, not elsewhere classified', 'Diseases of the respiratory system'],
            ['J18', 'Pneumonia, organism unspecified', 'Diseases of the respiratory system'],
            ['J20', 'Acute bronchitis', 'Diseases of the respiratory system'],
            ['J30', 'Vasomotor and allergic rhinitis', 'Diseases of the respiratory system'],
            ['J40', 'Bronchitis, not specified as acute or chronic', 'Diseases of the respiratory system'],
            ['J44', 'Other chronic obstructive pulmonary disease', 'Diseases of the respiratory system'],
            ['J45', 'Asthma', 'Diseases of the respiratory system'],
            ['J98', 'Other disorders of respiratory system', 'Diseases of the respiratory system'],
            ['K21', 'Gastro-oesophageal reflux disease', 'Diseases of the digestive system'],
            ['K29', 'Gastritis and duodenitis', 'Diseases of the digestive system'],
            ['K30', 'Functional dyspepsia', 'Diseases of the digestive system'],
            ['K52', 'Other and unspecified noninfective gastroenteritis and colitis', 'Diseases of the digestive system'],
            ['K57', 'Diverticular disease of intestine', 'Diseases of the digestive system'],
            ['K80', 'Cholelithiasis', 'Diseases of the digestive system'],
            ['L01', 'Impetigo', 'Diseases of the skin and subcutaneous tissue'],
            ['L03', 'Cellulitis and acute lymphangitis', 'Diseases of the skin and subcutaneous tissue'],
            ['L40', 'Psoriasis', 'Diseases of the skin and subcutaneous tissue'],
            ['L50', 'Urticaria', 'Diseases of the skin and subcutaneous tissue'],
            ['M05', 'Rheumatoid arthritis with rheumatoid factor', 'Diseases of the musculoskeletal system and connective tissue'],
            ['M10', 'Gout', 'Diseases of the musculoskeletal system and connective tissue'],
            ['M25', 'Other joint disorders, not elsewhere classified', 'Diseases of the musculoskeletal system and connective tissue'],
            ['M54', 'Dorsalgia', 'Diseases of the musculoskeletal system and connective tissue'],
            ['M79', 'Other soft tissue disorders, not elsewhere classified', 'Diseases of the musculoskeletal system and connective tissue'],
            ['N17', 'Acute kidney failure', 'Diseases of the genitourinary system'],
            ['N18', 'Chronic kidney disease', 'Diseases of the genitourinary system'],
            ['N39', 'Other disorders of urinary system', 'Diseases of the genitourinary system'],
            ['N48', 'Hypertrophic disease of penis', 'Diseases of the genitourinary system'],
            ['O20', 'Haemorrhage in early pregnancy', 'Pregnancy, childbirth and the puerperium'],
            ['O80', 'Single spontaneous delivery', 'Pregnancy, childbirth and the puerperium'],
            ['P59', 'Neonatal jaundice from other and unspecified causes', 'Certain conditions originating in the perinatal period'],
            ['R05', 'Cough', 'Symptoms, signs and abnormal clinical laboratory findings'],
            ['R06', 'Abnormalities of breathing', 'Symptoms, signs and abnormal clinical laboratory findings'],
            ['R10', 'Abdominal and pelvic pain', 'Symptoms, signs and abnormal clinical laboratory findings'],
            ['R11', 'Nausea and vomiting', 'Symptoms, signs and abnormal clinical laboratory findings'],
            ['R42', 'Dizziness and giddiness', 'Symptoms, signs and abnormal clinical laboratory findings'],
            ['R50', 'Fever of other and unknown origin', 'Symptoms, signs and abnormal clinical laboratory findings'],
            ['R51', 'Headache', 'Symptoms, signs and abnormal clinical laboratory findings'],
            ['S06', 'Intracranial injury', 'Injury, poisoning and certain other consequences of external causes'],
            ['S62', 'Fracture of wrist and hand', 'Injury, poisoning and certain other consequences of external causes'],
            ['T14', 'Injury of unspecified body region', 'Injury, poisoning and certain other consequences of external causes'],
            ['Z00', 'General examination and investigation of persons without complaint and reported diagnosis', 'Factors influencing health status'],
            ['Z34', 'Supervision of normal pregnancy', 'Factors influencing health status'],
            ['Z71', 'Persons encountering health services for other counselling and medical advice', 'Factors influencing health status'],
            ['Z76', 'Persons encountering health services in other circumstances', 'Factors influencing health status'],
        ];

        foreach ($codes as [$code, $description, $category]) {
            ICD10Code::updateOrCreate(
                ['code' => $code],
                ['description' => $description, 'category' => $category]
            );
        }

        $this->command->info('Seeded ' . count($codes) . ' ICD-10 codes.');
    }
}

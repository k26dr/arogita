<?php
/**
 * Created by PhpStorm.
 * User: kedar
 * Date: 8/25/14
 * Time: 5:27 PM
 */

$required_fields = array('patient_id');
$field_map = array(
    'patient_id' => 'patient_data.pid',
    'first_name' => 'patient_data.fname',
    'last_name' => 'patient_data.lname',
    'dob' => 'patient_data.DOB',
    'gender' => 'patient_data.sex',
    'height' => 'form_vitals.height',
    'weight' => 'form_vitals.weight',
    'contact_number' => 'patient_data.phone_cell',
    'address' => 'patient_data.street',
    'emergency_contact_person' => 'patient.contact_relationship',
    'emergency_contact_number' => 'patient_data.phone_contact',
    'hypertension' => 'form_reviewofs.high_blood_pressure',
    'heart_attack' => 'form_reviewofs.heart_attack',
    'cardiac_arrythmias' => 'form_reviewofs.irregular_heart_beat',
    'valvular_disease',
    'kidney_stones' => 'form_reviewofs.kidney_stones',
    'kidney_failure' => 'form_reviewofs.kidney_failure',
    'hepatic_disease',
    'anemia',
    'asthma_copd',
    'thyroid_disease' => 'hyperthyroidism', // there's both hyper and hypo thyroidism
    'diabetes_mellitus' => 'insulin_dependent_diabetes', // there's insulin and noninsulin _dependent_diabetes
    'chronic_bronchitis' => 'form_reviewofs.chronic_bronchitis',
    'pulmonary_tuberculosis',
    'osteoporosis',
    'osteo_arthritis' => 'form_reviewofs.osetoarthritis',
    'others' => 'history_data.additional_history',
    'others_text' => 'history_data.usertext11',
    'father' => 'history_data.history_father',
    'mother' => 'history_data.history_mother',
    'siblings' => 'history_data.history_siblings',
    'spouse' => 'history_data.history_spouse',
    'children' => 'history_data.history_offspring',
    'tobacco' => 'history_data.tobacco',
    'coffee' => 'history_data.coffee',
    'alcohol' => 'history_data.alcohol',
    'recreational_drugs' => 'history_data.recreational_drugs',
    'exercise_patterns' => 'history_data.exercise_patterns',
    'hazardous_activities' => 'history_data.hazardous_activities',
    'sleep_pattern' => 'history_data.sleep_patterns',
    'allergies' => 'lists',
    'past_surgeries_illness' => 'lists',
    'date_inserted',
    'updated_notes',
    'medications' => 'lists',
    'profile_image_url',
    'email_address',
    'inches',
    'height_unit'
);
$accepted_fields= array_keys($field_map);
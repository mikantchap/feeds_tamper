<?php

namespace Drupal\feeds_tamper_circle\Plugin\Tamper;

use Drupal\Core\Form\FormStateInterface;
use Drupal\tamper\Exception\TamperException;
use Drupal\tamper\TamperableItemInterface;
use Drupal\tamper\TamperBase;

/**
 * Plugin implementation for D7 field_key_themes.
 *
 * @Tamper(
 *   id = "circle_simple_mapping",
 *   label = @Translation("Circle simple mapping"),
 *   description = @Translation("Maps the D7 field option keys to D9 equivalents."),
 *   category = "Custom Circle"
 * )
 */
class CircleSimpleMapping extends TamperBase {

  const MAPPING = 'mapping';

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    $config = parent::defaultConfiguration();
    $config[self::MAPPING] = 'key_themes';
    return $config;
  }
  
  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $form[self::MAPPING] = [
      '#type' => 'select',
      '#title' => $this->t('Mapping for field'),
      '#options' => $this->getOptions(),
      '#default_value' => $this->getSetting(self::MAPPING),
      '#description' => $this->t('The mapping required for the field'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitConfigurationForm(array &$form, FormStateInterface $form_state) {
    parent::submitConfigurationForm($form, $form_state);
    $this->setConfiguration([self::MAPPING => $form_state->getValue(self::MAPPING)]);
  } 

  /**
   * Get the mapping options.
   *
   * @return array
   *   List of options, might be dynamic in the future based on what .inc files exist for mappings.
   */
  protected function getOptions() {
    return [
      'age' => $this->t('Age group'),
      'carer' => $this->t('Does this report feature carers?'),
      'caller_details' => $this->t('Caller details'),     
      'ethnicity' => $this->t('Ethnicity'),
      'gender' => $this->t('Gender'), 
      'gender_2' => $this->t('Trans issues'), 
      'local_healthwatch' => $this->t('Local Healthwatch (node reference)'), 
      'indicate_below_key_staff_c' => $this->t('Indicate key staff'),
      'indicate_consumer_principles' => $this->t('Indicate Consumer Principles'),      
      'indicate_processes_include' => $this->t('Indicate processes included'),
      'indicate_system_wide_aspec' => $this->t('Indicate system wide aspects'),  
      'key_themes' => $this->t('Key themes'),
      'partner_type_research_' => $this->t('Partner Type (research)'),
      'people_with_disability' => $this->t('Types of disabilities'),
      'people_with_long_term_cond' => $this->t('Types of long term conditions'),
      'pregnancy_and_maternity' => $this->t('Pregnancy/maternity'),
      'which_was_the_primary_rese' => $this->t('Primary research method used'),
      'roles' => $this->t('Roles'),
      'seldom_heard_groups' => $this->t('Seldom heard groups'),
      'seldom_heard_yes_no' => $this->t('Seldom heard yes/no'),
      'sexual_orientation' => $this->t('Sexual orientation'),
      'services' => $this->t('Details of health and care services included in the report'),
      'select_type_of_feedback_in' => $this->t('Select feedback or information type'),
      'boolean' => $this->t('Yes/No/On/Off etc to Boolean'),
      'yes_no' => $this->t('Yes/No/Not Known etc to Yes/No'),
      'report' => $this->t('Report attachment 404 issue')
    ];
  }
 

  /**
   * {@inheritdoc}
   * Expecting a string of comma separated text values
   * No need to use explode/implode plugins
   */
  public function tamper($data, TamperableItemInterface $item = NULL) {
    //dpm($data);
    $map = $this->get_mapping();
    $dataArray = explode(",", $data);
    $d9_field_options = array();

    foreach ($dataArray as $key => $value):
     $value = trim($value);
     if (array_key_exists($value, $map)):
       
       //if the value is actually a sub array
       // used to set multiple values for a single input
       if (is_array($map[$value])):
         $sub_array = $map[$value];
         foreach ($sub_array as $subkey => $subvalue):
           $d9_field_options[] = $subvalue;
         endforeach;
       else:
         $d9_field_options[] = $map[$value];
       endif;

     endif;
    endforeach;
    
    
    $d9_field_options = array_unique($d9_field_options);

    //expects eg array(1,2,3) not string 
    //dpm($d9_field_options);
    return $d9_field_options;
  }

  public function get_mapping(){

    require "mappings/" . $this->getSetting(self::MAPPING) . ".inc";
    return $map;
  }

  /**
   * {@inheritdoc}
   */
  /*public function multiple() {
    return $this->multiple;
  }*/

}

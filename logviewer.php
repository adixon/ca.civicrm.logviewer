<?php

require_once 'logviewer.civix.php';
use CRM_Logviewer_ExtensionUtil as E;

/**
 * Implements hook_civicrm_config().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_config
 */
function logviewer_civicrm_config(&$config) {
  _logviewer_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_install
 */
function logviewer_civicrm_install() {
  _logviewer_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function logviewer_civicrm_enable() {
  _logviewer_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_navigationMenu().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_navigationMenu
 *
 */
function logviewer_civicrm_navigationMenu(&$navMenu) {
  _logviewer_civix_insert_navigation_menu($navMenu, 'Administer/Administration Console', [
    'label' => E::ts('View Log'),
    'name' => 'Log Viewer',
    'url' => 'civicrm/admin/logviewer',
    'permission' => 'administer CiviCRM',
    'operator' => 'AND',
    'separator' => NULL,
    'active' => 1,
  ]);
  _logviewer_civix_navigationMenu($menu);
}

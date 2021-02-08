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
 * Implements hook_civicrm_xmlMenu().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_xmlMenu
 */
function logviewer_civicrm_xmlMenu(&$files) {
  _logviewer_civix_civicrm_xmlMenu($files);
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
 * Implements hook_civicrm_postInstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_postInstall
 */
function logviewer_civicrm_postInstall() {
  _logviewer_civix_civicrm_postInstall();
}

/**
 * Implements hook_civicrm_uninstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_uninstall
 */
function logviewer_civicrm_uninstall() {
  _logviewer_civix_civicrm_uninstall();
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
 * Implements hook_civicrm_disable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_disable
 */
function logviewer_civicrm_disable() {
  _logviewer_civix_civicrm_disable();
}

/**
 * Implements hook_civicrm_upgrade().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_upgrade
 */
function logviewer_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _logviewer_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implements hook_civicrm_managed().
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_managed
 */
function logviewer_civicrm_managed(&$entities) {
  _logviewer_civix_civicrm_managed($entities);
}

/**
 * Implements hook_civicrm_angularModules().
 *
 * Generate a list of Angular modules.
 *
 * Note: This hook only runs in CiviCRM 4.5+. It may
 * use features only available in v4.6+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_angularModules
 */
function logviewer_civicrm_angularModules(&$angularModules) {
  _logviewer_civix_civicrm_angularModules($angularModules);
}

/**
 * Implements hook_civicrm_alterSettingsFolders().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_alterSettingsFolders
 */
function logviewer_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _logviewer_civix_civicrm_alterSettingsFolders($metaDataFolders);
}

/**
 * Implements hook_civicrm_navigationMenu().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_navigationMenu
 *
 */
function logviewer_civicrm_navigationMenu(&$navMenu) {
  $pages = [
    'admin_page' => [
      'label'      => E::ts('View Log'),
      'name'       => 'Log Viewer',
      'url' => 'civicrm/admin/logviewer',
      'parent' => ['Administer', 'Administration Console'],
      'permission' => 'administer CiviCRM',
      'operator' => 'AND',
      'separator'  => NULL,
      'active'     => 1,
    ],
  ];
  foreach ($pages as $item) {
    // Check that our item doesn't already exist.
    $menu_item_search = ['url' => $item['url']];
    $menu_items = [];
    CRM_Core_BAO_Navigation::retrieve($menu_item_search, $menu_items);
    if (empty($menu_items)) {
      $path = implode('/', $item['parent']);
      unset($item['parent']);
      _logviewer_civix_insert_navigation_menu($navMenu, $path, $item);
    }
  }
}

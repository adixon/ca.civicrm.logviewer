<?php

use CRM_Logviewer_ExtensionUtil as E;

/**
 * Form controller class
 *
 * @see https://docs.civicrm.org/dev/en/latest/framework/quickform/
 */
class CRM_Logviewer_Form_LogViewer extends CRM_Core_Form {

  /**
   * Log level file name.
   *
   * @var string
   */
  protected string $logFileName;

  /**
   * Log levels with Psr::Log::LogLevel as key and corresponding label as value,
   * starting with emergency.
   *
   * @var array
   */
  protected array $logLevels;

  /**
   * Class constructor
   *
   * @access public
   */
  public function __construct(
    $state = NULL,
    $action = CRM_Core_Action::NONE,
    $method = 'post',
    $name = NULL
  ) {
    $file_log = CRM_Core_Error::createDebugLogger();
    $this->logFileName = $file_log->_filename;
    $file_log->close();

    $this->logLevels = $this->getLogLevels();

    parent::__construct($state, $action, $method, $method, $name);
  }

  /**
   * @throws \CRM_Core_Exception
   */
  public function buildQuickForm(): void {
    $this->assign('currentTime', date('Y-m-d H:i:s'));
    $this->assign('fileName', $this->logFileName);

    // add form elements
    $this->add(
      'select', // field type
      'severity', // field name
      E::ts('Severity'), // field label
      #$this->getColorOptions(), // list of options
      $this->logLevels,
      FALSE,
      ['multiple' => TRUE],
    );
    $this->addButtons([
      [
        'type' => 'submit',
        'name' => E::ts('Filter'),
        'isDefault' => TRUE,
      ],
      // @todo this is not visible in the UI
      ['type' => 'reset', 'name' => E::ts('Reset')],
    ]);

    // export form elements
    $this->assign('elementNames', $this->getRenderableElementNames());

    // Defaults for result entries
    $this->assign('logFound', TRUE);
    $this->assign('logEntries', []);
    $this->assign('noSearch', TRUE);

    parent::buildQuickForm();

    if (empty($_POST)) {
      // Fill table for the first time.
      $this->postProcess();
    }
  }

  public function postProcess(): void {
    $values = $this->exportValues();
    $filter_severity = $values['severity'];

    $severities_found = [];
    foreach (array_keys($this->logLevels) as $log_level) {
      $severities_found[$log_level] = 0;
    }

    $entries = [];
    $handle = @fopen($this->logFileName,'r');
    if (!$handle) {
      $this->assign('logFound', FALSE);
    }
    else {
      $this->assign('noSearch', FALSE);
      $this->assign('logFound', TRUE);
      $line = 0;
      while (!feof($handle)) {
        $line++;
        $dd = fgets($handle);
        if (strlen($dd) >= 24 && (' ' != $dd[0])) {
          // Ex: 2023-12-25 09:00:00-0400
          if (preg_match('/^([0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2}.\d+)/', $dd, $matches)) {
            $date = $matches[1];
            $entry_url = CRM_Utils_System::url('civicrm/admin/logviewer/logentry', $query = 'lineNumber=' . $line);
            $msg = trim(substr($dd, strlen($date)));

            // There could be empty messages /i.e. just "[error]"
            if (preg_match('/^\[(\w+)\]\s*(.*)/', $msg, $matches)) {
              $severity = $matches[1];
              $msg = $matches[2];
            }
            else {
              // There should only be the konwn severities - but we provide a fallback nonetheless.
              $severity = 'UNKNOWN';
            }
            if (array_key_exists($severity, $severities_found)) {
              $severities_found[$severity]++;
            }
            else {
              $severities_found[$severity] = 1;
            }

            if (empty($filter_severity)
              || in_array($severity, $filter_severity)
            ) {
              $entries[$line] = [
                'lineNumber' => '<a href="' . $entry_url . '">' . $line . '</a>',
                'dateTime' => $date,
                'message' => $msg,
                'severity' => $severity,
              ];
            }
          }
        }
      }
      fclose($handle);
      krsort($entries);
    }
    $this->assign('logEntries', $entries);

    parent::postProcess();
  }

  /**
   * Get the fields/elements defined in this form.
   *
   * @return array (string)
   */
  public function getRenderableElementNames(): array {
    // The _elements list includes some items which should not be
    // auto-rendered in the loop -- such as "qfKey" and "buttons".  These
    // items don't have labels.  We'll identify renderable by filtering on
    // the 'label'.
    $elementNames = [];
    foreach ($this->_elements as $element) {
      /** @var HTML_QuickForm_Element $element */
      $label = $element->getLabel();
      if (!empty($label)) {
        $elementNames[] = $element->getName();
      }
    }
    return $elementNames;
  }

  /**
   * Get log level array.
   *
   * @return array
   *   Log levels with Psr::Log::LogLevel as key and corresponding label as value,
   *   starting with emergency.
   */
  protected function getLogLevels() {
    $severities = CRM_Utils_Check::getSeverityOptions();
    $log_levels = [];

    // Reverse order to start with emergency.
    foreach (array_reverse($severities) as $severity) {
      $log_levels[$severity['name']] = $severity['label'];
    }

    return $log_levels;
  }

}

<?php

use CRM_Logviewer_ExtensionUtil as E;

/**
 *
 */
class CRM_Logviewer_Page_LogViewer extends CRM_Core_Page {

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

  public function __construct($title = NULL, $mode = NULL) {
    $file_log = CRM_Core_Error::createDebugLogger();
    $this->logFileName = is_callable(['CRM_Core_Error', 'generateLogFileName']) ? \CRM_Core_Error::generateLogFileName('') : $file_log->_filename;
    $file_log->close();

    $this->logLevels = $this->getLogLevels();

    parent::__construct($title, $mode);
  }

  public function run() {
    $this->assign('currentTime', date('Y-m-d H:i:s'));
    $this->assign('fileName', $this->logFileName);

    if (array_key_exists('logviewer_reset', $_POST)) {
      // Simulate reset behaviour.
      $filter_severity = [];
    }
    else {
      $filter_severity = $_POST['severity'] ?? [];
    }

    // Build options.
    $options = [];
    foreach ($this->logLevels as $key => $label) {
      $options[] = [
        'key' => $key,
        'label' => $label,
        'selected' => in_array($key, $filter_severity),
      ];
    }
    $this->assign('options', $options);

    $severities_found = [];
    foreach (array_keys($this->logLevels) as $log_level) {
      $severities_found[$log_level] = 0;
    }

    // Get Log entries.
    $entries = [];
    $handle = @fopen($this->logFileName, 'r');
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

            // There could be empty messages, e.g. just "[error]".
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

    // Add JS to use Datatable.
    CRM_Core_Resources::singleton()->addScriptFile(E::LONG_NAME, 'js/logviewer.js'); #, 1, 'html-header');

    parent::run();
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

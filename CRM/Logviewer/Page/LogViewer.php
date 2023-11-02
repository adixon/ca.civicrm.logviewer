<?php

class CRM_Logviewer_Page_LogViewer extends CRM_Core_Page {

  public function run() {
    $this->assign('currentTime', date('Y-m-d H:i:s'));
    $file_log = CRM_Core_Error::createDebugLogger();
    $logFileName = $file_log->_filename;
    $file_log->close();
    $this->assign('fileName', $logFileName);
    $entries = [];

    if ($handle = @fopen($logFileName,'r')) {
      $line = 0;
      while (!feof($handle)) {
        $line++;
        $dd = fgets($handle);
        if (strlen($dd) >= 24 && (' ' != $dd[0])) {
          // Ex: 2023-12-25 09:00:00-0400
          if (preg_match('/^([0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2}.\d+)/', $dd, $matches)) {
            $date = $matches[1];
            $entry_url = CRM_Utils_System::url('civicrm/admin/logviewer/logentry', $query = 'lineNumber='.$line);
            $entries[$line] = [
              'lineNumber' => '<a href="'.$entry_url.'">'.$line.'</a>',
              'dateTime' => $date,
              'message' => substr($dd, strlen($date)),
            ];
          }
        }
      }
      fclose($handle);
      krsort($entries);
      $this->assign('logEntries', $entries);
    }
    parent::run();
  }

}

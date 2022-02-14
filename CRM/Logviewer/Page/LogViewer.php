<?php

class CRM_Logviewer_Page_LogViewer extends CRM_Core_Page {

  public function run() {
    $this->assign('currentTime', date('Y-m-d H:i:s'));
    $file_log = CRM_Core_Error::createDebugLogger();
    $logFileName = $file_log->_filename;
    $file_log->close();
    $this->assign('fileName', $logFileName);
    $entries = array();

    if ($handle = @fopen($logFileName,'r')) {
      $line = 0;
      while (!feof($handle)) {
        $line++;
        $dd = fgets($handle);
        if (strlen($dd) >= 15 && (' ' != $dd[0])) {
          // Also support localized dates such as: "fév 14 12:51:13"
          $date = mb_substr($dd,0,15);
          if (preg_match("/^\w{3} [0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2}$/u",$date)) {
            $entry_url = CRM_Utils_System::url('civicrm/admin/logviewer/logentry', $query = 'lineNumber='.$line);
            $entries[$line] = array('lineNumber' => '<a href="'.$entry_url.'">'.$line.'</a>', 'dateTime' => $date, 'message' => substr($dd,16));
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

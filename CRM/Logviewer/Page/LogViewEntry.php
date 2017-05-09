<?php

class CRM_Logviewer_Page_LogViewEntry extends CRM_Core_Page {

  public function run() {
    $config = CRM_Core_Config::singleton();
    $file_log = CRM_Core_Error::createDebugLogger();
    // print_r($file_log); die();
    $logFileName = $file_log->_filename;
    $logFileFormat = $file_log->_lineFormat;
    $file_log->close();
    $this->assign('fileName', $logFileName);
    $handle = fopen($logFileName,'r') or die ('File opening failed');
    $entry = '';
    $line = 0;
    $lineNumber = CRM_Utils_Array::value('lineNumber', $_GET);
    if (empty($lineNumber)) { die('Invalid lineNumber'); }
    while (!feof($handle)) {
      $line++;
      $dd = fgets($handle);
      if ($line < $lineNumber) {
        continue;
      }
      elseif ($line == $lineNumber) {
        $date = substr($dd,0,15);
        $this->assign('dateTime', $date);
        $entry = substr($dd, 16);
      } 
      else {
        if (strlen($dd) >= 15 && (' ' != $dd[0])) {
          $date = substr($dd,0,15);
          if (preg_match("/^[A-Z][a-z]{2} [0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2}$/",$date)) {
            break;
          }
        }
        $entry .= $dd;
      }
    }
    fclose($handle);
    $this->assign('logEntry', $entry);

    parent::run();
  }

}

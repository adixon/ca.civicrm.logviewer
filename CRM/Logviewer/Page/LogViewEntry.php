<?php

use CRM_Logviewer_ExtensionUtil as E;

class CRM_Logviewer_Page_LogViewEntry extends CRM_Core_Page {

  public function run() {
    // CRM_Core_Resources::singleton()->addScriptUrl('https://cdn.rawgit.com/google/code-prettify/master/loader/run_prettify.js');
    CRM_Core_Resources::singleton()->addScriptUrl('//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.11.0/highlight.min.js', 10, 'page-header');
    CRM_Core_Resources::singleton()->addStyleUrl('//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.11.0/styles/default.min.css');
    $file_log = CRM_Core_Error::createDebugLogger();
    $logFileName = $file_log->_filename;
    $logFileFormat = $file_log->_lineFormat;
    $file_log->close();
    $this->assign('fileName', $logFileName);
    $handle = fopen($logFileName,'r') or die ('File opening failed');
    $entry = '';
    $line = $prevLine = $nextLine = 0;
    $lineNumber = $_GET['lineNumber'] ?? NULL;
    if (empty($lineNumber)) { die('Invalid lineNumber'); }
    while (!feof($handle)) {
      $line++;
      $dd = fgets($handle);
      if ($line < $lineNumber) {
        if (preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2}.\d+/", $dd)) {
          $prevLine = $line;
        }
        continue;
      }
      elseif ($line == $lineNumber) {
        $date = substr($dd, 0, 24);
        $this->assign('dateTime', $date);
        $entry = substr($dd, 26);
      }
      else {
        if (strlen($dd) >= 25 && (' ' != $dd[0])) {
          $date = substr($dd, 0, 24);
          if (preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2}.\d+$/",$date)) {
            $nextLine = $line;
            break;
          }
        }
        $entry .= $dd;
      }
    }
    fclose($handle);

    $prev_a = $next_a = '';
    if($prevLine){
      $prev_url = CRM_Utils_System::url('civicrm/admin/logviewer/logentry', $query = 'lineNumber='.$prevLine);
      $prev_a = '<a class="button" href="'.$prev_url.'"><span><i class="crm-i fa-chevron-left" aria-hidden="true"></i> ' . E::ts('Prev') . '</span></a>';
    }
    if($nextLine){
      $next_url = CRM_Utils_System::url('civicrm/admin/logviewer/logentry', $query = 'lineNumber='.$nextLine);
      $next_a = '<a class="button" href="'.$next_url.'"><span><i class="crm-i fa-chevron-right" aria-hidden="true"></i> ' . E::ts('Next') . '</span></a>';
    }
    $entry = htmlentities($entry, ENT_QUOTES, 'UTF-8');
    
    $this->assign('prevURL', $prev_a);
    $this->assign('nextURL', $next_a);
    $this->assign('logEntry', $entry);

    parent::run();
  }

}

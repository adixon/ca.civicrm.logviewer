<?php

class CRM_Logviewer_Page_LogDownload extends CRM_Core_Page {

  public function run() {
    $file_log = CRM_Core_Error::createDebugLogger();
    $logFileName = is_callable(['CRM_Core_Error', 'generateLogFileName']) ? \CRM_Core_Error::generateLogFileName('') : $file_log->_filename;
    $file_log->close();

    //Mark as a plain-text file.
    CRM_Utils_System::setHttpHeader('Content-Type', 'text/plain');

    //Download the file with its name.
    CRM_Utils_System::setHttpHeader('Content-Disposition', 'attachment; filename=' . basename($logFileName));
    readfile($logFileName);
    CRM_Utils_System::civiExit();

  }

}

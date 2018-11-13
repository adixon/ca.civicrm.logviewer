# ca.civicrm.logviewer
CiviCRM Log Viewer

This is a naive, simple log viewer for the CiviCRM admin/debug log that normally goes in files/civicrm/ConfigAndLog/

It shows a summary of the entries in the current log file, in reverse chronological order, with links to a detailed view of each log entry.

It's useful for quick debugging during development and testing, and also useful on production sites to allow site maintainers to quickly check for errors that might not otherwise be evident, especially low-level errors that get caught and passed up.

It uses a javascript library (highlight.js) for minimally making the output look nicer. It might break in other languages and/or non-default time/date formats, since it relies on regular expressions for parsing the log output. 

To access the log viewer go to `Administer > Administration Console > View Log`.

Patches and bug reports welcomed.

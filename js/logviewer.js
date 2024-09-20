/**
 * Display as DataTable.
 */

CRM.$(document).ready(function() {
  let oTable = CRM.$('#logviewer').dataTable();

  // Sort by ID desc (fnSort() is deprecated).
  oTable.api().order([0, 'desc']).draw();
} );

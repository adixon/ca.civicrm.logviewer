{* HEADER *}

<p>{ts 1=$currentTime}The current time is %1.{/ts}</p>
<p>{ts 1=$fileName}Logfile: %1{/ts}</p>

{foreach from=$elementNames item=elementName}
  <div class="crm-section">
    <div class="label">{$form.$elementName.label}</div>
    <div class="content">{$form.$elementName.html}</div>
    <div class="clear"></div>
  </div>
{/foreach}

<div class="crm-submit-buttons">
  {include file="CRM/common/formButtons.tpl" location="top"}
  {* {crmButton p='civicrm/admin/logviewer' q='reset=1' icon='refresh'}{ts}Refresh{/ts}{/crmButton} *}
  {crmButton p='civicrm/admin/logviewer/download' q='reset=1' icon='download'}{ts}Download Log File{/ts}{/crmButton}
</div>

{if $logEntries}
  <p>{ts 1=$logEntries|count}Found %1 result(s).{/ts}</p>
  <table id="logviewer">
    <thead>
      <tr class="columnheader">
        <th>{ts}Row{/ts}</th>
        <th>{ts}Severity{/ts}</th>
        <th>{ts}Date{/ts}</th>
        <th>{ts}Message{/ts}</th>
        {* <th></th> *}
      </tr>
    </thead>
    <tbody>
      {foreach from=$logEntries item=row}
        <tr class="{cycle values="odd-row,even-row"}">
          <td>{$row.lineNumber}</td>
          <td>{$row.severity}</td>
          <td>{$row.dateTime}</td>
          <td>{$row.message}</td>
        </tr>
      {/foreach}
    </tbody>
  </table>
{elseif $noSearch}
  <p>{ts}<em>Filter</em> for results.{/ts}</p>
{elseif $logFound}
  <p>{ts}Found no entries.{/ts}</p>
{else}
  <p>{ts 1=$fileName}Unable to read entries from logfile at %1{/ts}</p>
{/if}

{* FOOTER *}
<div class="crm-submit-buttons">
{include file="CRM/common/formButtons.tpl" location="bottom"}
</div>

{* View as DataTable *}
<script>
  CRM.$(document).ready( function () {
    CRM.$('#logviewer').DataTable();
  } );
</script>


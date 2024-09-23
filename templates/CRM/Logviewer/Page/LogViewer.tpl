<p>{ts 1=$currentTime}The current time is %1.{/ts}</p>
<p>{ts 1=$fileName}Logfile: %1{/ts}</p>

<form action="{crmURL p='civicrm/admin/logviewer'}" method="post" name="post" id="post" class="CRM_Logviewer_Page_LogViewer">
  <div class="crm-section">
    <div class="label"><label for="severity">Severity</label></div>
    <div class="content">
      <select multiple="multiple" name="severity[]" id="severity" class="crm-form-multiselect" size="{$options|@count}">
        {foreach from=$options item=row}
          <option value="{$row.key}" {if $row.selected}selected="selected"{/if}>{$row.label}</option>
        {/foreach}
      </select>
    </div>
    <div class="clear"></div>
  </div>

  <div class="crm-submit-buttons">
    <button class="crm-form-submit default validate crm-button crm-button-type-submit btn-primary" value="1" type="submit" name="logviewer_submit" id="logviewer_submit">
      <i aria-hidden="true" class="crm-i fa-search"></i>{ts}Filter{/ts}</button>
    <button class="crm-form-submit validate crm-button crm-button-type-submit btn-secondary" value="1" type="submit" name="logviewer_reset" id="logviewer_reset">
      <i aria-hidden="true" class="crm-i fa-reset"></i>{ts}Reset{/ts}</button>
    {crmButton p='civicrm/admin/logviewer/download' q='reset=1' icon='download'}{ts}Download Log File{/ts}{/crmButton}
  </div>
</form>

{if $logEntries}
  <p>{ts 1=$logEntries|@count}Found %1 result(s).{/ts}</p>
  <table id="logviewer">
    <thead>
      <tr class="columnheader">
        <th>{ts}Row{/ts}</th>
        <th>{ts}Severity{/ts}</th>
        <th>{ts}Date{/ts}</th>
        <th>{ts}Message{/ts}</th>
        {* Add extra column, because DataTable right-aligns the last column *}
        <th></th>
      </tr>
    </thead>
    <tbody>
      {foreach from=$logEntries item=row}
        <tr class="{cycle values="odd-row,even-row"}">
          <td>{$row.lineNumber}</td>
          <td>{$row.severity}</td>
          <td>{$row.dateTime}</td>
          <td>{$row.message}</td>
          <td></td>
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

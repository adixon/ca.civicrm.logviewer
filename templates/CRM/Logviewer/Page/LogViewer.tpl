<h3>Log Viewer</h3>

<p>{ts 1=$currentTime}The current time is %1.{/ts}</p>

{if $logEntries}
    <table>
    <tr class="columnheader">
        <th>{ts}Row{/ts}</th>
        <th>{ts}Date{/ts}</th>
        <th>{ts}Message{/ts}</th>
        <th></th>
    </tr>

    {foreach from=$logEntries item=row}
    <tr class="{cycle values="odd-row,even-row"}">
        <td>{$row.lineNumber}</td>
        <td>{$row.dateTime}</td>
        <td>{$row.message}</td>
    </tr>
    {/foreach}
    </table>
{/if}

{crmButton p='civicrm/admin/logviewer' q='reset=1' icon='list'}{ts}List{/ts}{/crmButton} {$prevURL} {$nextURL}
<br />
<h3>Log Entry for {$dateTime}</h3>
{literal}<script>hljs.initHighlightingOnLoad();</script>{/literal}
<pre class="prettyprint"><code>{$logEntry}</code></pre>
{crmButton p='civicrm/admin/logviewer' q='reset=1' icon='list'}{ts}List{/ts}{/crmButton} {$prevURL} {$nextURL}

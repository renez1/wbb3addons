{* $Id$ *}
{include file='header'}
<script type="text/javascript">
//<![CDATA[
function runCron(cj) {
    if(cj == 'journal') {
        if(document.forms['atCronJobs'].elements['cronLogEnabled'].checked || document.forms['atCronJobs'].elements['cronStatEnabled'].checked) {
            var cLog = 0;
            var cStat = 0;
            var cAdminMail = 0;
            if(document.forms['atCronJobs'].elements['cronLogEnabled'].checked) cLog = 1;
            if(document.forms['atCronJobs'].elements['cronStatEnabled'].checked) cStat = 1;
            if(document.forms['atCronJobs'].elements['cronLogUseAdminEmail'].checked) cAdminMail = 1;

            if(confirm('{lang}wcf.acp.adminTools.confirm.cron{/lang}')) {
                if(document.getElementById('progressJournal')) document.getElementById('progressJournal').style.display = 'block';
                window.location.href = 'index.php?form=AdminToolsCron&cRun=journal&log='+cLog+'&stat='+cStat+'&adminMail='+cAdminMail+'&packageID={@PACKAGE_ID}{@SID_ARG_2ND_NOT_ENCODED}';
            }
        } else {
            alert('{lang}wcf.acp.adminTools.error.cron{/lang}');
        }
    }
    else if(cj == 'db') {
        if(document.forms['atCronJobs'].elements['cronDbAnalyze'].checked || document.forms['atCronJobs'].elements['cronDbOptimize'].checked
        || document.forms['atCronJobs'].elements['cronDbBackup'].checked) {
            var cAnalyze = 0;
            var cOptimize = 0;
            var cBackup = 0;
            if(document.forms['atCronJobs'].elements['cronDbAnalyze'].checked) cAnalyze = 1;
            if(document.forms['atCronJobs'].elements['cronDbOptimize'].checked) cOptimize = 1;
            if(document.forms['atCronJobs'].elements['cronDbBackup'].checked) cBackup = 1;
            if(confirm('{lang}wcf.acp.adminTools.confirm.cron{/lang}')) {
                if(document.getElementById('progressDB')) document.getElementById('progressDB').style.display = 'block';
                window.location.href = 'index.php?form=AdminToolsCron&cRun=db&analyze='+cAnalyze+'&optimize='+cOptimize+'&backup='+cBackup+'&packageID={@PACKAGE_ID}{@SID_ARG_2ND_NOT_ENCODED}';
            }
        } else {
            alert('{lang}wcf.acp.adminTools.error.cron{/lang}');
        }
    }
}
//]]>
</script>

<div class="mainHeadline">
	<img src="{@RELATIVE_WCF_DIR}icon/cronjobsL.png" alt="" />
	<div class="headlineContainer">
		<h2>{lang}wcf.acp.adminTools.cron{/lang}</h2>
	</div>
</div>

{if !$errorField|empty}
	<p class="error">{lang}wcf.global.form.error{/lang}</p>
{elseif !$success|empty}
    <p class="success">{lang}wcf.acp.adminTools.success.saved{/lang}</p>
{/if}
{if !$cronActive}
    <p class="info">{lang}wcf.acp.adminTools.cron.disabled{/lang}</p>
{/if}

<div class="contentHeader">
	<div class="largeButtons">
		<ul>
            <li><a href="index.php?page=CronjobsList&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}"><img src="{@RELATIVE_WCF_DIR}icon/cronjobsM.png" alt="" title="{lang}wcf.acp.menu.link.cronjobs.view{/lang}" /> <span>{lang}wcf.acp.menu.link.cronjobs.view{/lang}</span></a></li>
		    <li><a href="index.php?page=CronjobsShowLog&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}"><img src="{@RELATIVE_WCF_DIR}icon/cronjobsM.png" alt="" /> <span>{lang}wcf.acp.menu.link.cronjobs.showLog{/lang}</span></a></li>
		</ul>
	</div>
</div>

<form method="post" name="atCronJobs" action="index.php?form=AdminToolsCron">
	<div class="border content">
		<div class="container-1">
<!-- Protokoll ***************************************** -->
			<fieldset>
				<legend>{lang}wcf.acp.adminTools.cron.cronDelLogDays.legend{/lang}</legend>
				<div class="formElement" id="cronDelLogDays">
					<div class="formFieldLabel">
						<label for="cronDelLogDays">{lang}wcf.acp.adminTools.cron.cronDelLogDays{/lang}</label>
					</div>
					<div class="formField">
						<input type="text" class="inputText" name="cronDelLogDays" id="cronDelLogDays" value="{if $cronDelLogDays|isset}{@$cronDelLogDays}{else}0{/if}" maxlength="4" />
						{if $errorField == 'cronDelLogDays'}
							<div class="innerError">
							    {if $errorType == 'notNumeric'}{lang}wcf.acp.adminTools.error.notNumeric{/lang}{/if}
							</div>
						{/if}
					</div>
					<div class="formFieldDesc hidden" id="cronDelLogDaysHelpMessage">
						{lang}wcf.acp.adminTools.cron.cronDelLogDays.description{/lang}
					</div>
				</div>
				<script type="text/javascript">//<![CDATA[
					inlineHelp.register('cronDelLogDays');
				//]]></script>
    		</fieldset>

<!-- Verweise ****************************************** -->
{if $wbbExists}
			<fieldset>
				<legend>{lang}wcf.acp.adminTools.cron.cronDelMovedThreadDays.legend{/lang}</legend>
				<div class="formElement" id="cronDelMovedThreadDays">
					<div class="formFieldLabel">
						<label for="cronDelMovedThreadDays">{lang}wcf.acp.adminTools.cron.cronDelMovedThreadDays{/lang}</label>
					</div>
					<div class="formField">
						<input type="text" class="inputText" name="cronDelMovedThreadDays" id="cronDelMovedThreadDays" value="{if $cronDelMovedThreadDays|isset}{@$cronDelMovedThreadDays}{else}0{/if}" maxlength="4" />
						{if $errorField == 'cronDelMovedThreadDays'}
							<div class="innerError">
							    {if $errorType == 'notNumeric'}{lang}wcf.acp.adminTools.error.notNumeric{/lang}{/if}
							</div>
						{/if}
					</div>
					<div class="formFieldDesc hidden" id="cronDelMovedThreadDaysHelpMessage">
						{lang}wcf.acp.adminTools.cron.cronDelMovedThreadDays.description{/lang}
					</div>
				</div>
				<script type="text/javascript">//<![CDATA[
					inlineHelp.register('cronDelMovedThreadDays');
				//]]></script>
    		</fieldset>
{/if}
<!-- PMs *********************************************** -->
			<fieldset>
				<legend>{lang}wcf.acp.adminTools.cron.pm.legend{/lang}</legend>
				<div class="formElement" id="cronDelPmDaysDiv">
					<div class="formFieldLabel">
						<label for="cronDelPmDays">{lang}wcf.acp.adminTools.cron.pm.delDays{/lang}</label>
					</div>
					<div class="formField">
						<input type="text" class="inputText" name="cronDelPmDays" id="cronDelPmDays" value="{if $cronDelPmDays|isset}{@$cronDelPmDays}{else}0{/if}" maxlength="4" />
						{if $errorField == 'cronDelPmDays'}
							<div class="innerError">
							    {if $errorType == 'notNumeric'}{lang}wcf.acp.adminTools.error.notNumeric{/lang}{/if}
							</div>
						{/if}
					</div>
					<div class="formFieldDesc hidden" id="cronDelPmDaysHelpMessage">
						{lang}wcf.acp.adminTools.cron.pm.delDays.description{/lang}
					</div>
				</div>
				<script type="text/javascript">//<![CDATA[
					inlineHelp.register('cronDelPmDays');
				//]]></script>

				<div class="formElement" id="cronDelPmDaysShowInfo">
					<div class="formField">
						<label><input type="checkbox" id="cronDelPmDaysShowInfo" name="cronDelPmDaysShowInfo" value="1"{if $cronDelPmDaysShowInfo} checked="checked"{/if} /> {lang}wcf.acp.adminTools.cron.pm.showUserInfo{/lang}</label>
					</div>
					<div class="formFieldDesc hidden" id="cronDelPmDaysShowInfoHelpMessage">
						{lang}wcf.acp.adminTools.cron.pm.showUserInfo.description{/lang}
					</div>
				</div>
				<script type="text/javascript">//<![CDATA[
					inlineHelp.register('cronDelPmDaysShowInfo');
				//]]></script>

				<div class="formElement" id="cronDelPmDaysExclUgrpsDiv">
					<div class="formFieldLabel">
						<label for="cronDelPmDaysExclUgrps">{lang}wcf.acp.adminTools.cron.pm.delExclUgrps{/lang}</label>
					</div>
					<div class="formField">
						<input type="text" class="inputText" name="cronDelPmDaysExclUgrps" id="cronDelPmDaysExclUgrps" value="{if $cronDelPmDaysExclUgrps|isset}{@$cronDelPmDaysExclUgrps}{else}{/if}" maxlength="254" />
						{if $errorField == 'cronDelPmDaysExclUgrps'}
							<div class="innerError">
							    {if $errorType == 'commaSeparatedIntList'}{lang}wcf.acp.adminTools.error.commaSeparatedIntList{/lang}{/if}
							</div>
						{/if}
					</div>
					<div class="formFieldDesc hidden" id="cronDelPmDaysExclUgrpsHelpMessage">
						{lang}wcf.acp.adminTools.cron.pm.delExclUgrps.description{/lang}
					</div>
				</div>
				<script type="text/javascript">//<![CDATA[
					inlineHelp.register('cronDelPmDaysExclUgrps');
				//]]></script>

				<div class="formElement" id="cronDelPmDaysExclUserDiv">
					<div class="formFieldLabel">
						<label for="cronDelPmDaysExclUser">{lang}wcf.acp.adminTools.cron.pm.delExclUser{/lang}</label>
					</div>
					<div class="formField">
						<input type="text" class="inputText" name="cronDelPmDaysExclUser" id="cronDelPmDaysExclUser" value="{if $cronDelPmDaysExclUser|isset}{@$cronDelPmDaysExclUser}{else}{/if}" maxlength="254" />
						{if $errorField == 'cronDelPmDaysExclUser'}
							<div class="innerError">
							    {if $errorType == 'commaSeparatedIntList'}{lang}wcf.acp.adminTools.error.commaSeparatedIntList{/lang}{/if}
							</div>
						{/if}
					</div>
					<div class="formFieldDesc hidden" id="cronDelPmDaysExclUserHelpMessage">
						{lang}wcf.acp.adminTools.cron.pm.delExclUser.description{/lang}
					</div>
				</div>
				<script type="text/javascript">//<![CDATA[
					inlineHelp.register('cronDelPmDaysExclUser');
				//]]></script>

				<div class="formElement" id="cronDelPmDaysShowExclInfo">
					<div class="formField">
						<label><input type="checkbox" id="cronDelPmDaysShowExclInfo" name="cronDelPmDaysShowExclInfo" value="1"{if $cronDelPmDaysShowExclInfo} checked="checked"{/if} /> {lang}wcf.acp.adminTools.cron.pm.showExclInfo{/lang}</label>
					</div>
					<div class="formFieldDesc hidden" id="cronDelPmDaysShowExclInfoHelpMessage">
						{lang}wcf.acp.adminTools.cron.pm.showExclInfo.description{/lang}
					</div>
				</div>
				<script type="text/javascript">//<![CDATA[
					inlineHelp.register('cronDelPmDaysShowExclInfo');
				//]]></script>
    		</fieldset>

<!-- inaktive Benutzer ********************************* -->
			<fieldset>
				<legend>{lang}wcf.acp.adminTools.cron.inActiveUser.legend{/lang}</legend>
				<p class="description">{lang}wcf.acp.adminTools.cron.inActiveUser.legend.description{/lang}</p>
				<div class="formElement" id="cronDelInactiveUserDaysDiv">
					<div class="formFieldLabel">
						<label for="cronDelInactiveUserDays">{lang}wcf.acp.adminTools.cron.inActiveUser.delDays{/lang}</label>
					</div>
					<div class="formField">
						<input type="text" class="inputText" name="cronDelInactiveUserDays" id="cronDelInactiveUserDays" value="{if $cronDelInactiveUserDays|isset}{@$cronDelInactiveUserDays}{else}0{/if}" maxlength="4" />
						{if $errorField == 'cronDelInactiveUserDays'}
							<div class="innerError">
							    {if $errorType == 'notNumeric'}{lang}wcf.acp.adminTools.error.notNumeric{/lang}{/if}
							</div>
						{/if}
					</div>
					<div class="formFieldDesc hidden" id="cronDelInactiveUserDaysHelpMessage">
						{lang}wcf.acp.adminTools.cron.inActiveUser.delDays.description{/lang}
					</div>
				</div>
				<script type="text/javascript">//<![CDATA[
					inlineHelp.register('cronDelInactiveUserDays');
				//]]></script>

				<div class="formElement" id="cronDelInactiveUserExclUgrpsDiv">
					<div class="formFieldLabel">
						<label for="cronDelInactiveUserExclUgrps">{lang}wcf.acp.adminTools.cron.inActiveUser.delExclUgrps{/lang}</label>
					</div>
					<div class="formField">
						<input type="text" class="inputText" name="cronDelInactiveUserExclUgrps" id="cronDelInactiveUserExclUgrps" value="{if $cronDelInactiveUserExclUgrps|isset}{@$cronDelInactiveUserExclUgrps}{else}{/if}" maxlength="254" />
						{if $errorField == 'cronDelInactiveUserExclUgrps'}
							<div class="innerError">
							    {if $errorType == 'commaSeparatedIntList'}{lang}wcf.acp.adminTools.error.commaSeparatedIntList{/lang}{/if}
							</div>
						{/if}
					</div>
					<div class="formFieldDesc hidden" id="cronDelInactiveUserExclUgrpsHelpMessage">
						{lang}wcf.acp.adminTools.cron.inActiveUser.delExclUgrps.description{/lang}
					</div>
				</div>
				<script type="text/javascript">//<![CDATA[
					inlineHelp.register('cronDelInactiveUserExclUgrps');
				//]]></script>

				<div class="formElement" id="cronDelInactiveUserExclDiv">
					<div class="formFieldLabel">
						<label for="cronDelInactiveUserExcl">{lang}wcf.acp.adminTools.cron.inActiveUser.delExclUser{/lang}</label>
					</div>
					<div class="formField">
						<input type="text" class="inputText" name="cronDelInactiveUserExcl" id="cronDelInactiveUserExcl" value="{if $cronDelInactiveUserExcl|isset}{@$cronDelInactiveUserExcl}{else}{/if}" maxlength="254" />
						{if $errorField == 'cronDelInactiveUserExcl'}
							<div class="innerError">
							    {if $errorType == 'commaSeparatedIntList'}{lang}wcf.acp.adminTools.error.commaSeparatedIntList{/lang}{/if}
							</div>
						{/if}
					</div>
					<div class="formFieldDesc hidden" id="cronDelInactiveUserExclHelpMessage">
						{lang}wcf.acp.adminTools.cron.inActiveUser.delExclUser.description{/lang}
					</div>
				</div>
				<script type="text/javascript">//<![CDATA[
					inlineHelp.register('cronDelInactiveUserExcl');
				//]]></script>
    		</fieldset>

<!-- Archiv ******************************************** -->
{if $wbbExists}
			<fieldset>
				<legend>{lang}wcf.acp.adminTools.cron.cronThreadArchive.legend{/lang}</legend>
				<p class="description">{lang}wcf.acp.adminTools.cron.cronThreadArchive.legend.description{/lang}</p>
				<div class="formElement" id="cronThreadArchiveDays">
					<div class="formFieldLabel">
						<label for="cronThreadArchiveDays">{lang}wcf.acp.adminTools.cron.cronThreadArchiveDays{/lang}</label>
					</div>
					<div class="formField">
						<input type="text" class="inputText" name="cronThreadArchiveDays" id="cronThreadArchiveDays" value="{if $cronThreadArchiveDays|isset}{@$cronThreadArchiveDays}{else}0{/if}" maxlength="4" />
						{if $errorField == 'cronThreadArchiveDays'}
							<div class="innerError">
							    {if $errorType == 'notNumeric'}{lang}wcf.acp.adminTools.error.notNumeric{/lang}{/if}
							</div>
						{/if}
					</div>
					<div class="formFieldDesc hidden" id="cronThreadArchiveDaysHelpMessage">
						{lang}wcf.acp.adminTools.cron.cronThreadArchiveDays.description{/lang}
					</div>
				</div>
				<script type="text/javascript">//<![CDATA[
					inlineHelp.register('cronThreadArchiveDays');
				//]]></script>

				<div class="formElement" id="cronThreadArchiveSrc">
					<div class="formFieldLabel">
						<label for="cronThreadArchiveSrc">{lang}wcf.acp.adminTools.cron.cronThreadArchiveSrc{/lang}</label>
					</div>
					<div class="formField">
						<select name="cronThreadArchiveSrc[]" id="cronThreadArchiveSrc" multiple="multiple" size="10">
						    {if $cronThreadArchiveBoards|count}
    						    {foreach from=$cronThreadArchiveBoards item=board}
                                    <option value="{$board.boardID}"{if !$board.SRC|empty} selected="selected"{/if}>{@$board.title} [{$board.boardID}]</option>
    						    {/foreach}
    						{/if}
					    </select>
					</div>
					<div class="formFieldDesc hidden" id="cronThreadArchiveSrcHelpMessage">
						{lang}wcf.acp.adminTools.cron.cronThreadArchiveSrc.description{/lang}
					</div>
				</div>
				<script type="text/javascript">//<![CDATA[
					inlineHelp.register('cronThreadArchiveSrc');
				//]]></script>

				<div class="formElement" id="cronThreadArchiveTgt">
					<div class="formFieldLabel">
						<label for="cronThreadArchiveTgt">{lang}wcf.acp.adminTools.cron.cronThreadArchiveTgt{/lang}</label>
					</div>
					<div class="formField">
						<select name="cronThreadArchiveTgt" id="cronThreadArchiveTgt">
						    <option value="0"></option>
						    {if $cronThreadArchiveBoards|count}
    						    {foreach from=$cronThreadArchiveBoards item=board}
                                    <option value="{$board.boardID}"{if $cronThreadArchiveTgt && $cronThreadArchiveTgt == $board.boardID} selected="selected"{/if}>{@$board.title} [{$board.boardID}]</option>
    						    {/foreach}
    						{/if}
					    </select>
						{if $errorField == 'cronThreadArchiveTgt'}
							<div class="innerError">
							    {if $errorType == 'equalTgtSrc'}{lang}wcf.acp.adminTools.error.equalTgtSrc{/lang}{/if}
							</div>
						{/if}
					</div>
					<div class="formFieldDesc hidden" id="cronThreadArchiveTgtHelpMessage">
						{lang}wcf.acp.adminTools.cron.cronThreadArchiveTgt.description{/lang}
					</div>
				</div>
				<script type="text/javascript">//<![CDATA[
					inlineHelp.register('cronThreadArchiveTgt');
				//]]></script>

				<div class="formElement" id="cronThreadArchiveExclPolls">
					<div class="formField">
						<label><input type="checkbox" id="cronThreadArchiveExclPolls" name="cronThreadArchiveExclPolls" value="1"{if $cronThreadArchiveExclPolls} checked="checked"{/if} /> {lang}wcf.acp.adminTools.cron.cronThreadArchiveExclPolls{/lang}</label>
					</div>
					<div class="formFieldDesc hidden" id="cronThreadArchiveExclPollsHelpMessage">
						{lang}wcf.acp.adminTools.cron.cronThreadArchiveExclPolls.description{/lang}
					</div>
				</div>
				<script type="text/javascript">//<![CDATA[
					inlineHelp.register('cronThreadArchiveExclPolls');
				//]]></script>
				<div class="formElement" id="cronThreadArchiveExclAnnouncement">
					<div class="formField">
						<label><input type="checkbox" id="cronThreadArchiveExclAnnouncement" name="cronThreadArchiveExclAnnouncement" value="1"{if $cronThreadArchiveExclAnnouncement} checked="checked"{/if} /> {lang}wcf.acp.adminTools.cron.cronThreadArchiveExclAnnouncement{/lang}</label>
					</div>
					<div class="formFieldDesc hidden" id="cronThreadArchiveExclAnnouncementHelpMessage">
						{lang}wcf.acp.adminTools.cron.cronThreadArchiveExclAnnouncement.description{/lang}
					</div>
				</div>
				<script type="text/javascript">//<![CDATA[
					inlineHelp.register('cronThreadArchiveExclAnnouncement');
				//]]></script>
				<div class="formElement" id="cronThreadArchiveExclSticky">
					<div class="formField">
						<label><input type="checkbox" id="cronThreadArchiveExclSticky" name="cronThreadArchiveExclSticky" value="1"{if $cronThreadArchiveExclSticky} checked="checked"{/if} /> {lang}wcf.acp.adminTools.cron.cronThreadArchiveExclSticky{/lang}</label>
					</div>
					<div class="formFieldDesc hidden" id="cronThreadArchiveExclStickyHelpMessage">
						{lang}wcf.acp.adminTools.cron.cronThreadArchiveExclSticky.description{/lang}
					</div>
				</div>
				<script type="text/javascript">//<![CDATA[
					inlineHelp.register('cronThreadArchiveExclSticky');
				//]]></script>
				<div class="formElement" id="cronThreadArchiveExclClosed">
					<div class="formField">
						<label><input type="checkbox" id="cronThreadArchiveExclClosed" name="cronThreadArchiveExclClosed" value="1"{if $cronThreadArchiveExclClosed} checked="checked"{/if} /> {lang}wcf.acp.adminTools.cron.cronThreadArchiveExclClosed{/lang}</label>
					</div>
					<div class="formFieldDesc hidden" id="cronThreadArchiveExclClosedHelpMessage">
						{lang}wcf.acp.adminTools.cron.cronThreadArchiveExclClosed.description{/lang}
					</div>
				</div>
				<script type="text/javascript">//<![CDATA[
					inlineHelp.register('cronThreadArchiveExclClosed');
				//]]></script>
				<div class="formElement" id="cronThreadArchiveExclDeleted">
					<div class="formField">
						<label><input type="checkbox" id="cronThreadArchiveExclDeleted" name="cronThreadArchiveExclDeleted" value="1"{if $cronThreadArchiveExclDeleted} checked="checked"{/if} /> {lang}wcf.acp.adminTools.cron.cronThreadArchiveExclDeleted{/lang}</label>
					</div>
					<div class="formFieldDesc hidden" id="cronThreadArchiveExclDeletedHelpMessage">
						{lang}wcf.acp.adminTools.cron.cronThreadArchiveExclDeleted.description{/lang}
					</div>
				</div>
				<script type="text/javascript">//<![CDATA[
					inlineHelp.register('cronThreadArchiveExclDeleted');
				//]]></script>
    		</fieldset>
{/if}

<!-- Journal ******************************************* -->
			<fieldset>
				<legend>{lang}wcf.acp.adminTools.cron.mail.legend{/lang}</legend>
                <p class="description">{lang}wcf.acp.adminTools.cron.mail.legend.description{/lang}</p>
				<div class="formElement" id="cronLogEnabled">
					<div class="formField">
						<label><input type="checkbox" id="cronLogEnabled" name="cronLogEnabled" value="1"{if $cronLogEnabled} checked="checked"{/if} /> {lang}wcf.acp.adminTools.cron.mail.logEnabled{/lang}</label>
					</div>
					<div class="formFieldDesc hidden" id="cronLogEnabledHelpMessage">
						{lang}wcf.acp.adminTools.cron.mail.logEnabled.description{/lang}
					</div>
				</div>
				<script type="text/javascript">//<![CDATA[
					inlineHelp.register('cronLogEnabled');
				//]]></script>
				<div class="formElement" id="cronStatEnabled">
					<div class="formField">
						<label><input type="checkbox" id="cronStatEnabled" name="cronStatEnabled" value="1"{if $cronStatEnabled} checked="checked"{/if} /> {lang}wcf.acp.adminTools.cron.mail.statEnabled{/lang}</label>
					</div>
					<div class="formFieldDesc hidden" id="cronStatEnabledHelpMessage">
						{lang}wcf.acp.adminTools.cron.mail.statEnabled.description{/lang}
					</div>
				</div>
				<script type="text/javascript">//<![CDATA[
					inlineHelp.register('cronStatEnabled');
				//]]></script>
				<div class="formElement" id="cronLogUseAdminEmail">
					<div class="formField">
						<label><input type="checkbox" id="cronLogUseAdminEmail" name="cronLogUseAdminEmail" value="1"{if $cronLogUseAdminEmail} checked="checked"{/if} /> {lang}wcf.acp.adminTools.cron.mail.sendLogToAdmin{/lang}</label>
					</div>
					<div class="formFieldDesc hidden" id="cronLogUseAdminEmailHelpMessage">
						{lang}wcf.acp.adminTools.cron.mail.sendLogToAdmin.description{/lang}
					</div>
				</div>
				<script type="text/javascript">//<![CDATA[
					inlineHelp.register('cronLogUseAdminEmail');
				//]]></script>

				<div class="formElement" id="progressJournal" style="display:none; text-align:right;">
                    <div class="formField"><img src="{@RELATIVE_WCF_DIR}icon/adminToolsProgressbar.gif" alt="" /></div>
                </div>
                <div class="smallButtons">
                    <ul>
        			    <li><a href="javascript:void(0);" onClick="runCron('journal');"><img src="{@RELATIVE_WCF_DIR}icon/adminToolsRunCronS.png" alt="" /> <span>{lang}wcf.acp.adminTools.cron.run{/lang}</span></a></li>
        			</ul>
        	    </div>
			</fieldset>

<!-- DB ************************************************ -->
			<fieldset>
				<legend>{lang}wcf.acp.adminTools.cron.db.legend{/lang}</legend>
                <p class="description">{lang}wcf.acp.adminTools.cron.db.legend.description{/lang}</p>
				<div class="formElement" id="cronDbAnalyze">
					<div class="formField">
						<label><input type="checkbox" id="cronDbAnalyze" name="cronDbAnalyze" value="1"{if $cronDbAnalyze} checked="checked"{/if} /> {lang}wcf.acp.adminTools.cron.db.analyze{/lang}</label>
					</div>
					<div class="formFieldDesc hidden" id="cronDbAnalyzeHelpMessage">
						{lang}wcf.acp.adminTools.cron.db.analyze.description{/lang}
					</div>
				</div>
				<script type="text/javascript">//<![CDATA[
					inlineHelp.register('cronDbAnalyze');
				//]]></script>

				<div class="formElement" id="cronDbOptimize">
					<div class="formField">
						<label><input type="checkbox" id="cronDbOptimize" name="cronDbOptimize" value="1"{if $cronDbOptimize} checked="checked"{/if} /> {lang}wcf.acp.adminTools.cron.db.optimize{/lang}</label>
					</div>
					<div class="formFieldDesc hidden" id="cronDbOptimizeHelpMessage">
						{lang}wcf.acp.adminTools.cron.db.optimize.description{/lang}
					</div>
				</div>
				<script type="text/javascript">//<![CDATA[
					inlineHelp.register('cronDbOptimize');
				//]]></script>

				<div class="formElement" id="cronDbBackup">
					<div class="formField">
						<label><input type="checkbox" id="cronDbBackup" name="cronDbBackup" value="1"{if $cronDbBackup} checked="checked"{/if} /> {lang}wcf.acp.adminTools.cron.db.backup{/lang}</label>
					</div>
					<div class="formFieldDesc hidden" id="cronDbBackupHelpMessage">
						{lang}wcf.acp.adminTools.cron.db.backup.description{/lang}
					</div>
				</div>
				<script type="text/javascript">//<![CDATA[
					inlineHelp.register('cronDbBackup');
				//]]></script>

				<div class="formElement" id="progressDB" style="display:none; text-align:right;">
                    <div class="formField"><img src="{@RELATIVE_WCF_DIR}icon/adminToolsProgressbar.gif" alt="" /></div>
                </div>
                <div class="smallButtons">
                    <ul>
        			    <li><a href="javascript:void(0);" onClick="runCron('db');"><img src="{@RELATIVE_WCF_DIR}icon/adminToolsRunCronS.png" alt="" /> <span>{lang}wcf.acp.adminTools.cron.run{/lang}</span></a></li>
        			</ul>
        	    </div>
			</fieldset>
		</div>
	</div>
    <div class="formSubmit">
        {@SID_INPUT_TAG}
        <input type="hidden" name="packageID" value="{@PACKAGE_ID}" />
    	<input type="submit" accesskey="s" value="{lang}wcf.global.button.submit{/lang}" />
    	<input type="reset" accesskey="r" value="{lang}wcf.global.button.reset{/lang}" />
    </div>
</form>

{include file='footer'}

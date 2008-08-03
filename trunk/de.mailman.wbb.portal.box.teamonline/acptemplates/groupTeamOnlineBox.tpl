<div class="formElement" id="showOnTeamOnlineBoxDiv">
	<div class="formField">
        <label><input onclick="if (this.checked) enableOptions('teamOnlineMarking'); else disableOptions('teamOnlineMarking')" type="checkbox" name="showOnTeamOnlineBox" id="showOnTeamOnlineBox" value="1" {if $showOnTeamOnlineBox == 1}checked="checked" {/if}/> {lang}wcf.acp.group.showOnTeamOnlineBox{/lang}</label>
	</div>
</div>

<div class="formElement" id="teamOnlineMarkingDiv">
	<div class="formFieldLabel">
		<label for="teamOnlineMarking">{lang}wcf.acp.group.teamOnlineMarking{/lang}</label>
	</div>
	<div class="formField">
		<input type="text" class="inputText" id="teamOnlineMarking" name="teamOnlineMarking" value="{$teamOnlineMarking}" />
	</div>
	<div class="formFieldDesc hidden" id="teamOnlineMarkingHelpMessage">
		{lang}wcf.acp.group.teamOnlineMarking.description{/lang}
	</div>
</div>
<script type="text/javascript">//<![CDATA[
	inlineHelp.register('teamOnlineMarking');
    {if $showOnTeamOnlineBox != 1}disableOptions('teamOnlineMarking');{/if}
//]]></script>

{include file="documentHeader"}
<!-- package: de.mailman.wcf.icsHolidayExporter -->
<head>
    <title>{lang}wcf.icsHolidayExporter.title{/lang} - {lang}{PAGE_TITLE}{/lang}</title>
    {include file='headInclude' sandbox=false}
</head>
<body{if $templateName|isset} id="tpl{$templateName|ucfirst}"{/if}>
{include file='header' sandbox=false}

<div id="main">
	<ul class="breadCrumbs">
		<li><a href="index.php?page=Index{@SID_ARG_2ND}"><img src="{icon}indexS.png{/icon}" alt="" /> <span>{lang}{PAGE_TITLE}{/lang}</span></a> &raquo;</li>
	</ul>
	
	<div class="mainHeadline">
		<img src="{icon}icsHolidayExportL.png{/icon}" alt="" />
		<div class="headlineContainer">
			<h2>{lang}wcf.icsHolidayExporter.title{/lang} {@$icsheVersion}</h2>
			<p>{lang}wcf.icsHolidayExporter.description{/lang}</p>
		</div>
	</div>

	{if $userMessages|isset}{@$userMessages}{/if}
	
	{if $errorField}
		<p class="error">{lang}wcf.global.form.error{/lang}</p>
	{/if}

    <form method="post" action="index.php?form=IcsHolidayExporter">
        <div class="border content">
            <div class="container-1">
				<fieldset>
					<legend>{lang}wcf.icsHolidayExporter.legend{/lang}</legend>

					<div class="formGroup">
						<div class="formGroupLabel">
							<label for="fromYear">{lang}wcf.icsHolidayExporter.settings{/lang}</label>
						</div>
						
						<div class="formGroupField">
							<fieldset id="exportPeriod">

							    <legend><label for="fromYear">{lang}wcf.icsHolidayExporter.settings{/lang}</label></legend>

								<div class="floatedElement">
									<div class="floatedElement">
										<p>{lang}wcf.icsHolidayExporter.fromYear{/lang}</p>
									</div>

									<div class="floatedElement{if $errorField == 'timeFrame'} formError{/if}">
										<label for="fromYear">{lang}wcf.icsHolidayExporter.year{/lang}</label>
										<select name="fromYear" id="fromYear">
										    <option value="0"></option>
										    {foreach from=$years item=year}
										        <option value="{$year}"{if $fromYear == $year} selected="selected"{/if}>{$year}</option>
										    {/foreach}
										</select>
									</div>
								</div>

								<div class="floatedElement">
									<div class="floatedElement">
										<p>{lang}wcf.icsHolidayExporter.toYear{/lang}</p>
									</div>

									<div class="floatedElement{if $errorField == 'timeFrame'} formError{/if}">
										<label for="toYear">{lang}wcf.icsHolidayExporter.year{/lang}</label>
										<select name="toYear" id="toYear">
										    <option value="0"></option>
										    {foreach from=$years item=year}
										        <option value="{$year}"{if $toYear == $year} selected="selected"{/if}>{$year}</option>
										    {/foreach}
										</select>
									</div>
								</div>

								<div class="floatedElement">
									<div class="floatedElement">
										<p>{lang}wcf.icsHolidayExporter.country{/lang}</p>
									</div>

									<div class="floatedElement{if $errorField == 'country'} formError{/if}">
										<label for="country">{lang}wcf.icsHolidayExporter.countryCode{/lang}</label>
										<select name="country" id="country">
										    <option value=""></option>
                                            {foreach from=$ctryCodes item=ctry}
										        <option value="{$ctry}"{if $country == $ctry} selected="selected"{/if}>{$ctry}</option>
                                            {/foreach}
										</select>
									</div>
								</div>
							</fieldset>
							{assign var="cnt" value=0}
                            {lang}wcf.icsHolidayExporter.exports{/lang}
                            {foreach from=$exports item=export}
                                {if $cnt} &bull; {/if}
                                {@$export.ctryCode} {#$export.cnt}
                                {assign var="cnt" value=1}
                            {/foreach}
						</div>
					</div>
                </fieldset>
                {@SID_INPUT_TAG}
            </div>
        </div>
        <div class="formSubmit">
            <input type="submit" accesskey="s" value="{lang}wcf.global.button.submit{/lang}" />
            <input type="reset" accesskey="r" value="{lang}wcf.global.button.reset{/lang}" />
        </div>
    </form>

	{if 'ICSHOLIDAYEXPORTER_BRANDINGFREE'|defined == false}
	<div> 
		<div>
			<div align="center">{lang}wcf.global.icsHolidayExporter.copyright{/lang}</div>
		</div>
	</div>
	{/if}
</div>

{include file='footer' sandbox=false}
</body>
</html>

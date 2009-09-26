{include file="documentHeader"}
<!-- $Id$ -->
<!-- package: de.mailman.wcf.versionChecker -->
<head>
	<title>{lang}wcf.versionChecker.title{/lang} - {lang}{PAGE_TITLE}{/lang}</title>
	{include file='headInclude' sandbox=false}
</head>
<body{if $templateName|isset} id="tpl{$templateName|ucfirst}"{/if}>
{include file='header' sandbox=false}

<div id="main">
	<ul class="breadCrumbs">
		<li><a href="index.php?page=Index{@SID_ARG_2ND}"><img src="{icon}indexS.png{/icon}" alt="" /> <span>{lang}{PAGE_TITLE}{/lang}</span></a> &raquo;</li>
	</ul>
	
    <div class="mainHeadline">
    	<img src="{icon}packageL.png{/icon}" alt="" />
    	<div class="headlineContainer">
			<h2>{lang}wcf.versionChecker.title{/lang}</h2>
			<p>{lang}wcf.versionChecker.description{/lang}</p>
    	</div>
    </div>

	{if $verResult|isset && $verResult > -2 && $verResult < 2}
		<p class="info">
		    {if $verResult == -1}{lang}wcf.versionChecker.lower{/lang}
		    {else if $verResult == 0}{lang}wcf.versionChecker.equal{/lang}
		    {else}{lang}wcf.versionChecker.greater{/lang}
		    {/if}
		</p>
	{/if}

    <div class="border content">
    	<div class="container-1">
            <fieldset>
            	<legend>{lang}wcf.versionChecker.legend{/lang}</legend>
                <form method="post" action="index.php?page=VersionChecker">
                    <div class="formElement" id="version1Div">
                    	<div class="formFieldLabel">
                    		<label for="version1">{lang}wcf.versionChecker.version1{/lang}</label>
                    	</div>
                    	<div class="formField">
                    		<input type="text" class="inputText" name="version1" id="version1" value="{if $version1|isset}{@$version1}{/if}" maxlength="32" />
                    	</div>
                    </div>
                    
                    <div class="formElement" id="version2Div">
                    	<div class="formFieldLabel">
                    		<label for="version2">{lang}wcf.versionChecker.version2{/lang}</label>
                    	</div>
                    	<div class="formField">
                    		<input type="text" class="inputText" name="version2" id="version2" value="{if $version2|isset}{@$version2}{/if}" maxlength="32" />
                    	</div>
                    </div>

                    <div class="formSubmit">
                    	<input type="submit" name="send" accesskey="s" value="{lang}wcf.global.button.submit{/lang}" />
                    	<input type="button" name="reset" accesskey="r" value="{lang}wcf.global.button.reset{/lang}" onClick="javascript:window.location.href='index.php?page=VersionChecker'" />
                    	<input type="hidden" name="action" value="compare" />
                    	{@SID_INPUT_TAG}
                    </div>
                </form>
            </fieldset>
        </div>
    </div>
	<div> 
		<div>
			<div align="center">{lang}wcf.global.versionChecker.copyright{/lang}</div>
		</div>
	</div>
</div>
{include file='footer' sandbox=false}
</body>
</html>

{include file="documentHeader"}
<head>
	<title>{lang}wbb.versionChecker.title{/lang} - {PAGE_TITLE}</title>
	{include file='headInclude' sandbox=false}
</head>
<body>
{include file='header' sandbox=false}
<div id="main">

    <div class="mainHeadline">
    	<img src="{@RELATIVE_WCF_DIR}icon/packageL.png" alt="" />
    	<div class="headlineContainer">
    		<h2>{lang}wbb.versionChecker.title{/lang}</h2>
    	</div>
    </div>

	{if $verResult|isset && $verResult > -2 && $verResult < 2}
		<p class="info">
		    {if $verResult == -1}{lang}wbb.versionChecker.lower{/lang}
		    {else if $verResult == 0}{lang}wbb.versionChecker.equal{/lang}
		    {else}{lang}wbb.versionChecker.greater{/lang}
		    {/if}
		</p>
	{/if}

    <div class="border content">
    	<div class="container-1">
            <fieldset>
            	<legend>{lang}wbb.versionChecker.legend{/lang}</legend>
                <form method="post" action="index.php?page=VersionChecker">
                    <div class="formElement" id="version1Div">
                    	<div class="formFieldLabel">
                    		<label for="version1">{lang}wbb.versionChecker.version1{/lang}</label>
                    	</div>
                    	<div class="formField">
                    		<input type="text" class="inputText" name="version1" id="version1" value="{if $version1|isset}{@$version1}{/if}" maxlength="32" />
                    	</div>
                    </div>
                    
                    <div class="formElement" id="version2Div">
                    	<div class="formFieldLabel">
                    		<label for="version2">{lang}wbb.versionChecker.version2{/lang}</label>
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
</div>
{include file='footer' sandbox=false}
</body>
</html>

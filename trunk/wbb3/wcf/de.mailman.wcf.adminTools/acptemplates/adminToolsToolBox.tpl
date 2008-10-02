{* $Id$ *}
{include file='header'}

<script type="text/javascript">
//<![CDATA[
function saveBoard() {
    var valExec = false;
    var fLen = document.forms['ftbBoard'].length;
    if(document.forms['ftbBoard'].elements['boardSrcID'].value && document.forms['ftbBoard'].elements['boardSrcID'].value != document.forms['ftbBoard'].elements['boardTgtID'].value) valExec = true;
    if(valExec) {
        valExec = false;
        for(i=0;i<fLen;i++) {
            if(document.forms['ftbBoard'].elements[i].type == 'checkbox' && !document.forms['ftbBoard'].elements[i].disabled) {
                if(document.forms['ftbBoard'].elements[i].checked) {
                    valExec = true;
                    break;
                }
            }
        }
    }
    if(valExec && confirm('{lang}wcf.acp.adminTools.confirm{/lang}')) document.forms['ftbBoard'].submit();
}

function saveUgrps() {
    if(confirm('{lang}wcf.acp.adminTools.confirm{/lang}')) document.forms['ftbUgrp'].submit();
}

function savePrefBoard() {
    if(document.forms['ftbPrefix'].elements['boardPrefSrcID'].value && document.forms['ftbPrefix'].elements['boardPrefSrcID'].value != document.forms['ftbPrefix'].elements['boardPrefTgt'].value) {
        if(confirm('{lang}wcf.acp.adminTools.confirm{/lang}')) document.forms['ftbPrefix'].submit();
    }
}

function saveUseroptions() {
    if(confirm('{lang}wcf.acp.adminTools.confirm{/lang}')) document.forms['ftbUserOption'].submit();
}

function runJob(job) {
    if(job == 'cache') {
        if(document.forms['ftbCache'].elements['cacheDel'].checked
        || document.forms['ftbCache'].elements['cacheTpl'].checked
        || document.forms['ftbCache'].elements['cacheLang'].checked
        || document.forms['ftbCache'].elements['cacheOpt'].checked
        || document.forms['ftbCache'].elements['cacheRSS'].checked) {
            var cDel = 0;
            var cTpl = 0;
            var cLang = 0;
            var cOpt = 0;
            var cRSS = 0;
            if(document.forms['ftbCache'].elements['cacheDel'].checked) cDel = 1;
            if(document.forms['ftbCache'].elements['cacheTpl'].checked) cTpl = 1;
            if(document.forms['ftbCache'].elements['cacheLang'].checked) cLang = 1;
            if(document.forms['ftbCache'].elements['cacheOpt'].checked) cOpt = 1;
            if(document.forms['ftbCache'].elements['cacheRSS'].checked) cRSS = 1;
            if(confirm('{lang}wcf.acp.adminTools.confirm.cache{/lang}')) {
                if(document.getElementById('progressJournal')) document.getElementById('progressJournal').style.display = 'block';
                window.location.href = 'index.php?form=AdminToolsToolBox&cRun=cache&cacheDel='+cDel+'&cacheTpl='+cTpl+'&cacheLang='+cLang+'&cacheOpt='+cOpt+'&cacheRSS='+cRSS+'&packageID={@PACKAGE_ID}{@SID_ARG_2ND_NOT_ENCODED}';
            }
        }
    }
}

function selCB(fName) {
    var setTo=false;
    var fLen=document.forms[fName].length;
    for(i=0;i<fLen;i++) {
        if(document.forms[fName].elements[i].type == 'checkbox' && !document.forms[fName].elements[i].disabled) {
            if(!document.forms[fName].elements[i].checked) {
                setTo = true;
                break;
            }
        }
    }
    for(i=0;i<fLen;i++) {
        if(document.forms[fName].elements[i].type == 'checkbox' && !document.forms[fName].elements[i].disabled) {
            document.forms[fName].elements[i].checked = setTo;
        }
    }
}

function saveSpider() {
    if(confirm('{lang}wcf.acp.adminTools.confirm.save{/lang}')) {
        document.forms['ftbSpider'].elements['spiderAction'].value = 'save';
        document.forms['ftbSpider'].submit();
    } else {
        return false;
    }
}

function deleteSpider() {
    if(confirm('{lang}wcf.acp.adminTools.confirm.delete{/lang}')) {
        document.forms['ftbSpider'].elements['spiderAction'].value = 'delete';
        document.forms['ftbSpider'].submit();
    } else {
        return false;
    }
}

function syncSpider() {
    if(confirm('{lang}wcf.acp.adminTools.confirm{/lang}')) {
        document.forms['ftbSpider'].elements['spiderAction'].value = 'sync';
        document.forms['ftbSpider'].submit();
    } else {
        return false;
    }
}
function exportSpider() {
    document.forms['ftbSpider'].elements['spiderAction'].value = 'export';
    document.forms['ftbSpider'].submit();
}
function importSpider() {
    if(!document.forms['ftbImportSpider'].elements['importSpider'].value) {
        return false;
    } else if(confirm('{lang}wcf.acp.adminTools.confirm.importSpider{/lang}')) {
        document.forms['ftbImportSpider'].submit();
    } else {
        return false;
    }
}
//]]>
</script>

<div class="mainHeadline">
    <img src="{@RELATIVE_WCF_DIR}icon/adminToolsToolBoxL.png" alt="" />
    <div class="headlineContainer">
        <h2>{lang}wcf.acp.adminTools.toolBox{/lang}</h2>
    </div>
</div>
{if $errorField}
    <p class="error">{lang}wcf.global.form.error{/lang}</p>
{/if}
{if $errMsg}
    <p class="error">{$errMsg}</p>
{/if}
{if $sucMsg}
    <p class="success">{$sucMsg}</p>
{/if}

    <div class="border content">
        <div class="container-1">
<!-- CACHE ********************************************* -->
            <fieldset>
                <legend>{lang}wcf.acp.adminTools.toolBox.cache.legend{/lang}</legend>
                <form method="post" name="ftbCache" action="index.php?form=AdminToolsToolBox">
                    <div class="formCheckBox formElement" id="cacheDelDiv">
                        <div class="formField">
                            <label><input type="checkbox" id="cacheDel" name="cacheDel" value="1"{if $cacheDel} checked="checked"{/if} /> {lang}wcf.acp.adminTools.toolBox.cache.del{/lang}</label>
                        </div>
                        <div class="formFieldDesc hidden" id="cacheDelHelpMessage">
                            {lang}wcf.acp.adminTools.toolBox.cache.del.description{/lang}
                        </div>
                    </div>
                    <script type="text/javascript">//<![CDATA[
                        inlineHelp.register('cacheDel');
                    //]]></script>

                    <div class="formCheckBox formElement" id="cacheTplDiv">
                        <div class="formField">
                            <label><input type="checkbox" id="cacheTpl" name="cacheTpl" value="1"{if $cacheTpl} checked="checked"{/if} /> {lang}wcf.acp.adminTools.toolBox.cache.tpl{/lang}</label>
                        </div>
                        <div class="formFieldDesc hidden" id="cacheTplHelpMessage">
                            {lang}wcf.acp.adminTools.toolBox.cache.tpl.description{/lang}
                        </div>
                    </div>
                    <script type="text/javascript">//<![CDATA[
                        inlineHelp.register('cacheTpl');
                    //]]></script>

                    <div class="formCheckBox formElement" id="cacheLangDiv">
                        <div class="formField">
                            <label><input type="checkbox" id="cacheLang" name="cacheLang" value="1"{if $cacheLang} checked="checked"{/if} /> {lang}wcf.acp.adminTools.toolBox.cache.lang{/lang}</label>
                        </div>
                        <div class="formFieldDesc hidden" id="cacheLangHelpMessage">
                            {lang}wcf.acp.adminTools.toolBox.cache.lang.description{/lang}
                        </div>
                    </div>
                    <script type="text/javascript">//<![CDATA[
                        inlineHelp.register('cacheLang');
                    //]]></script>

                    <div class="formCheckBox formElement" id="cacheOptDiv">
                        <div class="formField">
                            <label><input type="checkbox" id="cacheOpt" name="cacheOpt" value="1"{if $cacheOpt} checked="checked"{/if} /> {lang}wcf.acp.adminTools.toolBox.cache.options{/lang}</label>
                        </div>
                        <div class="formFieldDesc hidden" id="cacheOptHelpMessage">
                            {lang}wcf.acp.adminTools.toolBox.cache.options.description{/lang}
                        </div>
                    </div>
                    <script type="text/javascript">//<![CDATA[
                        inlineHelp.register('cacheOpt');
                    //]]></script>

                    <div class="formCheckBox formElement" id="cacheRSSDiv">
                        <div class="formField">
                            <label><input type="checkbox" id="cacheRSS" name="cacheRSS" value="1"{if $cacheRSS} checked="checked"{/if}{if !$spRssExists || !$wbbExists} disabled="disabled"{/if} /> {lang}wcf.acp.adminTools.toolBox.cache.rss{/lang}</label>
                        </div>
                        <div class="formFieldDesc hidden" id="cacheRSSHelpMessage">
                            {lang}wcf.acp.adminTools.toolBox.cache.rss.description{/lang}
                        </div>
                    </div>
                    <script type="text/javascript">//<![CDATA[
                        inlineHelp.register('cacheRSS');
                    //]]></script>

                    <div class="formElement" id="progressDBDiv" style="display:none; text-align:right;">
                        <div class="formField"><img src="{@RELATIVE_WCF_DIR}icon/adminToolsProgressbar.gif" alt="" /></div>
                    </div>

                    <div class="smallButtons">
                        <ul>
                            <li><a href="javascript:void(0);" onClick="runJob('cache');"><img src="{@RELATIVE_WCF_DIR}icon/adminToolsRunCronS.png" alt="" /> <span>{lang}wcf.acp.adminTools.toolBox.cache.exec{/lang}</span></a></li>
                            <li><a href="javascript:void(0);" onClick="selCB('ftbCache');"><img src="{@RELATIVE_WCF_DIR}icon/defaultS.png" alt="" /></a></li>
                        </ul>
                    </div>
                    <input type="hidden" name="packageID" value="{@PACKAGE_ID}" />
                    {@SID_INPUT_TAG}
                </form>
            </fieldset>

<!-- BOARD PERMISSIONS ********************************* -->
{if $wbbExists}
            <fieldset>
                <legend>{lang}wcf.acp.adminTools.toolBox.board.legend{/lang}</legend>
                <p class="description">{lang}wcf.acp.adminTools.toolBox.board.legend.description{/lang}</p>
                <form method="post" name="ftbBoard" action="index.php?form=AdminToolsToolBox">
                    <div class="formElement" id="boardSrcIDDiv">
                        <div class="formFieldLabel">
                            <label for="boardSrc">{lang}wcf.acp.adminTools.toolBox.board.source{/lang}</label>
                        </div>
                        <div class="formField">
                            <select name="boardSrcID" id="boardSrc">
                                {if $boards|count}
                                    {foreach from=$boards key=boardID item=title}
                                        <option value="{$boardID}">{@$title}</option>
                                    {/foreach}
                                {/if}
                            </select>
                            ({#$boards|count})
                            {if $errorField == 'boardSrc'}
                                <p class="innerError">
                                    {if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
                                </p>
                            {/if}
                        </div>
                        <div class="formFieldDesc hidden" id="boardSrcHelpMessage">
                            {lang}wcf.acp.adminTools.toolBox.board.source.description{/lang}
                        </div>
                    </div>
                    <script type="text/javascript">//<![CDATA[
                        inlineHelp.register('boardSrc');
                    //]]></script>

                    <div class="formElement" id="boardTgtDiv">
                        <div class="formFieldLabel">
                            <label for="boardTgt">{lang}wcf.acp.adminTools.toolBox.board.target{/lang}</label>
                        </div>
                        <div class="formField">
                            <select name="boardTgtID" id="boardTgt">
                                {if $boards|count}
                                    {foreach from=$boards key=boardID item=title}
                                        <option value="{$boardID}">{@$title}</option>
                                    {/foreach}
                                {/if}
                            </select>
                            {if $errorField == 'boardTgt'}
                                <p class="innerError">
                                    {if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
                                    {if $errorType == 'equal'}{lang}wcf.acp.adminTools.error.equal{/lang}{/if}
                                </p>
                            {/if}
                        </div>
                        <div class="formFieldDesc hidden" id="boardTgtHelpMessage">
                            {lang}wcf.acp.adminTools.toolBox.board.target.description{/lang}
                        </div>
                    </div>
                    <script type="text/javascript">//<![CDATA[
                        inlineHelp.register('boardTgt');
                    //]]></script>

                    <div class="formCheckBox formElement" id="boardUserDiv">
                        <div class="formField">
                            <label><input type="checkbox" id="boardUser" name="boardUser" value="1" /> {lang}wcf.acp.adminTools.toolBox.board.user{/lang}</label>
                        </div>
                        <div class="formFieldDesc hidden" id="boardUserHelpMessage">
                            {lang}wcf.acp.adminTools.toolBox.board.user.description{/lang}
                        </div>
                    </div>
                    <script type="text/javascript">//<![CDATA[
                        inlineHelp.register('boardUser');
                    //]]></script>

                    <div class="formCheckBox formElement" id="boardGroupsDiv">
                        <div class="formField">
                            <label><input type="checkbox" id="boardGroups" name="boardGroups" value="1" /> {lang}wcf.acp.adminTools.toolBox.board.groups{/lang}</label>
                        </div>
                        <div class="formFieldDesc hidden" id="boardGroupsHelpMessage">
                            {lang}wcf.acp.adminTools.toolBox.board.groups.description{/lang}
                        </div>
                    </div>
                    <script type="text/javascript">//<![CDATA[
                        inlineHelp.register('boardGroups');
                    //]]></script>

                    <div class="formCheckBox formElement" id="boardModsDiv">
                        <div class="formField">
                            <label><input type="checkbox" id="boardMods" name="boardMods" value="1" /> {lang}wcf.acp.adminTools.toolBox.board.mods{/lang}</label>
                            {if $errorField == 'boardRights'}
                                <p class="innerError">
                                    {if $errorType == 'empty'}{lang}wcf.acp.adminTools.error.notSelected{/lang}{/if}
                                </p>
                            {/if}
                        </div>
                        <div class="formFieldDesc hidden" id="boardModsHelpMessage">
                            {lang}wcf.acp.adminTools.toolBox.board.mods.description{/lang}
                        </div>
                    </div>
                    <script type="text/javascript">//<![CDATA[
                        inlineHelp.register('boardMods');
                    //]]></script>

                    <div class="smallButtons">
                        <ul>
                            <li><a href="javascript:void(0);" onClick="saveBoard();"><img src="{@RELATIVE_WCF_DIR}icon/adminToolsRunCronS.png" alt="" /> <span>{lang}wcf.acp.adminTools.txt.execute{/lang}</span></a></li>
                            <li><a href="javascript:void(0);" onClick="selCB('ftbBoard');"><img src="{@RELATIVE_WCF_DIR}icon/defaultS.png" alt="" /></a></li>
                        </ul>
                    </div>

                    <input type="hidden" name="packageID" value="{@PACKAGE_ID}" />
                    <input type="hidden" name="boardAction" value="1" />
                    {@SID_INPUT_TAG}
                </form>
            </fieldset>
{/if}


<!-- BOARD PREFIXES ************************************ -->
{if $wbbExists}
            <fieldset>
                <legend>{lang}wcf.acp.adminTools.toolBox.prefix.legend{/lang}</legend>
                <form method="post" name="ftbPrefix" action="index.php?form=AdminToolsToolBox">
                    <div class="formElement" id="boardSrcDiv">
                        <div class="formFieldLabel">
                            <label for="boardPrefSrc">{lang}wcf.acp.adminTools.toolBox.prefix.source{/lang}</label>
                        </div>
                        <div class="formField">
                            <select name="boardPrefSrcID" id="boardPrefSrc">
                                {if $prefBoards|count}
                                    {foreach from=$prefBoards key=boardID item=title}
                                        <option value="{$boardID}">{@$title}</option>
                                    {/foreach}
                                {/if}
                            </select>
                            ({#$prefBoards|count})
                            {if $errorField == 'boardPrefSrc'}
                                <p class="innerError">
                                    {if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
                                </p>
                            {/if}
                        </div>
                        <div class="formFieldDesc hidden" id="boardPrefSrcHelpMessage">
                            {lang}wcf.acp.adminTools.toolBox.prefix.source.description{/lang}
                        </div>
                    </div>
                    <script type="text/javascript">//<![CDATA[
                        inlineHelp.register('boardPrefSrc');
                    //]]></script>

                    <div class="formElement" id="boardPrefTgtDiv">
                        <div class="formFieldLabel">
                            <label for="boardPrefTgt">{lang}wcf.acp.adminTools.toolBox.prefix.target{/lang}</label>
                        </div>
                        <div class="formField">
                            <select name="boardPrefTgtID" id="boardPrefTgt">
                                {if $boards|count}
                                    {foreach from=$boards key=boardID item=title}
                                        <option value="{$boardID}">{@$title}</option>
                                    {/foreach}
                                {/if}
                            </select>
                            {if $errorField == 'boardPrefTgt'}
                                <p class="innerError">
                                    {if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
                                    {if $errorType == 'equal'}{lang}wcf.acp.adminTools.error.equal{/lang}{/if}
                                </p>
                            {/if}
                        </div>
                        <div class="formFieldDesc hidden" id="boardPrefTgtHelpMessage">
                            {lang}wcf.acp.adminTools.toolBox.prefix.target.description{/lang}
                        </div>
                    </div>
                    <script type="text/javascript">//<![CDATA[
                        inlineHelp.register('boardPrefTgt');
                    //]]></script>

                    <div class="smallButtons">
                        <ul>
                            <li><a href="javascript:void(0);" onClick="savePrefBoard();"><img src="{@RELATIVE_WCF_DIR}icon/adminToolsRunCronS.png" alt="" /> <span>{lang}wcf.acp.adminTools.txt.execute{/lang}</span></a></li>
                        </ul>
                    </div>

                    <input type="hidden" name="packageID" value="{@PACKAGE_ID}" />
                    <input type="hidden" name="prefixAction" value="1" />
                    {@SID_INPUT_TAG}
                </form>
            </fieldset>
{/if}

<!-- GROUP PERMISSIONS ********************************* -->
            <fieldset>
                <legend>{lang}wcf.acp.adminTools.toolBox.ugrp.legend{/lang}</legend>
                <p class="description">{lang}wcf.acp.adminTools.toolBox.ugrp.legend.description{/lang}</p>
                <form method="post" name="ftbUgrp" action="index.php?form=AdminToolsToolBox">
                    <div class="formElement" id="ugrpSrcDiv">
                        <div class="formFieldLabel">
                            <label for="ugrpSrc">{lang}wcf.acp.adminTools.toolBox.ugrp.source{/lang}</label>
                        </div>
                        <div class="formField">
                            <select name="ugrpSrcID" id="ugrpSrc">
                                {if $ugrps|count}
                                    {foreach from=$ugrps item=ugrp}
                                        <option value="{@$ugrp.groupID}">{@$ugrp.groupName}</option>
                                    {/foreach}
                                {/if}
                            </select>
                            ({#$ugrps|count})
                            {if $errorField == 'ugrpSrc'}
                                <p class="innerError">
                                    {if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
                                </p>
                            {/if}
                        </div>
                        <div class="formFieldDesc hidden" id="ugrpSrcHelpMessage">
                            {lang}wcf.acp.adminTools.toolBox.ugrp.source.description{/lang}
                        </div>
                    </div>
                    <script type="text/javascript">//<![CDATA[
                        inlineHelp.register('ugrpSrc');
                    //]]></script>

                    <div class="formElement" id="ugrpTgtDiv">
                        <div class="formFieldLabel">
                            <label for="ugrpTgt">{lang}wcf.acp.adminTools.toolBox.ugrp.target{/lang}</label>
                        </div>
                        <div class="formField">
                            <select name="ugrpTgtID" id="ugrpTgt">
                                {if $ugrps|count}
                                    {foreach from=$ugrps item=ugrp}
                                        <option value="{@$ugrp.groupID}">{@$ugrp.groupName}</option>
                                    {/foreach}
                                {/if}
                            </select>
                            {if $errorField == 'ugrpTgt'}
                                <p class="innerError">
                                    {if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
                                    {if $errorType == 'equal'}{lang}wcf.acp.adminTools.error.equal{/lang}{/if}
                                </p>
                            {/if}
                        </div>
                        <div class="formFieldDesc hidden" id="ugrpTgtHelpMessage">
                            {lang}wcf.acp.adminTools.toolBox.ugrp.target.description{/lang}
                        </div>
                    </div>
                    <script type="text/javascript">//<![CDATA[
                        inlineHelp.register('ugrpTgt');
                    //]]></script>

                    <div class="formCheckBox formElement" id="ugrpUserDiv">
                        <div class="formField">
                            <label><input type="checkbox" id="ugrpUser" name="ugrpUser" value="1" /> {lang}wcf.acp.adminTools.toolBox.ugrp.user{/lang}</label>
                            {if $errorField == 'ugrpUser'}
                                <p class="innerError">
                                    {if $errorType == 'empty'}{lang}wcf.acp.adminTools.error.notSelected{/lang}{/if}
                                </p>
                            {/if}
                        </div>
                        <div class="formFieldDesc hidden" id="ugrpUserHelpMessage">
                            {lang}wcf.acp.adminTools.toolBox.ugrp.user.description{/lang}
                        </div>
                    </div>
                    <script type="text/javascript">//<![CDATA[
                        inlineHelp.register('ugrpUser');
                    //]]></script>
{if $wbbExists}
                    <div class="formCheckBox formElement" id="ugrpBoardsDiv">
                        <div class="formField">
                            <label><input type="checkbox" id="ugrpBoards" name="ugrpBoards" value="1" /> {lang}wcf.acp.adminTools.toolBox.ugrp.boards{/lang}</label>
                            {if $errorField == 'ugrpBoards'}
                                <p class="innerError">
                                    {if $errorType == 'empty'}{lang}wcf.acp.adminTools.error.notSelected{/lang}{/if}
                                </p>
                            {/if}
                        </div>
                        <div class="formFieldDesc hidden" id="ugrpBoardsHelpMessage">
                            {lang}wcf.acp.adminTools.toolBox.ugrp.boards.description{/lang}
                        </div>
                    </div>
                    <script type="text/javascript">//<![CDATA[
                        inlineHelp.register('ugrpBoards');
                    //]]></script>
{/if}
                    <div class="smallButtons">
                        <ul>
                            <li><a href="javascript:void(0);" onClick="saveUgrps();"><img src="{@RELATIVE_WCF_DIR}icon/adminToolsRunCronS.png" alt="" /> <span>{lang}wcf.acp.adminTools.txt.execute{/lang}</span></a></li>
                            <li><a href="javascript:void(0);" onClick="selCB('ftbUgrp');"><img src="{@RELATIVE_WCF_DIR}icon/defaultS.png" alt="" /></a></li>
                        </ul>
                    </div>

                    <input type="hidden" name="packageID" value="{@PACKAGE_ID}" />
                    <input type="hidden" name="ugrpAction" value="1" />
                    {@SID_INPUT_TAG}
                </form>
            </fieldset>

<!-- USER OPTIONS ************************************** -->
            <fieldset>
                <legend>{lang}wcf.acp.adminTools.toolBox.userOption.legend{/lang}</legend>
                <p class="description">{lang}wcf.acp.adminTools.toolBox.userOption.legend.description{/lang}</p>
                <form method="post" name="ftbUserOption" action="index.php?form=AdminToolsToolBox">
                    <div class="formElement" id="optionIDDiv">
                        <div class="formFieldLabel">
                            <label for="optionID">{lang}wcf.acp.adminTools.toolBox.userOption.optionID{/lang}</label>
                        </div>
                        <div class="formField">
                            <select name="optionID" id="optionID">
                                <option value="0"></option>
                                {if $userOptions|count}
                                    {foreach from=$userOptions item=option}
                                        <option value="{$option.optionID}" title="{lang}wcf.user.option.{@$option.optionName}.description{/lang}">{lang}wcf.user.option.{@$option.optionName}{/lang}</option>
                                    {/foreach}
                                {/if}
                            </select>
                            ({#$userOptions|count})
                            {if $errorField == 'optionID'}
                                <p class="innerError">
                                    {if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
                                </p>
                            {/if}
                        </div>
                        <div class="formFieldDesc hidden" id="optionIDHelpMessage">
                            {lang}wcf.acp.adminTools.toolBox.userOption.optionID.description{/lang}
                        </div>
                    </div>
                    <script type="text/javascript">//<![CDATA[
                        inlineHelp.register('optionID');
                    //]]></script>

                    <div class="formElement" id="userOptionSetOffDiv">
                        <div class="formField">
                            <label><input type="radio" id="userOptionSetOff" name="userOptionSet" value="0" checked="checked" /> {lang}wcf.acp.adminTools.toolBox.userOption.setOff{/lang}</label>
                        </div>
                        <div class="formFieldDesc hidden" id="userOptionSetOffHelpMessage">
                            {lang}wcf.acp.adminTools.toolBox.userOption.setOff.description{/lang}
                        </div>
                    </div>
                    <script type="text/javascript">//<![CDATA[
                        inlineHelp.register('userOptionSetOff');
                    //]]></script>
                    <div class="formElement" id="userOptionSetOnDiv">
                        <div class="formField">
                            <label><input type="radio" id="userOptionSetOn" name="userOptionSet" value="1" /> {lang}wcf.acp.adminTools.toolBox.userOption.setOn{/lang}</label>
                        </div>
                        <div class="formFieldDesc hidden" id="userOptionSetOnHelpMessage">
                            {lang}wcf.acp.adminTools.toolBox.userOption.setOn.description{/lang}
                        </div>
                    </div>
                    <script type="text/javascript">//<![CDATA[
                        inlineHelp.register('userOptionSetOn');
                    //]]></script>

                    <div class="formElement" id="userOptionExclUgrpsDiv">
                        <div class="formFieldLabel">
                            <label for="userOptionExclUgrps">{lang}wcf.acp.adminTools.toolBox.userOption.exclUgrps{/lang}</label>
                        </div>
                        <div class="formField">
                            <input type="text" class="inputText" name="userOptionExclUgrps" id="userOptionExclUgrps" value="{if $userOptionExclUgrps|isset}{@$userOptionExclUgrps}{else}{/if}" maxlength="254" />
                            {if $errorField == 'userOptionExclUgrps'}
                                <div class="innerError">
                                    {if $errorType == 'commaSeparatedIntList'}{lang}wcf.acp.adminTools.error.commaSeparatedIntList{/lang}{/if}
                                </div>
                            {/if}
                        </div>
                        <div class="formFieldDesc hidden" id="userOptionExclUgrpsHelpMessage">
                            {lang}wcf.acp.adminTools.toolBox.userOption.exclUgrps.description{/lang}
                        </div>
                    </div>
                    <script type="text/javascript">//<![CDATA[
                        inlineHelp.register('userOptionExclUgrps');
                    //]]></script>

                    <div class="smallButtons">
                        <ul>
                            <li><a href="javascript:void(0);" onClick="saveUseroptions();"><img src="{@RELATIVE_WCF_DIR}icon/submitS.png" alt="" /> <span>{lang}wcf.acp.adminTools.txt.save{/lang}</span></a></li>
                        </ul>
                    </div>

                    <input type="hidden" name="packageID" value="{@PACKAGE_ID}" />
                    <input type="hidden" name="userOptionAction" value="1" />
                    {@SID_INPUT_TAG}
                </form>
            </fieldset>


<!-- SPIDER ******************************************** -->
            <fieldset>
                <legend>{lang}wcf.acp.adminTools.toolBox.spider.legend{/lang}</legend>
                <p class="description">{lang}wcf.acp.adminTools.toolBox.spider.legend.description{/lang}</p>
                <p class="description">{lang}wcf.acp.adminTools.toolBox.spider.info{/lang}</p>
                <form method="post" name="ftbSpider" action="index.php?form=AdminToolsToolBox#spider">
                    <div class="formElement" id="spidersDiv">
                        <div class="formFieldLabel">
                            <label for="spiders">{lang}wcf.acp.adminTools.toolBox.spider.select{/lang}</label>
                        </div>
                        <div class="formField"><a name="spider"></a>
                            <select name="spiderID" id="spiders" onChange="this.form.submit();">
                                <option value="0">{lang}wcf.acp.adminTools.toolBox.spider.new{/lang}</option>
                                {if $spiders|count}
                                    {foreach from=$spiders item=spider}
                                        <option value="{@$spider.spiderID}"{if $spider.spiderID == $spiderID} selected="selected"{/if}>{@$spider.spiderName}</option>
                                    {/foreach}
                                {/if}
                            </select>
                            ({#$spiderCntOwn})
                        </div>
                        <div class="formFieldDesc hidden" id="spidersHelpMessage">
                            {lang}wcf.acp.adminTools.toolBox.spider.select.description{/lang}
                        </div>
                    </div>
                    <script type="text/javascript">//<![CDATA[
                        inlineHelp.register('spiders');
                    //]]></script>

                    <div class="formElement" id="spiderIdentifierDiv">
                        <div class="formFieldLabel">
                            <label for="spiderIdentifier">{lang}wcf.acp.adminTools.toolBox.spider.identifier{/lang}</label>
                        </div>
                        <div class="formField">
                            <input class="inputText" type="text" id="spiderIdentifier" name="spiderIdentifier" value="{if $spiderCur.spiderIdentifier|isset}{@$spiderCur.spiderIdentifier}{/if}" />
                            {if $errorField == 'spiderIdentifier'}
                                <p class="innerError">
                                    {if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
                                    {if $errorType == 'exists'}{lang}wcf.acp.adminTools.error.duplicateEntry{/lang}{/if}
                                </p>
                            {/if}
                        </div>
                        <div class="formFieldDesc hidden" id="spiderIdentifierHelpMessage">
                            {lang}wcf.acp.adminTools.toolBox.spider.identifier.description{/lang}
                        </div>
                    </div>
                    <script type="text/javascript">//<![CDATA[
                        inlineHelp.register('spiderIdentifier');
                    //]]></script>

                    <div class="formElement" id="spiderNameDiv">
                        <div class="formFieldLabel">
                            <label for="spiderName">{lang}wcf.acp.adminTools.toolBox.spider.name{/lang}</label>
                        </div>
                        <div class="formField">
                            <input class="inputText" type="text" id="spiderName" name="spiderName" value="{if $spiderCur.spiderName|isset}{@$spiderCur.spiderName}{/if}" />
                            {if $errorField == 'spiderName'}
                                <p class="innerError">
                                    {if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
                                </p>
                            {/if}
                        </div>
                        <div class="formFieldDesc hidden" id="spiderNameHelpMessage">
                            {lang}wcf.acp.adminTools.toolBox.spider.name.description{/lang}
                        </div>
                    </div>
                    <script type="text/javascript">//<![CDATA[
                        inlineHelp.register('spiderName');
                    //]]></script>

                    <div class="formElement" id="spiderUrlDiv">
                        <div class="formFieldLabel">
                            <label for="spiderUrl">{lang}wcf.acp.adminTools.toolBox.spider.url{/lang}</label>
                        </div>
                        <div class="formField">
                            <input class="inputText" type="text" id="spiderUrl" name="spiderUrl" value="{if $spiderCur.spiderUrl|isset}{@$spiderCur.spiderUrl}{/if}" />
                        </div>
                        <div class="formFieldDesc hidden" id="spiderUrlHelpMessage">
                            {lang}wcf.acp.adminTools.toolBox.spider.url.description{/lang}
                        </div>
                    </div>
                    <script type="text/javascript">//<![CDATA[
                        inlineHelp.register('spiderUrl');
                    //]]></script>

                    <input type="hidden" name="spiderAction" value="select">
                    <input type="hidden" name="packageID" value="{@PACKAGE_ID}">
                    {@SID_INPUT_TAG}


                    <div class="smallButtons">
                        <ul>
                            <li><a href="javascript:void(0);" onClick="syncSpider();"><img src="{@RELATIVE_WCF_DIR}icon/adminToolsRunCronS.png" alt="" /> <span>{lang}wcf.acp.adminTools.toolBox.spider.sync{/lang}</span></a></li>
                            {if !$spiderCntOwn|empty}
                                <li><a href="javascript:void(0);" onClick="exportSpider();"><img src="{@RELATIVE_WCF_DIR}icon/adminToolsExportS.png" alt="" /> <span>{lang}wcf.acp.adminTools.txt.export{/lang}</span></a></li>
                            {/if}
                            {if !$spiderID|empty}
                                <li><a href="javascript:void(0);" onClick="deleteSpider();"><img src="{@RELATIVE_WCF_DIR}icon/deleteS.png" alt="" /> <span>{lang}wcf.acp.adminTools.txt.delete{/lang}</span></a></li>
                            {/if}
                            <li><a href="javascript:void(0);" onClick="saveSpider();"><img src="{@RELATIVE_WCF_DIR}icon/submitS.png" alt="" /> <span>{lang}wcf.acp.adminTools.txt.save{/lang}</span></a></li>
                        </ul>
                    </div>
                </form>

            <br />
            <fieldset>
                <legend>{lang}wcf.acp.adminTools.toolBox.spider.import.legend{/lang}</legend>

                <form enctype="multipart/form-data" method="post" name="ftbImportSpider" action="index.php?form=AdminToolsToolBox#spider">
                    <div class="formElement" id="importSpiderDiv">
                        <div class="formFieldLabel">
                            <label for="importSpider">{lang}wcf.acp.adminTools.toolBox.spider.import{/lang}</label>
                        </div>
                        <div class="formField">
                            <input type="file" id="importSpider" name="importSpider" value="" />
                        </div>
                        <div class="formFieldDesc hidden" id="importSpiderHelpMessage">
                            {lang}wcf.acp.adminTools.toolBox.spider.import.description{/lang}
                        </div>
                    </div>
                    <script type="text/javascript">//<![CDATA[
                        inlineHelp.register('importSpider');
                    //]]></script>

                    <input type="hidden" name="spiderAction" value="import">
                    <input type="hidden" name="packageID" value="{@PACKAGE_ID}">
                    {@SID_INPUT_TAG}
                    <div class="smallButtons">
                        <ul>
                            <li><a href="javascript:void(0);" onClick="importSpider();"><img src="{@RELATIVE_WCF_DIR}icon/adminToolsImportS.png" alt="" /> <span>{lang}wcf.acp.adminTools.txt.import{/lang}</span></a></li>
                        </ul>
                    </div>
                </form>
            </fieldset>

            </fieldset>
        </div>
    </div>
{include file='footer'}

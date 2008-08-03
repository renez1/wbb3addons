{include file="documentHeader"}
<head>
	<title>{lang}wcf.user.wantedPoster.title{/lang} - {PAGE_TITLE}</title>

    {include file='headInclude' sandbox=false}
	<script type="text/javascript" src="{@RELATIVE_WCF_DIR}js/TabbedPane.class.js"></script>
	<script language="javascript">
        function confirmDel(url) {
			if(confirm('{lang}wcf.user.wantedPoster.confirmDelete{/lang}')) window.location=url;
		}
	</script>
	{if $canUseBBCodes}{include file="wysiwyg"}{/if}
</head>
<body>
{include file='header' sandbox=false}

<div id="main">
{if $user->userID == $this->getUser()->userID}
    {include file='userWantedPosterUserEdit' sandbox=false}
{else}
    {include file='userWantedPosterModEdit' sandbox=false}
{/if}
</div>
{include file='footer' sandbox=false}
</body>
</html>

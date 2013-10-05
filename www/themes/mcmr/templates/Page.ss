<!DOCTYPE html>
<!--[if !IE]><!-->
<html lang="$ContentLocale">
<!--<![endif]-->
<!--[if IE 6 ]><html lang="$ContentLocale" class="ie ie6"><![endif]-->
<!--[if IE 7 ]><html lang="$ContentLocale" class="ie ie7"><![endif]-->
<!--[if IE 8 ]><html lang="$ContentLocale" class="ie ie8"><![endif]-->
<head>
	<% base_tag %>
	<title><% if $MetaTitle %>$MetaTitle<% else %>$Title<% end_if %> &raquo; $SiteConfig.Title</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0;">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	$MetaTags(false)
	<!--[if lt IE 9]>
	<script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
	<% require themedCSS('reset') %>
	<% require themedCSS('typography') %>
	<% require themedCSS('form') %>
	<% require themedCSS('layout') %>
	<% require themedCSS('mcmr') %>
	<link rel="shortcut icon" href="$ThemeDir/images/favicon.ico" />
</head>
<body class="$ClassName">
<% include Header %>
<div class="main" role="main">
	<% if IsDev %>
		<div class="dev_warning">Development Server - Changes will not be stored!</div>
	<% end_if %>
	<div class="inner typography line">
		 $Layout
	</div>
</div>
<% include Footer %>

<% require javascript('framework/thirdparty/jquery/jquery.js') %>
</body>
</html>

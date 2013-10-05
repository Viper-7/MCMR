<header class="header" role="banner">
	<div class="inner">
		<div class="Account">
			<% if CurrentUser %>
				Welcome Back $CurrentUser.Surname
			<% else %>
				<a href="Security/Login?BackURL=$Me.Link">Login</a> or <a href="Account">Register</a>
			<% end_if %>
		</div>
		<div style="float:clear; clear:both;"></div>
		<div class="unit size4of4 lastUnit">
			<a href="$BaseHref" class="brand" rel="home">
				<h1>$SiteConfig.Title</h1>
				<% if $SiteConfig.Tagline %>
				<p>$SiteConfig.Tagline</p>
				<% end_if %>
			</a>
			<% include Navigation %>
		</div>
	</div>
</header>

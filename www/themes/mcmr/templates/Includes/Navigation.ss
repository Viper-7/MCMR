<nav class="primary">
	<span class="nav-open-button">²</span>
	<ul>
		<% loop $Menu(2) %>
			<li class="$LinkingMode"><a href="$Link" title="$Title.XML">$MenuTitle.XML</a></li>
		<% end_loop %>
		<li class="link"><a href="/Account" title="Account Settings">Account</a></li>
	</ul>
</nav>

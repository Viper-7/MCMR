<aside class="sidebar unit size1of4">
	<nav class="secondary">
		<h3>
			<a href="/">Home</a>
		</h3>
		<ul>
			<% loop Menu(1) %>
				<% if Title != "Home" %>
					<li class="$LinkingMode">
						<a href="$Link" class="$LinkingMode" title="Go to the $Title.XML page">
							<span class="arrow">&rarr;</span>
							<span class="text">$MenuTitle.XML</span>
						</a>
					</li>
				<% end_if %>
			<% end_loop %>
		</ul>
	</nav>
</aside>

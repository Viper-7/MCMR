<% loop $Menu(1) %>
	<li class="$LinkingMode">
		<a href="$Link" class="$LinkingMode" title="Go to the $Title.XML page">
			<span class="arrow">&rarr;</span>
			<span class="text">$MenuTitle.XML</span>
		</a>
	</li>
<% end_loop %>

<div class="py-0 shadow-sm" style="background-color: #fff;">
    <% loop Analyses %>
        <% if not $RememberedHidden %>
            $Me
            <% if not $Last %>
                <hr style="margin: 0px;">
            <% end_if %>
        <% end_if %>
    <% end_loop %>
</div>

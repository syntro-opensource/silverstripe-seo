<div class="form-group field health-analysis-field">

    <label class="form__field-label">$FieldTitle</label>

    <div class="form__field-holder health-analyses">
        <%-- Analyses go here --%>
        <% loop $Results %>
            <% if not $Hidden %>
                <div style="border: 0; border-left-width: 5px; border-left-style: solid;" class="rounded-0 border-$Level alert alert-$Level shadow-sm p-2  my-2">
                    $Response.RAW
                </div>
            <% end_if %>
        <% end_loop %>
    </div>
    <p class="form__field-extra-label">$RightTitle</p>
</div>

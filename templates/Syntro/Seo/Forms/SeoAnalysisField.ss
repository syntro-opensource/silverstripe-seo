<div class="form-group field health-analysis-field">

    <label class="form__field-label">$FieldTitle</label>

    <div class="form__field-holder health-analyses">
        <%-- Analyses go here --%>
        <% loop $Results %>
            <% if not $Hidden %>
                <div class="health-analysis text-$Level d-flex align-items-center mb-2">
                    <div class="health-indicator bg-secondary bg-$Level rounded-circle d-inline-block mr-2" style="width:9px;height:9px;"></div>
                    <div class="d-inline-block" style="max-width: 80%">$Response.RAW</div>
                </div>
            <% end_if %>
        <% end_loop %>
    </div>
</div>

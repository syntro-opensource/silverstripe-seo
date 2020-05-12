<div class="form-group field text">

    <label class="form__field-label">SERP Preview</label>

    <div class="form__field-holder" style="padding: 7px 1.5385rem; position: relative;">
        <div class="google-search-preview card shadow" style="background-color: #fff;">
            <div class="card-body">
                <div class="google-serp-container" style="font-family: arial,sans-serif;">
                    <div class="google-serp-container__header">
                        <div class="header__breadcrumbs" style="">
                            <cite style="
                                color: #202124;
                                font-style: normal;
                                font-size: 14px;
                                padding-top: 1px;
                                line-height: 1.3;
                            ">
                                $BaseURL
                                <span style="
                                    color: #5f6368;
                                ">
                                    <% loop Crumbs %>
                                        › $Crumb
                                    <% end_loop %>
                                </span>
                            </cite>
                        </div>
                        <h3 style="
                            margin-bottom: 3px;
                            padding-top: 4px;
                            font-size: 20px;
                            line-height: 1.3;
                            font-weight: normal;
                            color: rgb(26, 13, 171);
                        ">
                            <a href="$Page.Link" target="_blank">$Title</a>
                        </h3>

                    </div>
                    <div class="google-serp-container__content">
                        <div class="content__snippet" style="
                            line-height: 1.58;
                            color: #4d5156;
                            font-size: 14px;
                        ">
                            <% if $MetaDescription() %>
                                $MetaDescription()
                            <% else_if $FirstParagraph %>
                                $FirstParagraph
                            <% else %>
                                <em class="text-secondary">
                                    No description found for this page. <br/>
                                    Google will display content from the page, depending on the keyword.
                                </em>
                            <% end_if %>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <p class="form__field-extra-label" id="extra-label-Form_EditForm_MetaImageDefault">$RightTitle</p>
</div>

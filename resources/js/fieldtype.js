(function ($)
{
    "use strict";

    if (typeof PresentationFieldType == 'undefined')
    {
        var PresentationFieldType = {};
    }

    PresentationFieldType = Garnish.Base.extend(
    {
        init: function ()
        {

        },

        postActionRequest: function (action)
        {
            var self = this, data = {};

            Craft.postActionRequest(action, data, function (response, textStatus, jqXHR)
            {
                if (textStatus == 'success' && response.message)
                {
                    this.onSuccessResponse(response);
                }
                else
                {
                    this.onErrorResponse(jqXHR);
                }
            }, {
                complete: $.noop
            });
        },

        onSuccessResponse: function (response)
        {

        },

        onErrorResponse: function (jqXHR)
        {

        }
    });
})(jQuery);

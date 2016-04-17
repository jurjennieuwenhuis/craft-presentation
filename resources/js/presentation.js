(function ($, Craft)
{

    Craft.Presentation = Garnish.Base.extend(
    {
        init: function ()
        {
            console.log('Presentation.init() invoked');
            this.$defaultPresentationToggle = $('.presentation-default-template-toggle');
            this.$defaultPresentationContainer = $('.presentation-default-template-container');

            this.addListener(this.$defaultPresentationToggle, 'click', 'toggleDefaultTemplate');
        },

        toggleDefaultTemplate: function (event)
        {
            if (this.$defaultPresentationContainer.hasClass('hidden'))
            {
                this.$defaultPresentationContainer.removeClass('hidden');
            }
            else if (! this.$defaultPresentationToggle.is(':checked'))
            {
                this.$defaultPresentationContainer.addClass('hidden');
            }
        }
    }
    );


})(jQuery, Craft);

jQuery(function ($)
{
    new Craft.Presentation();
});

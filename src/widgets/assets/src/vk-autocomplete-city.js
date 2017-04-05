/*!
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link https://skeeks.com/
 * @copyright 2010 SkeekS
 * @date 07.03.2017
 */
(function(sx, $, _)
{
    sx.classes.VkAutocompleteCity = sx.classes.Component.extend({

        _init: function()
        {},


        _onDomReady: function()
        {
            var self = this;

            this.addTemplate();

            if (this.get('strict'))
            {
                this.jElement = $('#' + self.get('elementId'));
                this.jElementAuto = $('#' + this.get('autocompleteId'));

                this.jElementAuto.on('keyup', function()
                {
                    self.jElement.val('');
                });

                this.jElementAuto.on('change', function()
                {
                    _.delay(function()
                    {
                        if (!self.jElement.val())
                        {
                            self.jElementAuto.val('');
                        }
                    }, 200);

                });
            }
        },

        addTemplate: function()
        {
            jQuery('#' + this.get('autocompleteId')).autocomplete( 'instance' )._renderItem = function( ul, item ) {
                var title = item.title;
                var subtitle = [];
                if (item.region)
                {
                    subtitle.push(item.region);
                }

                if (item.area)
                {
                    subtitle.push(item.area);
                }

                if (subtitle.length > 0)
                {
                    title = title + ' <small>(' +  subtitle.join(' / ') + ')</small>';
                }

                return $( "<li>" )
                    .attr( "data-value", item.id )
                    .append( title )
                    .appendTo( ul );
            };
        }
    });
})(sx, sx.$, sx._);
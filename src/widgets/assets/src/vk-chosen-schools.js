/*!
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link https://skeeks.com/
 * @copyright 2010 SkeekS
 * @date 07.03.2017
 */
(function(sx, $, _)
{
    sx.classes.VkChoosenSchools = sx.classes.Component.extend({

        _onDomReady: function()
        {
            var self = this;

            this.jSelectSchool = $('#' + this.get('elementId'));
            this.defaultValue = this.get('value');

            this.jCity = $('#' + this.get('observeVkCityInputId'));
            this.jCity.on('change', function()
            {
                self.initByCity($(this).val());
            });

            if (this.jCity.val())
            {
                self.initByCity(this.jCity.val());
            }
        },

        initByCity: function(city_id)
        {
            var self = this;

            var jSelectSchool = this.jSelectSchool;
            jSelectSchool.empty().append();
            jSelectSchool.attr('disabled', 'disabled');
            jSelectSchool.empty();
            jSelectSchool.append($("<option>", {
                        'value' : ''
                    }).append('Загрузка...'));

            jSelectSchool.chosen({'placeholder_text_single': 'Загрузка...'});
            jSelectSchool.trigger("chosen:updated");

            var ajaxQuery = sx.ajax.preparePostQuery(this.get('backend'), {
                'city_id' : city_id
            });

            ajaxQuery.bind('success', function(e, data)
            {
                jSelectSchool.removeAttr('disabled');
                jSelectSchool.empty();
                _.each(data.response, function(title, id)
                {
                    jSelectSchool.append($("<option>", {
                        'value' : id
                    }).append(title));

                    if (id == self.defaultValue)
                    {
                        jSelectSchool.val(id);
                    }
                });

                jSelectSchool.trigger("chosen:updated");
            });

            ajaxQuery.execute();
        },
    });

})(sx, sx.$, sx._);
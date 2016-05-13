var plModal = {
    initiated: false,

    $modal:    null,
    $title:    null,
    $cancel:   null,
    $save:     null,
    $target:   null,
    $props:    null,

    init: function() {
        this.initiated = true;

        this.$modal    = $('#pl_modal');
        this.$title    = this.$modal.find('.modal-title');
        this.$cancel   = this.$modal.find('.modal_cancel_link');
        this.$save     = this.$modal.find('.modal_save_link');

        this.bind();
    },

    bind: function() {
        var _this = this;

        this.$modal.on('show.bs.modal', function(e) {
            _this.$target = $(e.relatedTarget);
            _this.$title.text(_this.$target.data('title'));
            _this.$props = _this.$target.closest('.grid-stack-item').find('.page-layout-widget-types');

            _this.$modal.find('.pl_modal_select_prop').each(function(){
                var current = _this.$props.find('[data-prop="'+ $(this).data('prop') +'"]').data('prop-value');
                if (current) {
                    $(this).val(current);
                    $(this).trigger('change');
                }
            });
        });

        this.$save.on('click', function() {
            //var $form = _this.$modal.find('form');

            _this.$modal.find('.pl_modal_select_prop').each(function(){
                var key = $(this).data('prop');
                var value = $(this).find('option:selected').val();
                var text = $(this).find('option:selected').text();
                _this.$props.find('[data-prop="'+key+'"]')
                    .data('prop-value', value)
                    .text(text);
            });

            $('.grid-stack').trigger('change');
            _this.$cancel.trigger('click');
        });
    },

    updateControls: function() {
        Admin.setup_select2(this.$modal);
        Admin.setup_icheck(this.$modal);
    }
};

$(function() {
    plModal.init();

    $(document).on('click', '.pl_delete_link', function(){
        if (confirm('Confirm delete')) {
            var grid = $('.grid-stack').data('gridstack');
            grid.remove_widget($(this).closest('.grid-stack-item'));
        }

        return false;
    });

    var $thisControl = $page_layout_thisControl;
    var data_input = $thisControl.find('.pageLayoutItemData input:first').val();
    var state = data_input ? JSON.parse(data_input) : {};
    var properties = page_layout_properties;
    var settings = page_layout_settings;

    $thisControl.find('.grid-stack').gridstack(settings);

    var grid = $thisControl.find('.grid-stack').data('gridstack');
    var item_template = $thisControl.find('.pageLayoutItemTemplate').html();
    grid.min_width('.grid-stack-item', settings.item_min_width);

    var add_new_widget = function(widget_id, widget_name, x, y, w, h, widget_properties) {

        var $newWidget = $(item_template);

        $newWidget.data('widget-id', widget_id);
        $newWidget.find('.page-layout-widget-title').text(widget_name);
        $newWidget.find('.page-layout-widget-types [data-prop]').each(function(){
            var prop = $(this).attr('data-prop');
            if (typeof properties[prop] != 'undefined') {
                $(this).text(properties[prop][widget_properties[prop]]);
                $(this).data('prop-value', widget_properties[prop]);
            } else {
                $(this).hide();
                $(this).parent().find('[data-prop-name="'+prop+'"]').hide();
            }
        });

        grid.add_widget($newWidget, x, y, w, h);
        grid.min_width('.grid-stack-item', settings.item_min_width);
    };

    var serialize_widgets = function (items) {
        state = $('.grid-stack .grid-stack-item:visible').map(function(){
            var $el = $(this);
            var node = $el.data('_gridstack_node');
            var props = {};
            $el.find('dd').each(function(){
                props[$(this).data('prop')] = $(this).data('prop-value');
            });
            return {
                id: $el.data('widget-id'),
                x: node.x,
                y: node.y,
                width: node.width,
                height: node.height,
                properties: props
            };
        });
        state = GridStackUI.Utils.sort(state);
        $thisControl.find('.pageLayoutItemData input:first').val(JSON.stringify(state));
    };

    var unserialize_widgets = function() {
        var items = GridStackUI.Utils.sort(state);
        grid.remove_all();
        $.each(items, function(){
            var props = typeof this.properties == 'undefined' ? {} : this.properties;
            var $sourceItem = $thisControl.find('.pageLayoutAvailableWidgets option[value="'+this.id+'"]');
            if ($sourceItem.length) {
                add_new_widget(
                    this.id,
                    $sourceItem.text(),
                    this.x, this.y, this.width, this.height, props
                );
            }
        });
    };
    unserialize_widgets();

    var get_last_y = function() {
        var items = GridStackUI.Utils.sort(state),
            max = 0;
        $.each(items, function(){
            if(this.y > max) {
                max = this.y;
            }
        });
        return max+1;
    }

    var is_container = function(array, value) {
        for(i = 0; i < array.length; i++) {
            if(array[i] == value) {
                return true;
            }
        }
        return false;
    }

    //Добавить виджет
    $thisControl.find('.pageLayoutAddWidget').click(function() {
        var widget_properties = {};
        var widget_type = $thisControl.find('.pageLayoutAvailableWidgets option:selected').data('type-widget');
        var widget_list = settings.widgets_container;
        var widget_is_container = is_container(widget_list, widget_type);

        $.each(properties, function(k,v){
            widget_properties[k] = Object.keys(v)[0];
            if(k == 'is_container' && widget_is_container) {
                widget_properties[k] = 1;
            }
        });

        var y = get_last_y();

        add_new_widget(
            $thisControl.find('.pageLayoutAvailableWidgets option:selected').val(),
            $thisControl.find('.pageLayoutAvailableWidgets option:selected').text(),
            0, y, 12, 1, widget_properties
        );
        return false;
    });

    //Изменение
    $thisControl.find('.grid-stack').on('change', function (e, items) {
        serialize_widgets(items);
    });
});

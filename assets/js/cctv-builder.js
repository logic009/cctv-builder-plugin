/**
 * CCTV Builder Frontend JavaScript
 */

(function($) {
    'use strict';

    var CCTVBuilder = {
        components: {},
        selectedItems: {},
        
        init: function() {
            this.loadComponents();
            this.bindEvents();
        },

        loadComponents: function() {
            var self = this;
            
            $.ajax({
                type: 'POST',
                url: cctvBuilderData.ajaxUrl,
                data: {
                    action: 'cctv_get_components',
                    nonce: cctvBuilderData.nonce
                },
                success: function(response) {
                    if (response.success) {
                        self.components = response.data;
                        self.renderComponents();
                    }
                },
                error: function() {
                    console.error('Failed to load components');
                }
            });
        },

        renderComponents: function() {
            var self = this;

            // Render DVR/NVR options
            if (this.components.dvr_nvr) {
                this.renderRadioOptions('dvr-nvr-options', 'dvr_nvr', this.components.dvr_nvr.items);
            }

            // Render Camera options
            if (this.components.cameras) {
                this.renderCheckboxOptions('cameras-options', 'cameras', this.components.cameras.items);
            }

            // Render Storage options
            if (this.components.storage) {
                this.renderCheckboxOptions('storage-options', 'storage', this.components.storage.items);
            }

            // Render Cables options
            if (this.components.cables) {
                this.renderCheckboxOptions('cables-options', 'cables', this.components.cables.items);
            }

            // Render Power Supply options
            if (this.components.power_supply) {
                this.renderRadioOptions('power-supply-options', 'power_supply', this.components.power_supply.items);
            }

            // Render Monitor options
            if (this.components.monitor) {
                this.renderCheckboxOptions('monitor-options', 'monitor', this.components.monitor.items);
            }
        },

        renderRadioOptions: function(containerId, category, items) {
            var html = '';
            
            items.forEach(function(item) {
                html += '<label class="component-option">';
                html += '<input type="radio" name="' + category + '" value="' + item.id + '" data-price="' + item.price + '">';
                html += '<div class="component-option-info">';
                html += '<div class="component-option-name">' + item.name + '</div>';
                html += '<div class="component-option-description">' + item.description + '</div>';
                html += '</div>';
                html += '<div class="component-option-price">$' + parseFloat(item.price).toFixed(2) + '</div>';
                html += '</label>';
            });

            $('#' + containerId).html(html);
        },

        renderCheckboxOptions: function(containerId, category, items) {
            var html = '';
            
            items.forEach(function(item) {
                html += '<label class="component-option">';
                html += '<input type="checkbox" name="' + category + '" value="' + item.id + '" data-price="' + item.price + '">';
                html += '<div class="component-option-info">';
                html += '<div class="component-option-name">' + item.name + '</div>';
                html += '<div class="component-option-description">' + item.description + '</div>';
                html += '</div>';
                html += '<div class="component-option-price">$' + parseFloat(item.price).toFixed(2) + '</div>';
                html += '</label>';
            });

            $('#' + containerId).html(html);
        },

        bindEvents: function() {
            var self = this;

            // Handle radio button changes
            $(document).on('change', 'input[type="radio"]', function() {
                self.updateSelectedItems();
                self.updateSummary();
            });

            // Handle checkbox changes
            $(document).on('change', 'input[type="checkbox"]', function() {
                self.updateSelectedItems();
                self.updateSummary();
            });

            // Add to Quote button
            $('#add-to-quote-btn').on('click', function() {
                self.saveQuote();
            });

            // Reset button
            $('#reset-builder-btn').on('click', function() {
                self.resetBuilder();
            });
        },

        updateSelectedItems: function() {
            this.selectedItems = {};

            // Get selected radio options
            $('input[type="radio"]:checked').each(function() {
                var itemId = $(this).val();
                var price = parseFloat($(this).data('price'));
                
                if (itemId) {
                    CCTVBuilder.selectedItems[itemId] = 1;
                }
            });

            // Get selected checkboxes
            $('input[type="checkbox"]:checked').each(function() {
                var itemId = $(this).val();
                var price = parseFloat($(this).data('price'));
                
                if (itemId) {
                    CCTVBuilder.selectedItems[itemId] = 1;
                }
            });
        },

        updateSummary: function() {
            var self = this;
            var itemsList = $('#selected-items');
            var html = '';
            var hasItems = false;

            // Clear previous items
            itemsList.empty();

            // Build selected items list
            $.each(this.selectedItems, function(itemId, quantity) {
                var item = self.getItemById(itemId);
                
                if (item) {
                    hasItems = true;
                    var itemTotal = item.price * quantity;
                    
                    html += '<div class="selected-item">';
                    html += '<span class="selected-item-name">' + item.name + '</span>';
                    html += '<span class="selected-item-quantity">x' + quantity + '</span>';
                    html += '<span class="selected-item-price">$' + itemTotal.toFixed(2) + '</span>';
                    html += '<button class="selected-item-remove" data-item-id="' + itemId + '">×</button>';
                    html += '</div>';
                }
            });

            if (!hasItems) {
                html = '<p class="no-items-message">No items selected yet</p>';
            }

            itemsList.html(html);

            // Calculate and update pricing
            this.calculatePrice();
        },

        getItemById: function(itemId) {
            for (var category in this.components) {
                if (this.components[category].items) {
                    for (var i = 0; i < this.components[category].items.length; i++) {
                        if (this.components[category].items[i].id === itemId) {
                            return this.components[category].items[i];
                        }
                    }
                }
            }
            return null;
        },

        calculatePrice: function() {
            var self = this;
            
            $.ajax({
                type: 'POST',
                url: cctvBuilderData.ajaxUrl,
                data: {
                    action: 'cctv_calculate_price',
                    nonce: cctvBuilderData.nonce,
                    items: JSON.stringify(this.selectedItems)
                },
                success: function(response) {
                    if (response.success) {
                        var data = response.data;
                        
                        $('#subtotal-price').text('$' + parseFloat(data.subtotal).toFixed(2));
                        $('#tax-price').text('$' + parseFloat(data.tax).toFixed(2));
                        $('#total-price').text('$' + parseFloat(data.total).toFixed(2));
                    }
                },
                error: function() {
                    console.error('Failed to calculate price');
                }
            });
        },

        saveQuote: function() {
            var self = this;

            if (Object.keys(this.selectedItems).length === 0) {
                alert('Please select at least one item');
                return;
            }

            var quoteData = {
                items: this.selectedItems,
                timestamp: new Date().toISOString()
            };

            $.ajax({
                type: 'POST',
                url: cctvBuilderData.ajaxUrl,
                data: {
                    action: 'cctv_save_quote',
                    nonce: cctvBuilderData.nonce,
                    quote: JSON.stringify(quoteData)
                },
                success: function(response) {
                    if (response.success) {
                        alert('Quote saved successfully! Quote ID: ' + response.data.quote_id);
                    } else {
                        alert('Failed to save quote: ' + response.data.message);
                    }
                },
                error: function() {
                    alert('Error saving quote');
                }
            });
        },

        resetBuilder: function() {
            // Clear all selections
            $('input[type="radio"], input[type="checkbox"]').prop('checked', false);
            
            this.selectedItems = {};
            this.updateSummary();
        }
    };

    // Initialize on document ready
    $(document).ready(function() {
        CCTVBuilder.init();
    });

})(jQuery);

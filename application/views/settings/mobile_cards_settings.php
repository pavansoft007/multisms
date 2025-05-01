<section class="panel appear-animation" data-appear-animation="<?php echo $global_config['animations']; ?>" data-appear-animation-delay="100">
    <div class="panel-heading">
        <h4 class="panel-title">
            <i class="fas fa-mobile-alt"></i> <?=translate('mobile_cards_settings')?>
        </h4>
    </div>
    <div class="panel-body">
        <?php echo form_open($this->uri->uri_string(), array('class' => 'validate form-horizontal form-bordered', 'id' => 'mobile_cards_form')); ?>
        <div class="form-group">
            <label class="col-md-3 control-label"><?=translate('user_role')?></label>
            <div class="col-md-6">
                <select name="role_id" class="form-control" id="role_id" data-plugin-selectTwo data-width="100%" required>
                    <option value=""><?=translate('select')?></option>
                    <?php foreach ($roles as $role): ?>
                    <option value="<?=$role['id']?>"><?=$role['name']?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        
        <div class="form-group" id="card_items_container" style="display: none;">
            <label class="col-md-3 control-label"><?=translate('card_items')?></label>
            <div class="col-md-6">
                <div class="checkbox-replace" id="card_items_list">
                    <!-- Card items will be loaded here via AJAX -->
                </div>
                <div class="mt-md">
                    <p class="text-muted">
                        <i class="fas fa-info-circle"></i> <?=translate('drag_to_reorder_cards')?>
                    </p>
                </div>
            </div>
        </div>
        
        <footer class="panel-footer mt-lg">
            <div class="row">
                <div class="col-md-2 col-sm-offset-3">
                    <button type="submit" class="btn btn btn-default btn-block" name="submit" value="save">
                        <i class="fas fa-plus-circle"></i> <?=translate('save');?>
                    </button>
                </div>
            </div>
        </footer>
        <?php echo form_close(); ?>
    </div>
</section>

<script type="text/javascript">
    $(document).ready(function() {
        $('#role_id').on('change', function() {
            var roleId = $(this).val();
            if (roleId) {
                $.ajax({
                    url: "<?=base_url('mobilemain/get_card_items')?>",
                    type: 'POST',
                    data: {
                        role_id: roleId
                    },
                    dataType: 'json',
                    success: function(response) {
                        var html = '<div id="sortable-cards">';
                        $.each(response.all_items, function(key, value) {
                            var checked = '';
                            if ($.inArray(key, response.selected_items) !== -1) {
                                checked = 'checked';
                            }
                            html += '<div class="checkbox-replace mt-md card-item" data-card="' + key + '">';
                            html += '<label class="i-checks">';
                            html += '<input type="checkbox" name="card_items[]" value="' + key + '" ' + checked + '>';
                            html += '<i></i> ' + value;
                            if (checked) {
                                html += ' <i class="fas fa-arrows-alt text-muted ml-xs"></i>';
                            }
                            html += '</label>';
                            html += '</div>';
                        });
                        html += '</div>';
                        $('#card_items_list').html(html);
                        $('#card_items_container').show();
                        
                        // Initialize sortable
                        if ($.fn.sortable) {
                            $("#sortable-cards").sortable({
                                items: ".card-item:has(input:checked)",
                                cursor: "move",
                                update: function(event, ui) {
                                    // Update the order of checked items
                                    console.log("Order updated");
                                    
                                    // Reorder the form inputs to match the visual order
                                    var sortedItems = [];
                                    $("#sortable-cards .card-item:has(input:checked)").each(function() {
                                        var cardItem = $(this).data('card');
                                        sortedItems.push(cardItem);
                                    });
                                    
                                    console.log("New order:", sortedItems);
                                    
                                    // Remove existing inputs
                                    $('input[name="card_items[]"]').prop('checked', false);
                                    
                                    // Re-check in the new order
                                    $.each(sortedItems, function(index, item) {
                                        $('input[name="card_items[]"][value="' + item + '"]').prop('checked', true);
                                    });
                                }
                            });
                        }
                        
                        // Add event listener for checkboxes
                        $('#card_items_list input[type="checkbox"]').on('change', function() {
                            if ($(this).is(':checked')) {
                                $(this).closest('.card-item').append(' <i class="fas fa-arrows-alt text-muted ml-xs"></i>');
                            } else {
                                $(this).closest('.card-item').find('.fa-arrows-alt').remove();
                            }
                            
                            // Refresh sortable
                            if ($.fn.sortable) {
                                $("#sortable-cards").sortable('refresh');
                            }
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', error);
                        alert('Error loading card items. Please try again.');
                    }
                });
            } else {
                $('#card_items_container').hide();
            }
        });
        
        // Form submission validation
        $('#mobile_cards_form').on('submit', function(e) {
            var roleId = $('#role_id').val();
            var cardItems = $('input[name="card_items[]"]:checked').length;
            
            if (!roleId) {
                e.preventDefault();
                alert('Please select a user role');
                return false;
            }
            
            if (cardItems === 0) {
                if (!confirm('No card items selected. This will hide all cards for this role. Continue?')) {
                    e.preventDefault();
                    return false;
                }
            }
            
            // Log the selected items for debugging
            console.log('Submitting form with role ID:', roleId);
            console.log('Selected card items:', $('input[name="card_items[]"]:checked').map(function() {
                return $(this).val();
            }).get());
            
            // Add a hidden field with the current timestamp to prevent caching issues
            $('<input>').attr({
                type: 'hidden',
                name: 'timestamp',
                value: new Date().getTime()
            }).appendTo('#mobile_cards_form');
            
            return true;
        });
    });
</script>
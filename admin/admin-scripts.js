
//Old - working code
//  jQuery(document).ready(function($) {
//     // Handle changes in the placement select to toggle the visibility of custom page select
//     $('.page-select').change(function() {
//         var type = $(this).data('type');
//         var customSelect = $(this).closest('form').find('.custom-page-select');
//         var selectedValue = $(this).val();
//         customSelect.toggle(selectedValue === 'custom_page');
//     });

//     // Append arrow icons next to each "Edit Script" button
//     $('.edit-script-button').each(function() {
//         $(this).append('<span class="pws-arrow down"></span>');
//     });

//     // Toggle visibility and fetch data for the edit form
//     $('.edit-script-button').click(function() {
//         var index = $(this).data('index');
//         var type = $(this).data('type');
//         var form = $('#script-form-' + type);
//         var buttonContainer = $(this).closest('.pws-script-entry');

//         // Ensure the form knows it's an edit, not a new entry
//         form.find('input[name="pws_script_index"]').val(index); 

//         // Move the form to the right position and toggle visibility
//         form.detach().insertAfter(buttonContainer);
//         toggleForm(form, $(this), type, index);
//     });

//     // Handle the "Add Script" button functionality
//     $('button[id^="add-script-button"]').click(function() {
//         var type = $(this).attr('id').replace('add-script-button-', '');
//         var form = $('#script-form-' + type);
//         resetForm(form); // Clear form for new entry
//         form.detach().insertBefore($(this)); // Position form above the button
//         form.slideDown(); // Display form
//         $('.edit-script-button .pws-arrow').addClass('down').removeClass('up'); // Reset all arrows to down
//         form.data('lastIndex', -1); // Reset the last index
//     });

//     function toggleForm(form, currentButton, type, index) {
//         if (form.is(':visible') && form.data('lastIndex') === index) {
//             form.slideToggle();
//             currentButton.find('.pws-arrow').toggleClass('down up');
//         } else {
//             $('.script-form').not(form).slideUp().find('.pws-arrow').addClass('down').removeClass('up');
//             currentButton.find('.pws-arrow').removeClass('down').addClass('up');
//             fetchAndShowForm(form, type, index, currentButton);
//             form.data('lastIndex', index);
//         }
//     }

//     function fetchAndShowForm(form, type, index, currentButton) {
//         $.ajax({
//             url: ajaxurl,
//             type: 'POST',
//             data: {
//                 action: 'fetch_pws_script_data',
//                 script_type: type,
//                 script_index: index
//             },
//             dataType: 'json',
//             beforeSend: function() {
//                 form.find('input, textarea, select').prop('disabled', true);
//                 $('.edit-script-button').not(currentButton).find('.pws-arrow').addClass('down').removeClass('up');
//             },
//             success: function(response) {
//                 if (response.success) {
//                     populateFormFields(form, response.data, type);
//                     form.slideDown();
//                 } else {
//                     alert('Failed to load script data: ' + response.data.message);
//                 }
//             },
//             complete: function() {
//                 form.find('input, textarea, select').prop('disabled', false);
//             },
//             error: function(xhr, status, error) {
//                 alert('Error fetching data: ' + error);
//                 form.find('input, textarea, select').prop('disabled', false);
//             }
//         });
//     }

//     function populateFormFields(form, data, type) {
//         form.find('input[name="pws_script_name"]').val(data.name);
//         form.find('textarea[name="pws_script_code"]').val(data.code);
//         form.find('select[name="pws_script_placement"]').val(data.placement).trigger('change');
//         if (data.placement === 'custom_page') {
//             form.find('.custom-page-select select').val(data.custom_page_id);
//         }
//     }

//     function resetForm(form) {
//         form.find('input[type="text"], textarea').val('');
//         form.find('select').val('global').trigger('change');
//         form.find('.custom-page-select').hide();
//     }

//     // Handle the delete script button click
//     $('.delete-script-button').click(function() {
//         var index = $(this).data('index');
//         var type = $(this).data('type');
//         if (!confirm('Are you sure you want to delete this script?')) return;

//         $.ajax({
//             url: ajaxurl,
//             type: 'POST',
//             data: {
//                 action: 'delete_pws_script_data',
//                 script_type: type,
//                 script_index: index
//             },
//             dataType: 'json',
//             success: function(response) {
//                 if (response.success) {
//                     alert('Script deleted successfully.');
//                     $('#script-entry-' + type + '-' + index).remove();
//                 } else {
//                     alert('Failed to delete script: ' + response.data.message);
//                 }
//             },
//             error: function(xhr, status, error) {
//                 alert('Error: ' + error);
//             }
//         });
//     });
// });


//New code
jQuery(document).ready(function($) {
    // Handle changes in the placement select to toggle the visibility of custom page select
    $('.page-select').change(function() {
        var type = $(this).data('type');
        var customSelect = $(this).closest('form').find('.custom-page-select');
        var selectedValue = $(this).val();
        customSelect.toggle(selectedValue === 'custom_page');
    });

    // Append arrow icons next to each "Edit Script" button
    $('.edit-script-button').each(function() {
        $(this).append('<span class="pws-arrow down"></span>');
    });

    // Toggle visibility and fetch data for the edit form
    $('.edit-script-button').click(function() {
        var index = $(this).data('index');
        var type = $(this).data('type');
        var form = $('#script-form-' + type);
        var buttonContainer = $(this).closest('.pws-script-entry');

        // Ensure the form knows it's an edit, not a new entry
        form.find('input[name="pws_script_index"]').val(index); 

        // Move the form to the right position and toggle visibility
        form.detach().insertAfter(buttonContainer);
        toggleForm(form, $(this), type, index);
    });

    // Handle the "Add Script" button functionality
    $('button[id^="add-script-button"]').click(function() {
        var type = $(this).attr('id').replace('add-script-button-', '');
        var form = $('#script-form-' + type);
        resetForm(form); // Clear form for new entry
        form.detach().insertBefore($(this)); // Position form above the button
        form.slideDown(); // Display form
        $('.edit-script-button .pws-arrow').addClass('down').removeClass('up'); // Reset all arrows to down
        form.data('lastIndex', -1); // Reset the last index
    });

    function toggleForm(form, currentButton, type, index) {
        if (form.is(':visible') && form.data('lastIndex') === index) {
            form.slideToggle();
            currentButton.find('.pws-arrow').toggleClass('down up');
        } else {
            $('.script-form').not(form).slideUp().find('.pws-arrow').addClass('down').removeClass('up');
            currentButton.find('.pws-arrow').removeClass('down').addClass('up');
            fetchAndShowForm(form, type, index, currentButton);
            form.data('lastIndex', index);
        }
    }

    function fetchAndShowForm(form, type, index, currentButton) {
        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'fetch_pws_script_data',
                script_type: type,
                script_index: index
            },
            dataType: 'json',
            beforeSend: function() {
                form.find('input, textarea, select').prop('disabled', true);
                $('.edit-script-button').not(currentButton).find('.pws-arrow').addClass('down').removeClass('up');
            },
            success: function(response) {
                if (response.success) {
                    populateFormFields(form, response.data, type);
                    form.slideDown();
                } else {
                    alert('Failed to load script data: ' + response.data.message);
                }
            },
            complete: function() {
                form.find('input, textarea, select').prop('disabled', false);
            },
            error: function(xhr, status, error) {
                alert('Error fetching data: ' + error);
                form.find('input, textarea, select').prop('disabled', false);
            }
        });
    }

    function populateFormFields(form, data, type) {
        form.find('input[name="pws_script_name"]').val(data.name);
        form.find('textarea[name="pws_script_code"]').val(data.code);
        form.find('select[name="pws_script_placement"]').val(data.placement).trigger('change');
        if (data.placement === 'custom_page') {
            form.find('.custom-page-select select').val(data.custom_page_id);
        }
    }

    function resetForm(form) {
        form.find('input[type="text"], textarea').val('');
        form.find('select').val('global').trigger('change');
        form.find('.custom-page-select').hide();
    }

    // Handle the delete script button click
    $('.delete-script-button').click(function() {
        var index = $(this).data('index');
        var type = $(this).data('type');
        if (!confirm('Are you sure you want to delete this script?')) return;

        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'delete_pws_script_data',
                script_type: type,
                script_index: index
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    alert('Script deleted successfully.');
                    $('#script-entry-' + type + '-' + index).remove();
                    // Update indices for remaining scripts
                    updateScriptIndices(type);
                } else {
                    alert('Failed to delete script: ' + response.data.message);
                }
            },
            error: function(xhr, status, error) {
                alert('Error: ' + error);
            }
        });
    });

    // Update indices for scripts after deletion
    function updateScriptIndices(type) {
        $('.edit-script-button[data-type="' + type + '"]').each(function(index) {
            $(this).data('index', index);
        });
        $('.delete-script-button[data-type="' + type + '"]').each(function(index) {
            $(this).data('index', index);
        });
    }
});


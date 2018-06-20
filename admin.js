function display(div_id) {
    $('div.admin_opt').each(function() {
        $(this).css('display','none');
    });
    $('#'.concat(div_id)).css('display','block');
}

function change_password() {
    old_pwd = $('#old_pwd').val();
    new_pwd1 = $('#new_pwd1').val();
    new_pwd2 = $('#new_pwd2').val();
    if (new_pwd1 != new_pwd2) {
        $('input.new_pwd').each(function() {
            $(this).css('border','3px solid red');
        });
    } else {
        url = 'admin_actions.php';
        post_data = {'action' : 'change_password', 'old_pwd' : old_pwd, 'new_pwd' : new_pwd1}
        $.post(url, post_data, function(data) {
            $('#change_pwd_header').html(data);
            $('#old_pwd').val('');
            $('#new_pwd1').val('');
            $('#new_pwd2').val('');
        });
    }
}


function change_password(user_id) {
    new_pwd1 = $('#new_pwd_' + user_id + ' input[name=new_pwd1]').val();
    new_pwd2 = $('#new_pwd_' + user_id + ' input[name=new_pwd2]').val();
    if (new_pwd1 != new_pwd2) {
        $('form#new_pwd_' + user_id + ' input').each(function() {
            $(this).css('border','3px solid red');
            alert('Passwords must match.');
        });
    } else {
        url = 'admin_actions.php';
        post_data = {'action' : 'change_other_password', 'user_id' : user_id, 'new_pwd' : new_pwd1};
        $.post(url, post_data, function(data) {
            alert(data);
            $('form#new_pwd_' + user_id + ' input').each(function() {
                $(this).removeAttr('style');
                $(this).val('');
            });
        });
    }
}


function delete_user(user_id) {
    if (confirm('Are you sure you want to delete ' + user_id)) {
        url = 'admin_actions.php';
        post_data = {'action' : 'delete_user', 'user_id' : user_id};
        $.post(url, post_data, function(data) {
            alert(data);
            if (data == 'User deleted.') {
                $('table#all_profiles_table tr#' + user_id).remove();
            }
        });
    }
}

function admin_create_user() {
    user_id = $('input[name=new_user_id]').val();
    priv = $('select[name=new_user_priv]').val();
    new_pwd1 = $('input[name=new_pwd1]').val();
    new_pwd2 = $('input[name=new_pwd2]').val();
    if (new_pwd1 != new_pwd2) {
        alert('Passwords must match');
    } else {
        url = 'admin_actions.php';
        post_data = {'action' : 'create_user', 'user_id' : user_id, 'new_pwd' : new_pwd1, 'priv' : priv};
        $.post(url, post_data, function(data) {
            alert(data);
            if (data === 'User created.') {
                $('input[name=new_user_id]').val('');
                $('select[name=new_user_priv]').val('');
                $('input[name=new_pwd1]').val('');
                $('input[name=new_pwd2]').val('');
                cell1 = '<td>' + user_id + '</td>';
                cell2 = '<td>' + priv.charAt(0).toUpperCase() + priv.slice(1); + '</td>';
                cell3 = '<td><form id="new_pwd_' + user_id + '">New Password: <input type="password" name="new_pwd1" />Confirm: <input type="password" name="new_pwd2" /></form></td>';
                cell4 = '<td><input type="button" value="Change password" onclick="change_password(\'' + user_id + '\')" /></td>';
                cell5 = '<td><input type="button" value="Delete user" onclick="delete_user(\'' + user_id + '\')" /></td>';
                $('table#all_profiles_table').append('<tr>' + cell1 + cell2 + cell3 + cell4 + cell5 + '</tr>');
            }
        });
    }
}

function create_category() {
    category = $('#custom_category').val();
    $('select#product_category').append('<option value = \"' + category + '\">' + category + '</option>');
    $('select#product_category').val(category);
    $('#custom_category').val('');
}

function create_product() {
    $('form#add_product').submit();
    $('input[name=product_name]').val('');
    $('select[name=product_category]').val('');
    $('textarea[name=product_desc]').val('');
    $('input[name=product_price]').val('');
    $('input[name=img_upload]').val('');
}

function admin_display_product_list() {
    url = 'admin_actions.php';
    post_data = {'action' : 'display_product_list', 'product_cat' : $('#admin_select_product_cat').val()};
    $.post(url, post_data, function(data) {
        $('#admin_products').html(data);
    });
}

function admin_product_view() {
    button = $('#product_view_button');
    if (button.val() == 'Create Product') {
        button.val('Display Products');
        $('select#product_category').val($('select#admin_select_product_cat').val());
        $('div#admin_products').css('display', 'none');
        $('div#add_product').css('display', 'block');
    } else if (button.val() == 'Display Products') {
        button.val('Create Product');
        $('div#admin_products').css('display', 'block');
        $('div#add_product').css('display', 'none');
    }
}

function delete_product(product_id) {
    url = 'admin_actions.php';
    post_data = {'action' : 'delete_product', 'product_id' : product_id};
    table = $('table#' + product_id);
    $.post(url, post_data, function(data) {
        alert(data);
        table.remove();
        admin_display_product_list();
    });
}

function edit_product(product_id) {
    button = $('table#' + product_id + ' input.edit_product_button');
    table = $('table#' + product_id);
    display_tds = table.find('td.product_display');
    edit_tds = table.find('td.product_edit');
    if (button.val() == 'Edit Product') {
        button.val('Submit Changes');
        display_tds.hide();
        edit_tds.show();
    } else if (button.val() == 'Submit Changes') {
        url = 'admin_actions.php';
        post_data = {'action' : 'edit_product', 'product_id_pk' : product_id};
        edit = false;
        //Product ID
        old_id = $('table#' + product_id + ' tr.product_id td.product_display');
        new_id = $('table#' + product_id + ' tr.product_id td.product_edit input');
        if (new_id.val() != old_id.html()) {
            old_id.html(new_id.val());
            post_data['product_id'] = new_id.val();
            edit = true;
        }
        //Product Name
        old_name = $('table#' + product_id + ' tr.product_name td.product_display');
        new_name = $('table#' + product_id + ' tr.product_name td.product_edit input');
        if (new_name.val() != old_name.html()) {
            old_name.html(new_name.val());
            post_data['product_name'] = new_name.val();
            edit = true;
        }
        //Product Category
        old_cat = $('table#' + product_id + ' tr.product_cat td.product_display');
        new_cat = $('table#' + product_id + ' tr.product_cat td.product_edit input');
        if (new_cat.val() != old_cat.html()) {
            old_cat.html(new_cat.val());
            post_data['product_cat'] = new_cat.val();
            edit = true;
        }
        //Product Description
        old_desc = $('table#' + product_id + ' tr.product_desc td.product_display');
        new_desc = $('table#' + product_id + ' tr.product_desc td.product_edit textarea');
        if (new_desc.val() != old_desc.html()) {
            old_desc.html(new_desc.val());
            post_data['product_desc'] = new_desc.val();
            edit = true;
        }
        //Product Price
        old_price = $('table#' + product_id + ' tr.product_price td.product_display');
        new_price = $('table#' + product_id + ' tr.product_price td.product_edit input');
        if (new_price.val() != old_price.html()) {
            old_price.html(new_price.val());
            post_data['product_price'] = new_price.val();
            edit = true;
        }

        if (edit === true) {
            $.post(url, post_data, function(data) {
                alert(data);
                button.val('Edit Product');
                display_tds.show();
                edit_tds.hide();
                admin_display_product_list();
            });
        }
    }
}

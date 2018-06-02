function redirect_url(page_url,blank)
{	
	if(page_url!='')
        {
            if(blank == '1'){
                window.open(page_url, '_blank');
            }else{
                window.location=page_url;
            }
             
        }
        else
        {
                return false ;
        }
}

function viewProdsearch(url) {
    var parentcat = $('#prParentGroup').val();
    var childcat = $('#prGroup').val();
    var newurl = url;
    redirect_url(newurl);
}

function ajaxKeyUpLogin(selector,event)
{
    if(event.keyCode=='13')
    {
        userLogin();
    }
}
function showMessege(){
    if($('#remember-me').is(':checked')){
        $('.login-info').show();
    }else{
        $('.login-info').hide();
    }
}

function userLogin()
{
    $("#loginUser").submit();
}

function remove_formError(fieldId,addOnFlag)
{
//    alert(fieldId);
    if(addOnFlag == 'true')
    {
        $("#"+fieldId).parent('div').parent('div').parent('div').removeClass('has-error');
    }
    else
    {
        $("#"+fieldId).parent('div').parent('div').removeClass('has-error');
    
    }
    $("#"+fieldId).parent('div').parent('div').children('.help-block').addClass('hide');
    $("#"+fieldId).parent('div').children('.help-block').addClass('hide');
}

function remove_formFieldError(fieldId)
{
//    alert(fieldId);
    $("#"+fieldId).parent('div').removeClass('has-error');
    $("#"+fieldId).parent('div').children('.help-block').addClass('hide');
}

function open_forgot_password_form()
{
    $(".forget-form").show();
    
    $(".login-form").hide();
}

function open_login_form()
{
    $(".forget-form").hide();
    
    $(".login-form").show();
}

function forgot_password()
{
    var szForgotEmail=$("#szForgotEmail").val();
    var newvalue="szForgotEmail="+szForgotEmail+"&mode=__FORGOT_PASSWORD__";
    
    if(szForgotEmail == '')
    {
        $("#forgot_email_error").html("Email address is required");
        $("#forgot_email_error").parent('span').removeClass("hide");
        $("#szForgotEmail").parent('div').parent('div').addClass("has-error");
    }
    if(szForgotEmail != '')
    {
        var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        if(!regex.test(szForgotEmail))
        {
            $("#forgot_email_error").html("Email address is not valid.");
            $("#forgot_email_error").parent('span').removeClass("hide");
            $("#szForgotEmail").parent('div').parent('div').addClass("has-error");
        }
        else
        {
           
            jQuery.post(__SITE_JS_ADMIN_PATH__+"/common_ajax.php",newvalue,function(result){
                var result_ary = result.split("||||");
                if(result_ary[0]=='SUCCESS')
                {
                    $("#szForgotEmail").val('');
                     open_login_form();
                    $("#forgot_success").removeClass("hide");
                }
                else if(result_ary[0]=='ERROR')
                {
                    $("#forgot_email_error").html("The email address you entered is not registered with the system. Please try again.");
                    $("#forgot_email_error").parent('span').removeClass("hide");
                    $("#szForgotEmail").parent('div').parent('div').addClass("has-error");
                }

            });
        }
    }
}


function openChangePasswordForm()
{
    jQuery('#loader').attr('style','display:block');
    $.post(__SITE_JS_ADMIN_PATH__+"/ajax_user.php",{mode:'__OPEN_CHANGE_PASSWORD_FORM__'},function(result){
        var result_ary = result.split("||||");
        if(result_ary[0]=='SUCCESS')
        {
            $("#my_profile").html(result_ary[1]);
            
        }
        jQuery('#loader').attr('style','display:none');

    });
}

function changePassword()
{
    var value=jQuery("#changePasswordForm").serialize();
    var newvalue=value+"&mode=__CHANGE_PASSWORD__";
    jQuery('#loader').attr('style','display:block');
    jQuery.post(__SITE_JS_ADMIN_PATH__+"/ajax_user.php",newvalue,function(result){
        var result_ary = result.split("||||");
        if(result_ary[0]=='SUCCESS')
        {
            $("#my_profile").html(result_ary[1]);
            $('#static').modal("show");
        }
        else if(result_ary[0]=='ERROR')
        {
            $("#my_profile").html(result_ary[1]);
        }
        jQuery('#loader').attr('style','display:none');
    });
}



function toggelStatus(event,id,status){
    var values = 'id='+id+'&mode=__ACTIVE_INACTIVE_USER__&status='+status;
    jQuery('#loader').attr('style','display:block');
    jQuery.post(__SITE_JS_ADMIN_PATH__+"/ajax_client.php",values,function(result){
        var result_ary = result.split("||||");
        if(result_ary[0]=='SUCCESS')
        {
            jQuery(event).toggleClass('toggle-button-selected');
            $("#page_content").html(result_ary[1]);
        }
        jQuery('#loader').attr('style','display:none');
    });
    
}


function getstate(){
    
    var value=$("#countries").val();
    var newvalue="country="+value+"&mode=__GET_STATES__";
    $('#loader').attr('style','display:block');
    $.post(__SITE_JS_ADMIN_PATH__+"/common_ajax.php",newvalue,function(result){
        var result_ary = result.split("||||");
        if(result_ary[0]=='SUCCESS')
        {
            $("#mystate").remove();
            $("#mycity").remove();
            $(result_ary[1]).insertAfter("#mycountry");
        }
        $('#loader').attr('style','display:none');
    });
}

function getcity(){
    var value=$("#states").val();
    var newvalue="state="+value+"&mode=__GET_CITIES__";
    $('#loader').attr('style','display:block');
    $.post(__SITE_JS_ADMIN_PATH__+"/common_ajax.php",newvalue,function(result){
        var result_ary = result.split("||||");
        if(result_ary[0]=='SUCCESS')
        {
            $("#mycity").remove();
            $(result_ary[1]).insertAfter("#mystate");
        }
        $('#loader').attr('style','display:none');
    });
}
function customerDetails(customerId)
{
    jQuery('#loader').attr('style','display:block');
    $.post(__SITE_JS_ADMIN_PATH__+"/common_ajax.php",{mode:'__CUSTOMER_DETAILS__',customerId:customerId},function(result){
        var result_ary = result.split("||||");
        if(result_ary[0]=='SUCCESS')
        {
            $("#popup").html(result_ary[1]);
            $('#clientStatus').modal("show");
        }
        jQuery('#loader').attr('style','display:none');

    });
}
function addNewUser(){
    
    var value=$("#addNewUserForm").serialize();
    var newvalue=value+"&mode=__ADD_NEW_USER__";
    $('#loader').attr('style','display:block');
    $.post(__SITE_JS_ADMIN_PATH__+"/common_ajax.php",newvalue,function(result){
        var result_ary = result.split("||||");
        if(result_ary[0]=='SUCCESS')
        {
            $("#page_content").html(result_ary[1]);
            $('#static').modal("show");
        }
        else if(result_ary[0]=='ERROR')
        {
            $("#page_content").html(result_ary[1]);
        }
        $('#loader').attr('style','display:none');
    });
}

function addNewProduct(){
    
    var value=$("#addNewProductForm").serialize();
    var colorval = jQuery('#prColor').val();
    var newvalue=value+"&colorval="+colorval+"&mode=__ADD_NEW_PRODUCT__";
    $('#loader').attr('style','display:block');
    $.post(__SITE_JS_ADMIN_PATH__+"/product_ajax.php",newvalue,function(result){
        var result_ary = result.split("||||");
        if(result_ary[0]=='SUCCESS')
        {
            $("#page_content").html(result_ary[1]);
            $('#static').modal("show");
        }
        else if(result_ary[0]=='ERROR')
        {
            $("#page_content").html(result_ary[1]);
        }
        $('#loader').attr('style','display:none');
    });
}

function addNewGroup(){
    
    var value=$("#addNewGroupForm").serialize();
    var newvalue=value+"&mode=__ADD_NEW_GROUP__";
    $('#loader').attr('style','display:block');
    $.post(__SITE_JS_ADMIN_PATH__+"/common_ajax.php",newvalue,function(result){
        var result_ary = result.split("||||");
        if(result_ary[0]=='SUCCESS')
        {
            $("#page_content").html(result_ary[1]);
            $('#static').modal("show");
        }
        else if(result_ary[0]=='ERROR')
        {
            $("#page_content").html(result_ary[1]);
        }
        $('#loader').attr('style','display:none');
    });
}

function addNewDriver(){
    
    var value=$("#addNewDriverForm").serialize();
   
    var newvalue=value+"&mode=__ADD_NEW_DRIVER__";
    $('#loader').attr('style','display:block');
    $.post(__SITE_JS_ADMIN_PATH__+"/common_ajax.php",newvalue,function(result){
        var result_ary = result.split("||||");
        if(result_ary[0]=='SUCCESS')
        {
            $("#page_content").html(result_ary[1]);
            $('#static').modal("show");
        }
        else if(result_ary[0]=='ERROR')
        {
            $("#page_content").html(result_ary[1]);
        }
        $('#loader').attr('style','display:none');
    });
}

function deleteDriver(driverId){
    
    
    var newvalue="&mode=__DELETE_DRIVER__&driverId="+driverId;
    
    $('#loader').attr('style','display:block');
    $.post(__SITE_JS_ADMIN_PATH__+"/common_ajax.php",newvalue,function(result){
        var result_ary = result.split("||||");
        if(result_ary[0]=='SUCCESS')
        {
            $("#page_content").html(result_ary[1]);
            $('#static').modal("show");
        }
         $('#loader').attr('style','display:none');
       
    });
}

function deleteDriverConfirmation(driverId){
    
    
    
    var newvalue="&mode=__DELETE_DRIVER_CONFIRMATION__&driverId="+driverId;
    
    $('#loader').attr('style','display:block');
    $.post(__SITE_JS_ADMIN_PATH__+"/common_ajax.php",newvalue,function(result){
        var result_ary = result.split("||||");
        if(result_ary[0]=='SUCCESS')
        {
            $("#page_content").html(result_ary[1]);
            $('#static').modal("show");
        }
         $('#loader').attr('style','display:none');
       
    });
}

function editDriver(driverId){
    
    
    var value=$("#editDriverForm").serialize();
    
    var newvalue=value+"&mode=__EDIT_DRIVER__&driverId="+driverId;;
    $('#loader').attr('style','display:block');
    $.post(__SITE_JS_ADMIN_PATH__+"/common_ajax.php",newvalue,function(result){
        var result_ary = result.split("||||");
        if(result_ary[0]=='SUCCESS')
        {
            $("#page_content").html(result_ary[1]);
            $('#static').modal("show");
        }
        else if(result_ary[0]=='ERROR')
        {
            $("#page_content").html(result_ary[1]);
        }
        $('#loader').attr('style','display:none');
    });
}

function addRunSlot(){
    
    var value=$("#addRunSlotForm").serialize();
    
    var newvalue=value+"&mode=__ADD_RUN_SLOT__";
    $('#loader').attr('style','display:block');
    $.post(__SITE_JS_ADMIN_PATH__+"/common_ajax.php",newvalue,function(result){
        var result_ary = result.split("||||");
        if(result_ary[0]=='SUCCESS')
        {
            $("#page_content").html(result_ary[1]);
            $('#static').modal("show");
        }
        else if(result_ary[0]=='ERROR')
        {
            $("#page_content").html(result_ary[1]);
        }
        $('#loader').attr('style','display:none');
    });
}

function getWeekId(weekId)
{
    var value=$("#weekDaySchedule").serialize();
    
    var newvalue=value+"&mode=__GET_CUSTOMER__&weekId="+weekId;
    jQuery('#loader').attr('style','display:block');
   $.post(__SITE_JS_ADMIN_PATH__+"/common_ajax.php",newvalue,function(result){
        var result_ary = result.split("||||");
        if(result_ary[0]=='SUCCESS')
        {
            $("#page_content").html(result_ary[1]);
        }
        jQuery('#loader').attr('style','display:none');

    });
}
function addScheduleDriver(){
  
    var value=$("#addCustomerSchedule").serialize();
    
    var newvalue=value+"&mode=__ADD_SCHEDULE_DRIVER__";
    $('#loader').attr('style','display:block');
    $.post(__SITE_JS_ADMIN_PATH__+"/common_ajax.php",newvalue,function(result){
        var result_ary = result.split("||||");
        if(result_ary[0]=='SUCCESS')
        {
            $("#page_content").html(result_ary[1]);
            $('#static').modal("show");
        }
        else if(result_ary[0]=='ERROR')
        {
            $("#page_content").html(result_ary[1]);
        }
        $('#loader').attr('style','display:none');
    });
}
function customerWeekDaySlotDetails(customerId)
{
    jQuery('#loader').attr('style','display:block');
    $.post(__SITE_JS_ADMIN_PATH__+"/common_ajax.php",{mode:'__CUSTOMER_WEEK_DAY_SLOT_DETAILS__',customerId:customerId},function(result){
        var result_ary = result.split("||||");
        if(result_ary[0]=='SUCCESS')
        {
            $("#popup").html(result_ary[1]);
            $('#customerWeekDayStatus').modal("show");
        }
        jQuery('#loader').attr('style','display:none');

    });
}

function deleteCustomer(customerId,usreId){
    
    
    var newvalue="&mode=__DELETE_CUSTOMER__&customerId="+customerId+"&usreId="+usreId;
    
   $('#loader').attr('style','display:block');
    $.post(__SITE_JS_ADMIN_PATH__+"/common_ajax.php",newvalue,function(result){
        var result_ary = result.split("||||");
        if(result_ary[0]=='SUCCESS')
        {
            $("#page_content").html(result_ary[1]);
            $('#static').modal("show");
        }
         $('#loader').attr('style','display:none');
       
    });
}

function deleteCustomerConfirmation(customerId,userId){
    
   var newvalue="&mode=__DELETE_CUSTOMER_CONFIRMATION__&customerId="+customerId+"&userId="+userId;
   
    $('#loader').attr('style','display:block');
    $.post(__SITE_JS_ADMIN_PATH__+"/common_ajax.php",newvalue,function(result){
        var result_ary = result.split("||||");
        if(result_ary[0]=='SUCCESS')
        {
            $("#page_content").html(result_ary[1]);
            $('#static').modal("show");
        }
         $('#loader').attr('style','display:none');
       
    });
}

function editUser(){
    var value=$("#editUserForm").serialize();
    var newvalue=value+"&mode=__EDIT_USER__";
    $('#loader').attr('style','display:block');
    $.post(__SITE_JS_ADMIN_PATH__+"/common_ajax.php",newvalue,function(result){
        var result_ary = result.split("||||");
        if(result_ary[0]=='SUCCESS')
        {
            $("#page_content").html(result_ary[1]);
            $('#static').modal("show");
        }
        else if(result_ary[0]=='ERROR')
        {
            $("#page_content").html(result_ary[1]);
        }
        $('#loader').attr('style','display:none');
    });
}

function editGetState(){
    var value=$("#countries").val();
    var newvalue="country="+value+"&mode=__EDIT_GET_STATES__";
    $('#loader').attr('style','display:block');
    $.post(__SITE_JS_ADMIN_PATH__+"/common_ajax.php",newvalue,function(result){
        var result_ary = result.split("||||");
        if(result_ary[0]=='SUCCESS')
        {
            $("#mystate").remove();
            $("#mycity").remove();
            $(result_ary[1]).insertAfter("#mycountry");
        }
        $('#loader').attr('style','display:none');
    });
}

function editGetCity(){
    var value=$("#states").val();
    var newvalue="state="+value+"&mode=__EDIT_GET_CITIES__";
    $('#loader').attr('style','display:block');
    $.post(__SITE_JS_ADMIN_PATH__+"/common_ajax.php",newvalue,function(result){
        var result_ary = result.split("||||");
        if(result_ary[0]=='SUCCESS')
        {
            $("#mycity").remove();
            $(result_ary[1]).insertAfter("#mystate");
        }
        $('#loader').attr('style','display:none');
    });
}

function getCustomers(prodid){
    //var value=$("#prProduct").val();
    var newvalue="prodid="+prodid+"&mode=__GET_CUSTOMERS__";
    $('#loader').attr('style','display:block');
    $.post(__SITE_JS_ADMIN_PATH__+"/product_ajax.php",newvalue,function(result){
        var result_ary = result.split("||||");
        if(result_ary[0]=='SUCCESS')
        {
            $("#page_content").html(result_ary[1]);
            $('#static').modal("show");
        }
        $('#loader').attr('style','display:none');
    });
}

function addProductPrice(){
    
    var value=$("#ProductPriceForm").serialize();
   
    var newvalue=value+"&mode=__ADD_PRODUCT_PRICE__";
    $('#loader').attr('style','display:block');
    $.post(__SITE_JS_ADMIN_PATH__+"/product_ajax.php",newvalue,function(result){
        var result_ary = result.split("||||");
        if(result_ary[0]=='SUCCESS')
        {
            $("#page_content").html(result_ary[1]);
            $('#static').modal("show");
        }
        else if(result_ary[0]=='ERROR')
        {
            $("#page_content").html(result_ary[1]);
        }
        $('#loader').attr('style','display:none');
    });
}

function editNewProduct(){
    
    var value=$("#editNewProductForm").serialize();
    var colorval = jQuery('#prColor').val();
	
	var newvalue=value+"&colorval="+colorval+"&mode=__EDIT_PRODUCT__";
    $('#loader').attr('style','display:block');
    $.post(__SITE_JS_ADMIN_PATH__+"/product_ajax.php",newvalue,function(result){
        var result_ary = result.split("||||");
        if(result_ary[0]=='SUCCESS')
        {
            $("#page_content").html(result_ary[1]);
            $('#static').modal("show");
        }
        else if(result_ary[0]=='ERROR')
        {
            $("#page_content").html(result_ary[1]);
        }
        $('#loader').attr('style','display:none');
    });
}
function deleteProduct(productId){
    
    
    var newvalue="&mode=__DELETE_PRODUCT__&productId="+productId;
    
   $('#loader').attr('style','display:block');
    $.post(__SITE_JS_ADMIN_PATH__+"/product_ajax.php",newvalue,function(result){
        var result_ary = result.split("||||");
        if(result_ary[0]=='SUCCESS')
        {
            $("#page_content").html(result_ary[1]);
            $('#static').modal("show");
        }
         $('#loader').attr('style','display:none');
       
    });
}

function deleteProductConfirmation(productId){
    
   var newvalue="&mode=__DELETE_PRODUCT_CONFIRMATION__&productId="+productId;
   
    $('#loader').attr('style','display:block');
    $.post(__SITE_JS_ADMIN_PATH__+"/product_ajax.php",newvalue,function(result){
        var result_ary = result.split("||||");
        if(result_ary[0]=='SUCCESS')
        {
            $("#page_content").html(result_ary[1]);
            $('#static').modal("show");
        }
         $('#loader').attr('style','display:none');
       
    });
}
function productDetails(productId)
{
    jQuery('#loader').attr('style','display:block');
    $.post(__SITE_JS_ADMIN_PATH__+"/product_ajax.php",{mode:'__PRODUCT_DETAILS__',productId:productId},function(result){
        var result_ary = result.split("||||");
        if(result_ary[0]=='SUCCESS')
        {
            $("#popup").html(result_ary[1]);
            $('#clientStatus').modal("show");
        }
        jQuery('#loader').attr('style','display:none');

    });
}

function updateInventory(){
    
    var value=$("#ProductinventoryForm").serialize();
    var qtyval = $('#priSmall').val();
    if(qtyval > 0){
        $('.error').hide();
    var newvalue=value+"&mode=__UPDATE_QUANTITY__";
    $('#loader').attr('style','display:block');
    $.post(__SITE_JS_ADMIN_PATH__+"/product_ajax.php",newvalue,function(result){
        var result_ary = result.split("||||");
        if(result_ary[0]=='SUCCESS')
        {
            $("#page_content").html(result_ary[1]);
            $('#static').modal("show");
        }
        else if(result_ary[0]=='ERROR')
        {
            $("#page_content").html(result_ary[1]);
        }
        $('#loader').attr('style','display:none');
    });
    }else{
        $('.error').show();
        return false;
    }
}
function sortProductListing(sortBy,sortValue)
{ 

    var newvalue="&mode=__PRODUCT_SORTING__&sortBy="+sortBy+"&sortValue="+sortValue;
    jQuery('#loader').attr('style','display:block');
    jQuery.post(__SITE_JS_ADMIN_PATH__+"/product_ajax.php",newvalue,function(result){
        var result_ary = result.split("||||");
        if(result_ary[0]=='SUCCESS')
        {
            $("#page_content").html(result_ary[1]);
            
        }
        jQuery('#loader').attr('style','display:none');
    });
}
function sortcustomersListing(sortBy,sortValue)
{ 
    
    var newvalue="&mode=__CUSTOMERS_SORTING__&sortBy="+sortBy+"&sortValue="+sortValue;
    jQuery('#loader').attr('style','display:block');
    jQuery.post(__SITE_JS_ADMIN_PATH__+"/product_ajax.php",newvalue,function(result){
        var result_ary = result.split("||||");
        if(result_ary[0]=='SUCCESS')
        {
            $("#page_content").html(result_ary[1]);
            
        }
        jQuery('#loader').attr('style','display:none');
    });
}

function productInventory(productId){
    
    var newvalue="&mode=__PRODUCT_INVONTORY__&productId="+productId;
    
   $('#loader').attr('style','display:block');
    $.post(__SITE_JS_ADMIN_PATH__+"/product_ajax.php",newvalue,function(result){
        var result_ary = result.split("||||");
        if(result_ary[0]=='SUCCESS')
        {
            $("#page_content").html(result_ary[1]);
            $('#static').modal("show");
        }
         $('#loader').attr('style','display:none');
       
    });
}

function getUserSearch()
{
    var value=jQuery("#userSearchForm").serialize();
    var newvalue=value+"&mode=__GET_USER_SEARCH__";
    jQuery('#loader').attr('style','display:block');
    jQuery.post(__SITE_JS_ADMIN_PATH__+"/product_ajax.php",newvalue,function(result){
        var result_ary = result.split("||||");
        if(result_ary[0]=='SUCCESS')
        {
            $("#page_content").html(result_ary[1]);
        }
        jQuery('#loader').attr('style','display:none');
    });
}

function searchProduct()
{
    var searchValue=jQuery("#searcProduct").serialize();
    var newvalue=searchValue+"&mode=__GET_PRODUCT_SEARCH__";
    jQuery('#loader').attr('style','display:block');
    jQuery.post(__SITE_JS_ADMIN_PATH__+"/product_ajax.php",newvalue,function(result){
        var result_ary = result.split("||||");
        if(result_ary[0]=='SUCCESS')
        {
            $("#page_content").html(result_ary[1]);
        }
        jQuery('#loader').attr('style','display:none');
    });
}
function deleteGroup(groupId){
    
    
    var newvalue="&mode=__DELETE_GROUP__&groupId="+groupId;
    
   $('#loader').attr('style','display:block');
    $.post(__SITE_JS_ADMIN_PATH__+"/common_ajax.php",newvalue,function(result){
        var result_ary = result.split("||||");
        if(result_ary[0]=='SUCCESS')
        {
            $("#page_content").html(result_ary[1]);
            $('#static').modal("show");
        }
         $('#loader').attr('style','display:none');
       
    });
}
function deleteGroupConfirmation(groupId){
    
   var newvalue="&mode=__DELETE_GROUP_CONFIRMATION__&groupId="+groupId;
   
    $('#loader').attr('style','display:block');
    $.post(__SITE_JS_ADMIN_PATH__+"/common_ajax.php",newvalue,function(result){
        var result_ary = result.split("||||");
        if(result_ary[0]=='SUCCESS')
        {
            $("#page_content").html(result_ary[1]);
            $('#static').modal("show");
        }
         $('#loader').attr('style','display:none');
       
    });
}

function editGroup(){
    
    var value=$("#editGroupForm").serialize();
    var newvalue=value+"&mode=__EDIT_GROUP__";
    $('#loader').attr('style','display:block');
    $.post(__SITE_JS_ADMIN_PATH__+"/common_ajax.php",newvalue,function(result){
        var result_ary = result.split("||||");
        if(result_ary[0]=='SUCCESS')
        {
            $("#page_content").html(result_ary[1]);
            $('#static').modal("show");
        }
        else if(result_ary[0]=='ERROR')
        {
            $("#page_content").html(result_ary[1]);
        }
        $('#loader').attr('style','display:none');
    });
}
function orderStatus(orderId,stausValue){
    
    var newvalue="orderId="+orderId+"&mode=__ORDER_STATUS__&stausValue="+stausValue;
    $('#loader').attr('style','display:block');
    $.post(__SITE_JS_ADMIN_PATH__+"/product_ajax.php",newvalue,function(result){
        var result_ary = result.split("||||");
        if(result_ary[0]=='SUCCESS')
        {
            $("#page_content").html(result_ary[1]);
            $('#static').modal("show");
        }
        $('#loader').attr('style','display:none');
    });
}

function changeOrderStaus(){
    
    var value=$("#changeOrderStatusForm").serialize();
    var newvalue=value+"&mode=__CHANGE_ORDER_STATUS__";
    $('#loader').attr('style','display:block');
    $.post(__SITE_JS_ADMIN_PATH__+"/product_ajax.php",newvalue,function(result){
        var result_ary = result.split("||||");
        if(result_ary[0]=='SUCCESS')
        {
            $("#page_content").html(result_ary[1]);
            $('#static').modal("show");
        }
         $('#loader').attr('style','display:none');
    });
}

function getOrderSearch()
{
    var value=jQuery("#orderSearchForm").serialize();
    var newvalue=value+"&mode=__GET_ORDER_SEARCH__";
    jQuery('#loader').attr('style','display:block');
    jQuery.post(__SITE_JS_ADMIN_PATH__+"/product_ajax.php",newvalue,function(result){
        var result_ary = result.split("||||");
        if(result_ary[0]=='SUCCESS')
        {
            $("#page_content").html(result_ary[1]);
        }
        else if(result_ary[0]=='ERROR')
        {
            $("#page_content").html(result_ary[1]);
        }
        $('#loader').attr('style','display:none');
        jQuery('#loader').attr('style','display:none');
    });
}

function priceManagement(productId,customerId){
   
    var newvalue="productId="+productId+"&mode=__PRICE_MANAGEMENT__&customerId="+customerId;
    $('#loader').attr('style','display:block');
    $.post(__SITE_JS_ADMIN_PATH__+"/product_ajax.php",newvalue,function(result){
        var result_ary = result.split("||||");
        if(result_ary[0]=='SUCCESS')
        {
            $("#page_content").html(result_ary[1]);
            $('#static').modal("show");
        }
        $('#loader').attr('style','display:none');
    });
}
function validatePrice (price) {
    return /^(?:\d+|\d{1,3}(?:,\d{3})+)(?:\.\d+)?$/.test(price);
}

function updatePrice(){
    
    var value=$("#priceManagementForm").serialize();
    var price = $("#priSmall").val();
    if(validatePrice(price)){
        $('.priceerr').hide();
        $("#priSmall").css('border','1px solid #c2cad8');
        var newvalue=value+"&mode=__UPDATE_PRICE__";
        $('#loader').attr('style','display:block');
        $.post(__SITE_JS_ADMIN_PATH__+"/product_ajax.php",newvalue,function(result){
            var result_ary = result.split("||||");
            if(result_ary[0]=='SUCCESS')
            {
                $("#page_content").html(result_ary[1]);
                $('#static').modal("show");
            }
            $('#loader').attr('style','display:none');
        });
    }else{
        $('.priceerr').show();
        $("#priSmall").css('border','1px solid red');
        return false;
    }

}

function getPrice(){
    
    
    var value=$("#addCartForm").serialize();
    var newvalue=value+"&mode=__GET_PRODUCT_PRICE__";
   
    $('#loader').attr('style','display:block');
    $.post(__SITE_JS_ADMIN_PATH__+"/product_ajax.php",newvalue,function(result){
        var result_ary = result.split("||||");
        if(result_ary[0]=='SUCCESS')
        {
            $("#page_content").html(result_ary[1]);
        }
         $('#loader').attr('style','display:none');
        
    });
}
function updateQuantity(){
    
   var value=$("#addCartForm").serialize();
   var newvalue=value+"&mode=__UPDATE_CART_QUANTITY__";
   
    $('#loader').attr('style','display:block');
    $.post(__SITE_JS_ADMIN_PATH__+"/product_ajax.php",newvalue,function(result){
        var result_ary = result.split("||||");
        if(result_ary[0]=='SUCCESS')
        {
            $("#page_content").html(result_ary[1]);
        }
         $('#loader').attr('style','display:none');
        
    });
}
function refreshTrolly(){
    var newvalue="mode=__REFRESH_TROLLY__";
    $.post(__SITE_JS_ADMIN_PATH__+"/product_ajax.php",newvalue,function(result){
        var result_ary = result.split("||||");
        if(result_ary[0]=='SUCCESS')
        {
            $('.trolly').html(result_ary[1]);
            return true;
        }

    });
}
function addToCart(prodid){
    $('.error'+prodid).hide();
    $('#quantity'+prodid).css('border','0px');
   var colorval = $('#colorId'+prodid).val();
   var qty = $('#quantity'+prodid).val();
   var custid = $('#customerId'+prodid).val();
   var price = $('#price'+prodid).val();
   if(!$.isNumeric(qty)){
       $('#quantity'+prodid).css('border','1px solid red');
       $('.error'+prodid).show();
       return false;
   }
   var newvalue="colorval="+colorval+"&qty="+qty+"&custid="+custid+"&price="+price+"&prodid="+prodid+"&mode=__ADD_TO_CART__";
   
    $('#loader').attr('style','display:block');
    $.post(__SITE_JS_ADMIN_PATH__+"/product_ajax.php",newvalue,function(result){
        var result_ary = result.split("||||");
        if(result_ary[0]=='SUCCESS')
        {
            /*var currenturl = window.location.href;
            redirect_url(currenturl);*/
            //alert(qty+' items added to your cart.');
            refreshTrolly();
            return true;
        }
        else if(result_ary[0]=='ERROR')
        {
            $("#page_content").html(result_ary[1]);
        }
         $('#loader').attr('style','display:none');
        
    });
}
function addColorCart(colorId,prodid){
   $('.prodcolor'+prodid).css('border','0px');
   $('#col'+prodid+'-'+colorId).css('border','1px solid #000');
   $('#colorId'+prodid).val(colorId);
   
}

function orderDocketPrint() {
    $('#label-print').hide();
    var divToPrint=document.getElementById('label-print');
    var newWin=window.open('','Print-Window');

    newWin.document.open();

    newWin.document.write('<html><body onload="window.print()">'+divToPrint.innerHTML+'</body></html>');

    newWin.document.close();

    setTimeout(function(){newWin.close();},10);
    $('#label-print').show();
    //$('#ord-docket').print();
}

function updateFrontCart(){
    $('#updatecart').submit();
}
function deleteFromCart(customerid,cartid){
   
   var newvalue="cartid="+cartid+"&mode=__DELETE_CART_ITEM__&customerid="+customerid;
   
    $('#loader').attr('style','display:block');
    $.post(__SITE_JS_ADMIN_PATH__+"/product_ajax.php",newvalue,function(result){
        var result_ary = result.split("||||");
        if(result_ary[0]=='SUCCESS')
        {
            var url = window.location.href;
            redirect_url(url);
        }
         $('#loader').attr('style','display:none');
        
    });
}
function CheckInternetConnection() {
    var online = true;
    var offline = false;
    setInterval(function(){
        var status = navigator.onLine;
        if (status) {
            if(!online){
                $('#myModal .modal-body p').html('Connected to internet. You may proceed.');
                $('#myModal').modal('show');
                setTimeout(function(){
                    $('#myModal').modal('hide');
                },5000);
                //alert("Connected to internet. You may proceed.");
                offline = false;
                online = true;
            }

        } else {
            if(!offline){
                $('#myModal .modal-body p').html('You are not connected to internet. Please check your internet connection!!!');
                $('#myModal').modal('show');
                setTimeout(function(){
                    $('#myModal').modal('hide');
                },5000);
                //alert("You are not connected to internet. Please check your internet connection!!!");
                offline = true;
                online = false;
            }
        }
    }, 3000);
}
function placeOrder(){
    $('#orderchekout').html('Processing');
    $('#orderchekout').css('pointer-events','none');
    $('#checkvalcount').val(0);
    var totalitem = $('#totalitem').val();
    for(var i=0; i<totalitem; i++){
        var qtyid = "Quantity"+i;
        $('#'+qtyid).css('border','0px');
        var qty = $('#'+qtyid).val();
        if(!$.isNumeric(qty)){
               $('#'+qtyid).css('border','1px solid red');
       }else{
           $('#checkvalcount').val(i);
       }
    }
    setTimeout(function(){
        var checkcountval = $('#checkvalcount').val();
        checkcountval= parseInt(checkcountval) + 1;
        if(checkcountval == totalitem){
            $(".error").hide();
            $('#placeorder').submit();
        }else{
            $(".error").show();
            return false;
        }
    },500);
}
function viewOrderHistory(orderId) {
   jQuery('#loader').attr('style','display:block');
    $.post(__SITE_JS_ADMIN_PATH__+"/product_ajax.php",{mode:'__VIEW_ORDER_HISTORY__',orderId:orderId},function(result){
        var result_ary = result.split("||||");
        if(result_ary[0]=='SUCCESS')
        {
            $("#popup").html(result_ary[1]);
            $('#view-history').modal("show");
        }
        jQuery('#loader').attr('style','display:none');

    });
}
function updatePassword(){
   
   var value=$("#changePasswordForm").serialize();
    var newvalue=value+"&mode=__UPDATE_PASSWORD__";
  $('#loader').attr('style','display:block');
    $.post(__SITE_JS_ADMIN_PATH__+"/product_ajax.php",newvalue,function(result){
        var result_ary = result.split("||||");
        
        if(result_ary[0]=='SUCCESS')
        {
            $("#myProfilePageBody").html(result_ary[1]);
        }
        else if(result_ary[0]=='ERROR')
        {
            $("#myProfilePageBody").html(result_ary[1]);
        }
         $('#loader').attr('style','display:none');
        
    });
}
function expandOrderHistoryInfoDetai()
{
    jQuery.post(__SITE_JS_ADMIN_PATH__+"/product_ajax.php",{mode:'EXANDD_ORDER_HISTORY_FORM'},function(result){
        var result_ary = result.split("||||");
        if(jQuery.trim(result_ary[0]) =='SUCCESS')
        {
            jQuery('#myProfilePageBody').html(result_ary[1]);
            jQuery('#addOrderHistoryInfoTab').attr('class','active');
            jQuery('#changePasswordTab').attr('class','');
            
        }
        
    });
}
function expandChangePassword()
{
    jQuery.post(__SITE_JS_ADMIN_PATH__+"/product_ajax.php",{mode:'EXANDD_CHANGE_PASSWORD_FORM'},function(result){
        var result_ary = result.split("||||");
        if(jQuery.trim(result_ary[0]) =='SUCCESS')
        {
            jQuery('#myProfilePageBody').html(result_ary[1]);
            jQuery('#addOrderHistoryInfoTab').attr('class','');
            jQuery('#changePasswordTab').attr('class','active');
            
        }
        
    });
}
function reOrderProduct(orderId){
   
   
   var newvalue="&mode=__RE_ORDER_PRODUCT__&orderId="+orderId;
   
    $('#loader').attr('style','display:block');
    $.post(__SITE_JS_ADMIN_PATH__+"/product_ajax.php",newvalue,function(result){
        var result_ary = result.split("||||");
        if(result_ary[0]=='SUCCESS')
        {
            redirect_url(result_ary[1]);
        }
         $('#loader').attr('style','display:none');
        
    });
}

function updateQuantityOnCart(qtyid,prodid,size){
    
   var qty = $("#"+qtyid).val();
   var newvalue="prodid="+prodid+"&qty="+qty+"&size="+size+"&mode=__UPDATE_CART_QUANTITY_ON_CART__";
   
    $('#loader').attr('style','display:block');
    $.post(__SITE_JS_ADMIN_PATH__+"/product_ajax.php",newvalue,function(result){
        var result_ary = result.split("||||");
        if(result_ary[0]=='SUCCESS')
        {
            $("#"+qtyid).css('border','1px solid red');
            //$(".actions").css('pointer-events','none');
            //$(".updatecart").css('pointer-events','none');
            $(".error").html("");
            $(".error").html(result_ary[1]);
        }
        else{
            $("#"+qtyid).css('border','none');
            //$(".updatecart").css('pointer-events','inherit');
            $(".error").html("");
        }
         $('#loader').attr('style','display:none');
        
    });
}
function productPagination(pageId)
{
   
   
   var newvalue="&mode=__PRODUCT_PAGINATION__&pageId="+pageId;
   
    $('#loader').attr('style','display:block');
    $.post(__SITE_JS_ADMIN_PATH__+"/product_ajax.php",newvalue,function(result){
        var result_ary = result.split("||||");
        if(result_ary[0]=='SUCCESS')
        {
            $("#page_content").html(result_ary[1]);
        }
         $('#loader').attr('style','display:none');
        
    });
}
function updateQuantityOnCartreorder(qtyid,prodid,size){
    
   var qty = $("#"+qtyid).val();
   var newvalue="prodid="+prodid+"&qty="+qty+"&size="+size+"&mode=__UPDATE_CART_QUANTITY_ON_CART__";
   
    $('#loader').attr('style','display:block');
    $.post(__SITE_JS_ADMIN_PATH__+"/product_ajax.php",newvalue,function(result){
        var result_ary = result.split("||||");
        if(result_ary[0]=='SUCCESS')
        {
            $("#"+qtyid).css('border','1px solid red');
            //$(".actions").css('pointer-events','none');
            $(".error").html("");
            $(".error").html(result_ary[1]);
            $('#loader').attr('style','display:none');
        }else{
            $("#"+qtyid).css('border','none');
            //$(".actions").css('pointer-events','inherit');
            var countcheck = $("#checkvalcount").val();
            countcheck = parseInt(countcheck) + 1;
            $("#checkvalcount").val(countcheck);
            $(".error").html("");
            $('#loader').attr('style','display:none');
        }
    });
}
function editRunSlot(){
    
    var value=$("#editRunSlotForm").serialize();
    var newvalue=value+"&mode=__EDIT_RUN_SLOT__";
    $('#loader').attr('style','display:block');
    $.post(__SITE_JS_ADMIN_PATH__+"/common_ajax.php",newvalue,function(result){
        var result_ary = result.split("||||");
       
        if(result_ary[0]=='SUCCESS')
        {
            $("#page_content").html(result_ary[1]);
            $('#static').modal("show");
        }
        else if(result_ary[0]=='ERROR')
        {
            $("#page_content").html(result_ary[1]);
        }
        $('#loader').attr('style','display:none');
    });
}

function editSlot(id)
{
    $(".detail_hide").show();
    $("#detail_"+id).hide();
    $("#detail_driver_"+id).hide();
    $(".custom_hide").hide();
    $("#input_"+id).show();
    $("#input_driver_"+id).show();
}

function saveSlot(id)
{
   
   var mon  = $("#mon_"+id).val();
   var tus = $("#tus_"+id).val();
   var wed  = $("#wed_"+id).val();
   var thurs  = $("#thurs_"+id).val();
   var fri  = $("#fri_"+id).val();
   var sat  = $("#sat_"+id).val();
   var sun  = $("#sun_"+id).val();
   
   var monDriver  = $("#mon-driver-"+id).val();
   var tuesDriver = $("#tues-driver-"+id).val();
   var wedDriver  = $("#wed-driver-"+id).val();
   var thursDriver  = $("#thurs-driver-"+id).val();
   var friDriver  = $("#fri-driver-"+id).val();
   var satDriver  = $("#sat-driver-"+id).val();
   var sunDriver  = $("#sun-driver-"+id).val();
   
    var newvalue="mon="+mon+"&tus="+tus+"&wed="+wed+"&thurs="+thurs+"&fri="+fri+"&sat="+sat+"&sun="+sun+"&customerId="+id+"&monDriver="+monDriver+"&tuesDriver="+tuesDriver+"&wedDriver="+wedDriver+"&thursDriver="+thursDriver+"&friDriver="+friDriver+"&satDriver="+satDriver+"&sunDriver="+sunDriver+"&mode=__SAVE_RUN_SLOT__";
    $('#loader').attr('style','display:block');
    $.post(__SITE_JS_ADMIN_PATH__+"/common_ajax.php",newvalue,function(result){
        var result_ary = result.split("||||");
       
        if(result_ary[0]=='SUCCESS')
        {
            $("#page_content").html(result_ary[1]);
            $('#static').modal("show");
        }
       $('#loader').attr('style','display:none');
    });
   
   
}

function deleteSlot(id)
{
   var newvalue="&customerId="+id+"&mode=__DELETE_RUN_SLOT__";
    $('#loader').attr('style','display:block');
    $.post(__SITE_JS_ADMIN_PATH__+"/common_ajax.php",newvalue,function(result){
        var result_ary = result.split("||||");
       
        if(result_ary[0]=='SUCCESS')
        {
            $("#page_content").html(result_ary[1]);
            $('#static').modal("show");
        }
       $('#loader').attr('style','display:none');
    });
   
   
}
function searchRunSlot()
{
    var value=jQuery("#runSlotSearchForm").serialize();
    var newvalue=value+"&mode=__GET_RUN_SLOT_SEARCH__";
    jQuery('#loader').attr('style','display:block');
   $.post(__SITE_JS_ADMIN_PATH__+"/common_ajax.php",newvalue,function(result){
        var result_ary = result.split("||||");
        if(result_ary[0]=='SUCCESS')
        {
            $("#page_content").html(result_ary[1]);
        }
        jQuery('#loader').attr('style','display:none');
    });
}

function editorderadmin(orderid){
    $('.error').hide();
    var newvalue="&mode=__ORDER_EDIT_ADMIN__&orderid="+orderid;
    
   $('#loader').attr('style','display:block');
    $.post(__SITE_JS_ADMIN_PATH__+"/product_ajax.php",newvalue,function(result){
        var result_ary = result.split("||||");
        if(result_ary[0]=='SUCCESS')
        {
            $("#popup").html(result_ary[1]);
            $('#static').modal("show");
        }
         $('#loader').attr('style','display:none');
       
    });
}
function statusTodispatch(orderid){
    if(orderid > 0){
        var newvalue="&mode=__STATUS_TO_DISPATCHED__&ordid="+orderid;
        $('#loader').attr('style','display:block');
        $.post(__SITE_JS_ADMIN_PATH__+"/product_ajax.php",newvalue,function(result){
            var result_ary = result.split("||||");
            if(result_ary[0]=='SUCCESS')
            {
                $("#page_content").html(result_ary[1]);
                $('#static').modal("show");
            }

            $('#loader').attr('style','display:none');
        });
    }
}
function dispatchOrder(){
    $('#loader').attr('style','display:block');
    var totalcount = $('#totcount').val();
    var ordid = $('#ordid').val();
    var err = 0;
    var emptyerr = 0;
    for(var j=0; j<totalcount; j++){
        var newqty = $('#prod'+j).val();
        if(newqty == ''){
            emptyerr++;
            $('#prod'+j).css('border','1px solid red');
        }
    }
    for(var i=0; i<totalcount; i++){
        var origqty = $('#origqty'+i).val();
        var newqty = $('#prod'+i).val();
        if(parseInt(newqty) > parseInt(origqty)){
            err++;
            $('#prod'+i).css('border','1px solid red');
        }
    }
    if(emptyerr == totalcount){
        $('#loader').attr('style','display:none');
        $('.emptyerr').show();
    }else if(err != 0){
        $('#loader').attr('style','display:none');
        $('.qtyerr').show();
    }else{
        var value=$("#OrderedinventoryForm").serialize();
    var newvalue=value+"&mode=__DISPATCHED__&counter="+totalcount+"&ordid="+ordid;
    $('#loader').attr('style','display:block');
    $.post(__SITE_JS_ADMIN_PATH__+"/product_ajax.php",newvalue,function(result){
        var result_ary = result.split("||||");
        if(result_ary[0]=='SUCCESS')
        {
            $("#page_content").html(result_ary[1]);
            $('#static').modal("show");
        }
        
        $('#loader').attr('style','display:none');
    });
    }
    
    
}
function addNewAdminUser(){
    
    var value=$("#addNewAdminUserForm").serialize();
    var newvalue=value+"&mode=__ADD_NEW_ADMIN_USER__";
    $('#loader').attr('style','display:block');
    $.post(__SITE_JS_ADMIN_PATH__+"/common_ajax.php",newvalue,function(result){
        var result_ary = result.split("||||");
        if(result_ary[0]=='SUCCESS')
        {
            $("#page_content").html(result_ary[1]);
            $('#static').modal("show");
        }
        else if(result_ary[0]=='ERROR')
        {
            $("#page_content").html(result_ary[1]);
        }
        $('#loader').attr('style','display:none');
    });
}

function deleteAdminUser(usreId){
    
    
    var newvalue="&mode=__DELETE_ADMIN_USER__&usreId="+usreId;
    
   $('#loader').attr('style','display:block');
    $.post(__SITE_JS_ADMIN_PATH__+"/common_ajax.php",newvalue,function(result){
        var result_ary = result.split("||||");
        if(result_ary[0]=='SUCCESS')
        {
            $("#page_content").html(result_ary[1]);
            $('#static').modal("show");
        }
         $('#loader').attr('style','display:none');
       
    });
}

function deleteAdminUserConfirmation(userId){
    
   var newvalue="&mode=__DELETE_ADMIN_USER_CONFIRMATION__&userId="+userId;
   
    $('#loader').attr('style','display:block');
    $.post(__SITE_JS_ADMIN_PATH__+"/common_ajax.php",newvalue,function(result){
        var result_ary = result.split("||||");
        if(result_ary[0]=='SUCCESS')
        {
            $("#page_content").html(result_ary[1]);
            $('#static').modal("show");
        }
         $('#loader').attr('style','display:none');
       
    });
}

function changePassword()
{
    jQuery('#loader').attr('style','display:block');
    var value = jQuery("#changePasswordForm").serialize();
    
    var newvalue=value+"&mode=__CHANGE_PASSWORD__";
    
    $.post(__SITE_JS_ADMIN_PATH__+"/common_ajax.php",newvalue,function(result){
        var result_ary = result.split("||||");
        if(result_ary[0]=='SUCCESS')
        {
            $("#page_content").html(result_ary[1]);
            $('#static').modal("show");
        }
        
        else if(result_ary[0]=='ERROR')
        {
            $("#page_content").html(result_ary[1]);
        }
         $('#loader').attr('style','display:none');
    });
}

function getOrderReportSearch()
{
    var value=jQuery("#orderSearchForm").serialize();
    var newvalue=value+"&mode=__GET_ORDER_REPORT_SEARCH__";
    jQuery('#loader').attr('style','display:block');
    jQuery.post(__SITE_JS_ADMIN_PATH__+"/product_ajax.php",newvalue,function(result){
        var result_ary = result.split("||||");
        if(result_ary[0]=='SUCCESS')
        {
            $("#page_content").html(result_ary[1]);
        }
        else if(result_ary[0]=='ERROR')
        {
            $("#page_content").html(result_ary[1]);
        }
        $('#loader').attr('style','display:none');
        jQuery('#loader').attr('style','display:none');
    });
}
function isDate(txtDate)
{
    var currVal = txtDate;
    if(currVal == '')
    return false;
    //Declare Regex
    var rxDatePattern = /^(\d{1,2})(\/|-)(\d{1,2})(\/|-)(\d{4})$/;
    var dtArray = currVal.match(rxDatePattern); // is format OK?
    if (dtArray == null)
    return false;
    //Checks for mm/dd/yyyy format.
    dtDay = dtArray[1];
    dtMonth = dtArray[3];
    dtYear = dtArray[5];
    if (dtMonth < 1 || dtMonth > 12)
    return false;
else if (dtDay < 1 || dtDay> 31)
    return false;
else if ((dtMonth==4 || dtMonth==6 || dtMonth==9 || dtMonth==11) && dtDay ==31)
    return false;
else if (dtMonth == 2)
    {
        var isleap = (dtYear % 4 == 0 && (dtYear % 100 != 0 || dtYear % 400 == 0));
        if (dtDay> 29 || (dtDay ==29 && !isleap))
        return false;
    }
    return true;
}
function getOrderDeatilReportSearch()
{
    var datefrom = $('#enddt').val();
    var dateto = $('#endcreatedon').val();
    if(!isDate(datefrom)){
        $('.datetoerr').remove();
        $('.datefromerr').remove();
        $('.datefrom').addClass('has-error');
        $('<span class="help-block datefromerr">Enter valid from date.</span>').insertAfter('#enddt');
        return false;
    }else if(!isDate(dateto)){
        $('.datefromerr').remove();
        $('.datetoerr').remove();
        $('.dateto').addClass('has-error');
        $('<span class="help-block datetoerr">Enter valid to date.</span>').insertAfter('#endcreatedon');
        return false;
    }else{
        var value=jQuery("#orderSearchForm").serialize();
        var newvalue=value+"&mode=__GET_ORDER_DETAIL_REPORT_SEARCH__";
        jQuery('#loader').attr('style','display:block');
        jQuery.post(__SITE_JS_ADMIN_PATH__+"/product_ajax.php",newvalue,function(result){
            var result_ary = result.split("||||");
            if(result_ary[0]=='SUCCESS')
            {
                $("#page_content").html(result_ary[1]);
            }
            else if(result_ary[0]=='ERROR')
            {
                $("#page_content").html(result_ary[1]);
            }
            $('#loader').attr('style','display:none');
            jQuery('#loader').attr('style','display:none');
        });
    }

}

function getLabelReport()
{
    var value=jQuery("#labelReportForm").serialize();
    var newvalue=value+"&mode=__GET_LABEL_REPORT__";
    jQuery('#loader').attr('style','display:block');
    jQuery.post(__SITE_JS_ADMIN_PATH__+"/product_ajax.php",newvalue,function(result){
        var result_ary = result.split("||||");
        if(result_ary[0]=='SUCCESS')
        {
            $("#popup").html(result_ary[1]);
        }
        else if(result_ary[0]=='ERROR')
        {
            $("#popup").html(result_ary[1]);
        }
        $('#loader').attr('style','display:none');
        jQuery('#loader').attr('style','display:none');
    });
}

function pendingOrder(){
    $('#loader').attr('style','display:block');
    var totalcount = $('#totcount').val();
    var ordid = $('#ordid').val();
    var err = 0;
    for(var i=0; i<totalcount; i++){
        var origqty = $('#origqty'+i).val();
        var newqty = $('#prod'+i).val()
        if(parseInt(newqty) > parseInt(origqty)){
            err++;
            $('#prod'+i).css('border','1px solid red');
        }
    }
    if(err != 0){
        $('#loader').attr('style','display:none');
        $('.qtyerr').show();
    }else{
        var value=$("#OrderedinventoryForm").serialize();
    var newvalue=value+"&mode=__PENDING__&counter="+totalcount+"&ordid="+ordid;
    $('#loader').attr('style','display:block');
    $.post(__SITE_JS_ADMIN_PATH__+"/product_ajax.php",newvalue,function(result){
        var result_ary = result.split("||||");
        if(result_ary[0]=='SUCCESS')
        {
            $("#page_content").html(result_ary[1]);
            $('#static').modal("show");
        }
        
        $('#loader').attr('style','display:none');
    });
    }
    
    
}

function GetSubcategories(){
    
    var value=$("#prParentGroup").val();
    
    var newvalue="parentcat="+value+"&mode=__GET_SUBCATEGORIES__";
    $('#loader').attr('style','display:block');
    $.post(__SITE_JS_ADMIN_PATH__+"/common_ajax.php",newvalue,function(result){
        var result_ary = result.split("||||");
        if(result_ary[0]=='SUCCESS')
        {
            $("#subcat").remove();
            $(result_ary[1]).insertAfter("#parentcat");
            $('#prName').val(result_ary[2]);
        }
        $('#loader').attr('style','display:none');
    });
    
}

function GetSubcategoriesEdit(){
    
    var value=$("#prParentGroup").val();
    
    var newvalue="parentcat="+value+"&mode=__GET_SUBCATEGORIES_EDIT__";
    $('#loader').attr('style','display:block');
    $.post(__SITE_JS_ADMIN_PATH__+"/common_ajax.php",newvalue,function(result){
        var result_ary = result.split("||||");
        if(result_ary[0]=='SUCCESS')
        {
            $("#subcat").remove();
            $(result_ary[1]).insertAfter("#parentcat");
        }
        $('#loader').attr('style','display:none');
    });
    
}

function cancelOrder(){
    $('#loader').attr('style','display:block');
    var ordid = $('#ordid').val();
    var newvalue="ordid="+ordid+"&mode=__CANCEL_ORDER__";
    $('#loader').attr('style','display:block');
    $.post(__SITE_JS_ADMIN_PATH__+"/product_ajax.php",newvalue,function(result){
        var result_ary = result.split("||||");
        if(result_ary[0]=='SUCCESS')
        {
            $("#page_content").html(result_ary[1]);
            $('#static').modal("show");
        }
        
        $('#loader').attr('style','display:none');
    });
    }
   function GetSubcategoriesSearch(){
    
    var value=$("#prParentGroup").val();
    
    var newvalue="parentcat="+value+"&mode=__PRODUCT_CATEGORY_SEARCH__";
    $('#loader').attr('style','display:block');
    $.post(__SITE_JS_ADMIN_PATH__+"/common_ajax.php",newvalue,function(result){
        var result_ary = result.split("||||");
        if(result_ary[0]=='SUCCESS')
        {
            $("#subcat").remove();
            $(result_ary[1]).insertAfter("#parentcat");
        }
        $('#loader').attr('style','display:none');
    });
    
} 
function WeekDayValue()
{
    var weekdayval=jQuery("#usWeekDay").val();
    var newvalue="weekdayval="+weekdayval+"&mode=__GET_WEEKDAY_SCHEDULE_REPORT__";
    jQuery('#loader').attr('style','display:block');
   $.post(__SITE_JS_ADMIN_PATH__+"/common_ajax.php",newvalue,function(result){
        var result_ary = result.split("||||");
        if(result_ary[0]=='SUCCESS')
        {
            $("#page_content").html(result_ary[1]);
        }
        jQuery('#loader').attr('style','display:none');
    });
}

function ImportPriceModal(){
    
    var newvalue="mode=__IMPORT_MODAL__";
    
   $('#loader').attr('style','display:block');
    $.post(__SITE_JS_ADMIN_PATH__+"/product_ajax.php",newvalue,function(result){
        var result_ary = result.split("||||");
        if(result_ary[0]=='SUCCESS')
        {
            $("#page_content").html(result_ary[1]);
            $('#static').modal("show");
        }
         $('#loader').attr('style','display:none');
       
    });
}
function ImportCustomerModal(){

    var newvalue="mode=__IMPORT_CUSTOMER_MODAL__";

    $('#loader').attr('style','display:block');
    $.post(__SITE_JS_ADMIN_PATH__+"/product_ajax.php",newvalue,function(result){
        var result_ary = result.split("||||");
        if(result_ary[0]=='SUCCESS')
        {
            $("#page_content").html(result_ary[1]);
            $('#static').modal("show");
        }
        $('#loader').attr('style','display:none');

    });
}
function deleteOrderProd(orderDetId, orderid){
    
    var newvalue="orderDetId="+orderDetId+"&orderid="+orderid+"&mode=__DELETE_ORDER_PRODUCT__";
    
   $('#loader').attr('style','display:block');
    $.post(__SITE_JS_ADMIN_PATH__+"/product_ajax.php",newvalue,function(result){
        var result_ary = result.split("||||");
        if(result_ary[0]=='SUCCESS')
        {
            $("#page_content").html(result_ary[1]);
            $('#static').modal("show");
        }
         $('#loader').attr('style','display:none');
       
    });
}

function AddOrderProd(){
    $('.add-order-form').show();
}
function GetAvailQtys(){
    
    var value=$("#add-ord-prod").val();
    
    var newvalue="prodid="+value+"&mode=__GET_AVAIL_QTY__";
    $('#loader').attr('style','display:block');
    $.post(__SITE_JS_ADMIN_PATH__+"/product_ajax.php",newvalue,function(result){
        var result_ary = result.split("||||");
        if(result_ary[0]=='SUCCESS')
        {
            $('#add-order-values-ajax').remove();
            $(result_ary[1]).insertAfter("#add-order-prod-form");
        }
        $('#loader').attr('style','display:none');
    });
    
}

function AddNewOrdProd(){
    var ordid = $('#ordid').val();
    var ordered = $('#new-ord-prd').val();
    var dispatched = $('#new-ord-prod-dispatch').val();
    var avail = $('#avail-qty').val();
    var prodid = $('#add-ord-prod').val();
    if(ordered == '' || ordered <= '0'){
        $('.add-ord-prod-err .error').html('Ordered quantity must be greater than 0.');
        $('.add-ord-prod-err .error').show();
        return false;
    }else if(parseInt(dispatched) > parseInt(avail)){
        $('.add-ord-prod-err .error').html('Dispatched quantity can not be greater than Available quantity.');
        $('.add-ord-prod-err .error').show();
        return false;
    }else{
        
    
    var newvalue="ordid="+ordid+"&ordered="+ordered+"&dispatched="+dispatched+"&avail="+avail+"&prodid="+prodid+"&mode=__SAVE_NEW_ORDER_PROD__";
    $('#loader').attr('style','display:block');
    $.post(__SITE_JS_ADMIN_PATH__+"/product_ajax.php",newvalue,function(result){
        var result_ary = result.split("||||");
        if(result_ary[0]=='SUCCESS')
        {
            $('.add-ord-prod-err .error').hide();
            $('.add-ord-prod-err .error').html('');
            $("#popup").html(result_ary[1]);
            $('#static').modal("show");
        }
        $('#loader').attr('style','display:none');
    });
    }
}

function deleteOrderProdConfirmation(orderDetId,orderid){
    
    var newvalue="orderDetId="+orderDetId+"&orderid="+orderid+"&mode=__DELETE_ORDER_PRODUCT_CONFIRMATION__";
    
   $('#loader').attr('style','display:block');
    $.post(__SITE_JS_ADMIN_PATH__+"/product_ajax.php",newvalue,function(result){
        var result_ary = result.split("||||");
        if(result_ary[0]=='SUCCESS')
        {
            $("#page_content").html(result_ary[1]);
            $('#static').modal("show");
        }
         $('#loader').attr('style','display:none');
       
    });
}

function userForgetForm()
{
   
    var newvalue="&mode=__OPEN_FORGET_FORM__";
   
    $.post(__SITE_JS_ADMIN_PATH__+"/common_ajax.php",newvalue,function(result){
        var result_ary = result.split("||||");
        if(result_ary[0]=='SUCCESS')
        {
            $("#customer-login").html(result_ary[1]);
        }
       
    });
}
function UserLogin()
{
   
    var newvalue="&mode=__OPEN_LOGIN_FORM__";
   
    $.post(__SITE_JS_ADMIN_PATH__+"/common_ajax.php",newvalue,function(result){
        var result_ary = result.split("||||");
        if(result_ary[0]=='SUCCESS')
        {
            $("#customer-login").html(result_ary[1]);
        }
       
    });
}
function userForget()
{
     var szForgotEmail=jQuery("#szEmail").val();
     var newvalue="szForgotEmail="+szForgotEmail+"&mode=__FORGOT_USER__";
   
    $.post(__SITE_JS_ADMIN_PATH__+"/common_ajax.php",newvalue,function(result){
        var result_ary = result.split("||||");
        if(result_ary[0]=='SUCCESS')
        {
            $("#customer-login").html(result_ary[1]);
        }
        else if(result_ary[0]=='ERROR')
        {
            $("#customer-login").html(result_ary[1]);
        }
    });
}

function getRunDataReport()
{
    var value=jQuery("#runDataSearchForm").serialize();
    var newvalue=value+"&mode=__GET_RUN_DATA_REPORT__";
    jQuery('#loader').attr('style','display:block');
    jQuery.post(__SITE_JS_ADMIN_PATH__+"/common_ajax.php",newvalue,function(result){
        var result_ary = result.split("||||");
        if(result_ary[0]=='SUCCESS')
        {
           $("#page_content").html(result_ary[1]);
        }
        else if(result_ary[0]=='ERROR')
        {
            $("#page_content").html(result_ary[1]);
        }
        $('#loader').attr('style','display:none');
        jQuery('#loader').attr('style','display:none');
    });
}
function editAdminUser(){
    
    var value=$("#editAdminUserForm").serialize();
    var newvalue=value+"&mode=__EDIT_ADMIN_USER__";
    $('#loader').attr('style','display:block');
    $.post(__SITE_JS_ADMIN_PATH__+"/common_ajax.php",newvalue,function(result){
        var result_ary = result.split("||||");
        if(result_ary[0]=='SUCCESS')
        {
            $("#page_content").html(result_ary[1]);
            $('#static').modal("show");
        }
        else if(result_ary[0]=='ERROR')
        {
            $("#page_content").html(result_ary[1]);
        }
        $('#loader').attr('style','display:none');
    });
}
function search_ajax_way(){
	var search_this=$("#add-ord-prod-view").val();
        var order_id=$("#ordid").val();
	$.post(__SITE_JS_ADMIN_PATH__+"/product_ajax.php", {searchit : search_this, order_id: order_id, mode:'__AJAX_SEARCH_PRODUCT__'}, function(data){
		$("#display_results").html(data);
	})
}
function fillPartIdOnInput(productid,prodDescription)
{
	$("#add-ord-prod").val(productid);
        $("#add-ord-prod-view").val(prodDescription);
	$("#display_results").html('');
        GetAvailQtys();
}
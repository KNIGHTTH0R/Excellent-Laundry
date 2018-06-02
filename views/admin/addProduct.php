<script type="text/javascript">
var __BASE_URL__ = '<?php echo __BASE_URL__;?>';
</script>
<script type="text/javascript" src="<?php echo __BASE_LAYOUT_JS__?>/jquery.fileuploadmulti.min.js"></script>
<?php 
$colorArr = $kCommon->loadColors();
$grouparr = $kCommon->loadGroups();
$iPhotoCount=$_POST['addProdAry']['iPhotoCount'];
$szIncidentPhoto=  rtrim($_POST['addProdAry']['prImage1'],';');
$photoAry=explode(';',$szIncidentPhoto);
$lastprodDet = $kProduct->loadProducts();
?>
<div class="col-md-12">
    <div class="portlet light bordered">
        <div class="portlet-title">
            <div class="caption">
                <i class="icon-equalizer font-grey-sunglo"></i>
                <span class="caption-subject font-grey-sunglo bold uppercase">Add New Product</span>
            </div>
        </div>
        <div class="portlet-body form">
            <!-- BEGIN FORM-->
            <form name="addNewProductForm" id="addNewProductForm" method="post" class="form-horizontal" enctype="multipart/form-data">
                
                <div class="form-body">
                    <div id="parentcat" class="form-group <?php if($kProduct->arErrorMessages['prParentGroup'] !=''){ ?> has-error <?php } ?>">
                        <label class="col-md-3 control-label">Category</label>
                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-dot-circle-o"></i>
                                </span>
                                <select class="form-control custom-select" name="addProdAry[prParentGroup]" onchange="GetSubcategories();"  id="prParentGroup" onfocus="remove_formError(this.id,'true')">
                                        <option value="0">Category</option>
                                        <?php 
                                            if(!empty($grouparr))
                                            {
                                                    foreach($grouparr as $group)
                                                    {
                                                            if($_POST['addProdAry']['prParentGroup']){
                                                                    $selected = ($group['id'] == $_POST['addProdAry']['prParentGroup'] ? 'selected="selected"' : '');}
                                                            
					?>
                                                            <option value="<?php echo $group['id']; ?>" <?php echo $selected;?> ><?php echo $group['name']; ?></option>
					<?php													
                                                    }
                                            }
                                            ?>
                                </select>
                            </div>
                            <?php if($kProduct->arErrorMessages['prParentGroup'] !=''){ ?> 
                            <span class="help-block"><i class="icon icon-remove-sign"></i> <?php echo $kProduct->arErrorMessages['prParentGroup']; ?></span>
                            <?php } ?>
                        </div>
                    </div>
                <div id="subcat" class="form-group <?php if($kProduct->arErrorMessages['prGroup'] !=''){ ?> has-error <?php } ?>">
                        <label class="col-md-3 control-label">Sub Category</label>
                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-dot-circle-o"></i>
                                </span>
                                <select class="form-control custom-select" name="addProdAry[prGroup]"  id="prGroup" onfocus="remove_formError(this.id,'true')">
                                        <option value="">Sub Category</option>
                                        <?php 
                                        if($_POST['addProdAry']['prParentGroup']>0){
                                            $subcatarr = $kCommon->loadGroups(0,$_POST['addProdAry']['prParentGroup']);
                                            if(!empty ($subcatarr)){
                                                foreach ($subcatarr as $subcats){ 
                                                    $selected = ($subcats['id'] == $_POST['addProdAry']['prGroup'] ? 'selected="selected"' : '');
                                                    ?>
                                                    <option value="<?php echo $subcats['id'];?>" <?php echo $selected;?>><?php echo $subcats['name'];?></option>
                                                <?php }
                                            }
                                        }
                                        ?>
                                        
                                </select>
                            </div>
                            <?php if($kProduct->arErrorMessages['prGroup'] !=''){ ?> 
                            <span class="help-block"><i class="icon icon-remove-sign"></i> <?php echo $kProduct->arErrorMessages['prGroup']; ?></span>
                            <?php } ?>
                        </div>
                    </div>
                    <div style="display: none" class="form-group <?php if($kProduct->arErrorMessages['prName'] !=''){ ?> has-error <?php } ?>">
                        <label class="col-md-3 control-label">Product Code</label>
                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-dot-circle-o"></i>
                                </span>
                                <input autocomplete="off" type="text" class="form-control" name="addProdAry[prName]" id='prName' onfocus="remove_formError(this.id,'true')" placeholder="Product Code" value='<?php echo ($_POST['addProdAry']['prName']?$_POST['addProdAry']['prName']:''); ?>'> 
                            </div>
                            <?php if($kProduct->arErrorMessages['prName'] !=''){ ?> 
                            <span class="help-block"><i class="icon icon-remove-sign"></i> <?php echo $kProduct->arErrorMessages['prName']; ?></span>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="form-group <?php if($kProduct->arErrorMessages['prDescription'] !=''){ ?> has-error <?php } ?>">
                        <label class="col-md-3 control-label">Product Name</label>
                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-dot-circle-o"></i>
                                </span>
                                <textarea class="form-control" name="addProdAry[prDescription]" id='prDescription' onfocus="remove_formError(this.id,'true')" placeholder="Product Name"><?php echo $_POST['addProdAry']['prDescription']; ?></textarea>
                            </div>
                            <?php if($kProduct->arErrorMessages['prDescription'] !=''){ ?> 
                            <span class="help-block"><i class="icon icon-remove-sign"></i> <?php echo $kProduct->arErrorMessages['prDescription']; ?></span>
                            <?php } ?>
                        </div>
                    </div>
                    

                    <div class="form-group row <?php if(trim($kProduct->arErrorMessages['prImage1']) !='') { ?> has-error <?php } ?>">
                        <span class="col-md-3 control-label required_field">Product Image</span>
                        <div class="col-md-4 szIncidentPhotoDiv" id="szIncidentPhoto_div">
                            <script>

                            $(document).ready(function()
                            {
                                var settings = {
                                        url: "<?php echo __URL_BASE_ADMIN__; ?>/product_ajax.php",
                                        method: "POST",
                                        allowedTypes:"jpg,png,gif,jpe,jpeg,JPEG,JPG,PNG",
                                        fileName: "myfile",
                                        multiple: true,
                                        onSuccess:function(files,data,xhr)
                                        {
                                            $("#status").html("<font color='green'>Upload is success</font>");
                                            data=JSON.parse(data);
                                            console.log(data);
                                            var iPhotoCount= $("#iPhotoCount").val();
                                            if(iPhotoCount < 5)
                                            {
                                                var uploadedPhotos=$("#prImage1").val();
                                                if(uploadedPhotos=='')
                                                {
                                                    $("#prImage1").val(data.name);
                                                }
                                                else
                                                {
                                                    var newValue=uploadedPhotos+';'+data.name;
                                                    $("#prImage1").val(newValue);
                                                }

                                                $("#szIncidentPhoto_upload").html(files);
                                                $("#iPhotoCount").val(parseInt($("#iPhotoCount").val())+1);
                                                $("#szIncidentPhoto_div").children('.preview_file').append(data.img_div);
                                                $("#remove_btn_"+data.rand_num).attr('onclick',"removeIncidentPhoto('"+data.rand_num+"','"+data.name+"')");
                                            }
                                        },
                                        afterUploadAll:function()
                                        {
                                            var iPhotoCount= $("#iPhotoCount").val();
//                                                                    alert("all images uploaded!!");
                                            if(iPhotoCount >= 1)
                                            {
                                                $(".szIncidentPhotoDiv .ajax-upload-dragdrop").addClass('hide');
                                            }
                                            $(".szIncidentPhotoDiv .upload-statusbar").addClass('hide');
                                            $("#szIncidentPhoto_div").children('.preview_file').removeClass('hide');
                                            $("#szIncidentPhoto_div").children('.help-block').addClass('hide');
                                            $("#szIncidentPhoto_div").parent('div').removeClass('has-error ');
                                        },
                                        onError: function(files,status,errMsg)
                                        {		
                                                $("#status").html("<font color='red'>Upload is Failed</font>");
                                        }
                                }
                                
                                $("#szIncidentPhoto_upload").uploadFile(settings);
                                <?php if($iPhotoCount >= 1) { ?> 
                                setTimeout(function() { hideUploadBtn(); }, 500);
                                function hideUploadBtn()
                                {
                                    $(".ajax-upload-dragdrop").addClass('hide');
                                }
                                
                                <?php }?>
                            });
                                function removeIncidentPhoto(ranno,ranname){
                                    $('#photoDiv_'+ranno).hide();
                                    $(".ajax-upload-dragdrop").removeClass('hide');
                                    $('#prImage1').val('');
                                }
                            </script>
                            <div class="<?php if($iPhotoCount >= 1) { ?> hide <?php }?>" id="szIncidentPhoto_upload">Upload</div>	
                            <input type="hidden" name="addProdAry[prImage1]" id="prImage1" onfocus="remove_formError('prImage1');" value="<?php echo $szIncidentPhoto;?>" /> 
                            <input type="hidden" name="addProdAry[iPhotoCount]" id="iPhotoCount" value="<?php echo (int)$iPhotoCount; ?>" /> 
                            <div class="preview_file <?php if(empty($photoAry)) { ?> hide <?php }?>">
                                <?php
                                for($i=0;$i<$iPhotoCount;$i++)
                                {
                                    $randomNum=rand().time();
                                ?>
                                    <div id="photoDiv_<?php echo $randomNum; ?>">
                                        <img class="file_preview_image" src="<?php echo __BASE_URL_PRODUCT_IMAGES__; ?>/<?php echo $photoAry[$i]; ?>" width="60" height="60"/>
                                        <a href="javascript:void(0);" id="remove_btn_<?php echo $randomNum; ?>" class="btn red-intense btn-sm" onclick="removeIncidentPhoto('<?php echo $randomNum; ?>','<?php echo $photoAry[$i]; ?>');">Remove</a>

                                    </div>
                                <?php
                                }
                                ?>
                            </div> 
                            <?php if($kProduct->arErrorMessages['prImage1']) { ?>
                                <span class="help-block"><?php echo $kProduct->arErrorMessages['prImage1']; ?></span>
                            <?php } ?>
                        </div>
                    </div>
                    
                    <div class="form-group <?php if($kProduct->arErrorMessages['prSmall'] !=''){ ?> has-error <?php } ?>">
                        <label class="col-md-3 control-label">Quantity</label>
                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-dot-circle-o"></i>
                                </span>
                                <input autocomplete="off" type="number" class="form-control" name="addProdAry[prSmall]" id='prSmall' onfocus="remove_formError(this.id,'true')" placeholder="Small Quantity" value='<?php echo ($_POST['addProdAry']['prSmall']? $_POST['addProdAry']['prSmall']:'0'); ?>'> 
                            </div>
                            <?php if($kProduct->arErrorMessages['prSmall'] !=''){ ?> 
                            <span class="help-block"><i class="icon icon-remove-sign"></i> <?php echo $kProduct->arErrorMessages['prSmall']; ?></span>
                            <?php } ?>
                        </div>
                    </div>
                    
                    <div class="form-group <?php if($kProduct->arErrorMessages['prSmallWeight'] !=''){ ?> has-error <?php } ?>">
                        <label class="col-md-3 control-label">Weight (Kg)</label>
                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-dot-circle-o"></i>
                                </span>
                                <input autocomplete="off" type="number" class="form-control" name="addProdAry[prSmallWeight]" id='prSmallWeight' onfocus="remove_formError(this.id,'true')" placeholder="Small Weight" value='<?php echo ($_POST['addProdAry']['prSmallWeight']? $_POST['addProdAry']['prSmallWeight']:'0'); ?>'> 
                            </div>
                            <?php if($kProduct->arErrorMessages['prSmallWeight'] !=''){ ?> 
                            <span class="help-block"><i class="icon icon-remove-sign"></i> <?php echo $kProduct->arErrorMessages['prSmallWeight']; ?></span>
                            <?php } ?>
                        </div>
                    </div>
                    
                    <div class="form-group <?php if($kProduct->arErrorMessages['prColor'] !=''){ ?> has-error <?php } ?>">
                        <label class="col-md-3 control-label">Colors</label>
                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-dot-circle-o"></i>
                                </span>
                                <select class="bs-select form-control" multiple name="addProdAry[prColor][]" id="prColor" onfocus="remove_formError(this.id,'true')">
                                           
                                            <?php 
                                            if(!empty($colorArr))
                                            {
                                                    foreach($colorArr as $color)
                                                    {
                                                            if($_POST['addProdAry']['prColor']){
                                                                    $selected = ($color['id'] == $_POST['addProdAry']['prColor'] ? 'selected="selected"' : '');	
                                                            }
                                                            /*elseif($locId)
                                                                    $selected = ($color['id'] == $locId ? 'selected="selected"' : '');*/
                                                             //echo '<option value="'.$color['id'].'" '.$selected.'>'.$color['name'].'</option>';																	

                                                      ?>     
															<option value="<?php echo $color['id']; ?>" <?php if(!empty($_POST['addProdAry']['prColor'])){ if(in_array($color['id'],$_POST['addProdAry']['prColor'])) { ?> selected="selected" <?php } } ?>><?php echo $color['name']; ?></option>
															
                                                   <?php }
                                            }
                                            ?>
                                </select>
                                
                            </div>
                            <?php if($kProduct->arErrorMessages['prColor'] !=''){ ?> 
                            <span class="help-block"><i class="icon icon-remove-sign"></i> <?php echo $kProduct->arErrorMessages['prColor']; ?></span>
                            <?php } ?>
                        </div>
                    </div>
                    
                </div>
                <div class="form-actions">
                    <div class="row">
                        <div class="col-md-offset-3 col-md-4">
                            <button class="btn blue" onclick="addNewProduct(); return false;">Save</button>
                            <a class="btn default" href="<?php echo __VIEW_PRODUCTS_LIST_URL__; ?>">Cancel</a>
                        </div>
                    </div>
                </div>
            </form>
            <!-- END FORM-->
        </div>
    </div>
</div>

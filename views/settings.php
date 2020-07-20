<div class="m-b-40">
    <h5 class="fs-16 fw-4 text-info m-b-20"><i class="fas fa-caret-right"></i> <?php _e('PayTr onetime')?></h5>
    <div class="form-group">
        <label for="status"><?php _e('PAYTR')?></label>
        <div>
            <label class="i-radio i-radio--tick i-radio--brand m-r-10">
                <input type="radio" name="paytr_status" <?php _e( get_option('paytr_status', 0)  == 1?"checked":"" )?> value="1"> <?php _e('Enable')?>
                <span></span>
            </label>
            <label class="i-radio i-radio--tick i-radio--brand m-r-10">
                <input type="radio" name="paytr_status" <?php _e( get_option('paytr_status', 0)  == 0?"checked":"" )?> value="0"> <?php _e('Disable')?>
                <span></span>
            </label>
        </div>
    </div>
    <div class="form-group">
        <label for="paytr_merchant_id"><?php _e('paytr_merchant_id')?></label>
        <input type="text" class="form-control" id="paytr_merchant_id" name="paytr_merchant_id" value="<?php _e( get_option('paytr_merchant_id', '') )?>">
    </div>
    <div class="form-group">
        <label for="paytr_merchant_key"><?php _e('paytr_merchant_key')?></label>
        <input type="text" class="form-control" id="paytr_merchant_key" name="paytr_merchant_key" value="<?php _e( get_option('paytr_merchant_key', '') )?>">
    </div>
    <div class="form-group">
        <label for="paytr_merchant_salt"><?php _e('paytr_merchant_salt')?></label>
        <input type="text" class="form-control" id="paytr_merchant_salt" name="paytr_merchant_salt" value="<?php _e( get_option('paytr_merchant_salt', '') )?>">
    </div>


    <div class="form-group">
        <label for="status"><?php _e('Test_Mode')?></label>
        <div>
            <label class="i-radio i-radio--tick i-radio--brand m-r-10">
                <input type="radio" name="paytr_test_mode" <?php _e( get_option('paytr_test_mode', 0)  == 1?"checked":"" )?> value="1"> <?php _e('Enable')?>
                <span></span>
            </label>
            <label class="i-radio i-radio--tick i-radio--brand m-r-10">
                <input type="radio" name="paytr_test_mode" <?php _e( get_option('paytr_test_mode', 0)  == 0?"checked":"" )?> value="0"> <?php _e('Disable')?>
                <span></span>
            </label>
        </div>
    </div>

</div>







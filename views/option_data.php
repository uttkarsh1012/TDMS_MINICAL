<div class="app-page-title">
    <div class="page-title-wrapper">
        <div class="page-title-heading">
            <div class="page-title-icon">
                <i class="pe-7s-diskette text-success"></i>
            </div>
            <div><?php echo l('minical-extension-boilerplate/Bolierplate Extenstion'); ?>

            </div>
        </div>
    </div>
</div>
<div class="topnav nav-color mb-3">
    <ul>
        <li><a class="<?php if($this->uri->segment(1) == 'sample_page') echo 'active'; ?>"
                href="<?php echo base_url().'sample_page'?>"><?php echo l('minical-extension-boilerplate/Blank Page', true); ?></a>
        </li>
        <li><a class="<?php if($this->uri->segment(1) == 'bookings_list') echo 'active'; ?>"
                href="<?php echo base_url().'bookings_list'?>"><?php echo l('minical-extension-boilerplate/Booking list', true); ?></a>
        </li>
        <li><a class="<?php if($this->uri->segment(1) == 'customer_list') echo 'active'; ?>"
                href="<?php echo base_url().'customer_list'?>"><?php echo l('minical-extension-boilerplate/Customer List', true); ?></a>
        </li>
        <li><a class="<?php if($this->uri->segment(1) == 'post_data') echo 'active'; ?>"
                href="<?php echo base_url().'post_data'?>"><?php echo l('minical-extension-boilerplate/Post Custom data', true); ?></a>
        </li>
        <li><a class="<?php if($this->uri->segment(1) == 'option_data') echo 'active'; ?>"
                href="<?php echo base_url().'option_data'?>"><?php echo l('minical-extension-boilerplate/Options Custom data', true); ?></a>
        </li>
        <li><a class="<?php if($this->uri->segment(1) == 'info_page') echo 'active'; ?>"
                href="<?php echo base_url().'info_page'?>"><?php echo l('minical-extension-boilerplate/Document', true); ?></a>
        </li>
    </ul>
</div>

<div class="main-card card">
    <div class="card-body">
        <h5>This is a sample page of CRUD(Create, read, update and delete) for custom data (options-table). It's just
            how
            you can handle the custom data in miniCal. You can either modify it or delete it.</h5>
        <hr>
        <div><button class="pull-right btn btn-primary" id="add_option_model_button">Add Option</button></div>
        <br><br>
        <div>
            <table id="option_list" class="display" style="width:100%">
                <thead>
                    <tr>
                        <th class=" text-center">Option ID</th>
                        <th class=" text-center">Option Name</th>
                        <th class=" text-center">Option Value</th>
                        <th class=" text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        if (isset($options) && $options):
                        foreach ($options as $option) : 
                        if (isset($option['option_id'])):
                    ?>

                    <tr class=''>
                        <td class="text-center"><?php echo $option['option_id']; ?></td>
                        <td class="text-center">
                            <?php echo $option['option_name'] ? $option['option_name'] : ' '; ?>
                        </td>
                        <td class="text-center" style="width:auto"><?php echo $option['option_value'] ?? '';?></td>
                        <td class=" text-center">
                            <button class="btn btn-primary edit_option_button"
                                id="<?php echo $option['option_name']; ?>">Edit</button>
                            <button class="btn btn-danger delete_option_button"
                                id="<?php echo $option['option_name']; ?>">Delete</button>
                        </td>
                    </tr>
                    <?php 
                endif;
                endforeach;
                else:
                 ?>
                    <tr class='' data-booking-id=''>
                        <td></td>
                        <td></td>
                        <td class="text-center">
                            <h3><?php echo l('minical-extension-boilerplate/No data', true);?></h3>
                        </td>
                        <td></td>
                        <td></td>
                    </tr>
                    <?php 
                    endif;
                ?>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- and option model-->
<div class="modal" id="add_option_model" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Custom Data (Option)</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group row">
                        <label for="option_name"
                            class="col-sm-2 col-form-label"><?php echo l('minical-extension-boilerplate/Option Name');?><span
                                class="required">*</span></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="option_name" placeholder="Enter Option Name">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="option_value"
                            class="col-sm-2 col-form-label"><?php echo l('minical-extension-boilerplate/Option Value');?></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="option_value" placeholder="Enter Option Value">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label form-check-label" for="autoload">
                            <?php echo l('minical-extension-boilerplate/Autoload');?>
                        </label>
                        <div class="col-sm-10">
                            <input class="form-check-input autoload" type="checkbox" value="" id="autoload">
                        </div>
                    </div>
                </form>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary"
                    id="add_option"><?php echo l('minical-extension-boilerplate/Add Option');?></button>

                <button type="button" class="btn btn-primary"
                    id="update_option"><?php echo l('minical-extension-boilerplate/Update Option');?></button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
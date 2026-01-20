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
        <h5>This is a sample page of CRUD(Create, read, update and delete) for custom data (post-table). It's just how
            you can handle the custom data in miniCal. You can either modify it or delete it.</h5><br />
        <hr>
        <div><button class="pull-right btn btn-primary" id="add_post_model_button">Add Post</button></div><br /><br>
        <table id="post_list" class="display" style="width:100%">
            <thead>
                <tr>
                    <th class=" text-center">Post ID</th>
                    <th class=" text-center">Title</th>
                    <th class=" text-center">Content</th>
                    <th class=" text-center">Status</th>
                    <th class=" text-center">Type</th>
                    <th class=" text-center">Mime type</th>
                    <th class=" text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                        if (isset($posts) && $posts):
                        foreach ($posts as $post) : 
                        if (isset($post['post_id'])):
                    ?>

                <tr class=''>
                    <td class="text-center"><?php echo $post['post_id']; ?></td>
                    <td class="text-center">
                        <?php echo $post['post_title'] ? $post['post_title'] : ' '; ?>
                    </td>
                    <td class="text-center" style="width:auto"><?php echo $post['post_content'] ?? '';?></td>
                    <td class="text-center"><?php echo $post['post_status'] ?? ''; ?></td>
                    <td class="text-center"><?php echo $post['post_type'] ?? ''; ?></td>
                    <td class="text-center"><?php echo $post['post_mime_type'] ?? '';?></td>
                    <td class=" text-center">
                        <button class="btn btn-primary edit_post_button"
                            id="<?php echo $post['post_id']; ?>">Edit</button>
                        <button class="btn btn-danger delete_post_button"
                            id="<?php echo $post['post_id']; ?>">Delete</button>
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
                        <h3><?php echo l('minical-extension-boilerplate/no data', true);?></h3>
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

<!-- and post model-->
<div class="modal" id="add_post_model" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Custom Data (POST)</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <input type="hidden" id="post_id">
                    <div class="form-group row">
                        <label for="title"
                            class="col-sm-2 col-form-label"><?php echo l('minical-extension-boilerplate/Post Title');?>*</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="title" placeholder="Enter Post title">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="type"
                            class="col-sm-2 col-form-label"><?php echo l('minical-extension-boilerplate/Post Type');?>*</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="type" placeholder="Enter Post Type">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="content"
                            class="col-sm-2 col-form-label"><?php echo l('minical-extension-boilerplate/Post Content');?></label>
                        <div class="col-sm-10">
                            <textarea class="form-control" id="content" rows="3"
                                placeholder="Enter Post Content"></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="mime-type"
                            class="col-sm-2 col-form-label"><?php echo l('minical-extension-boilerplate/Post Mime Type');?></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="mime-type" placeholder="Enter Post Mime Type">
                        </div>
                    </div>
                    <div class="post-meta">
                        <hr>

                        <h5>Post-Meta Data</h5>

                        <div class="form-group row">
                            <label for=""
                                class="col-sm-2 col-form-label"><?php echo l('minical-extension-boilerplate/custom field 1');?></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="field_1" placeholder="">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for=""
                                class="col-sm-2 col-form-label"><?php echo l('minical-extension-boilerplate/custom field 2');?></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="field_2" placeholder="">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for=""
                                class="col-sm-2 col-form-label"><?php echo l('minical-extension-boilerplate/custom field 3');?></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="field_3" placeholder="">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary"
                    id="post_data"><?php echo l('minical-extension-boilerplate/Add Post');?></button>

                <button type="button" class="btn btn-primary"
                    id="update_data"><?php echo l('minical-extension-boilerplate/Update Post');?></button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
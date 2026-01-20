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
        <h5>This is a template page just for you to understand how an extension works. You can either modify it or
            delete it.</h5>
    </div>
</div>
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
        <!-- Required for opening booking dialog while viewing [Show all...] pages -->
        <div id="booking_dialog"></div>
        <h5>This is a sample page showing the list of last 20 customers, it's just for you to understand how an
            extension
            works. You can either modify it or
            delete it.</h5>
        <hr>
        <div class="">
            <table id="customer_list" class="display" style="width:100%">
                <thead>
                    <tr>
                        <th class="text-center"><?php echo l('minical-extension-boilerplate/S. No.', true);?></th>
                        <th class="text-center"><?php echo l('minical-extension-boilerplate/customer name', true);?>
                        </th>

                        <th class="text-center"><?php echo l('minical-extension-boilerplate/email', true);?></th>

                        <th class="text-center"><?php echo l('minical-extension-boilerplate/phone', true);?></th>

                        <th class="text-center"><?php echo l('minical-extension-boilerplate/address', true);?></th>

                    </tr>
                </thead>
                <tbody>
                    <?php 
                    if (isset($customers) && $customers):
                        $i = 1;
                    foreach ($customers as $customer) : 
                        
                    if (isset($customer['customer_name']) && $customer):
                    ?>
                    <tr class='booking'>
                        <td class="text-center">
                            <?php echo $i;?></td>
                        <td class="text-center">
                            <?php echo $customer['customer_name']; ?></td>
                        <td class="text-center">
                            <?php echo $customer['email'] ? $customer['email'] : ''; ?>
                        </td>
                        <td class="text-center">
                            <?php echo $customer['phone'] ? $customer['phone'] : ''; ?>
                        </td>
                        <td class="text-center">
                            <?php echo $customer['address'] ? $customer['address'] : ''; ?>
                        </td>
                    </tr>
                    <?php  endif;
                    $i++;
                    endforeach;
                    else: ?>
                    <tr class='booking' data-booking-id=''>
                        <td></td>
                        <td></td>
                        <td class="text-center">
                            <h3><?php echo l('minical-extension-boilerplate/No Data', true);?></h3>
                        </td>
                        <td></td>
                        <td></td>
                    </tr>
                    <?php 
                        endif;
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
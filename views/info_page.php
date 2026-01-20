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
        <h4>Here you can get other info on how miniCal works.</h4>
        <hr>
        <h4>Hooks</h4>
        <p>They provide a way for running a function at a specific point in the execution of miniCal Core.</p>

        1. You can find detailed info about Actions at <a
            href="https://github.com/minical/minical/wiki/The-list-of-the-miniCal-actions">miniCal Actions.</a>
        <br>

        2. You can find detailed info about filters at <a
            href="https://github.com/minical/minical/wiki/The-List-of-the-miniCal-Filters">miniCal Filters.</a>


        <h4>Cron Setup</h4>
        <p>Cron is how miniCal does the scheduling time-based tasks. Several miniCal core features, such as checking for
            updates and getting bookings from OTA's like Booking, Airbnb, and Expedia, utilize Cron <a
                href="https://github.com/minical/minical/wiki/miniCal-Cron-Setup">miniCal
                Cron setup.</a></p>

        <h4>.ENV Setup</h4>
        <p>Environment variables allow developers to extract sensitive credentials from their source code and to use
            different configuration variables based on their working environment <a
                href="https://github.com/minical/minical/wiki/.env-example">miniCal
                .env setup.</a></p>

        <h4>Extension Autoload File</h4>
        <p>The autoload file contains all the JS/CSS/Helper or any third-party file that needs to be loaded, in
            extension <a href="https://github.com/minical/minical/wiki/Autoload-file"> autoload file.
            </a></p>

        <h4>Extension Config File</h4>
        <p>The config file contains all the extension's configuration details. Here you can find more details about <a
                href="https://github.com/minical/minical/wiki/Config-file">config file.
            </a></p>

        <h4>Extension Route File</h4>
        <p>Routes are responsible for responding to URL requests. Here you can find more details about <a
                href="https://github.com/minical/minical/wiki/Route-File">route file.
            </a></p>
    </div>
</div>
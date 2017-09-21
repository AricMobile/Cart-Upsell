<ul class="section-tabs">    
    <li <?php if (strstr($_SERVER['PHP_SELF'], "home.php") != '') { ?>class="active"<?php } ?>><a href="home.php?shop=<?= $shop; ?>">Dashbord</a></li>
    <li <?php if (strstr($_SERVER['PHP_SELF'], "banner.php") != '') { ?>class="active"<?php } ?>><a href="banner.php?shop=<?= $shop; ?>">Banner List</a></li>    
    <li <?php if (strstr($_SERVER['PHP_SELF'], "developer_guide.php") != '') { ?>class="active"<?php } ?>><a href="developer_guide.php?shop=<?= $shop; ?>">Help</a></li>    
</ul>
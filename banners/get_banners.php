<?php

include "../connect.php";

getAllData("banners","banners_status = 'active' AND NOW() BETWEEN banners_start_date AND banners_end_date");

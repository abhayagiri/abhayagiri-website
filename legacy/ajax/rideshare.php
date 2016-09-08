<!--
<a class="btn btn-primary btn-large fancybox fix" href="#ques" onclick="$('#ride_type').val('request')">Request</a>
<a class="btn btn-brown btn-large fancybox fix submitbtn" href="#ques" onclick="$('#ride_type').val('offer')">Offer</a>
<a id="next" class="fancybox" href="#ride_form" style="display:none" href="#ride_form"></a>
-->
<div class="table-box">
    <span class="title" style="color:#18489c">Request</span>
    <section class="responsive-table">
        <table class="table table-condensed table-hover table-bordered table-bordered-blue table-striped">
            <thead>
                <tr>
                    <th class="numeric">Name</th>
                    <th class="numeric">To</th>
                    <th class="numeric">From</th>
                    <th class="numeric">Date</th>
                    <th class="numeric">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $stmt = $func->rideshare('request');
                foreach ($stmt as $row) {
                    if ($row['dir'] == "to") {
                        $from = $row['loc'];
                        $to = "Abhayagiri";
                    } else {
                        $from = "Abhayagiri";
                        $to = $row['loc'];
                    }
                    ?>
                    <tr>
                        <td data-title="Name"><?php echo $row['name']; ?></td>
                        <td data-title="To"><?= $from ?></td>
                        <td data-title="From"><?= $to ?></td>
                        <td data-title="Date"><?= $row['date'] ?></td>
                        <td data-title="Action">
                            <a class="btn" value="<?= $row['id'] ?>" href="#inline">Contact</a>
                            <a class="btn" value="<?= $row['id'] ?>" href="#inline">Delete</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </section>
    <br><br>
    <span class="title" style="color:#784720">Offer</span>
    <section class="responsive-table">
        <table class="table table-condensed table-hover table-bordered table-bordered-brown table-striped">
            <thead>
                <tr>
                    <th class="numeric">Name</th>
                    <th class="numeric">To</th>
                    <th class="numeric">From</th>
                    <th class="numeric">Date</th>
                    <th class="numeric">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $stmt = $func->rideshare('offer');
                foreach ($stmt as $row) {
                    if ($row['dir'] == "to") {
                        $from = $row['loc'];
                        $to = "Abhayagiri";
                    } else {
                        $from = "Abhayagiri";
                        $to = $row['loc'];
                    }
                    ?>
                    <tr>
                        <td data-title="Name"><?php echo $row['name']; ?></td>
                        <td data-title="To"><?= $from ?></td>
                        <td data-title="From"><?= $to ?></td>
                        <td data-title="Date"><?= $row['date'] ?></td>
                        <td data-title="Action">
                            <a class="btn fancybox" value="<?= $row['id'] ?>" href="#inline">Contact</a>
                            <a class="btn fancybox" value="<?= $row['id'] ?>" href="#inline">Delete</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </section>
</div>

<div id="contact" style="display:none">
    <h1>Information</h1>
    Name:<br/><input type="text" name="name" id="name" tabindex="1" required><br/>
    Email:<br/><input type="text" name="from" id="from" tabindex="2" required><br/>
    Phone Number:<br/><input type="text" name="phone" id="phone" tabindex="3" required><br/>
    <input type="hidden" name="subject" id="subject">
    <input type="hidden" name="message" id="message">
    <section id="buttons">
        <input type="submit" name="submit" class="submitbtn" id="submitbtn" style="display:none">
        <input type="button" tabindex="5" class="submitbtn" value="Submit" onclick="setMessage()">
        <br style="clear:both;">
    </section>
</div>
<div id="ride_form" style="display:none;">
    <h1>Information</h1>
    Name:<br/><input type="text" name="name" id="ride_name" tabindex="1" required><br/>
    Email:<br/><input type="text" name="email" id="ride_email" tabindex="2" required><br/>
    Date:<br/><input type="text" name="loc" id="ride_date" tabindex="3" required><br/>
    <span id="loc_text">Location</span>:<br/><input type="text" name="loc" id="ride_loc" tabindex="5" required><br/>
    Seats:<br/><input type="text" name="loc" id="ride_occ" tabindex="6" required><br/>
    <input id="ride_type" type="hidden">
    <section id="buttons">
        <input type="button" tabindex="5" class="submitbtn" value="Submit" onclick="addRide()">
        <br style="clear:both;">
    </section>
</div>
</div>
<div id="ques" style="display:none;">
    <br><br>
    I am <select style="width:150px" name="ride_dir" id="ride_dir" tabindex="4">
        <option value="to">going to</option>
        <option value="from">leaving from</option>
    </select> the monastery.<br>
    <a class="btn btn-brown" onclick="$.fancybox.close();
                $('#next').trigger('click');
                $('#loc_text').html($('#ride_dir').val().charAt(0).toUpperCase() + $('#ride_dir').val().slice(1))">Next</a>
</div>
</div>
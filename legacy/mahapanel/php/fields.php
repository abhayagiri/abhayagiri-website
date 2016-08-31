<?php

function createField($db, $name, $type, $delete, $parent_id, $value, $upload_dir, $currentUser) {
    switch ($type) {
        case "file":
            ?>
            <a class="btn btn-primary" onclick="$('#<?= $name ?>_file').trigger('click');"><i class="icon-upload"></i> Upload file</a>
            <input id="<?= $name ?>" name="<?= $name ?>" value="<?= $value ?>" type='text'>
            <input id="<?= $name ?>_file" name="<?= $name ?>" class="file span4 btn" type="file" style="visibility:hidden;height:0;" value="<?= $value ?>" 
                   onchange ="initUpload('<?= $name ?>', $(this).val(), '<?= $upload_dir ?>');">
                   <?php
                   break;
               case "text":
                   ?> 
            <input id="<?= $name ?>" name="<?= $name ?>" type="text" class="<?= $name ?> span4" value="<?= $value ?>">
            <?php
            break; 
        case "title":
            ?>
            <input id="<?= $name ?>" name="<?= $name ?>" type="text" class="<?= $name ?> span4"
            <?php
            if ($delete == "no") { 
                echo "readonly"; 
            }
            ?> value="<?= $value ?>"><?php
                   break;
               case "textarea":
                   ?>
            <br><br><br>
            <textarea id="<?= $name ?>" name="<?= $name ?>" class="wysiwyg"><?= $value ?></textarea>
            <?php
            break;
        case "dropdown":
            ?>
            <select id="<?= $name ?>" name="<?= $name ?>" class="dropdown">
                <?php
                $stmt = $db->_join("dropdowns", "options", 'dropdowns.title as dtitle,dropdowns.id,dropdowns.source', 'options.title,options.value,options.date', array("dropdowns.id" => "options.parent"), array("dtitle" => "$name"));
                $source = $stmt[0]['source'];
                if ($source == 0) {
                    foreach ($stmt as $row) {
                        ?>
                        <option value='<?= $row['value'] ?>'
                        <?php
                        if ($row['value'] == $value) {
                            echo "selected='selected'";
                        }
                        ?>
                                ><?= $row['title'] ?></option>
                                <?php
                            }
                        } else {
                            $stmt = $db->_select('pages', 'url_title', array('id' => $source));
                            $source = $stmt[0]['url_title'];
                            $stmt = $db->_select($source, '*');
                            echo "<option value=''></option>";
                            foreach ($stmt as $row) {
                                ?>
                        <option value='<?= $row['title'] ?>'
                        <?php
                        if ($row['title'] == $value) {
                            echo "selected='selected'";
                        }
                        ?>
                                ><?= $row['title'] ?></option>
                                <?php                             }
                        }
                        ?>
            </select>
            <?php
            break;
        case "date":
            ?>
            <div  class="input-append date form_datetime" data-date="<?= $value ?>">
                <input id="date" size="16" type="text" name="date" value="<?= $value ?>" required>
                <span class="add-on"><i class="icon-remove"></i></span>
                <span class="add-on"><i class="icon-th"></i></span>
            </div>
            <?php
            break;
        case "duallist":
            ?>
            <div id="diallist" class="duallist" >
                <input id='access' name="access" type='hidden' value="<?= $value ?>">
                <?php
                break;
            case "user":
                ?>
                <input type="text" value="<?= $currentUser->title ?>" readonly>
                <input type="hidden" id="<?= $name ?>" name="<?= $name ?>" type="text" class="name span1" value="<?= $currentUser->id ?>" readonly>
                <?php
                break;
            case "parent":
                ?>
                <input id="parent" name="parent" type="text" class="name span1" value="<?= $parent_id ?>" readonly>
                <?php
                break;
        }
    }

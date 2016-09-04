<?php

require base_path('legacy/bootstrap.php');
require base_path('legacy/mahapanel/php/session.php');
require base_path('legacy/mahapanel/php/fields.php');

$db = App\Legacy\DB::getDB();

$table = $_POST['table'];
$delete = $_POST['delete'];
$table_id = $_POST['table_id'];
$parent_id = $_POST['parent_id'];
$entry_id = $_POST['entry_id'];
$action = $_POST['action'];
?>

<span class="btn-close" onclick="togglePanel('#form')"><i class="icon-remove-sign"></i></span><br><br><br>
<div class ="container-fluid" id="formpage">
    <br><br><br><br>
    <div class="row-fluid sortable">
        <div class="box span12">
            <div class="formbox">
                <div id='uploading'>
                    <i class='icon icon-spin icon-spinner'></i><br><br>
                    <i>Uploading file...</i>
                </div>
                <div data-original-title>
                    <h2><i class="icon-edit"></i><span class="break"></span>  <?= ucfirst($table) ?> Entry</h2>
                </div>
                <br>
                <hr class="muted">
                <div>
                    <form class="form-horizontal">
                        <div id="alert-dupe" class="alert alert-error fade in" style="display:none">
                            <button type="button" class="close" data-dismiss="alert">×</button>
                            <strong>URL title already exists!</strong> Please choose a different URL title.
                        </div>
                        <?php
                        $stmt = $db->_select("columns", "title,column_type,display_title,upload_directory", array("parent" => $table_id), "ORDER BY date DESC");
                        if (isset($entry_id) && $entry_id!="") {
                            $vals = $db->_select($table, '*', array("id" => $entry_id));
                            $vals = $vals[0];
                        } else {
                            $vals = [];
                        }
                        foreach ($stmt as $row) {
                            $name = $row['title'];
                            $type = $row['column_type'];
                            $title = $row['display_title'];
                            $value = array_get($vals, $name);
                            $upload_dir = $row['upload_directory'];
                            ?>

                            <div class = "control-group <?php if(($name=="url_title" || $name=="title") && $table=="uploads"){echo"hidden";}?>" >
                                <label class = "control-label"><?= $title ?>:</label>
                                <div>
    <?= createField($db, $name, $type, $delete, $parent_id, $value,$upload_dir, $currentUser) ?>
                                </div>
                            </div>
                        <?php }
                        ?>


                </div>
                <hr class="muted">
<?php if ($action == "insert") { ?>
                    <button id="submit" type="submit" class="btn btn-large btn-primary btn-formsubmit pull-right"
                            onclick="
            submitForm();
            return false;">
                        <i class = 'icon-approve'></i> Submit
                    </button>
<?php } elseif ($action == "edit") { ?>

                    <button id="edit" type="submit" class="btn btn-large btn-success btn-formsubmit pull-right"
                            onclick="
            submitEdit(entry_id);
            return false;">
                        <i class = 'icon-pencil'></i> Update
                    </button>
    <?php if ($delete == "yes") { ?>
                        <button class = 'confirm btn btn-large btn-danger btn-delete pull-right'
                                onclick="submitDelete(entry_id);
                return false;">
                            <i class = 'icon-remove'></i> Delete
                        </button>
                        <?php                     }
                }
                ?>
                <button type="reset" class="btn btn-large btn-cancel pull-right"
                        onclick="togglePanel('#form')">
                    Cancel
                </button>
                </form>
                <br>

            </div>
        </div>
    </div><!--/span-->

</div><!--/row-->

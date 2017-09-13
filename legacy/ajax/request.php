<!--image-->
<div id="banner">
    <div class="title"><?= "<i class='icon-book'></i> Book Request" ?></div>
    <img src="/media/images/banner/books.jpg">
</div>
<!--/image-->
<!--body-->
<div id="breadcrumb-container">
    <div class="container-fluid">
        <ul class="breadcrumb">
            <li>
                <a href="/" onclick="nav('home', 'Custom');
                        return false;">Home</a>
                <span class="divider">/</span>
            </li>
            <li>
                <a href="/" onclick="nav('books', 'Table', '<?= $_icon ?>');
                        return false;">Books</a>
                <span class="divider">/</span>
            </li>
            <li class='active'>Book Request</li>
        </ul>
    </div>
</div>
<div id="content">
    <form id="form" method='POST' class="form-horizontal" action="">
        <div class='container-fluid'>
            <div id="alert">
                <div class = "alert alert-success" style="display:none">Your message has been sent successfully.</div>
                <div class = "alert alert-error" style="display:none">Please fill out all fields before submitting.</div>
                <div class = "alert alert-warning" style="display:none">You message is being sent, please hold...</div>
            </div>
            <?= \App\Models\Blob::getBlob('books.request.form') ?>
            <br><br>
            <legend>Selection</legend>
            <div class = "alert alert-warning">If the Title and Author fields of your order are blank, please write your selection in the comments box below. Thank you.</div>
            <div id="selection">
                <?php

                $books = Session::get('books');
                if (!empty($books)) {
                    foreach ($books as $id => $quantity) {
                        $book = \App\Models\Book::findOrFail($id);
                        $title = e($book->title);
                        $author = e($book->author->title_en);
                        $imageUrl = e($book->image_url);
                        $weight = e($book->weight);
                        ?>

                        <div class='media'>
                            <span class='pull-left'>
                                <img class='img-books media-object' src="<?= $imageUrl ?>">
                            </span>
                            <div class='media-body'>
                                <div class="row-fluid">
                                    <div class="span8">
                                        <span class='title'><?= $title ?></span><br>
                                        <?= $author ?><br>
                                        <a href='javascript:removeBook(<?= $id ?>)'>Remove</a>
                                        <input name="title[]" value="<?= $title ?>" type="hidden">
                                        <input name="author[]" value="<?= $author ?>" type="hidden">
                                    </div >
                                    <div class="body span2">
                                        Weight: <?= $weight ?>
                                        <input name="weight[]" value="<?= $weight ?>" type="hidden">
                                    </div>
                                    <div class="body span2">
                                        Quantity: <br>
                                        <div class="input-append">
                                            <input value="<?= $quantity ?>" name="quantity[]" class='span2' type='text' maxlength="3" style="width:32px" />
                                            <button class="btn" type="button" onclick="updateBook(<?= $id ?>, $(this).siblings('input').val())">Update</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php                     }
                }
                ?>
            </div>
            <br>
            <hr>
            <div class="row-fluid">
                <span class="span6"></span>
                <span class="span6">
                    <a class="btn pull-left" href="/books" style="margin-left:-73px" onclick="nav('books');
                        return false;"><i class="icon icon-plus"></i> Add More Books
                    </a>
                </span>
            </div>
            <br>
            <br>
            <legend>Shipping Information</legend>
            <div class="control-group">
                <label class="control-label" for="fname">First Name</label>
                <div class="controls">
                    <input id="fname" class="span3" name="fname" type="text" placeholder="First Name" required>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="lname">Last Name</label>
                <div class="controls">
                    <input id="lname" class="span3" name="lname" type="text" placeholder="Last Name" required>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="email">Email</label>
                <div class="controls">
                    <div class="input-prepend">
                        <span class="add-on"><i class="icon-envelope"></i></span>
                        <input id="email" class="span4" name="email" type="email" placeholder="Email" required>
                    </div>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="address">Address</label>
                <div class="controls">
                    <input id="address" class="span5" name="address" type="text" placeholder="Street" required>
                </div>
                <div class="controls controls-row">
                    <input id="city" class="span3" name="city" type="text" placeholder="City" required>
                    <input id="state" class="span1" name="state" type="text" placeholder="State" required>
                    <input id="zip" class="span1" name="zip" type="text" placeholder="Zip" required>

                </div>
                <div class="controls">
                    <input id="country" class="span5" name="country" type="text" placeholder="Country" required>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="comments">Comments</label>
                <div class="controls">
                    <textarea id="comments" name="comments" rows="12" class="span8"></textarea>
                </div>
            </div>
            <hr>
            <div class = "control-group">
                <input id="page" type="hidden" name="page" value="request">
                <button type = "submit" class = "btn btn-large btn-primary" onclick="submitForm('request');
                        return false;">Submit</button>
                <button type = "submit" class = "btn btn-large" onclick="clearForm();
                        return false;">Cancel</button>
            </div>
        </div>
    </form>
</div>

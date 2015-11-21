<?php require_once("../includes/initialize.php"); ?>
<?php
    if(empty($_GET['id'])) {
        $session->message("No photograph ID was provided.");
        redirect_to('index.php');
    }

    $photo = Photograph::find_by_id($_GET['id']);
    if(!$photo) {
        $session->message("The photo could not be located.");
        redirect_to('index.php');
    }

if(isset($_POST['submit'])) {
    $author = trim($_POST['author']);
    $body = trim($_POST['body']);
    
    $new_comment = Comment::make($photo->id, $author, $body);
    if($new_comment && $new_comment->save()) {
        redirect_to("photo.php?id={$photo->id}");
    } else {
        $message = "There was an error that prevented the comment from being saved.";
    }
    
} else {
    $author = "";
    $body = "";
}
    $comments = $photo->comments();
?>
<?php include_layout_template('header.php'); ?>

<a href="index.php">&laquo; Back</a><br />
<br />

<div style="margin-left: 20px;">
    <img src="<?php echo $photo->image_path(); ?>" />
    <p><?php echo $photo->caption; ?></p>
</div>

<div id="comments">
    <?php foreach($comments as $comment): ?>
        <div class="comment" style="margin-bottom: 2em;">
            <div class="author">
                <?php echo htmlentities($comment->author); ?> wrote:
            </div>
            <div class="body">
                <?php echo strip_tags($comment->body, '<strong><em><p>'); ?>
            </div>
            <div class="meta-info" style="font-size: 0.8em;">
                <?php echo datetime_to_text($comment->created); ?>
            </div>
        </div>
    <?php endforeach; ?>
    <?php if(empty($comments)) { echo "No Comments."; } ?>
</div>

<div id="comment-form">
    
    <?php echo output_message($message); ?>
    <form action="photo.php?id=<?php echo $photo->id; ?>" method="post">
        <div class="form-group">
            <h3 class="text-primary">New Comment</h3>
        </div>                                      
        <div class="form-group">
            <input class="form-control" type="text" placeholder="Your name" name="author" value="<?php echo $author; ?>" />
        </div>
        <div class="form-group">
            <textarea class="form-control" placeholder="Your comment" name="body" cols="40" rows="8"><?php echo $body; ?></textarea>
        </div>
        <div class="form-group">
            <input class="btn btn-success" type="submit" name="submit" value="Submit Comment" />
        </div>
    </form>
</div>

<?php include_layout_template('footer.php'); ?>